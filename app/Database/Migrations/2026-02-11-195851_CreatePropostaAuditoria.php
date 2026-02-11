<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePropostaAuditoria extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'proposta_id' => [
                'type' => 'INTEGER',
            ],
            'actor' => [
                'type' => 'TEXT',
            ],
            'evento' => [
                'type' => 'TEXT',
            ],
            'payload' => [
                'type' => 'TEXT', // JSON vira TEXT
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('proposta_id');
        $this->forge->createTable('proposta_auditoria');
    }

    public function down()
    {
        $this->forge->dropTable('proposta_auditoria');
    }
}