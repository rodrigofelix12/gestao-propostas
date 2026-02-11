<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Services\ClienteService;
use CodeIgniter\Exceptions\PageNotFoundException;

class ClienteController extends ResourceController
{
    protected $format = 'json';
    protected ClienteService $clienteService;

    public function __construct()
    {
        $this->clienteService = new ClienteService();
    }

    public function index()
    {
        return $this->respond(
            $this->clienteService->paginate(10)
        );
    }

    public function show($id = null)
    {
        try {
            $cliente = $this->clienteService->findById((int) $id);
            return $this->respond($cliente);
        } catch (PageNotFoundException $e) {
            return $this->failNotFound($e->getMessage());
        }
    }

    public function create()
    {
        $data = $this->request->getJSON(true);

        $result = $this->clienteService->create($data);

        if (isset($result['errors'])) {
            return $this->failValidationErrors($result['errors']);
        }

        return $this->respondCreated($result);
    }

    public function update($id = null)
    {
        $data = $this->request->getJSON(true);

        try {
            $result = $this->clienteService->update((int) $id, $data);

            if (isset($result['errors'])) {
                return $this->failValidationErrors($result['errors']);
            }

            return $this->respond($result);

        } catch (PageNotFoundException $e) {
            return $this->failNotFound($e->getMessage());
        }
    }

    public function delete($id = null)
    {
        try {
            $this->clienteService->delete((int) $id);
            return $this->respondDeleted(['message' => 'Cliente removido com sucesso']);
        } catch (PageNotFoundException $e) {
            return $this->failNotFound($e->getMessage());
        }
    }
}