<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UsersModel;

class Admin extends BaseController
{
    // ── Auth check — call at top of every method ──────────────────────
    private function requireAdmin()
    {
        $user = session()->get('user');
        if (!$user || strtolower($user['type'] ?? '') !== 'admin') {
            session()->setFlashdata('error', 'Access denied. Admin only.');
            return redirect()->to('/login');
        }
        return null; // null = authorized
    }

    // ── Dashboard ─────────────────────────────────────────────────────
    public function dashboard()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $orderModel   = new OrderModel();
        $productModel = new ProductModel();
        $db           = \Config\Database::connect();

        // Today's sales
        $today = date('Y-m-d');
        $todayRow = $db->query("
            SELECT COUNT(*) as cnt, COALESCE(SUM(total_amount),0) as total
            FROM orders
            WHERE DATE(created_at) = ? AND status != 'cancelled' AND deleted_at IS NULL
        ", [$today])->getRowArray();

        // Monthly sales
        $monthRow = $db->query("
            SELECT COALESCE(SUM(total_amount),0) as total
            FROM orders
            WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())
            AND status != 'cancelled' AND deleted_at IS NULL
        ")->getRowArray();

        // Order counts by status
        $statusRows = $db->query("
            SELECT status, COUNT(*) as cnt FROM orders WHERE deleted_at IS NULL GROUP BY status
        ")->getResultArray();
        $statusCounts = [];
        foreach ($statusRows as $r) $statusCounts[$r['status']] = $r['cnt'];

        // Products
        $allProds    = $productModel->findAll();
        $lowStockProd = array_filter($allProds, fn($p) => ($p['stock'] ?? 0) <= 5);

        // Recent orders
        $recentOrders = $orderModel->orderBy('created_at', 'DESC')->limit(8)->findAll();

        return view('admin/dashboard', [
            'todaySales'      => $todayRow['total'] ?? 0,
            'todayOrders'     => $todayRow['cnt'] ?? 0,
            'monthlySales'    => $monthRow['total'] ?? 0,
            'totalOrders'     => array_sum($statusCounts),
            'pendingOrders'   => ($statusCounts['to_pay'] ?? 0) + ($statusCounts['to_ship'] ?? 0),
            'totalProducts'   => count($allProds),
            'lowStock'        => count($lowStockProd),
            'statusCounts'    => $statusCounts,
            'recentOrders'    => $recentOrders,
            'lowStockProducts' => array_values($lowStockProd),
        ]);
    }

    // ── Inventory / Products ──────────────────────────────────────────
    public function products()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $productModel = new ProductModel();
        return view('admin/products', [
            'products' => $productModel->withDeleted()->findAll(),
        ]);
    }

