<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string, float>
     */

    protected $fillable = [
        'user_id',
        'price',
        'uuid',
        'address_id',
        'status',
        'paying_method',
    ];
}
