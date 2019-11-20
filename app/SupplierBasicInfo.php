<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class SupplierBasicInfo extends Model {

    protected $tablename = '';
    protected $table =  '';

    public function __construct()
    {
        parent::__construct();
        $this->tablename = \Session::get("zoneAlise").'_suppliers_personal_info';
        $this->table =  $this->tablename;
    }

    //protected $sequence = 'suppliers_id_seq';
    public $timestamps = true;
        
        public static function boot()
        {
            parent::boot();
            static::creating(function($post)
            {
                if(empty(Auth::user()->id)){
                    $post->created_by = 1;
                    $post->updated_by = 1;
                }else{
                    $post->created_by = Auth::user()->id;
                    $post->updated_by = Auth::user()->id;
                }
                
            });

            static::updating(function($post)
            {
                if(empty(Auth::user()->id)){
                    $post->updated_by = 1;
                }else{
                    $post->updated_by = Auth::user()->id;
                }
            });
            
           
        }

        public function supplyCategoryName() {
            return $this->belongsTo('App\SupplyCategory', 'supply_cat_id');
        }

        public function nsdName() {
            return $this->belongsTo('App\NsdName', 'registered_nsd_id');
        }
        
}

