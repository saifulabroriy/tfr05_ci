<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNameToUser extends Migration
{
    public function up()
    {
        $this->forge->addColumn('user', [
            'nama' => [
                'type' => 'CHAR',
                'constraint' => '100',
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('user', 'nama');
    }
}
