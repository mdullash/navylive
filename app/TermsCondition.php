<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class TermsCondition extends Model {

	protected $table = 'terms_conditions';
    protected $sequence = 'terms_conditions_id_seq';
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

    public function category()
    {
        return $this->belongsTo(TermsConditionCategory::class, 'category_id', 'id');
    }
}

