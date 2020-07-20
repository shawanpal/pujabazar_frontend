<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'sub_category_id', 'name', 'code', 'user_id',
     'quality', 'seller_id', 'price', 'discount', 'color', 'details', 'hot', 'trend'];

     public function images()
     {
         return $this->hasMany(Image::class,'product_id');
     }

     public function states()
     {
         return $this->hasMany(BusinessLocation::class,'product_id');
     }

     public function desc()
     {
         return $this->hasMany(ProductDesc::class,'product_id');
     }

     public function attributes()
     {
         return $this->hasMany(AttributeTerm::class,'product_id');
     }
}
