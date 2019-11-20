<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class Activity extends Model {

	
	protected $table = 'activity';
    protected $sequence = 'activity_id_seq';

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = $post->updated_by = Auth::user()->id;
        });

        static::updating(function($post) {
            $post->updated_by = Auth::user()->id;
        });
    }
        
        
}

