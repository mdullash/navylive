<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EvaluationPointTable extends Model
{
	protected $table = 'evaluation_point_table';
	public $timestamps = true;



	public static function boot()
	{
		parent::boot();
		static::creating(function($post)
		{
			$post->created_by = Auth::user()->id;
			$post->updated_by = Auth::user()->id;
		});

		static::updating(function($post)
		{
			$post->updated_by = Auth::user()->id;
		});


	}
}
