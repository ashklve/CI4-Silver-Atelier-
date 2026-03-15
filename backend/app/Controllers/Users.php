<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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
        $cart = session()->get('cart') ?? [];
        $data = [
            'title' => 'Your Shopping Cart',
            'cartItems' => array_values($cart) // re-index for easier looping in view
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

        $cart = session()->get('cart') ?? [];

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id'       => $id,
                'name'     => $name,
                'price'    => (float)$price,
                'image'    => $image ?? 'default.png',
                'quantity' => 1
            ];
        }

        session()->set('cart', $cart);

        $totalItems = array_reduce($cart, fn($sum, $item) => $sum + $item['quantity'], 0);

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
        $cart = session()->get('cart') ?? [];

        if (isset($cart[$id]) && $quantity > 0) {
            $cart[$id]['quantity'] = $quantity;
            session()->set('cart', $cart);
            return $this->response->setBody(json_encode(['success' => true, 'csrf_hash' => csrf_hash()]));
        }

        return $this->response->setStatusCode(404)->setBody(json_encode(['success' => false, 'message' => 'Item not found.', 'csrf_hash' => csrf_hash()]));
    }

    public function removeFromCart()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        $id = $this->request->getPost('id');
        $cart = session()->get('cart') ?? [];

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->set('cart', $cart);
            return $this->response->setBody(json_encode(['success' => true, 'csrf_hash' => csrf_hash()]));
        }

        return $this->response->setStatusCode(404)->setBody(json_encode(['success' => false, 'message' => 'Item not found.', 'csrf_hash' => csrf_hash()]));
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
