<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class DemandToTender extends Model {

	protected $table = 'demand_to_tender';
    public $timestamps = true;

    public function itemsToDemand() {
        return $this->hasMany('App\ItemToDemand','tender_no','tender_id');
    }
        
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

