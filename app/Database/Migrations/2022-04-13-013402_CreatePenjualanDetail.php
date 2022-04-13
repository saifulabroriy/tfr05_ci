<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualanDetail extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'idpenjualan'       => [
                'type'       => 'INT',
                'constraint' => '11',
            ],
            'idbarang'       => [
                'type'       => 'INT',
                'constraint' => '11',
            ],
            'harga'       => [
                'type'       => 'DECIMAL',
                'constraint' => '30,2',
            ],
            'jumlah'       => [
                'type'       => 'DECIMAL',
                'constraint' => '30,2',
            ],
            'subtotal'       => [
                'type'       => 'DECIMAL',
                'constraint' => '30,2',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('penjualan_detail');
    }

    public function down()
    {
        $this->forge->dropTable('penjualan_detail');
    }
}
