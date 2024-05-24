<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debtproduct extends Model
{
    use HasFactory;
    protected $fillable = ['debt_id', 'product_id', 'quantity'];

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
