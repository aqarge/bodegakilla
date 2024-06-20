<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Events\PaymentCreated;
use App\Events\PaymentUpdated;
use App\Events\PaymentDeleted;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $dispatchesEvents = [
        'created' => PaymentCreated::class,
        'updated' => PaymentUpdated::class,
        'deleted' => PaymentDeleted::class,
    ];
    protected $fillable = ['totaldebt_id', 'pay', 'notes'];
    
    public function totaldebt(): BelongsTo
    {
       return $this->belongsTo(Totaldebt::class);
    }
}
