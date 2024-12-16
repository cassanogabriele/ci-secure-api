<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'usertest', 'email' => 'user_test@hotmail.com', 'password' => password_hash('user_test@hotmail.com', PASSWORD_BCRYPT)],
        ];

        // Insérez les données dans la table des rôles
        $this->db->table('user')->insertBatch($data);
    }
}