    public function saveProduct()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $productModel = new ProductModel();
        $id           = $this->request->getPost('id');

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price'       => (float) $this->request->getPost('price'),
            'category'    => $this->request->getPost('category'),
            'stock'       => (int) $this->request->getPost('stock'),
            'status'      => (int) $this->request->getPost('status'),
        ];

        // Handle image upload
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'images', $newName);
            $data['image'] = $newName;
        } elseif ($id) {
            // Keep existing image if editing and no new upload
            unset($data['image']);
        }

        if ($id) {
            $productModel->update($id, $data);
            session()->setFlashdata('success', 'Product updated successfully.');
        } else {
            $productModel->insert($data);
            session()->setFlashdata('success', 'Product added successfully.');
        }

        return redirect()->to('/admin/products');
    }

    public function toggleProduct()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $id           = $this->request->getPost('id');
        $productModel = new ProductModel();
        $product      = $productModel->find($id);

        if ($product) {
            $productModel->update($id, ['status' => $product['status'] ? 0 : 1]);
        }

        return redirect()->to('/admin/products');
    }

    public function deleteProduct()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $id = $this->request->getPost('id');
        (new ProductModel())->delete($id);
        session()->setFlashdata('success', 'Product deleted.');
        return redirect()->to('/admin/products');
    }

    // ── Orders ────────────────────────────────────────────────────────
    public function orders()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $orderModel = new OrderModel();
        $status     = $this->request->getGet('status') ?? '';
        $orders     = $status
            ? $orderModel->where('status', $status)->orderBy('created_at', 'DESC')->findAll()
            : $orderModel->orderBy('created_at', 'DESC')->findAll();

        // Attach items to each order
        $itemModel = new \App\Models\OrderItemModel();
        foreach ($orders as &$o) {
            $o['items'] = $itemModel->where('order_id', $o['id'])->findAll();
        }

        return view('admin/orders', ['orders' => $orders, 'activeStatus' => $status]);
    }

    public function updateOrderStatus()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $id     = $this->request->getPost('id');
        $status = $this->request->getPost('status');
        $allowed = ['to_pay', 'to_ship', 'to_receive', 'completed', 'cancelled', 'refund'];

        if (!in_array($status, $allowed)) {
            session()->setFlashdata('error', 'Invalid status.');
            return redirect()->back();
        }

        // Map order status → payment_status automatically
        $paymentStatusMap = [
            'to_pay'      => 'pending',
            'to_ship'     => 'paid',      // admin confirmed payment → mark paid
            'to_receive'  => 'paid',
            'completed'   => 'paid',
            'cancelled'   => 'cancelled',
            'refund'      => 'refunded',
        ];

        $data = [
            'status'         => $status,
            'payment_status' => $paymentStatusMap[$status],
        ];

        // Set paid_at timestamp when moving to paid for the first time
        if (in_array($status, ['to_ship', 'to_receive', 'completed'])) {
            // Only set if not already set
            $order = (new OrderModel())->find($id);
            if (empty($order['paid_at'])) {
                $data['paid_at'] = date('Y-m-d H:i:s');
            }
        }

        // Clear paid_at if cancelled or refunded
        if (in_array($status, ['cancelled', 'refund'])) {
            $data['paid_at'] = null;
        }

        (new OrderModel())->update($id, $data);
        session()->setFlashdata('success', 'Order status updated.');

        return redirect()->back();
    }

    // ── Reports ───────────────────────────────────────────────────────
    public function reports()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $db           = \Config\Database::connect();
        $productModel = new ProductModel();

        // Daily sales for current month
        $dailySales = $db->query("
            SELECT
                DATE(created_at) as date,
                COUNT(*) as orders,
                COALESCE(SUM(total_amount),0) as revenue,
                SUM(payment_method = 'instapay') as instapay,
                SUM(payment_method = 'cod') as cod
            FROM orders
            WHERE MONTH(created_at) = MONTH(NOW())
              AND YEAR(created_at) = YEAR(NOW())
              AND status != 'cancelled'
              AND deleted_at IS NULL
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ")->getResultArray();

        // Today
        $today = date('Y-m-d');
        $todayRow = $db->query("
            SELECT COUNT(*) as cnt, COALESCE(SUM(total_amount),0) as total
            FROM orders WHERE DATE(created_at) = ? AND status != 'cancelled' AND deleted_at IS NULL
        ", [$today])->getRowArray();

        // Monthly
        $monthRow = $db->query("
            SELECT COALESCE(SUM(total_amount),0) as total
            FROM orders
            WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())
            AND status != 'cancelled' AND deleted_at IS NULL
        ")->getRowArray();

        // All-time
        $totalRevRow = $db->query("
            SELECT COALESCE(SUM(total_amount),0) as total
            FROM orders WHERE status NOT IN ('cancelled','refund') AND deleted_at IS NULL
        ")->getRowArray();

        // Avg order value
        $avgRow = $db->query("
            SELECT COALESCE(AVG(total_amount),0) as avg
            FROM orders WHERE status NOT IN ('cancelled','refund') AND deleted_at IS NULL
        ")->getRowArray();

        // Top products
        $topProducts = $db->query("
            SELECT oi.product_name, SUM(oi.quantity) as total_qty, SUM(oi.subtotal) as total_revenue
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            WHERE o.status NOT IN ('cancelled','refund') AND o.deleted_at IS NULL
            GROUP BY oi.product_name
            ORDER BY total_qty DESC
            LIMIT 10
        ")->getResultArray();

        // Inventory counts
        $allProds    = $productModel->withDeleted()->findAll();
        $activeProds = array_filter($allProds, fn($p) => $p['status'] == 1 && !$p['deleted_at']);
        $outOfStock  = array_filter($allProds, fn($p) => ($p['stock'] ?? 0) == 0);
        $lowStockArr = array_filter($allProds, fn($p) => ($p['stock'] ?? 0) > 0 && ($p['stock'] ?? 0) <= 5);

        return view('admin/reports', [
            'todaySales'    => $todayRow['total'] ?? 0,
            'todayOrders'   => $todayRow['cnt'] ?? 0,
            'monthlySales'  => $monthRow['total'] ?? 0,
            'totalRevenue'  => $totalRevRow['total'] ?? 0,
            'avgOrderValue' => $avgRow['avg'] ?? 0,
            'dailySales'    => $dailySales,
            'topProducts'   => $topProducts,
            'totalProducts' => count($allProds),
            'activeProducts' => count($activeProds),
            'outOfStock'    => count($outOfStock),
            'lowStockCount' => count($lowStockArr),
            'allProducts'   => array_values($allProds),
        ]);
    }


    // ── Verify Payment ────────────────────────────────────────────────
    public function verifyPayment()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $id     = $this->request->getPost('id');
        $action = $this->request->getPost('action'); // approve | reject

        $orderModel = new OrderModel();
        $order      = $orderModel->find($id);

        if (!$order) {
            session()->setFlashdata('error', 'Order not found.');
            return redirect()->back();
        }

        if ($action === 'approve') {
            $orderModel->update($id, [
                'payment_status' => 'paid',
                'status'         => 'to_ship',
                'paid_at'        => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('success', 'Payment approved. Order moved to To Ship.');
        } elseif ($action === 'reject') {
            $orderModel->update($id, [
                'payment_status' => 'failed',
                'status'         => 'to_pay',
            ]);
            session()->setFlashdata('error', 'Payment rejected. Order moved back to To Pay.');
        }

        return redirect()->to('/admin/orders');
    }

    // ── Storefront ────────────────────────────────────────────────────
    public function storefront()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        // Load settings from DB or a settings file
        $db       = \Config\Database::connect();
        $rows     = $db->query("SELECT setting_key, setting_value FROM storefront_settings")->getResultArray();
        $settings = [];
        foreach ($rows as $r) $settings[$r['setting_key']] = $r['setting_value'];

        return view('admin/storefront', ['settings' => $settings]);
    }

    public function saveStorefront()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $db     = \Config\Database::connect();
        $fields = [
            'store_name',
            'store_tagline',
            'contact_email',
            'contact_phone',
            'store_address',
            'store_hours',
            'shipping_fee',
            'hero_title',
            'hero_subtitle',
            'about_heading',
            'about_description',
            'newsletter_heading',
            'newsletter_subtext',
            'newsletter_discount',
            'social_facebook',
            'social_instagram',
            'social_tiktok',
            'social_shopee',
        ];

        foreach ($fields as $field) {
            $value = $this->request->getPost($field);
            if ($value !== null) {
                $db->query("
                    INSERT INTO storefront_settings (setting_key, setting_value)
                    VALUES (?, ?)
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
                ", [$field, $value]);
            }
        }

        // Handle image uploads
        foreach (['hero_image', 'about_image'] as $imgField) {
            $file = $this->request->getFile($imgField);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'images', $newName);
                $db->query("
                    INSERT INTO storefront_settings (setting_key, setting_value)
                    VALUES (?, ?)
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
                ", [$imgField, $newName]);
            }
        }

        session()->setFlashdata('success', 'Storefront updated successfully.');
        return redirect()->to('/admin/storefront');
    }

    // Accounts
    public function accounts()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $userModel = new UsersModel();
        return view('admin/accounts', [
            'users' => $userModel->findAll(),
        ]);
    }

    public function saveAccount()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;

        $userModel = new UsersModel();
        $id        = $this->request->getPost('id');
        $password  = $this->request->getPost('password');

        $data = [
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'username'       => $this->request->getPost('username'),
            'email'          => $this->request->getPost('email'),
            'type'           => $this->request->getPost('type'),
            'account_status' => (int) $this->request->getPost('status'),
        ];

        if (!empty($password)) {
            $data['password_hash'] = $password;
        }

        if ($id) {
            // Fix 1: Override validation rules for update
            // - Remove required from password (only validate if provided)
            // - Pass current ID so is_unique ignores the current record
            $userModel->setValidationRules([
                'username'   => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
                'email'      => "required|valid_email|is_unique[users.email,id,{$id}]",
                'first_name' => 'required|min_length[2]|max_length[100]',
                'last_name'  => 'required|min_length[2]|max_length[100]',
                'type'       => 'required|in_list[client,admin]',
                // password_hash intentionally omitted — only validate if present
            ]);

            // Fix 2: If password provided, add its validation back
            if (!empty($password)) {
                $userModel->setValidationRule('password_hash', 'min_length[8]');
            }

            if ($userModel->update($id, $data)) {
                session()->setFlashdata('success', 'Account updated successfully.');
            } else {
                session()->setFlashdata('error', implode(' ', $userModel->errors()));
                return redirect()->back()->withInput();
            }
        } else {
            // Create — all original rules apply including required password
            if (empty($password)) {
                session()->setFlashdata('error', 'Password is required for new accounts.');
                return redirect()->back()->withInput();
            }

            $data['password_hash']   = $password;
            $data['email_activated'] = 1;

            if ($userModel->insert($data)) {
                session()->setFlashdata('success', 'Account created successfully.');
            } else {
                session()->setFlashdata('error', implode(' ', $userModel->errors()) ?: 'Failed to create account. Username or Email might be taken.');
                return redirect()->back()->withInput();
            }
        }

        return redirect()->to('/admin/accounts');
    }

    public function deleteAccount()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;
        $id = $this->request->getPost('id');
        (new UsersModel())->delete($id);
        session()->setFlashdata('success', 'Account deleted.');
        return redirect()->to('/admin/accounts');
    }

    public function toggleAccountStatus()
    {
        if ($redirect = $this->requireAdmin()) return $redirect;
        $id = $this->request->getPost('id');
        $model = new UsersModel();
        $user = $model->find($id);
        if ($user) {
            $current = is_object($user) ? $user->account_status : $user['account_status'];
            $model->update($id, ['account_status' => $current ? 0 : 1]);
        }
        return redirect()->to('/admin/accounts');
    }
}
