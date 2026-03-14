<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class Auth extends BaseController
{
    // display login page
    public function login()
    {
        // if already logged in, redirect
        if (session()->has('user')) {
            $user = session()->get('user');
            if ($user['type'] === 'admin') {
                return redirect()->to('/admin/dashboard');
            }
            return redirect()->to('/');
        }

        return view('auth/login');
    }

    // handling login form submission
    public function authenticate()
    {
        $session = session();
        $validation = \Config\Services::validation();

        // validation rules
        $validation->setRule('username', 'Username or Email', 'required|min_length[3]');
        $validation->setRule('password', 'Password', 'required');

        $post = $this->request->getPost();

        // validate input
        if (!$validation->run($post)) {
            $session->setFlashdata('errors', $validation->getErrors());
            $session->setFlashdata('old', $post);
            return redirect()->back()->withInput();
        }

        // get rhe username/email from form
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // query database, check both username and email
        $userModel = new UsersModel();
        $user = $userModel->where('username', $username)
                         ->orWhere('email', $username)
                         ->first();

        // check if user exists
        if (!$user) {
            $session->setFlashdata('errors', ['username' => 'No account found with that username or email']);
            $session->setFlashdata('old', ['username' => $username]);
            return redirect()->back()->withInput();
        }

        // convert to array if it's an entity
        $userArr = is_array($user) ? $user : (method_exists($user, 'toArray') ? $user->toArray() : (array) $user);

        // verify password
        if (!password_verify($password, $userArr['password_hash'] ?? '')) {
            $session->setFlashdata('errors', ['password' => 'Incorrect password']);
            $session->setFlashdata('old', ['username' => $username]);
            return redirect()->back()->withInput();
        }

        // check if account is active
        if ($userArr['account_status'] != 1) {
            $session->setFlashdata('errors', ['username' => 'Your account has been deactivated. Please contact support.']);
            return redirect()->back()->withInput();
        }

        // create session
        $session->set('user', [
            'id' => $userArr['id'] ?? null,
            'username' => $userArr['username'] ?? null,
            'email' => $userArr['email'] ?? null,
            'first_name' => $userArr['first_name'] ?? null,
            'last_name' => $userArr['last_name'] ?? null,
            'type' => $userArr['type'] ?? 'client',
            'display_name' => trim(($userArr['first_name'] ?? '') . ' ' . ($userArr['last_name'] ?? '')),
            'profile_image' => $userArr['profile_image'] ?? null,
        ]);

        // redirect based on user type
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
        
        // remove session cookie
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
        //if already logged in, redirect
        if (session()->has('user')) {
            return redirect()->to('/');
        }

        return view('auth/signup');
    }

    // handle signup form submission
    public function register()
    {
        $session = session();
        $validation = \Config\Services::validation();

        // validation rules
        $validation->setRules([
            'fullname' => 'required|min_length[2]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'terms' => 'required'
        ]);

        $post = $this->request->getPost();

        // validate input
        if (!$validation->run($post)) {
            $session->setFlashdata('errors', $validation->getErrors());
            $session->setFlashdata('old', $post);
            return redirect()->back()->withInput();
        }

        // split fullname into first and last name
        $fullname = trim($post['fullname']);
        $nameParts = explode(' ', $fullname);

        $firstName = '';
        $middleName = null;
        $lastName = '';

        if (count($nameParts) == 1) {
            // only one name provided
            $firstName = $nameParts[0];
        } elseif (count($nameParts) == 2) {
            // twoce names: first and last
            $firstName = $nameParts[0];
            $lastName = $nameParts[1];
        } else {
            // three or more names, everything except last 2 is first name
            // second to last is middle name, last is last name
            $lastName = array_pop($nameParts);
            $middleName = array_pop($nameParts);
            $firstName = implode(' ', $nameParts);
        }

        // prepare data for insertion
        $userModel = new UsersModel();
        
        $data = [
            'username' => $post['username'],
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'email' => $post['email'],
            'password_hash' => password_hash($post['password'], PASSWORD_DEFAULT),
            'type' => 'client',
            'account_status' => 1,
            'email_activated' => 0,
            'newsletter' => isset($post['newsletter']) ? 1 : 0,
            'gender' => null,
            'phone' => null,
            'address' => null,
            'city' => null,
            'postal_code' => null,
            'profile_image' => null
        ];

        // insert into database
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