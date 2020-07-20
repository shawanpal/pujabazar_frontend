<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
	public $timestamps = false;
    protected $fillable = ['heading', 'url', 'published', 'image', 'content'];
}
