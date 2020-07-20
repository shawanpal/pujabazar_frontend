<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDesc extends Model
{
    public $timestamps = false;
    
    protected $fillable  = ['product_id','size','weight','weight_unit','size_unit','price','discount','stock'];
}
