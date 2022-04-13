<?php

namespace App\Database\Seeds;

class UserSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        // Menambahkan data seeder untuk daata User.
        $user = [
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

        $this->db->table('user')->insertBatch($user);
    }
}
