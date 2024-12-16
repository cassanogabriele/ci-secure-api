<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserActive extends Migration
{
    public function up()
    {
        $this->forge->addColumn('user', [
            'active' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('user', 'active');
    }
}
