<?php

namespace App\Controllers\Api\V1;

use App\Services\PropostaService;

class PropostaController extends BaseApiController
{
    protected PropostaService $service;

    public function __construct()
    {
        $this->service = new PropostaService();
    }

    // GET /propostas
    public function index()
    {
        return $this->handle(function () {

            $filters = $this->request->getGet();

            return $this->service->search($filters);
        });
    }

    // GET /propostas/{id}
    public function show($id = null)
    {
        return $this->handle(function () use ($id) {

            return $this->service->findById((int) $id);
        });
    }

    // POST /propostas
    public function create()
    {
        return $this->handle(function () {

            $data = $this->request->getJSON(true);

            $result = $this->service->create($data);

            return $this->respondCreated($result);
        });
    }

    // PUT /propostas/{id}
    public function update($id = null)
    {
        return $this->handle(function () use ($id) {

            $data = $this->request->getJSON(true);

            return $this->service->update((int) $id, $data);
        });
    }

    // DELETE /propostas/{id}
    public function delete($id = null)
    {
        return $this->handle(function () use ($id) {

            $this->service->delete((int) $id);

            return $this->respondDeleted(['message' => 'Proposta removida']);
        });
    }

    // POST /propostas/{id}/submit
    public function submit($id)
    {
        return $this->handle(fn() =>
            $this->service->submit((int) $id)
        );
    }

    public function approve($id)
    {
        return $this->handle(fn() =>
            $this->service->approve((int) $id)
        );
    }

    public function reject($id)
    {
        return $this->handle(fn() =>
            $this->service->reject((int) $id)
        );
    }

    public function cancel($id)
    {
        return $this->handle(fn() =>
            $this->service->cancel((int) $id)
        );
    }
}