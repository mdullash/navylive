<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class ItemToTender extends Model {

//	protected $table = 'itemToTender';
//    protected $sequence = 'itemToTender_id_seq';

    protected $tablename = '';
    protected $table =  '';

    public function __construct()
    {
        parent::__construct();
        $this->tablename = \Session::get("zoneAlise").'_itemtotender';
        $this->table =  $this->tablename;
    }

    public $timestamps = true;

        public static function boot()
        {
            parent::boot();
            static::creating(function($post)
            {
                if(!empty(Auth::user()->id)){
                    $post->created_by = Auth::user()->id;
                    $post->updated_by = Auth::user()->id;
                }
            });

            static::updating(function($post)
            {
                if(!empty(Auth::user()->id)){
                    $post->updated_by = Auth::user()->id;
                }
            });
            
        }

        public function supplyCategoryName() {
            return $this->belongsTo('App\SupplyCategory', 'item_cat_id');
        }

        public function denoName() {
            return $this->belongsTo('App\Deno', 'item_deno');
        }
        
}

