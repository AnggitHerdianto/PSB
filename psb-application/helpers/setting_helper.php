<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('setting')){
   function setting($nama = 'web_judul'){
      $CI =& get_instance();
      $CI->load->model('a/model_setting');
      $query = $CI->model_setting->cek_setting_nama($nama);
      $query = $query->row_array();
      return $query['setting_value'];
   }
}