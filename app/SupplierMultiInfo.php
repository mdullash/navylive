<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class SupplierMultiInfo extends Model {

    protected $tablename = '';
    protected $table =  '';

    public function __construct()
    {
        parent::__construct();
        $this->tablename = \Session::get("zoneAlise").'_supplier_multi_info';
        $this->table =  $this->tablename;
    }

    //protected $sequence = 'suppliers_id_seq';
    public $timestamps = true;
}

