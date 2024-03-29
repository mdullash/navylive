<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class Notice extends Model {

	protected $table = 'notices';
    protected $sequence = 'notices_id_seq';
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

        public function supplyCategoryName() {
            return $this->belongsTo('App\SupplyCategory', 'item_cat_id');
        }

        public function denoName() {
            return $this->belongsTo('App\Deno', 'item_deno');
        }
        
}

