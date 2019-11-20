<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class SupplierChat extends Model {

	protected $table = 'supplier_chat';
    public $timestamps = true;
        
       
        
        public static function boot()
        {
            parent::boot();
            static::creating(function($post)
            {
                $post->created_by = Auth::guard('supplier')->id();
                $post->updated_by = Auth::guard('supplier')->id();
            });

            static::updating(function($post)
            {
                $post->updated_by = Auth::guard('supplier')->id();
            });
            
           
        }
        
}

