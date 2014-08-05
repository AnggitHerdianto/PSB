<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_setting extends CI_Model{
   function cek_setting(){
      $query = 'SELECT * FROM setting';
      return $this->db->query($query);
   }
   function cek_setting_nama($data){
      $query = 'SELECT * FROM setting WHERE setting_nama = ?';
      return $this->db->query($query, to_null($data));
   }
   function edit($data){
      $query = 'UPDATE setting SET setting_value = "'.$data[1].'" WHERE setting_nama = "'.$data[0].'"';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
}