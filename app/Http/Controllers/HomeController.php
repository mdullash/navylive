<?php

namespace App\Http\Controllers;
use App\Banner;
use App\Cuisine;
use App\FoodMeasurment;
use App\LikeModel;
use App\OrderedProduct;
use App\Orders;
use App\Settings;
use Illuminate\Http\Request;
use App\Category;
use App\Location;
use App\Product;
use Facebook\Facebook;
use App\Subscription;
use Facebook\Authentication\AccessToken;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Session;
class HomeController extends Controller
{
    public function index(Request $request){

        return view('frontend.index');
   }

}



