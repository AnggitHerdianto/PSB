<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_kuota extends CI_Model{
   function index(){
      $query = 'SELECT kuota.*, gelombang.*, jurusan.* FROM kuota JOIN (SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1) AS gelombang ON gel_id = kuota_gel JOIN jurusan ON jur_id = kuota_jur ORDER BY gel_id ASC, jur_id ASC';
      return $this->db->query($query);
   }
   function delete($data){
      $query = 'DELETE FROM kuota WHERE kuota_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add($data){
      $query = 'INSERT INTO kuota SET kuota_gel = ?, kuota_jur = ?, kuota_jumlah = ?, kuota_cadangan = ?, kuota_keterangan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit($data){
      $query = 'UPDATE kuota SET kuota_gel = ?, kuota_jur = ?, kuota_jumlah = ?, kuota_cadangan = ?, kuota_keterangan = ? WHERE kuota_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_kuota_id($data){
      $query = ' SELECT kuota.*, gelombang.*, jurusan.* FROM kuota JOIN (SELECT  * FROM gelombang ORDER BY gel_id DESC LIMIT 1)AS gelombang ON gel_id = kuota_gel JOIN jurusan ON jur_id = kuota_jur WHERE kuota_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_gel_max(){
      $query = 'SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1';
      return $this->db->query($query);
   }
   function cek_jur(){
      $query = 'SELECT * FROM jurusan';
      return $this->db->query($query);
   }
   function cek_jur_sisa(){
      $query = 'SELECT * FROM jurusan WHERE jur_id NOT IN (SELECT kuota_jur FROM kuota JOIN (SELECT  * FROM gelombang ORDER BY gel_id DESC LIMIT 1) AS gelombang ON gel_id = kuota_gel)';
      return $this->db->query($query);
   }
}