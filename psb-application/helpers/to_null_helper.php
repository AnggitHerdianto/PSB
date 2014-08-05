<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('to_null')){
   function to_null($data = array('')){
      for($i = 0; $i < count($data); $i++){
         if(strlen($data[$i]) == 0) $data[$i] = NULL;
      }
      return $data;
   }
}