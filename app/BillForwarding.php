<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class BillForwarding extends Model {

	protected $table = 'bill_forwarding';
    public $timestamps = true;
        
        public static function boot()
        {
            parent::boot();
            static::creating(function($post)
            {
                $post->created_by = Auth::user()->id;
                $post->created_by_rank = Auth::user()->rank;
            });

            static::updating(function($post)
            {
                $post->approved_by = Auth::user()->id;
                $post->approved_by_rank = Auth::user()->rank;
            });
            
           
        }

        
        
}

