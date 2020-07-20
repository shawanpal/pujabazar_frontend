<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	public $timestamps = false;

	protected $casts = ['cart' => 'array', 'package_item' => 'array'];

    protected $fillable = ['user_id', 'invoice_id', 'payment_id', 'cart', 'package_item', 'create_at', 'delivery_time', 'payment_status', 'shipping_status'];
}
