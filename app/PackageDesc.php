<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageDesc extends Model
{
    public $timestamps = false;

    protected $fillable  = ['package_id', 'quality', 'people', 'discount', 'stock'];
}
