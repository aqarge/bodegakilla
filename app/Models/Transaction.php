<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount_tran', 'descrip_tran', 'boxes_id', 'type_tran'
    ];

    public function boxes(): BelongsTo
    {
        return $this->belongsTo(Box::class, 'boxes_id');
    }
    
}
