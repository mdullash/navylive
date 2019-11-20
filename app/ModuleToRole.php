<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class ModuleToRole extends Model {

	
	protected $table = 'moduletorole';
    // protected $sequence = 'SQ_MODULETOROLE_ID';
    protected $sequence = 'moduletorole_id_seq';
        
        
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

