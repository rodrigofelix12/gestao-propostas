<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Domain\PropostaStatus;
use App\Domain\PropostaEvento;

class PropostaSeeder extends Seeder
{
    public function run()
    {
        $propostas = [
            [
                'cliente_id'  => 1,
                'produto'     => 'Seguro Vida',
                'valor_mensal'=> 150.00,
                'status'      => PropostaStatus::DRAFT,
                'origem'      => 'APP',
                'versao'      => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'cliente_id'  => 2,
                'produto'     => 'Seguro Auto',
                'valor_mensal'=> 320.00,
                'status'      => PropostaStatus::SUBMITTED,
                'origem'      => 'WEB',
                'versao'      => 2,
                'created_at'  => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at'  => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'cliente_id'  => 3,
                'produto'     => 'Plano Saúde',
                'valor_mensal'=> 550.00,
                'status'      => PropostaStatus::APPROVED,
                'origem'      => 'APP',
                'versao'      => 3,
                'created_at'  => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at'  => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
        ];

        $this->db->table('propostas')->insertBatch($propostas);

        $ids = $this->db->table('propostas')->select('id')->get()->getResultArray();

        foreach ($ids as $row) {

            $this->db->table('proposta_auditoria')->insert([
                'proposta_id' => $row['id'],
                'actor'       => 'seeder',
                'evento'      => PropostaEvento::CREATED,
                'payload'     => json_encode([
                    'seeded' => true
                ]),
                'created_at'  => date('Y-m-d H:i:s')
            ]);
        }
    }
}