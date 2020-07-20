<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeTerm extends Model
{
    protected $fillable = ['product_id', 'package_id', 'booking_id', 'attribute_id', 'term_id'];
}
