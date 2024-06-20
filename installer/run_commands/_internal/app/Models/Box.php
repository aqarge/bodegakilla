<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Box extends Model
{
    use HasFactory;
    protected $fillable = [
        'opening', 'income', 'expenses', 'revenue'
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'boxes_id');
    }
   
}
