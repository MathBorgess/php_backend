<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait TransactionTypeTraitOptions
{
    public static function options(): array
    {
        $cases = static::cases();
        $options = ["INCOME", "EXPENSE"];
        foreach ($cases as $case) {
            $label = $case->name;
            if (Str::contains($label, '_')) {
                $label = Str::replace('_', ' ', $label);
            }
            $options[] = [
                'value' => $case->value,
                'label' => Str::title($label)
            ];
        }
        return $options;
    }
}
