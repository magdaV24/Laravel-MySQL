<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderedProducts extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'uuid',
        'quantity'
    ];
}
