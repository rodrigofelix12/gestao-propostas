<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'clientes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'App\Entities\Cliente';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'nome',
        'email',
        'documento'
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'nome' => 'required|min_length[3]',
        'email' => 'required|valid_email|is_unique[clientes.email,id,{id}]',
        'documento' => 'required|valid_cpf_cnpj'
    ];
}
