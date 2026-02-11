<?php

namespace App\Models;

use CodeIgniter\Model;

class PropostaAuditoriaModel extends Model
{
    protected $table = 'proposta_auditoria';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'proposta_id',
        'actor',
        'evento',
        'payload'
    ];

    protected $returnType = \App\Entities\PropostaAuditoriaEntity::class;
    protected $useTimestamps = false;
}