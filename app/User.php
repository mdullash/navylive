<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Auth;
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $sequence = 'users_id_seq';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    //disable the created_at and updated_at columns
    public $timestamps = true;
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public static function boot() {
        parent::boot();
        static::creating(function($post) {

            //following
            if(!empty(Auth::user()->id)){
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
            }
        });

        static::updating(function($post) {
            if(!empty(Auth::user()->id)){
                $post->updated_by = Auth::user()->id;
            }
        });
    }

    //Find the user group info
    public function UserGroup() {
        return $this->belongsTo('UserGroup', 'group_id');
    }

    //Find the user group info
    public function Role(){
        return $this->belongsTo('App\Role','role_id');
    }


}
