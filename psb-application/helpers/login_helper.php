<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('login')){
   function login($user_username = FALSE, $user_password = FALSE){
      $CI =& get_instance();
      $CI->load->model('a/model_login');
      $query = $CI->model_login->login(array($user_username, $user_password));
      if($query->num_rows() == 1){
         $query = $query->row_array();
         $CI->session->set_userdata('login', $CI->encrypt->encode(md5($query['user_id'].$query['user_username'])));
         $CI->session->set_userdata('user_id', $CI->encrypt->encode($query['user_id']));
         return TRUE;
      }
      else{
         logout(); 
         return FALSE;
      }
   }
}
if (!function_exists('capcay')){
   function capcay($user_expiration = FALSE, $user_capcay = FALSE){
      $CI =& get_instance();
      $CI->load->model('a/model_login');
      $query = $CI->model_login->capcay(array($user_expiration, $user_capcay));
      if($query->num_rows() >= 1){
         return TRUE;
      }
      else{
         return FALSE;
      }
   }
}
if (!function_exists('logout')){
   function logout(){
      $CI =& get_instance();
      $CI->session->unset_userdata('login');
      $CI->session->unset_userdata('user_id');
      $CI->session->unset_userdata('cek_siswa_id');
      $CI->session->sess_destroy();
      return TRUE;
   }
}
if (!function_exists('is_login')){
   function is_login(){
      $CI =& get_instance();
      if($CI->session->userdata('login')){
         $CI->load->model('a/model_login');
         $user_id = $CI->encrypt->decode($CI->session->userdata('user_id'));
         $query = $CI->model_login->cek_user_id(array($user_id));
         if($query->num_rows() == 1) return TRUE; // login
         else return FALSE; // not login
      }
      else return FALSE; // not login
   }
}
if (!function_exists('is_admin')){
   function is_admin(){
      $CI =& get_instance();
      if(is_login()){
         $CI->load->model('a/model_login');
         $user_id = $CI->encrypt->decode($CI->session->userdata('user_id'));
         $query = $CI->model_login->cek_user_id(array($user_id));
         $query = $query->row_array();
         if($query['user_pangkat'] == 'admin') return TRUE; // admin
         else return FALSE; // user
      }
      else return FALSE; // user
   }
}