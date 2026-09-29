<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public const TYPES = [
        'income'  => 'Pemasukan',
        'expense' => 'Pengeluaran',
        'saving'  => 'Tabungan',
    ];

    protected $fillable = ['name', 'type'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getBadgeClassAttribute(): string
    {
        return ['income' => 'bg-success', 'expense' => 'bg-danger', 'saving' => 'bg-primary'][$this->type] ?? 'bg-secondary';
    }

    public function getTextClassAttribute(): string
    {
        return ['income' => 'text-success', 'expense' => 'text-danger', 'saving' => 'text-primary'][$this->type] ?? '';
    }

    public function getSignAttribute(): string
    {
        return ['income' => '+', 'expense' => '-', 'saving' => '→'][$this->type] ?? '';
    }
}
