<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['sub_category_id', 'product_id', 'package_id', 'booking_id', 'main_image', 'image'];
}
