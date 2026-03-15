<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CartItemModel;

class Users extends BaseController
{
    public function index(): string
    {
        return view('user/landing');
    }

    public function products(): string
    {
        return view('user/products');
    }

    public function cart()
    {
        $model = new CartItemModel();
        $cartItems = $model->where($this->getCartConditions())->findAll();

        $data = [
            'title' => 'Your Shopping Cart',
            'cartItems' => $cartItems
        ];
        return view('user/cart', $data);
    }

    public function addToCart()
    {
        $this->response->setHeader('Content-Type', 'application/json');

        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $price = $this->request->getPost('price');
        $image = $this->request->getPost('image');

        if (!$id || !$name || !$price) {
            return $this->response->setStatusCode(400)->setBody(json_encode(['success' => false, 'message' => 'Invalid product data.']));
        }

        $model = new CartItemModel();
        $conditions = $this->getCartConditions();

        // Check if item exists for this user/session
        $existingItem = $model->where($conditions)->where('product_id', $id)->first();

        if ($existingItem) {
            // Update quantity
            $model->update($existingItem['id'], ['quantity' => $existingItem['quantity'] + 1]);
        } else {
            // Insert new
            $data = array_merge($conditions, [
                'product_id' => $id,
                'name'       => $name,
                'price'      => (float)$price,
                'image'      => $image ?? 'default.png',
                'quantity'   => 1
            ]);
            $model->insert($data);
        }

        $totalItems = $this->getCartTotal($model);

        $responseData = [
            'success' => true,
            'message' => "'$name' added to cart.",
            'totalItems' => $totalItems,
            'csrf_hash' => csrf_hash()
        ];

        return $this->response->setBody(json_encode($responseData));
    }

    public function updateCart()
    {
        $this->response->setHeader('Content-Type', 'application/json');

        $id = $this->request->getPost('id');
        $quantity = (int)$this->request->getPost('quantity');

        $model = new CartItemModel();

        // Note: $id here refers to the primary key of the cart_items table (passed from view)
        // We also check conditions to ensure user owns this item
        $item = $model->where($this->getCartConditions())->where('id', $id)->first();

        if ($item && $quantity > 0) {
            $model->update($id, ['quantity' => $quantity]);
            return $this->response->setBody(json_encode(['success' => true, 'csrf_hash' => csrf_hash()]));
        }

        return $this->response->setStatusCode(404)->setBody(json_encode(['success' => false, 'message' => 'Item not found.', 'csrf_hash' => csrf_hash()]));
    }

    public function removeFromCart()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $id = $this->request->getPost('id');

        $model = new CartItemModel();
        $item = $model->where($this->getCartConditions())->where('id', $id)->first();

        if ($item) {
            $model->delete($id);
            return $this->response->setBody(json_encode(['success' => true, 'csrf_hash' => csrf_hash()]));
        }

        return $this->response->setStatusCode(404)->setBody(json_encode(['success' => false, 'message' => 'Item not found.', 'csrf_hash' => csrf_hash()]));
    }

    /**
     * Helper to get database query conditions for the current user/guest
     */
    private function getCartConditions(): array
    {
        $userId = session()->get('user')['id'] ?? null;

        if ($userId) {
            return ['user_id' => $userId];
        }

        return ['session_id' => session_id()];
    }

    private function getCartTotal($model)
    {
        $result = $model->selectSum('quantity')->where($this->getCartConditions())->first();
        return (int)($result['quantity'] ?? 0);
    }

    public function login(): string
    {
        return view('user/login');
    }
    public function moodBoard(): string
    {
        return view('user/moodBoard');
    }

    public function roadMapPage(): string
    {
        return view('user/roadMapPage');
    }


    public function signup(): string
    {
        return view('user/signup');
    }
}
