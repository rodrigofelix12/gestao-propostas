<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePropostas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'cliente_id' => [
                'type' => 'INTEGER',
            ],
            'produto' => [
                'type' => 'TEXT',
            ],
            'valor_mensal' => [
                'type' => 'REAL',
            ],
            'status' => [
                'type' => 'TEXT',
                'default' => 'DRAFT',
            ],
            'origem' => [
                'type' => 'TEXT',
            ],
            'versao' => [
                'type' => 'INTEGER',
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('cliente_id');
        $this->forge->createTable('propostas');
    }

    public function down()
    {
        $this->forge->dropTable('propostas');
    }
}