<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateProductsCategoryEnum extends Migration
{
    public function up()
    {
        // Update the category ENUM to COCOIR product categories
        $this->db->query("
            ALTER TABLE `products`
            MODIFY COLUMN `category` ENUM(
                'home_living',
                'gardening',
                'construction',
                'craft_utility',
                'agriculture'
            ) NOT NULL
        ");
    }
public function down()
{
    // No rollback needed
    $this->db->query("SELECT 1");
}
}