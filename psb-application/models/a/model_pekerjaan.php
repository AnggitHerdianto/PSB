<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_pekerjaan extends CI_Model{
   function index(){
      $query = 'SELECT * FROM (SELECT * FROM pekerjaan WHERE pek_nama NOT LIKE "%lain%" ORDER BY pek_id ASC) AS a
          UNION SELECT * FROM ( SELECT * FROM pekerjaan WHERE pek_nama LIKE "%lain%" ORDER BY pek_id ASC) AS b';
      return $this->db->query($query);
   }
   function delete($data){
      $query = 'DELETE FROM pekerjaan WHERE pek_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add($data){
      $query = 'INSERT INTO pekerjaan SET pek_nama = ?, pek_keterangan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit($data){
      $query = 'UPDATE pekerjaan SET pek_nama = ?, pek_keterangan = ? WHERE pek_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_pek_id($data){
      $query = 'SELECT * FROM pekerjaan WHERE pek_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_pek_nama($data){
      $query = ' SELECT * FROM pekerjaan WHERE pek_id != ? AND pek_nama = ?';
      return $this->db->query($query, to_null($data));
   }
}