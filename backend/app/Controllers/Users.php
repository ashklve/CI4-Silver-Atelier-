<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CartItemModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\UsersModel;

class Users extends BaseController
{
    public function index(): string
    {
        return view('user/landing');
    }

    public function about(): string
    {
        return view('user/about');
    }

    public function products(): string
    {
        $productModel = new ProductModel();
        $products     = $productModel->getActive();

        return view('user/products', ['products' => $products]);
    }

    // ── Profile GET ───────────────────────────────────────────────────
    public function profile()
    {
        if (!session()->has('user')) {
            return redirect()->to('/login');
        }

        $userId     = session()->get('user')['id'];
        $userModel  = new UsersModel();
        $profileData = $userModel->find($userId);

        // UsersModel may return an Entity object — convert to array for the view
        if (is_object($profileData) && method_exists($profileData, 'toArray')) {
            $profileData = $profileData->toArray();
        } elseif (is_object($profileData)) {
            $profileData = (array) $profileData;
        }

        return view('user/profile', ['profileData' => $profileData]);
    }

    // ── Profile POST ──────────────────────────────────────────────────
    public function updateProfile()
    {
        if (!session()->has('user')) {
            return redirect()->to('/login');
        }

        $userId    = session()->get('user')['id'];
        $userModel = new UsersModel();
        $section   = $this->request->getPost('section'); // personal | address | security

        // ── Personal info ──────────────────────────────────────────
        if ($section === 'personal') {
            $rules = [
                'first_name' => 'required|min_length[2]',
                'last_name'  => 'required|min_length[2]',
                'username'   => "required|min_length[3]|is_unique[users.username,id,$userId]",
                'email'      => "required|valid_email|is_unique[users.email,id,$userId]",
            ];

            if (!$this->validate($rules)) {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }

            $userModel->update($userId, [
                'first_name'  => $this->request->getPost('first_name'),
                'middle_name' => $this->request->getPost('middle_name'),
                'last_name'   => $this->request->getPost('last_name'),
                'username'    => $this->request->getPost('username'),
                'email'       => $this->request->getPost('email'),
                'phone'       => $this->request->getPost('phone'),
                'gender'      => $this->request->getPost('gender'),
                'newsletter'  => $this->request->getPost('newsletter') ? 1 : 0,
            ]);

            // Refresh session with updated info
            $updated = $userModel->find($userId);
            if (is_object($updated) && method_exists($updated, 'toArray')) {
                $updated = $updated->toArray();
            } elseif (is_object($updated)) {
                $updated = (array) $updated;
            }
            session()->set('user', [
                'id'            => $updated['id'],
                'username'      => $updated['username'],
                'email'         => $updated['email'],
                'first_name'    => $updated['first_name'],
                'last_name'     => $updated['last_name'],
                'type'          => $updated['type'],
                'display_name'  => trim($updated['first_name'] . ' ' . $updated['last_name']),
                'profile_image' => $updated['profile_image'],
            ]);

            session()->setFlashdata('success', 'Personal information updated successfully.');
            return redirect()->to('/profile#personal');
        }

        // ── Address ────────────────────────────────────────────────
        if ($section === 'address') {
            $userModel->update($userId, [
                'address'     => $this->request->getPost('address'),
                'city'        => $this->request->getPost('city'),
                'postal_code' => $this->request->getPost('postal_code'),
            ]);

            session()->setFlashdata('success', 'Default address saved. It will auto-fill at checkout.');
            return redirect()->to('/profile#address');
        }

        // ── Password ───────────────────────────────────────────────
        if ($section === 'security') {
            $user = $userModel->find($userId);
            if (is_object($user) && method_exists($user, 'toArray')) {
                $user = $user->toArray();
            } elseif (is_object($user)) {
                $user = (array) $user;
            }

            if (!password_verify($this->request->getPost('current_password'), $user['password_hash'])) {
                session()->setFlashdata('errors', ['current_password' => 'Current password is incorrect.']);
                return redirect()->to('/profile#security');
            }

            $rules = [
                'new_password'     => 'required|min_length[8]',
                'confirm_password' => 'required|matches[new_password]',
            ];

            if (!$this->validate($rules)) {
                session()->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->to('/profile#security');
            }

            $userModel->update($userId, [
                'password_hash' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
            ]);

            session()->setFlashdata('success', 'Password updated successfully.');
            return redirect()->to('/profile#security');
        }

        return redirect()->to('/profile');
    }

