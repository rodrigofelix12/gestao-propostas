<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;

class ClienteController extends ResourceController
{
    protected $modelName = 'App\Models\ClienteModel';
    protected $format    = 'json';

    // GET /api/clientes
    public function index()
    {
        $clientes = $this->model->paginate(10);

        return $this->respond([
            'data' => $clientes,
            'pager' => [
                'currentPage' => $this->model->pager->getCurrentPage(),
                'totalPages'  => $this->model->pager->getPageCount(),
                'total'       => $this->model->pager->getTotal(),
            ]
        ]);
    }

    // GET /api/clientes/{id}
    public function show($id = null)
    {
        $cliente = $this->model->find($id);

        if (!$cliente) {
            return $this->failNotFound('Cliente não encontrado');
        }

        return $this->respond($cliente);
    }

    // POST /api/clientes
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$this->model->insert($data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        return $this->respondCreated([
            'message' => 'Cliente criado com sucesso'
        ]);
    }

    // PUT /api/clientes/{id}
    public function update($id = null)
    {
        $cliente = $this->model->find($id);

        if (!$cliente) {
            return $this->failNotFound('Cliente não encontrado');
        }

        $data = $this->request->getJSON(true);

        if (!$this->model->update($id, $data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        return $this->respond([
            'message' => 'Cliente atualizado com sucesso'
        ]);
    }

    // DELETE /api/clientes/{id}
    public function delete($id = null)
    {
        $cliente = $this->model->find($id);

        if (!$cliente) {
            return $this->failNotFound('Cliente não encontrado');
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'message' => 'Cliente removido com sucesso'
        ]);
    }
}