<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $fillable = ['locality', 'postOffice', 'pincode', 'subDistrict', 'district', 'state', 'status'];
    public $timestamps = false;
}
