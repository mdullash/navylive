<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class NsdName extends Model {

	protected $table = 'nsdname';
    protected $sequence = 'nsdname_id_seq';
        public $timestamps = true;
    
        
}

