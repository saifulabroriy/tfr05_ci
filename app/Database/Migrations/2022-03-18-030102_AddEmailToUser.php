<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailToUser extends Migration
{
    public function up()
    {
        $this->forge->addColumn('user', [
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('user', 'email');
    }
}
