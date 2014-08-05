<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('view')){
   function view($user = 'a', $file = ''){
      $view['a'] = 'a/default-min/'.$file;
      $view['u'] = 'u/default-min/'.$file;
      $view['login'] = 'login/default-min/'.$file;
      return $view[$user];
   }
}
if(!function_exists('view_folder')){
   function view_folder($user = 'a', $file = ''){
      $view_folder['a'] = base_url().'psb-application/views/a/default-min/'.$file;
      $view_folder['u'] = base_url().'psb-application/views/u/default-min/'.$file;
      $view_folder['login'] = base_url().'psb-application/views/login/default-min/'.$file;
      return $view_folder[$user];
   }
}
if(!function_exists('view_external')){
   function view_external($app = 'jq', $file = ''){
      return base_url().'psb-application/third_party/'.$app.'/'.$file;
   }
}