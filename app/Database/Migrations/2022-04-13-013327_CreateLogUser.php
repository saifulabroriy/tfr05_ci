<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogUser extends Migration
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
                'constraint' => '5',
            ],
            'menu'       => [
                'type'       => 'CHAR',
                'constraint' => '255',
            ],
            'keterangan'       => [
                'type'       => 'CHAR',
                'constraint' => '255',
            ],
            'before'       => [
                'type'       => 'TEXT',
            ],
            'after'       => [
                'type'       => 'TEXT',
            ],
            'created_at timestamp default current_timestamp',
            'updated_at timestamp default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('log_user');
    }

    public function down()
    {
        $this->forge->dropTable('log_user');
    }
}
