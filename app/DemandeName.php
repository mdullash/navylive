<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DemandeName extends Model {

	protected $table = 'demande_name';
    public $timestamps = true;

    // public function navalocation_name() {
    //     return $this->belongsTo('App\NsdName', 'place_to_send');
    // }

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

