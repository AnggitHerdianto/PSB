<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('generate')){
   function generate($gel_id = FALSE){
      set_time_limit(0); 
      $CI =& get_instance();
      $CI->load->model('a/model_hasil');
      $CI->model_hasil->pilihan_status(array($gel_id)); // membuat kosong semua pilihan
      $cek_gel_id = $CI->model_hasil->cek_gel_id(array($gel_id));
      $cek_gel_id = $cek_gel_id->row_array();
      if(count($cek_gel_id) == 0) return FALSE;
      $cek_jur = $CI->model_hasil->cek_jur(array($gel_id));
      $cek_jur = $cek_jur->result_array();
      for($i = 1; $i <= $cek_gel_id['gel_jumlah_pilihan']; $i++) { // jumlah pilihan // pilihan ke
         if($i == 1){
            foreach ($cek_jur as $row) // jurusan yang dapat dipilih
               $CI->model_hasil->generate_satu(array($gel_id, $i, $row['jur_id']));
         }
         else{
            foreach ($cek_jur as $row) // jurusan yang dapat dipilih
               $CI->model_hasil->generate_dua(array($gel_id, $i, $row['jur_id']));
            $generate_cek = $CI->model_hasil->generate_cek(array($gel_id, $i));
            if($generate_cek->num_rows() > 0) $i = $i - 1;
         }
      }
   }
}