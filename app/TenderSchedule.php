<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TenderSchedule extends Model {

	protected $table = 'tender_schedule';
    public $timestamps = true;

    public function tenderTitle() {
            return $this->belongsTo('App\Tender', 'tender_id');
        }

    public function supplierName() {
            return $this->belongsTo('App\Supplier', 'supplier_id');
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

