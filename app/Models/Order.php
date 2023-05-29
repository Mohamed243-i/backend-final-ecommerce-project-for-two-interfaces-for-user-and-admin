<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderProduct;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    // protected $fillable = ['customer_name'];
    protected $guard = [];
    protected $hidden = [
     
        'updated_at',
    ];
    // public $timestamps = false;

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);        
    }
}
