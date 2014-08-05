<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_prov extends CI_Model{
   function index(){
      $query = 'SELECT * FROM provinsi ORDER BY prov_pulau ASC, prov_nama ASC';
      return $this->db->query($query);
   }
   function delete($data){
      $query = 'DELETE FROM provinsi WHERE prov_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add($data){
      $query = 'INSERT INTO provinsi SET prov_nama = ?, prov_pulau = ?, prov_keterangan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit($data){
      $query = 'UPDATE provinsi SET prov_nama = ?, prov_pulau = ?, prov_keterangan = ? WHERE prov_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_prov_id($data){
      $query = 'SELECT * FROM provinsi WHERE prov_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_prov_nama($data){
      $query = 'SELECT * FROM provinsi WHERE prov_id != ? AND prov_nama = ?';
      return $this->db->query($query, to_null($data));
   }
}