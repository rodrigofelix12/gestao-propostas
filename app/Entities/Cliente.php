<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Cliente extends Entity
{
    protected $attributes = [
        'id'         => null,
        'nome'       => null,
        'email'      => null,
        'documento'  => null,
        'created_at' => null,
        'updated_at' => null,
    ];

    protected $dates = ['created_at', 'updated_at'];
}
