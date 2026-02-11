<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        $clientes = [
            [
                'nome' => 'João Silva',
                'email' => 'joao.silva@email.com',
                'documento' => '11144477735', // CPF válido
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nome' => 'Maria Oliveira',
                'email' => 'maria.oliveira@email.com',
                'documento' => '93541134780', // CPF válido
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nome' => 'Empresa XPTO Ltda',
                'email' => 'contato@xpto.com',
                'documento' => '11222333000181', // CNPJ válido
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('clientes')->insertBatch($clientes);
    }
}