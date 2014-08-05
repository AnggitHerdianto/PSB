<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('encode')){
   function encode($str)   {
      $CI =& get_instance();
      return rtrim(base64_encode($str), '=');
   }
}
if (!function_exists('decode')){
   function decode($str){
      $CI =& get_instance();
      return base64_decode($str);
   }
}