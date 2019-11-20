<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Retender extends Model {

	protected $table = 'retender';
    public $timestamps = true;

    public function itemsToDemand() {
        return $this->hasMany('App\ItemToDemand','tender_no','tender_id')->where('nhq_app_status','=',2);
    }

        
    }

