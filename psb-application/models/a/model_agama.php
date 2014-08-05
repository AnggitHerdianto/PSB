<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_agama extends CI_Model{
   function index(){
      $query = 'SELECT * FROM agama ORDER BY agama_id ASC';
      return $this->db->query($query);
   }
   function delete($data){
      $query = 'DELETE FROM agama WHERE agama_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add($data){
      $query = 'INSERT INTO agama SET agama_nama = ?, agama_keterangan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit($data){
      $query = 'UPDATE agama SET agama_nama = ?, agama_keterangan = ? WHERE agama_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_agama_id($data){
      $query = 'SELECT * FROM agama WHERE agama_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_agama_nama($data){
      $query = 'SELECT * FROM agama WHERE agama_id != ? AND agama_nama = ?';
      return $this->db->query($query, to_null($data));
   }
}