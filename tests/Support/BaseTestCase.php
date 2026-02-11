<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;
use Config\Services;

class BaseTestCase extends CIUnitTestCase
{
    protected $db;

    protected function setUp(): void
    {
        parent::setUp();

        // Conecta no banco de teste em memória
        $this->db = Database::connect('tests');

        // Roda todas as migrations no banco de teste
        $migrations = Services::migrations(null, $this->db);
        $migrations->latest();

        // Seeders
        $this->runSeeders();

        // Trunca tabelas antes de cada teste
        $this->truncateTables();
    }

    protected function tearDown(): void
    {
        // Trunca tabelas após cada teste
        $this->truncateTables();

        $this->db->close();
        parent::tearDown();
    }

    private function truncateTables()
    {
        $tables = ['clientes', 'propostas', 'proposta_auditoria'];
        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->db->table($table)->truncate();
            }
        }
    }

    private function runSeeders()
    {
        // Pega o seeder configurado no ambiente
        $seeder = \Config\Database::seeder();

        // Chama os seeders do seu projeto
        $seeder->call(\App\Database\Seeds\ClienteSeeder::class);
        $seeder->call(\App\Database\Seeds\PropostaSeeder::class);
    }
}
