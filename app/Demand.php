<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Demand extends Model {

	protected $table = 'demands';
    public $timestamps = true;

    public function navalocation_name() {
        return $this->belongsTo('App\NsdName', 'place_to_send');
    }

    public function group_name() {
        return $this->belongsTo('App\GroupName', 'publication_or_class');
    }

    public function transferOrgName() {
        return $this->belongsTo('App\NsdName', 'transfer_to');
    }

    public function demandeNameInDemand() {
        return $this->belongsTo('App\DemandeName', 'requester');
    }
    
    public function itemsToDemand() {
        return $this->hasMany('App\ItemToDemand','demand_id');
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

