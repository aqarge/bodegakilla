<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debt extends Model
{
    use HasFactory;

    //protected $guarded = [];
   
    public function totaldebt(): BelongsTo
    {
       return $this->belongsTo(Totaldebt::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'debtproducts')->withPivot(['quantity','subtotal'])
        ->withTimestamps();
    }
    

   
}
