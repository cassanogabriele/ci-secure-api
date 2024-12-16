<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddClientActive extends Migration
{
    public function up()
    {
        $this->forge->addColumn('client', [
            'active' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('client', 'active');
    }
}
