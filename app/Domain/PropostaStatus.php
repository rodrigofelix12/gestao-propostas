<?php

namespace App\Domain;

class PropostaStatus
{
    public const DRAFT = 'DRAFT';
    public const SUBMITTED = 'SUBMITTED';
    public const APPROVED = 'APPROVED';
    public const REJECTED = 'REJECTED';
    public const CANCELED = 'CANCELED';

    public static function all(): array
    {
        return [
            self::DRAFT,
            self::SUBMITTED,
            self::APPROVED,
            self::REJECTED,
            self::CANCELED,
        ];
    }
}