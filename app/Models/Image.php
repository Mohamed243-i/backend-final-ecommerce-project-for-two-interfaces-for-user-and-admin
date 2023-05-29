<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Image extends Model
{
    
    use HasFactory;
    protected $guard = [];
    protected $hidden = [
        'created_at',
        'id',
        'product_id',
        'updated_at',
    ];
    // protected $fillable=['name','product_id'];
    // public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }


}
