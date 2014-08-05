<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_link extends CI_Model{
   function index(){
      $query = 'SELECT * FROM link ORDER BY link_id ASC';
      return $this->db->query($query);
   }
   function delete($data){
      $query = 'DELETE FROM link WHERE link_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add($data){
      $query = 'INSERT INTO link SET link_text = ?, link_url = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit($data){
      $query = 'UPDATE link SET link_text = ?, link_url = ? WHERE link_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_link_id($data){
      $query = 'SELECT * FROM link WHERE link_id = ?';
      return $this->db->query($query, to_null($data));
   }
}