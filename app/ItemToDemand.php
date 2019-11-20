<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ItemToDemand extends Model {

	protected $table = 'item_to_demand';
    public $timestamps = true;

//    public function group_name() {
//        return $this->belongsTo('App\GroupName', 'publication_or_class');
//    }
        
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

