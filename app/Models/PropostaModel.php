<?php

namespace App\Models;

use CodeIgniter\Model;

class PropostaModel extends Model
{
    protected $table = 'propostas';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'cliente_id',
        'produto',
        'valor_mensal',
        'status',
        'origem',
        'versao',
        'deleted_at'
    ];

    protected $returnType = \App\Entities\PropostaEntity::class;
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}