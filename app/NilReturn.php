<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NilReturn extends Model {

	protected $table = 'nil_return';
    public $timestamps = true;

    public function itemsToDemand() {
        return $this->hasMany('App\ItemToDemand','tender_no','tender_id')->where('nhq_app_status','=',4);
    }
}