    // ── Avatar upload ─────────────────────────────────────────────────
    public function updateAvatar()
    {
        if (!session()->has('user')) {
            return redirect()->to('/login');
        }

        $file = $this->request->getFile('avatar');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'images/profiles', $newName);

            $userId    = session()->get('user')['id'];
            $userModel = new UsersModel();
            $userModel->update($userId, ['profile_image' => $newName]);

            // Update session
            $sess = session()->get('user');
            $sess['profile_image'] = $newName;
            session()->set('user', $sess);

            session()->setFlashdata('success', 'Profile photo updated.');
        }

        return redirect()->to('/profile');
    }

    // ── Checkout GET (protected) ──────────────────────────────────────
    public function checkout()
    {
        if (!session()->has('user')) {
            session()->setFlashdata('error', 'Please sign in to proceed to checkout.');
            return redirect()->to('/login');
        }

        $cartModel = new CartItemModel();
        $cartItems = $cartModel->where($this->getCartConditions())->findAll();

        if (empty($cartItems)) {
            session()->setFlashdata('error', 'Your cart is empty.');
            return redirect()->to('/products');
        }

        // Load saved address from DB to pre-fill checkout
        $userId    = session()->get('user')['id'];
        $userModel = new UsersModel();
        $fullUser  = $userModel->find($userId);
        if (is_object($fullUser) && method_exists($fullUser, 'toArray')) {
            $fullUser = $fullUser->toArray();
        } elseif (is_object($fullUser)) {
            $fullUser = (array) $fullUser;
        }

        return view('user/checkout', [
            'cartItems' => $cartItems,
            'fullUser'  => $fullUser, // has address, city, postal_code, phone
        ]);
    }

    // ── Place Order POST ──────────────────────────────────────────────
    public function placeOrder()
    {
        if (!session()->has('user')) {
            return redirect()->to('/login');
        }

        $userId    = session()->get('user')['id'];
        $cartModel = new CartItemModel();
        $cartItems = $cartModel->where($this->getCartConditions())->findAll();

        if (empty($cartItems)) {
            return redirect()->to('/cart');
        }

        // Handle payment screenshot upload
        $proofPath = null;
        $proofFile = $this->request->getFile('payment_screenshot');
        if ($proofFile && $proofFile->isValid() && !$proofFile->hasMoved()) {
            $proofPath = $proofFile->getRandomName();
            $proofFile->move(FCPATH . 'images/payments', $proofPath);
        }

        $orderModel = new OrderModel();
        $orderId    = $orderModel->placeOrder([
            'user_id'         => $userId,
            'payment_method'  => $this->request->getPost('payment_method'),
            'payment_proof'   => $proofPath,
            'receive_method'  => $this->request->getPost('receive_method'),
            'recipient_name'  => $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name'),
            'recipient_phone' => $this->request->getPost('phone'),
            'recipient_email' => $this->request->getPost('email'),
            'address'         => $this->request->getPost('address'),
            'city'            => $this->request->getPost('city'),
            'postal_code'     => $this->request->getPost('zip'),
            'order_notes'     => $this->request->getPost('notes'),
            'shipping_amount' => $this->request->getPost('shipping_amount') ?? 0,
        ], $cartItems);

        if (!$orderId) {
            session()->setFlashdata('error', 'Something went wrong placing your order. Please try again.');
            return redirect()->to('/checkout');
        }

        // Clear cart after successful order
        $cartModel->where($this->getCartConditions())->delete();

        session()->setFlashdata('success', 'Order placed successfully! 🥥 We\'ll notify you once confirmed.');
        return redirect()->to('/orders?tab=to_pay');
    }

    // ── My Orders ─────────────────────────────────────────────────────
    public function orders()
    {
        if (!session()->has('user')) {
            session()->setFlashdata('error', 'Please sign in to view your orders.');
            return redirect()->to('/login');
        }

        $userId     = session()->get('user')['id'];
        $orderModel = new OrderModel();
        $orders     = $orderModel->getByUser($userId);

        return view('user/orders', [
            'title'  => 'My Orders — COCOIR',
            'orders' => $orders,
        ]);
    }

    // ── Cart ──────────────────────────────────────────────────────────
    public function cart()
    {
        $model     = new CartItemModel();
        $cartItems = $model->where($this->getCartConditions())->findAll();
        return view('user/cart', ['cartItems' => $cartItems]);
    }

    public function addToCart()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $id    = $this->request->getPost('id');
        $name  = $this->request->getPost('name');
        $price = $this->request->getPost('price');
        $image = $this->request->getPost('image');

        if (!$id || !$name || !$price) {
            return $this->response->setStatusCode(400)
                ->setBody(json_encode(['success' => false, 'message' => 'Invalid product data.']));
        }

        $model    = new CartItemModel();
        $cond     = $this->getCartConditions();
        $existing = $model->where($cond)->where('product_id', $id)->first();

        if ($existing) {
            $model->update($existing['id'], ['quantity' => $existing['quantity'] + 1]);
        } else {
            $model->insert(array_merge($cond, [
                'product_id' => $id,
                'name'       => $name,
                'price'      => (float) $price,
                'image'      => $image ?? 'default.png',
                'quantity'   => 1,
            ]));
        }

        return $this->response->setBody(json_encode([
            'success'    => true,
            'message'    => "'$name' added to cart.",
            'totalItems' => $this->getCartTotal($model),
            'csrf_hash'  => csrf_hash(),
        ]));
    }

    public function updateCart()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $id       = $this->request->getPost('id');
        $quantity = (int) $this->request->getPost('quantity');
        $model    = new CartItemModel();
        $item     = $model->where($this->getCartConditions())->where('id', $id)->first();

        if ($item && $quantity > 0) {
            $model->update($id, ['quantity' => $quantity]);
            return $this->response->setBody(json_encode(['success' => true, 'csrf_hash' => csrf_hash()]));
        }
        return $this->response->setStatusCode(404)
            ->setBody(json_encode(['success' => false, 'message' => 'Item not found.', 'csrf_hash' => csrf_hash()]));
    }

    public function removeFromCart()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $id    = $this->request->getPost('id');
        $model = new CartItemModel();
        $item  = $model->where($this->getCartConditions())->where('id', $id)->first();

        if ($item) {
            $model->delete($id);
            return $this->response->setBody(json_encode(['success' => true, 'csrf_hash' => csrf_hash()]));
        }
        return $this->response->setStatusCode(404)
            ->setBody(json_encode(['success' => false, 'message' => 'Item not found.', 'csrf_hash' => csrf_hash()]));
    }

    // ── Auth views ────────────────────────────────────────────────────
    public function login(): string   { return view('user/login'); }
    public function signup(): string  { return view('user/signup'); }
    public function moodBoard(): string   { return view('user/moodBoard'); }
    public function roadMapPage(): string { return view('user/roadMapPage'); }

    // ── Helpers ───────────────────────────────────────────────────────
    private function getCartConditions(): array
    {
        $userId = session()->get('user')['id'] ?? null;
        return $userId ? ['user_id' => $userId] : ['session_id' => session_id()];
    }

    private function getCartTotal($model): int
    {
        $result = $model->selectSum('quantity')->where($this->getCartConditions())->first();
        return (int) ($result['quantity'] ?? 0);
    }
}