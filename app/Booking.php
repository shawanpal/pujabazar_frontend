<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['code', 'user_id', 'category_id', 'sub_category_id', 'name', 'location', 'language', 'enlisted_in', 'preferable_events', 'preferable_place', 'performane_duration', 'price', 'performance_fee', 'video', 'on_stage_team', 'off_stage_team', 'off_stage_food', 'details', 'status'];
}
