<?php

namespace Tests\Feature;

use Tests\Support\BaseTestCase;
use App\Services\PropostaService;
use App\Domain\PropostaStatus;

class PropostaServiceTest extends BaseTestCase
{
    protected PropostaService $service;
    protected int $propostaId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PropostaService($this.db);

        // Criar uma proposta inicial para testes
        $proposta = $this->service->create([
            'cliente_id'   => 1,
            'produto'      => 'Seguro Vida',
            'valor_mensal' => 150,
            'origem'       => 'APP'
        ]);

        $this->propostaId = $proposta->id;
    }

    // --------------------------------------------------
    // 1️⃣ Teste de transição de status
    // --------------------------------------------------
    public function testTransicaoValida()
    {
        $submitted = $this->service->submit($this->propostaId);
        $this->assertEquals(PropostaStatus::SUBMITTED, $submitted->status);

        $approved = $this->service->approve($this->propostaId);
        $this->assertEquals(PropostaStatus::APPROVED, $approved->status);
    }

    public function testTransicaoInvalida()
    {
        // Aprovar direto sem passar por SUBMITTED
        $result = $this->service->changeStatus($this->propostaId, PropostaStatus::APPROVED);

        $this->assertArrayHasKey('errors', $result);
        $this->assertStringContainsString('Transição inválida', $result['errors']['status']);
    }

    // --------------------------------------------------
    // 2️⃣ Teste de idempotência
    // --------------------------------------------------
    public function testCreateIdempotent()
    {
        $data = [
            'cliente_id'   => 1,
            'produto'      => 'Seguro Vida',
            'valor_mensal' => 150,
            'origem'       => 'APP'
        ];

        $p1 = $this->service->create($data);
        $p2 = $this->service->create($data);

        $this->assertEquals($p1->produto, $p2->produto);
        $this->assertEquals($p1->valor_mensal, $p2->valor_mensal);
    }

    // --------------------------------------------------
    // 3️⃣ Teste de conflito de versão
    // --------------------------------------------------
    public function testVersaoConflito()
    {
        $proposta = $this->service->findById($this->propostaId);

        $result = $this->service->update($this->propostaId, [
            'produto' => 'Seguro Vida Premium',
            'versao'  => $proposta->versao - 1 // versão incorreta
        ]);

        $this->assertArrayHasKey('conflict', $result);
        $this->assertTrue($result['conflict']);
    }

    // --------------------------------------------------
    // 4️⃣ Teste de busca com filtros e paginação
    // --------------------------------------------------
    public function testBuscaComFiltrosEPaginacao()
    {
        // Criar mais propostas para testar paginação
        for ($i = 1; $i <= 20; $i++) {
            $this->service->create([
                'cliente_id'   => 1,
                'produto'      => 'Produto ' . $i,
                'valor_mensal' => 100 + $i,
                'origem'       => $i % 2 === 0 ? 'APP' : 'SITE'
            ]);
        }

        $result = $this->service->search([
            'origem'   => 'APP',
            'page'     => 2,
            'per_page' => 5
        ]);

        $this->assertCount(5, $result['data']);
        $this->assertEquals(2, $result['pager']['currentPage']);
        $this->assertArrayHasKey('total', $result['pager']);
    }
}