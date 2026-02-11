<?php

namespace App\Services;

use App\Models\PropostaModel;
use App\Models\PropostaAuditoriaModel;
use App\Domain\PropostaStatus;
use App\Domain\PropostaEvento;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Database;
use RuntimeException;
use DomainException;

class PropostaService
{
    private PropostaModel $propostaModel;
    private PropostaAuditoriaModel $auditoriaModel;
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
        $this->propostaModel  = new PropostaModel($this->db);
        $this->auditoriaModel = new PropostaAuditoriaModel($this->db);
    }

    public function create(array $data)
    {
        $this->db->transBegin();

        try {

            $data['status'] = PropostaStatus::DRAFT;
            $data['versao'] = 1;

            if (!$this->propostaModel->insert($data)) {
                throw new DomainException(
                    json_encode($this->propostaModel->errors())
                );
            }

            $id = $this->propostaModel->getInsertID();
            $proposta = $this->findById($id);

            $this->registrarAuditoria(
                $id,
                'system',
                PropostaEvento::CREATED,
                ['after' => $proposta->toArray()]
            );

            $this->commit();

            return $proposta;

        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function findById(int $id)
    {
        $proposta = $this->propostaModel->find($id);

        if (!$proposta) {
            throw PageNotFoundException::forPageNotFound("Proposta não encontrada");
        }

        return $proposta;
    }

    public function update(int $id, array $data)
    {
        $proposta = $this->findById($id);

        if (!isset($data['versao'])) {
            throw new DomainException('Versão é obrigatória');
        }

        if ((int)$data['versao'] !== (int)$proposta->versao) {
            throw new RuntimeException('Versão desatualizada', 409);
        }

        unset($data['versao']);

        $before = $proposta->toArray();
        $changed = array_diff_assoc($data, $before);

        if (empty($changed)) {
            return $proposta;
        }

        $this->db->transBegin();

        try {

            $data['versao'] = $proposta->versao + 1;

            if (!$this->propostaModel->update($id, $data)) {
                throw new DomainException(
                    json_encode($this->propostaModel->errors())
                );
            }

            $updated = $this->findById($id);

            $this->registrarAuditoria(
                $id,
                'system',
                PropostaEvento::UPDATED_FIELDS,
                [
                    'changed_fields' => $changed
                ]
            );

            $this->commit();

            return $updated;

        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function submit(int $id)  { return $this->changeStatus($id, PropostaStatus::SUBMITTED); }
    public function approve(int $id) { return $this->changeStatus($id, PropostaStatus::APPROVED); }
    public function reject(int $id)  { return $this->changeStatus($id, PropostaStatus::REJECTED); }
    public function cancel(int $id)  { return $this->changeStatus($id, PropostaStatus::CANCELED); }

    private function changeStatus(int $id, string $newStatus)
    {
        $proposta = $this->findById($id);

        if (!PropostaStatus::canTransition($proposta->status, $newStatus)) {
            throw new DomainException(
                "Transição inválida de {$proposta->status} para {$newStatus}"
            );
        }

        $this->db->transBegin();

        try {

            $oldStatus = $proposta->status;

            if (!$this->propostaModel->update($id, [
                'status' => $newStatus,
                'versao' => $proposta->versao + 1
            ])) {
                throw new DomainException(
                    json_encode($this->propostaModel->errors())
                );
            }

            $this->registrarAuditoria(
                $id,
                'system',
                PropostaEvento::STATUS_CHANGED,
                [
                    'from' => $oldStatus,
                    'to'   => $newStatus
                ]
            );

            $this->commit();

            return $this->findById($id);

        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        $proposta = $this->findById($id);

        $this->db->transBegin();

        try {

            $this->propostaModel->delete($id);

            $this->registrarAuditoria(
                $id,
                'system',
                PropostaEvento::DELETED_LOGICAL,
                ['status' => $proposta->status]
            );

            $this->commit();

        } catch (\Throwable $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function search(array $filters)
    {
        $model = clone $this->propostaModel;

        if (!empty($filters['status'])) {
            $model->where('status', $filters['status']);
        }

        if (!empty($filters['cliente_id'])) {
            $model->where('cliente_id', $filters['cliente_id']);
        }

        if (!empty($filters['origem'])) {
            $model->where('origem', $filters['origem']);
        }

        if (!empty($filters['data_inicio'])) {
            $model->where('created_at >=', $filters['data_inicio']);
        }

        if (!empty($filters['data_fim'])) {
            $model->where('created_at <=', $filters['data_fim']);
        }

        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? 10;

        $data = $model->paginate($perPage, 'default', $page);

        return [
            'data'  => $data,
            'pager' => $model->pager->getDetails()
        ];
    }

    private function commit(): void
    {
        if (!$this->db->transCommit()) {
            throw new DatabaseException('Erro ao confirmar transação');
        }
    }

    private function rollback(): void
    {
        $this->db->transRollback();
    }

    private function registrarAuditoria(
        int $propostaId,
        string $actor,
        string $evento,
        array $payload
    ): void {
        $this->auditoriaModel->insert([
            'proposta_id' => $propostaId,
            'actor'       => $actor,
            'evento'      => $evento,
            'payload'     => json_encode($payload),
            'created_at'  => date('Y-m-d H:i:s')
        ]);
    }

    public function getAuditoria(int $propostaId, array $filters = [])
    {
        $this->findById($propostaId);

        $builder = $this->auditoriaModel
            ->where('proposta_id', $propostaId)
            ->orderBy('created_at', 'DESC');

        $page    = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? 10;

        $data = $builder->paginate($perPage, 'default', $page);

        return [
            'data' => array_map(function ($item) {
                return [
                    'id'         => $item->id,
                    'evento'     => $item->evento,
                    'actor'      => $item->actor,
                    'payload'    => json_decode($item->payload, true),
                    'created_at' => $item->created_at,
                ];
            }, $data),
            'meta' => [
                'pagination' => [
                    'total'        => $this->auditoriaModel->pager->getTotal(),
                    'per_page'     => $perPage,
                    'current_page' => $page,
                    'last_page'    => $this->auditoriaModel->pager->getPageCount(),
                ]
            ]
        ];
    }

}