<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['name' => 'admin'],
            ['name' => 'moderator'],
            ['name' => 'superadmin'],
        ];

        // Insérez les données dans la table des rôles
        $this->db->table('role')->insertBatch($data);
    }
}
