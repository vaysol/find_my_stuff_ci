<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropAllTables extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        
        $tables = [
            'invitations',
            'items',
            'places',
            'household_members',
            'households',
            'users'
        ];
        
        foreach ($tables as $table) {
            $this->forge->dropTable($table, true);
        }
        
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        // Do nothing
    }
}
