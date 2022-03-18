<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'nama' => 'Akbar Umar Alfaroq',
                'email' => 'akbarumar88@gmail.com',
                'username' => 'akbarumar88',
                'password' => password_hash('akbar1234', PASSWORD_DEFAULT),
                'role' => 1
            ],
            [
                'nama' => 'Saiful Abroriy',
                'email' => 'saiful@gmail.com',
                'username' => 'saiful',
                'password' => password_hash('saiful', PASSWORD_DEFAULT),
                'role' => 1
            ],
        ];
        $this->db->table('user')->insertBatch($users);
    }
}
