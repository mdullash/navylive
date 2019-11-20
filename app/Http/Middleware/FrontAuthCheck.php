<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class FrontAuthCheck
{

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {

                Session::put('hit_link',url()->current());
                return redirect()->guest('/');
            }
        }else{

            if($this->auth->user()!=null && $this->auth->user()->group_id == 3 ){
                return $next($request);
            }

            else {
                if ($this->auth->user()->group_id == null){
                    Auth::logout();
                    return redirect()->guest('/');
                }else{
                return redirect()->guest('/logout');
                }
            }

        }


    }

}
