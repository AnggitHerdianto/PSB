<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_pendidikan extends CI_Model{
   function index(){
      $query = 'SELECT * FROM pendidikan ORDER BY pend_id ASC';
      return $this->db->query($query);
   }
   function delete($data){
      $query = 'DELETE FROM pendidikan WHERE pend_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add($data){
      $query = 'INSERT INTO pendidikan SET pend_nama = ?, pend_keterangan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit($data){
      $query = 'UPDATE pendidikan SET pend_nama = ?, pend_keterangan = ? WHERE pend_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_pend_id($data){
      $query = 'SELECT * FROM pendidikan WHERE pend_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_pend_nama($data){
      $query = 'SELECT * FROM pendidikan WHERE pend_id != ? AND pend_nama = ?';
      return $this->db->query($query, to_null($data));
   }
}