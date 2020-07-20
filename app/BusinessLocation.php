<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessLocation extends Model
{
    protected $fillable = ['product_id', 'package_id', 'booking_id', 'state_id'];
}
