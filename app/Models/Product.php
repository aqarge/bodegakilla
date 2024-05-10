<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    public function debts(): BelongsToMany
    {
        return $this->belongsToMany(Debt::class, 'debts', 'debts_id');
    }

    public function pro_types(): HasMany
    {
        return $this->HasMany(Pro_type::class, 'pro_types_id');
    }
}

