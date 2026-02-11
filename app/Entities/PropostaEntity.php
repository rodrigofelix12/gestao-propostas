<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Domain\PropostaStatus;

class PropostaEntity extends Entity
{
    protected $attributes = [
        'id'           => null,
        'cliente_id'   => null,
        'produto'      => null,
        'valor_mensal' => null,
        'status'       => PropostaStatus::DRAFT,
        'origem'       => null,
        'versao'       => 1,
        'created_at'   => null,
        'updated_at'   => null,
        'deleted_at'   => null,
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'id'           => 'integer',
        'cliente_id'   => 'integer',
        'valor_mensal' => 'float',
        'versao'       => 'integer'
    ];

    public function setStatus(string $status)
    {
        if (!PropostaStatus::isValid($status)) {
            throw new \InvalidArgumentException("Status inválido");
        }

        $this->attributes['status'] = $status;
        return $this;
    }
}