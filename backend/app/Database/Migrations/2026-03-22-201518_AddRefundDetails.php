<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRefundFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'refund_reason' => [
                'type'  => 'TEXT',
                'null'  => true,
                'after' => 'status',
            ],
            'refund_proof' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'refund_reason',
            ],
            'refund_qr' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'refund_proof',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', ['refund_reason', 'refund_proof', 'refund_qr']);
    }
}
