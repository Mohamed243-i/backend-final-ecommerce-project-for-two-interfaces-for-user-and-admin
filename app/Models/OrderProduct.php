<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderProduct extends Model
{
    use HasFactory;

    // protected $fillable = ['order_id', 'product_name', 'price', 'quantity'];
    protected $guard = [];
    // public $timestamps = false;
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
