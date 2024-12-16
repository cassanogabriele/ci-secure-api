<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSoftDeleteClient extends Migration
{
    public function up()
    {
        $this->forge->addColumn('client', [
            'softdelete' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
        ]);

        $this->forge->addColumn('user', [
            'softdelete' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('client', 'softdelete');
        $this->forge->dropColumn('user', 'softdelete');
        $this->forge->dropColumn('user_roles', 'softdelete');
    }
}
