<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	protected $fillable = [
        'user_id', 'country', 'state', 'pin', 'location', 'flat_house_office_no', 'street_society_office_name', 'address_type', 'address_other'
    ];

}
