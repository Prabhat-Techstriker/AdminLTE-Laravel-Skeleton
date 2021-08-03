<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Challenges extends Model
{
	protected $fillable = [
		'name', 
		'price',
		'description',
		'challenge_goal',
		'challenge_period',
	];
}
