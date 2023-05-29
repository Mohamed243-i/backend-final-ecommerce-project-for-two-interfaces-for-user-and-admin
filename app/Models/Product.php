<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Image;
use App\Models\OrderProduct;


class Product extends Model
{
    use HasFactory;
    protected $guard=[];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    // public $timestamps = false;


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function images() 
    {          
     return $this->hasMany(Image::class,'product_id');        
    }

    public function OrderProducts() 
    {          
     return $this->hasMany(OrderProduct::class,'product_id');        
    }
}
