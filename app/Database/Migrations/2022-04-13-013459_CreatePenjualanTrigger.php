<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualanTrigger extends Migration
{
    public function up()
    {
        $this->db->query('
        CREATE TRIGGER tr_kurangstok AFTER INSERT ON `penjualan_detail` FOR EACH ROW
            BEGIN
                UPDATE barang SET stock=stock-NEW.jumlah WHERE id=NEW.idbarang;
            END
        ');
    }

    public function down()
    {
        $this->db->query('DROP TRIGGER `tr_kurangstok`');
    }
}
