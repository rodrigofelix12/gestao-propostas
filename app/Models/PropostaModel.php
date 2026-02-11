<?php

namespace App\Models;

use CodeIgniter\Model;

class PropostaModel extends Model
{
    protected $table = 'propostas';
    protected $primaryKey = 'id';

    protected $useTimestamps = false;
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'cliente_id',
        'produto',
        'valor_mensal',
        'status',
        'origem',
        'versao',
        'deleted_at'
    ];

    protected $returnType = 'array';
}