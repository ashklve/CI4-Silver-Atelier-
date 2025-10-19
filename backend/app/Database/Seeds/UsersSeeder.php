<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'username' => '4adminash',
                'first_name' => 'Admin',
                'middle_name' => null,
                'last_name' => 'Silver',
                'email' => 'admin@silveratelier.com',
                'password_hash' => password_hash('ATsilver', PASSWORD_DEFAULT),
                'type' => 'admin',
                'account_status' => 1,
                'email_activated' => 1,
                'newsletter' => 1,
                'gender' => null,
                'phone' => '+63 912 345 6789',
                'address' => '123 Fashion Street',
                'city' => 'Manila',
                'postal_code' => '1000',
                'profile_image' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username' => 'testuser',
                'first_name' => 'Maria',
                'middle_name' => 'Santos',
                'last_name' => 'Cruz',
                'email' => 'maria@example.com',
                'password_hash' => password_hash('User123!', PASSWORD_DEFAULT),
                'type' => 'client',
                'account_status' => 1,
                'email_activated' => 1,
                'newsletter' => 1,
                'gender' => 'Female',
                'phone' => '+63 917 123 4567',
                'address' => '456 Style Avenue',
                'city' => 'Quezon City',
                'postal_code' => '1100',
                'profile_image' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
