<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class ClientSeeder extends Seeder
{
    public function run()
    {
        // Ajouter 10 clients
        for($i = 0; $i < 10; $i++){
            $this->db->table('client')->insert($this->generateClient());
        }
    }

    private function generateClient(): array
    {
        $faker = Factory::create();

        return [
            'name' => $faker->name(),
            'email' => $faker->email,
            'retainer_fee' => random_int(100000, 100000000)
        ];
    }
}
