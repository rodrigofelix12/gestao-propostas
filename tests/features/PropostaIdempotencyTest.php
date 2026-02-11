<?php

use Tests\Support\BaseTestCase;
use App\Services\PropostaService;

class PropostaIdempotencyTest extends BaseTestCase
{
    protected PropostaService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PropostaService($this->db);
    }

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
}
