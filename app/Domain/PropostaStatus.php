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

    public static function canTransition(string $from, string $to): bool
    {
        $transitions = [
            self::DRAFT => [self::SUBMITTED, self::CANCELED],
            self::SUBMITTED => [self::APPROVED, self::REJECTED, self::CANCELED],
            self::APPROVED => [],
            self::REJECTED => [],
            self::CANCELED => [],
        ];

        return in_array($to, $transitions[$from] ?? []);
    }

}