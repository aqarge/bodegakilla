<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    public function debts(): BelongsToMany
    {
        return $this->belongsToMany(Debt::class, 'debtproducts')->withPivot(['quantity','subtotal'])
        ->withTimestamps();
    }

    public function pro_type(): BelongsTo
    {
        return $this->belongsTo(Pro_type::class, 'pro_type_id');
    }
}

