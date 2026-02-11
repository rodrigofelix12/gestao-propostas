<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PropostaAuditoriaEntity extends Entity
{
    protected $attributes = [
        'id'           => null,
        'proposta_id'  => null,
        'actor'        => null,
        'evento'       => null,
        'payload'      => null,
        'created_at'   => null,
    ];

    protected $casts = [
        'id'          => 'integer',
        'proposta_id' => 'integer',
    ];

    protected $dates = [
        'created_at'
    ];

    public function setPayload(array $data)
    {
        $this->attributes['payload'] = json_encode($data);
        return $this;
    }

    public function getPayload()
    {
        return json_decode($this->attributes['payload'], true);
    }
}