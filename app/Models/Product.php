<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    
    public function pro_types(): belongsTo
    {
        return $this->belongsTo(Pro_type::class, 'pro_types_id');
    }
    public function debts(): BelongsTo
    {
        return $this->belongsTo(Debt::class, 'debts_id');
    }
    
}
