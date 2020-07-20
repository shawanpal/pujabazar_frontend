<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['code', 'user_id', 'category_id', 'sub_category_id', 'name', 'seller_id', 'details', 'hot', 'trend'];

    public function images()
     {
         return $this->hasMany(Image::class,'package_id');
     }

     public function states()
     {
         return $this->hasMany(BusinessLocation::class,'package_id');
     }

     public function desc()
     {
         return $this->hasMany(PackageDesc::class,'package_id');
     }

     public function attributes()
     {
         return $this->hasMany(AttributeTerm::class,'package_id');
     }
}
