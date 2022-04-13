<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePelanggan extends Migration
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
            'nama'       => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'alamat'       => [
                'type'       => 'TEXT'
            ],
            'notelp'       => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'created_at timestamp default current_timestamp',
            'updated_at timestamp default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pelanggan');
    }

    public function down()
    {
        $this->forge->dropTable('pelanggan');
    }
}
