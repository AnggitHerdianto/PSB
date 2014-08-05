<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_jurusan extends CI_Model{
   function index(){
      $query = 'SELECT * FROM jurusan ORDER BY jur_id ASC';
      return $this->db->query($query);
   }
   function delete($data){
      $query = 'DELETE FROM jurusan WHERE jur_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add($data){
      $query = 'INSERT INTO jurusan SET jur_nama = ?, jur_singkatan = ?, jur_no_sk = ?, jur_tanggal = ?, jur_keterangan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit($data){
      $query = 'UPDATE jurusan SET jur_nama = ?, jur_singkatan = ?, jur_no_sk = ?, jur_tanggal = ?, jur_keterangan = ? WHERE jur_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_jur_id($data){
      $query = 'SELECT * FROM jurusan WHERE jur_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_jur_nama($data){
      $query = 'SELECT * FROM jurusan WHERE jur_id != ? AND jur_nama = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_jur_dipakai($data){
     $query = 'SELECT * FROM (SELECT * FROM jurusan WHERE jur_id = ?) AS jurusan WHERE jur_id IN (SELECT pilihan_jur FROM pilihan WHERE pilihan_jur IS NOT NULL GROUP BY pilihan_jur)';
     return $this->db->query($query, to_null($data));
   }
   function gel_jumlah_pilihan(){
       $query = 'UPDATE gelombang
         JOIN (SELECT gel_id, gel_jumlah_pilihan FROM gelombang WHERE gel_ta = YEAR(NOW())) AS gelombang_max USING(gel_id)
         JOIN (SELECT COUNT(jur_id) AS jur_jumlah FROM jurusan LIMIT 1) AS jurusan_jumlah
         SET gelombang.gel_jumlah_pilihan = IF(
             gelombang_max.gel_jumlah_pilihan > jurusan_jumlah.jur_jumlah,
             jurusan_jumlah.jur_jumlah,
             gelombang_max.gel_jumlah_pilihan)';
      $query = $this->db->query($query);
      return $this->db->affected_rows() > 0;
   }
}