<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('link_terkait')){
   function link_terkait($batas = null){
      $CI =& get_instance();
      $CI->load->model('a/model_link');
      $query = $CI->model_link->index();
      $query = $query->result_array();
      $hasil = FALSE;
      foreach ($query as $key => $row){
         if($key == 0) $hasil = $hasil.anchor($row['link_url'], $row['link_text'], 'target=new');
         else $hasil = $hasil.$batas.anchor($row['link_url'], $row['link_text'], 'target=new');
      }
      return $hasil;
   }
}