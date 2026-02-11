<?php

namespace Tests\Feature;

use Tests\Support\BaseTestCase;
use App\Services\PropostaService;

class PropostaSearchTest extends BaseTestCase
{
    protected PropostaService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PropostaService($this.db);

        // Popular com 25 propostas
        for ($i = 1; $i <= 25; $i++) {
            $this->service->create([
                'cliente_id'   => 1,
                'produto'      => 'Produto ' . $i,
                'valor_mensal' => 100 + $i,
                'origem'       => $i % 2 == 0 ? 'APP' : 'SITE'
            ]);
        }
    }

    public function testBuscaComFiltrosEPaginacao()
    {
        $result = $this->service->search([
            'origem'   => 'APP',
            'page'     => 2,
            'per_page' => 5
        ]);

        $this->assertCount(5, $result['data']);
        $this->assertEquals(2, $result['pager']['currentPage']);
    }
}