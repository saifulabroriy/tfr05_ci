<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualan extends Migration
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
            'iduser'       => [
                'type'       => 'INT',
                'constraint' => '11',
            ],
            'idpelanggan'       => [
                'type'       => 'INT',
                'constraint' => '11',
            ],
            'tgl'       => [
                'type'       => 'DATETIME'
            ],
            'nofaktur'       => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'bayar'       => [
                'type'       => 'DECIMAL',
                'constraint' => '30,2',
            ],
            'kembali'       => [
                'type'       => 'DECIMAL',
                'constraint' => '30,2',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('penjualan');
    }

    public function down()
    {
        $this->forge->dropTable('penjualan');
    }
}
