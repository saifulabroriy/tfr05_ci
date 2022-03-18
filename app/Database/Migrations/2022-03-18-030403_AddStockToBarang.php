<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStockToBarang extends Migration
{
    public function up()
    {
        $this->forge->addColumn('barang', [
            'stock' => [
                'type' => 'INT'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('barang', 'stock');
    }
}
