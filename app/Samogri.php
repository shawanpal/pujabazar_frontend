<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Samogri extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['package_id', 'package_desc_id', 'item_id'];
}
