<?php

namespace App\Services;

use App\Models\ClienteModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ClienteService
{
    protected ClienteModel $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
    }

    public function paginate(int $perPage = 10): array
    {
        $data = $this->clienteModel->paginate($perPage);

        return [
            'data' => $data,
            'pager' => [
                'currentPage' => $this->clienteModel->pager->getCurrentPage(),
                'totalPages'  => $this->clienteModel->pager->getPageCount(),
                'total'       => $this->clienteModel->pager->getTotal(),
            ]
        ];
    }

    public function findById(int $id)
    {
        $cliente = $this->clienteModel->find($id);

        if (!$cliente) {
            throw PageNotFoundException::forPageNotFound('Cliente não encontrado');
        }

        return $cliente;
    }

    public function create(array $data)
    {
        if (!$this->clienteModel->insert($data)) {
            return [
                'errors' => $this->clienteModel->errors()
            ];
        }

        return $this->clienteModel->find($this->clienteModel->getInsertID());
    }

    public function update(int $id, array $data)
    {
        $cliente = $this->findById($id);

        if (!$this->clienteModel->update($id, $data)) {
            return [
                'errors' => $this->clienteModel->errors()
            ];
        }

        return $this->clienteModel->find($id);
    }

    public function delete(int $id): void
    {
        $this->findById($id);
        $this->clienteModel->delete($id);
    }
}