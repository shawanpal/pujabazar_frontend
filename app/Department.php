<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function items()
     {
         return $this->hasMany(Item::class,'department_id');
     }
}
