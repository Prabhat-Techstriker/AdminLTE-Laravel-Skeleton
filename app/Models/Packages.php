<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
	protected $fillable = [
		'package_name', 
		'package_price',
		'package_description',
		'goal_id',
		'package_duration',
	];
}
