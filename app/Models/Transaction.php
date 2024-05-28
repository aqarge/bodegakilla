<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;
    
    public function boxes(): BelongsTo
    {
        //return $this->hasMany(Tran_type::class);
        return $this->belongsTo(Box::class);
    }

}
