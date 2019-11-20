<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DemandToCollectionQuotation extends Model {

	protected $table = 'demand_to_collection_quotation';
    public $timestamps = true;

    public function itemsSelected() {
            return $this->hasMany('App\DemandSuppllierToCollQuotToItem', 'dmn_to_cal_qut_id');
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

