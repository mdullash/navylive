<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DemandToLpr extends Model {

	protected $table = 'demand_to_lpr';
    public $timestamps = true;

    public function itemsToDemand() {
        return $this->hasMany('App\ItemToDemand','lpr_id');
    }

    public function demandeNameInDemand() {
        return $this->belongsTo('App\DemandeName', 'requester');
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

