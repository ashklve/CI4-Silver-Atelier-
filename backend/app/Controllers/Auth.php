<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class Auth extends BaseController
{
    // display login page
    public function login()
    {
        if (session()->has('user')) {
            $user = session()->get('user');
            if ($user['type'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            }
            return redirect()->to('/');
        }

        return view('user/login'); // ✅ fixed path, removed duplicate return
    }

    // handling login form submission
    public function authenticate()
    {
        $session = session();
        $validation = \Config\Services::validation();

        $validation->setRule('username', 'Username or Email', 'required|min_length[3]');
        $validation->setRule('password', 'Password', 'required');

        $post = $this->request->getPost();

        if (!$validation->run($post)) {
            $session->setFlashdata('errors', $validation->getErrors());
            $session->setFlashdata('old', $post);
            return redirect()->back()->withInput();
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UsersModel();
        $user = $userModel->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$user) {
            $session->setFlashdata('errors', ['username' => 'No account found with that username or email']);
            $session->setFlashdata('old', ['username' => $username]);
            return redirect()->back()->withInput();
        }

        $userArr = is_array($user) ? $user : (method_exists($user, 'toArray') ? $user->toArray() : (array) $user);

        if (!password_verify($password, $userArr['password_hash'] ?? '')) {
            $session->setFlashdata('errors', ['password' => 'Incorrect password']);
            $session->setFlashdata('old', ['username' => $username]);
            return redirect()->back()->withInput();
        }

        if ($userArr['account_status'] != 1) {
            $session->setFlashdata('errors', ['username' => 'Your account has been deactivated. Please contact support.']);
            return redirect()->back()->withInput();
        }

        $session->set('user', [
            'id'            => $userArr['id'] ?? null,
            'username'      => $userArr['username'] ?? null,
            'email'         => $userArr['email'] ?? null,
            'first_name'    => $userArr['first_name'] ?? null,
            'last_name'     => $userArr['last_name'] ?? null,
            'type'          => $userArr['type'] ?? 'client',
            'display_name'  => trim(($userArr['first_name'] ?? '') . ' ' . ($userArr['last_name'] ?? '')),
            'profile_image' => $userArr['profile_image'] ?? null,
        ]);

        $type = strtolower($userArr['type'] ?? 'client');
        if ($type === 'admin') {
            return redirect()->to('/admin/dashboard')->with('success', 'Welcome back, Admin!');
        }

        return redirect()->to('/')->with('success', 'Welcome back, ' . $userArr['first_name'] . '!');
    }

    // handle logout
    public function logout()
    {
        session()->destroy();

        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 3600,
            $params['path'] ?? '/',
            $params['domain'] ?? '',
            isset($_SERVER['HTTPS']),
            true
        );

        return redirect()->to('/')->with('success', 'You have been logged out successfully.');
    }

    // display signup page
    public function signup()
    {
        if (session()->has('user')) {
            return redirect()->to('/');
        }

        return view('user/signup'); // ✅ fixed path, removed duplicate return
    }

    // handle signup form submission
    public function register()
    {
        $session = session();
        $validation = \Config\Services::validation();

        $validation->setRules([
            'fullname'         => 'required|min_length[2]',
            'username'         => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'phone'            => 'required',
            'address'          => 'required',
            'password'         => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'terms'            => 'required'
        ]);

        $post = $this->request->getPost();

        if (!$validation->run($post)) {
            $session->setFlashdata('errors', $validation->getErrors());
            $session->setFlashdata('old', $post);
            return redirect()->back()->withInput();
        }

        $fullname   = trim($post['fullname']);
        $nameParts  = explode(' ', $fullname);
        $firstName  = '';
        $middleName = null;
        $lastName   = '';

        if (count($nameParts) == 1) {
            $firstName = $nameParts[0];
        } elseif (count($nameParts) == 2) {
            $firstName = $nameParts[0];
            $lastName  = $nameParts[1];
        } else {
            $lastName   = array_pop($nameParts);
            $middleName = array_pop($nameParts);
            $firstName  = implode(' ', $nameParts);
        }

        $userModel = new UsersModel();

        $data = [
            'username'        => $post['username'], // ✅ removed duplicate, kept original username
            'first_name'      => $firstName,
            'middle_name'     => $middleName,
            'last_name'       => $lastName,
            'email'           => $post['email'],
            'password_hash'   => password_hash($post['password'], PASSWORD_DEFAULT),
            'type'            => 'client',
            'account_status'  => 1,
            'email_activated' => 0,
            'newsletter'      => isset($post['newsletter']) ? 1 : 0,
            'gender'          => null,
            'phone'           => $post['phone'],   // ✅ removed duplicate
            'address'         => $post['address'], // ✅ removed duplicate
            'city'            => null,
            'postal_code'     => null,
            'profile_image'   => null
        ];

        $inserted = $userModel->insert($data);

        if ($inserted) {
            $session->setFlashdata('success', 'Account created successfully! Please login.');
            return redirect()->to('/login');
        } else {
            $session->setFlashdata('errors', ['general' => 'Failed to create account. Please try again.']);
            $session->setFlashdata('old', $post);
            return redirect()->back()->withInput();
        }
    }

    // display forgot password page
    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }
}
