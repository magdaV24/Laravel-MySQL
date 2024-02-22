<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'description',
        'uuid'
    ];

    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }

    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    public function photos(){
        return $this->hasMany(Photo::class);
    }
}
