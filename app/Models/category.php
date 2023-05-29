<?php

namespace App\Models;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;


    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
