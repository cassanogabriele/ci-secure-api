<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserRole extends Seeder
{
    public function run()
    {
        $data = [
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 17, 'role_id' => 2],
            ['user_id' => 1, 'role_id' => 3],
        ];

        // Insérez les données dans la table des rôles
        $this->db->table('user_roles')->insertBatch($data);
    }
}
