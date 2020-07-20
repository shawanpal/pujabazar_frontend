<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['department_id', 'name', 'size_weight', 'sw_unit', 'quantity', 'q_unit', 'price'];
}
