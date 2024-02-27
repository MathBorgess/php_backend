<?php
namespace App\Enums;

use App\Traits\{TransactionTypeTraitOptions, TransactionTypeTraitValues};

enum TransactionTypeRequestStatus: string
{
    use TransactionTypeTraitValues;
    use TransactionTypeTraitOptions;
    case INCOME = 'INCOME';
    case EXPENSE = 'EXPENSE';
}
