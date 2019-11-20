<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace functions;
use Session,Redirect;

class OwnLibrary {

    //put your code here
    public static function numberformat($num = 0){
        return number_format($num, 2, '.', ',');
    }
    
    public static function printDate($date = '0000-00-00'){
        
        return date('F jS, Y', strtotime($date));
    }
    
    public static function printDateTime($dateTime = '0000-00-00 00:00:00'){
        
        return date('F jS, Y h:i A', strtotime($dateTime));
    }
    
    public static function validateAccess($moduleId = null, $activityId = null) {
        $haystack = Session::get('acl');

        $needle = array($moduleId => $activityId);

        if (!self::in_array_r($needle, $haystack)) {
            echo '<h2 style="text-align: center;">Permission denied. Contact with administration.</h2>';exit;
            header('Location: google.com');
            Redirect::to('user');
        }
    }
    
    public static function in_array_r($needle, $haystack) {

        $needleArr = array_keys($needle);
        $needleKey = $needleArr[0];
        $needleVal = $needle[$needleKey];

        foreach ($haystack as $key => $item) {
            if ($needleKey == $key) {
                foreach ($item as $activityItem) {
                    if ($needleVal == $activityItem) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public static function numToOrdinalWord($num)
        {
            $first_word = array('eth','First','Second','Third','Fourth','Fifth','Sixth','Seventh','Eighth','Ninth','Tenth','Eleventh','Twelfth','Thirteenth','Fourteenth','Fifteenth','Sixteenth','Seventeenth','Eighteenth','Nineteenth','Twentieth');
            $second_word =array('','','Twenty','Thirty','Forty','Fifty');

            if($num <= 20)
                return $first_word[$num];

            $first_num = substr($num,-1,1);
            $second_num = substr($num,-2,1);

            return $string = str_replace('y-eth','ieth',$second_word[$second_num].'-'.$first_word[$first_num]);
        }

    public static function numberTowords($num)
    {
       $number = $num;
       $no = round($number);
       $hundred = null;
       $digits_1 = strlen($no);
       $i = 0;
       $str = array();
       $words = array('0' => '', '1' => 'one', '2' => 'two',
        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
        '7' => 'seven', '8' => 'eight', '9' => 'nine',
        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
        '13' => 'thirteen', '14' => 'fourteen',
        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
        '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
        '60' => 'sixty', '70' => 'seventy',
        '80' => 'eighty', '90' => 'ninety');
       $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
       while ($i < $digits_1) {
         $divider = ($i == 2) ? 10 : 100;
         $number = floor($no % $divider);
         $no = floor($no / $divider);
         $i += ($divider == 10) ? 1 : 2;
         if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
         } else $str[] = null;
      }
      $str = array_reverse($str);
      $result = implode('', $str);
      $p=$result . "Taka only  ";
      //echo $p;
        return $p;
    }

    public static function numberTowords1($num)
  {
    $number = $num;
    $no = round($number);
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
                   '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
                   '7' => 'seven', '8' => 'eight', '9' => 'nine',
                   '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
                   '13' => 'thirteen', '14' => 'fourteen',
                   '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
                   '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
                   '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
                   '60' => 'sixty', '70' => 'seventy',
                   '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
      $divider = ($i == 2) ? 10 : 100;
      $number = floor($no % $divider);
      $no = floor($no / $divider);
      $i += ($divider == 10) ? 1 : 2;
      if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
                                   " " . $digits[$counter] . $plural . " " . $hundred
          :
          $words[floor($number / 10) * 10]
          . " " . $words[$number % 10] . " "
          . $digits[$counter] . $plural . " " . $hundred;
      } else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $p=$result;
    //echo $p;
    return $p;
  }

}
