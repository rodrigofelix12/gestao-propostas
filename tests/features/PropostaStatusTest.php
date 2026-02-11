<?php

namespace Tests\Feature;

use Tests\Support\BaseTestCase;
use App\Services\PropostaService;
use App\Domain\PropostaStatus;

class PropostaStatusTest extends BaseTestCase
{
    protected PropostaService $service;
    protected int $propostaId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PropostaService();

        // Criar uma proposta DRAFT para os testes
        $proposta = $this->service->create([
            'cliente_id'   => 1,
            'produto'      => 'Seguro Vida',
            'valor_mensal' => 150,
            'origem'       => 'APP'
        ]);

        $this->propostaId = $proposta->id;
    }

    public function testTransicaoValida()
    {
        $updated = $this->service->submit($this->propostaId);
        $this->assertEquals(PropostaStatus::SUBMITTED, $updated->status);

        $approved = $this->service->approve($this->propostaId);
        $this->assertEquals(PropostaStatus::APPROVED, $approved->status);
    }

    public function testTransicaoInvalida()
    {
        $this->service->submit($this->propostaId);
        $this->service->approve($this->propostaId);

        $result = $this->service->changeStatus($this->propostaId, PropostaStatus::DRAFT);

        $this->assertArrayHasKey('errors', $result);
        $this->assertStringContainsString('Transição inválida', $result['errors']['status']);
    }
}