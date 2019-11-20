<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Auth;

class Supplier extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract{

    use Authenticatable, Authorizable, CanResetPassword;

    protected $tablename = '';
    protected $table =  '';

    public function __construct()
    {
        parent::__construct();
        $this->tablename = \Session::get("zoneAlise").'_suppliers';
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

        public function supplierChats()
        {
            return $this->hasOne('App\SupplierChat','supplier_id')->latest();
          // return $this->hasMany('App\SupplierChat','supplier_id');
        }
        
}

