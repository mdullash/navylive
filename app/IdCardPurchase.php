<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IdCardPurchase extends Model
{
    protected $tablename = '';
    protected $table =  '';

    public function __construct()
    {
        parent::__construct();
        $this->tablename = \Session::get("zoneAlise").'_id_card_purchase';
        $this->table =  $this->tablename;
    }


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
}
