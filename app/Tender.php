<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tender extends Model {

//	protected $table = 'tenders';
//    protected $sequence = 'tenders_id_seq';

    protected $tablename = '';
    protected $table =  '';

    public function __construct()
    {
        parent::__construct();
        $this->tablename = \Session::get("zoneAlise").'_tenders';
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
            return $this->belongsTo('App\SupplyCategory', 'tender_cat_id');
        }

        public function nsdName() {
            return $this->belongsTo('App\NsdName', 'nsd_id');
        }
        
}

