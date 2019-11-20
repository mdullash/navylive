<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IssueDatas extends Model
{
    protected $table = 'issue_datas';
    public $timestamps = true;

    public function issuedName() {
        return $this->belongsTo('App\User', 'issued_by');
    }

    public function receivedName() {
        return $this->belongsTo('App\User', 'received_by');
    }

    public function approvedName() {
        return $this->belongsTo('App\User', 'approve_by');
    }

    public function groupName() {
        return $this->belongsTo('App\SupplyCategory', 'group_id');
    }

    public function demanding() {
        return $this->belongsTo('App\DemandeName', 'group_id');
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
