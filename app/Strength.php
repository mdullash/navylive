<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class Strength extends Model {

    protected $tablename = '';
    protected $table =  '';

    public function __construct()
    {
        parent::__construct();
        $this->tablename = 'strength';
        $this->table =  $this->tablename;
    }

	//protected $table = 'items';
    //protected $sequence = 'items_id_seq';
    public $timestamps  = true;
        
       
        
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

        public function strengthToItems(){
	        return $this->hasMany('App\StrengthToItem', 'strength_id', 'id');
        }
}

