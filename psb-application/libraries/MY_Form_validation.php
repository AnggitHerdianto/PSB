<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Form_validation extends CI_Form_validation{
   public function is_in($str, $field){
      list($table, $field) = explode('.', $field);
		$query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
      $this->CI->form_validation->set_message('is_in', 'Invalid value.');
		return $query->num_rows() === 1;
   }
   public function is_equal($str, $data){
      $data = explode(',', $data);
      foreach ($data as $row){ if(strtolower($str) == strtolower(trim($row))) return TRUE;}
      $this->CI->form_validation->set_message('is_equal', 'Invalid value.');
      return FALSE;
   }
   public function is_nilai($str){
      $str = str_replace(',', '.', $str);
      if(strlen($str) == 0) return TRUE;
      if(is_numeric($str)) {
         if($str >= 0 AND $str <= 10) return TRUE;
         else {
            $this->CI->form_validation->set_message('is_nilai', 'Must between 0-10.');
            return FALSE;
         }
      }
      else{
         $this->CI->form_validation->set_message('is_nilai', 'The field must contain only numbers.');
         return FALSE;
      }
   }
   public function is_test($str){
      $str = str_replace(',', '.', $str);
      if(strlen($str) == 0) return TRUE;
      if(is_numeric($str)) {
         if($str >= 0 AND $str <= 100) return TRUE;
         else {
            $this->CI->form_validation->set_message('is_test', 'Must between 0-100.');
            return FALSE;
         }
      }
      else{
         $this->CI->form_validation->set_message('is_test', 'The field must contain only numbers.');
         return FALSE;
      }
   }
   function is_date($tanggal){
      $tanggal = explode('-', $tanggal);
      if( count($tanggal) == 3 and is_numeric($tanggal[0]) and is_numeric($tanggal[1]) and is_numeric($tanggal[2]) and strlen($tanggal[2]) == 4 and checkdate($tanggal[1], $tanggal[0], $tanggal[2])) return TRUE;
      else{
          $this->CI->form_validation->set_message('is_date', 'Invalid date.');
          return FALSE;
      }
   }
}