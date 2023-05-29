<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
    // public $timestamps = false;


    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }


}
