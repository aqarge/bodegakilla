<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debt extends Model
{
     use HasFactory;
     
        protected $guarded = [];

        public function products(): BelongsToMany
        {
            return $this->belongsToMany(Product::class, 'client_product');
        }

        public function clients(): HasMany
        {
            return $this->hasMany(Client::class, 'client_id');
        }

}
