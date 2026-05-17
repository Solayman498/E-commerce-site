<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // নিশ্চিত করুন User মডেল ইমপোর্ট করা আছে

class Order extends Model
{
    protected $fillable = [
        'user_id', 
        'order_number', 
        'total_amount', 
        'status', 
        'payment_method', 
        'payment_status', 
        'shipping_name', 
        'shipping_phone', 
        'shipping_address'
    ];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }


    public function user() {
        return $this->belongsTo(User::class);
    }
}