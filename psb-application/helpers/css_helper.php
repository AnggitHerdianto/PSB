<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('css_form_class')){
   function css_form_class($error = ''){
      if(strlen($error) > 0) $result = 'error';
      else $result = '';     
      return $result;
   }
}
if (!function_exists('css_notice')){
   function css_notice($error = ''){
      if(strlen($error) > 0) return TRUE;
      else return FALSE;
   }
}