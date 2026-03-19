<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            // Snapshot of product at time of purchase
            // (product may be edited/deleted later — we keep the record)
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // nullable in case product is deleted
            ],
            'product_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'product_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => false,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->createTable('order_items', true);
    }

    public function down()
    {
        $this->forge->dropTable('order_items', true);
    }
}