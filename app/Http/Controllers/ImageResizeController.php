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
class ImageResizeController extends Controller
{
	
    //***************************************  Thumbnails Generating Functions :: Start *****************************
    public function load($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    public function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
        if ($permissions != null) {
            chmod($filename, $permissions);
        }
    }

    public function output($image_type = IMAGETYPE_JPEG) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    public function getWidth() {
        return imagesx($this->image);
    }

    public function getHeight() {
        return imagesy($this->image);
    }

    public function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    public function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    public function resize($width, $height) {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

    //***************************************  Thumbnails Generating Functions :: End *****************************
    static function custom_format($n, $d = 0) {
        if (strpos($n, ".") !== false) {
            $d = 2;
        }else{
            $d = 2;
        }
        $n = number_format($n, $d, '.', '');
        $n = strrev($n);

        if ($d) $d++;
        $d += 3;

        if (strlen($n) > $d)
            $n = substr($n, 0, $d) . ','
                . implode(',', str_split(substr($n, $d), 2));

        return strrev($n);
    }


}



