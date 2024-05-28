<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Totaldebt extends Model
{
    

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    
}
