<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PoDatas extends Model {

	protected $table = 'po_datas';
    public $timestamps = true;

    public function poApprovalName() {
        return $this->belongsTo('App\User', 'po_approve_by');
    }

    public function poCheckerName() {
        return $this->belongsTo('App\User', 'po_check_by');
    }
    
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

