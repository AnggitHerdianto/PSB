<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_mapel extends CI_Model{
   function index(){
      $query = 'SELECT mata_pelajaran.* FROM mata_pelajaran JOIN gelombang ON gel_id = mapel_gel WHERE gel_id = (SELECT MAX(gel_id) FROM gelombang) ORDER BY mapel_id ASC';
      return $this->db->query($query);
   }
   function delete($data){
      $query = 'DELETE FROM mata_pelajaran WHERE mapel_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_mapel_jml($data){
      $query = 'SELECT * FROM mata_pelajaran WHERE mapel_gel = ?';
      return $this->db->query($query, to_null($data));
   }
   function add($data){
      $query = 'INSERT INTO mata_pelajaran SET mapel_gel = (SELECT MAX(gel_id) FROM gelombang), mapel_nama = ?, mapel_singkatan = ?, mapel_keterangan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit($data){
      $query = 'UPDATE mata_pelajaran SET mapel_gel = ?, mapel_nama = ?,  mapel_singkatan = ?, mapel_keterangan = ? WHERE mapel_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_gel_max(){
      $query = 'SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1';
      return $this->db->query($query);
   }
   function cek_mapel_id($data){
      $query = 'SELECT * FROM mata_pelajaran WHERE mapel_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function add_cek_mapel_nama($data){
      $query = 'SELECT * FROM mata_pelajaran WHERE mapel_nama = ? AND mapel_gel = (SELECT MAX(gel_id) FROM gelombang)';
      return $this->db->query($query, to_null($data));
   }
   function edit_cek_mapel_nama($data){
      $query = 'SELECT * FROM mata_pelajaran WHERE mapel_id != ? AND mapel_nama = ? AND mapel_gel = (SELECT MAX(gel_id) FROM gelombang)';
      return $this->db->query($query, to_null($data));
   }
   function cek_mapel_dipakai($data){
      $query = 'SELECT * FROM (SELECT * FROM mata_pelajaran WHERE mapel_id = ?) AS mata_pelajaran JOIN (SELECT * FROM nilai WHERE nilai_uan > 0 ) AS nilai ON nilai_mapel = mapel_id';
      return $this->db->query($query, to_null($data));
   }
   function add_nilai(){
      $query = 'INSERT INTO nilai (nilai_siswa, nilai_mapel, nilai_uan)
         SELECT siswa_id, mapel_id, "0" FROM (SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1) AS gelombang
         JOIN siswa ON siswa_gel = gel_id
         JOIN (SELECT * FROM mata_pelajaran ORDER BY mapel_id DESC LIMIT 1) AS mata_pelajaran
         WHERE (siswa_id, mapel_id) NOT IN (SELECT nilai_siswa, nilai_mapel FROM nilai JOIN siswa ON siswa_id = nilai_siswa JOIN (SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1) AS gelombang ON gel_id = siswa_gel)';
      $query = $this->db->query($query);
      return $this->db->affected_rows() > 0;
   }
}