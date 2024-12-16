<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRole extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],  
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],   
            'created_at datetime default current_timestamp',
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('role');
    }

    public function down()
    {
        //
    }
}
