<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //compose all the views....
        view()->composer('*', function ($view) 
        {
            $a = empty(Request::segment(1))? '0/':Request::segment(1).'/';
            $b = empty(Request::segment(2))? '0/':Request::segment(2).'/';

            $zonesFhead = '';
            if(!empty(\Auth::user()->zones)){
                $abc = explode(',', \Auth::user()->zones);
                $zonesFhead = \App\Zone::where('status','=',1)->whereIn('id',$abc)->get();
            }

            //...with this variable
            $view->with('a', $a);    
            $view->with('b', $b); 
            $view->with('zonesFhead', $zonesFhead);      
        });  

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
