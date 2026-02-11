<?php

namespace Tests\Feature;

use Tests\Support\BaseTestCase;
use App\Services\PropostaService;

class PropostaVersionTest extends BaseTestCase
{
    protected PropostaService $service;
    protected int $propostaId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PropostaService();

        $proposta = $this->service->create([
            'cliente_id'   => 1,
            'produto'      => 'Seguro Vida',
            'valor_mensal' => 150,
            'origem'       => 'APP'
        ]);

        $this->propostaId = $proposta->id;
    }

    public function testVersaoConflito()
    {
        $proposta = $this->service->findById($this->propostaId);

        // Atualizar com versão incorreta
        $result = $this->service->update($this->propostaId, [
            'produto' => 'Seguro Vida Premium',
            'versao'  => $proposta->versao - 1
        ]);

        $this->assertArrayHasKey('conflict', $result);
        $this->assertTrue($result['conflict']);
    }
}