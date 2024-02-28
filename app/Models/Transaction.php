<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\{TransactionCategory, User};


class Transaction extends Model
{
    protected $table = 'transactions';
    use HasApiTokens, HasFactory, Notifiable, HasUuids;
    static function transactionTypes()
    {
        return [
            "income",
            "expense",
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'value',
        'type',
        'category_id',
        'user_id'
    ];

    protected $casts = [
        'value' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
