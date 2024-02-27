<?php
namespace App\Traits;

trait TransactionTypeTraitValues
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
