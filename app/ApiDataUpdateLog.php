<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class ApiDataUpdateLog extends Model {

	protected $table = 'api_data_update_log';
    protected $sequence = 'api_data_update_log_id_seq';
    public $timestamps = true;
        
       
        
        // public static function boot()
        // {
        //     parent::boot();
        //     static::creating(function($post)
        //     {
        //         $post->created_by = Auth::user()->id;
        //         $post->updated_by = Auth::user()->id;
        //     });

        //     static::updating(function($post)
        //     {
        //         $post->updated_by = Auth::user()->id;
        //     });
            
           
        // }

        
        
}

