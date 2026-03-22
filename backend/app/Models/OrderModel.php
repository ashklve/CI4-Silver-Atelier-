<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table      = 'orders';
    protected $primaryKey = 'id';

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $dateFormat    = 'datetime';

    protected $allowedFields = [
        'order_number',
        'user_id',
        'status',
        'payment_method',
        'payment_status',
        'payment_proof',
        'paid_at',
        'receive_method',
        'recipient_name',
        'recipient_phone',
        'recipient_email',
        'address',
        'city',
        'postal_code',
        'order_notes',
        'subtotal',
        'shipping_amount',
        'total_amount',
        'refund_reason',
        'refund_proof',
        'refund_qr',
    ];

    // ── Get all orders for a user with their items ────────────────────
    public function getByUser(int $userId): array
    {
        $orders = $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        if (empty($orders)) return [];

        $itemModel = new OrderItemModel();

        foreach ($orders as &$order) {
            $order['items'] = $itemModel->where('order_id', $order['id'])->findAll();
        }

        return $orders;
    }

    // ── Get single order with items (verifies ownership) ──────────────
    public function getOrderForUser(int $orderId, int $userId): ?array
    {
        $order = $this->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (!$order) return null;

        $itemModel    = new OrderItemModel();
        $order['items'] = $itemModel->where('order_id', $order['id'])->findAll();

        return $order;
    }

    // ── Generate unique order number e.g. ORD-20250001 ────────────────
    public function generateOrderNumber(): string
    {
        $year = date('Y');

        // Find the last order from this year only
        $last = $this->where('order_number LIKE', "ORD-{$year}-%")
            ->orderBy('id', 'DESC')
            ->first();

        $seq = 1;
        if ($last) {
            // order_number format: ORD-2026-0001
            // explode gives: ['ORD', '2026', '0001']
            $parts = explode('-', $last['order_number']);
            $seq   = (int) end($parts) + 1;
        }

        return 'ORD-' . $year . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }


    // ── Place a new order from cart items ─────────────────────────────
    public function placeOrder(array $data, array $cartItems): int|false
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $orderNumber = $this->generateOrderNumber();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shipping = (float)($data['shipping_amount'] ?? 0);

        $orderId = $this->insert([
            'order_number'    => $orderNumber,
            'user_id'         => $data['user_id'],
            'status'          => $data['payment_method'] === 'cod' ? 'to_ship' : 'to_pay',
            'payment_method'  => $data['payment_method'],
            'payment_status'  => $data['payment_method'] === 'cod' ? 'pending' : 'pending',
            'payment_proof'   => $data['payment_proof'] ?? null,
            'receive_method'  => $data['receive_method'],
            'recipient_name'  => $data['recipient_name']  ?? null,
            'recipient_phone' => $data['recipient_phone'] ?? null,
            'recipient_email' => $data['recipient_email'] ?? null,
            'address'         => $data['address']         ?? null,
            'city'            => $data['city']            ?? null,
            'postal_code'     => $data['postal_code']     ?? null,
            'order_notes'     => $data['order_notes']     ?? null,
            'subtotal'        => $subtotal,
            'shipping_amount' => $shipping,
            'total_amount'    => $subtotal + $shipping,
        ]);

        if (!$orderId) {
            $db->transRollback();
            return false;
        }

        // Insert order items
        $itemModel = new OrderItemModel();
        foreach ($cartItems as $item) {
            $itemModel->insert([
                'order_id'      => $orderId,
                'product_id'    => $item['product_id'] ?? null,
                'product_name'  => $item['name'],
                'product_image' => $item['image'] ?? null,
                'unit_price'    => $item['price'],
                'quantity'      => $item['quantity'],
                'subtotal'      => $item['price'] * $item['quantity'],
            ]);
        }

        $db->transComplete();

        return $db->transStatus() ? $orderId : false;
    }
}
