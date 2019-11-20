<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class contactDetail extends Model {

	protected $table = 'contactdetails';
    protected $sequence = 'contactdetails_id_seq';
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

        public function zoneName() {
            return $this->belongsTo('App\Zone', 'zones');
        }

        public function navalLOcationName() {
            return $this->belongsTo('App\NsdName', 'nsd_bsd');
        }
        
}

