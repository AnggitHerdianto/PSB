<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_gelombang extends CI_Model{
   function index(){
      $query = 'SELECT * FROM gelombang ORDER BY gel_id DESC';
      return $this->db->query($query);
   }
   function delete($data){
      $query = 'DELETE FROM gelombang WHERE gel_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add($data){
      $query = 'INSERT INTO gelombang SET gel_ta = ?, gel_kode = ?, gel_nama = ?, gel_tanggal_mulai = ?, gel_tanggal_selesai = ?, gel_jumlah_pilihan = ?, gel_keterangan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add_mapel(){
      $query = 'INSERT INTO mata_pelajaran (mapel_gel, mapel_nama, mapel_singkatan, mapel_keterangan)
               SELECT (SELECT MAX(gel_id) FROM gelombang) AS mapel_gel, mapel_nama, mapel_singkatan, mapel_keterangan FROM mata_pelajaran WHERE mapel_gel = (SELECT MAX(mapel_gel) FROM mata_pelajaran)';
      $query = $this->db->query($query);
      return $this->db->affected_rows() > 0;
   }
   function add_test(){
      $query = 'INSERT INTO jenis_test (jentest_gel, jentest_nama, jentest_singkatan, jentest_persen, jentest_keterangan)
               SELECT (SELECT MAX(gel_id) FROM gelombang) AS jentest_gel, jentest_nama, jentest_singkatan, jentest_persen, jentest_keterangan FROM jenis_test WHERE jentest_gel = (SELECT MAX(jentest_gel) FROM jenis_test)';
      $query = $this->db->query($query);
      return $this->db->affected_rows() > 0;
   }
   function edit($data){
      $query = 'UPDATE gelombang SET gel_ta = ?, gel_nama = ?, gel_tanggal_mulai = ?, gel_tanggal_selesai= ?, gel_jumlah_pilihan = ?, gel_keterangan = ? WHERE gel_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit_add($data){
       $query = 'UPDATE gelombang
               JOIN siswa ON siswa_gel = ?
               JOIN (SELECT * FROM pilihan WHERE pilihan_ke = 1) AS hasil ON hasil.pilihan_siswa = siswa_id
               JOIN pilihan ON pilihan.pilihan_siswa = siswa_id
               SET pilihan.pilihan_jur = hasil.pilihan_jur
               WHERE pilihan.pilihan_ke > 1 AND pilihan.pilihan_ke <= ? AND pilihan.pilihan_jur IS NULL';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_gel_id($data){
      $query = 'SELECT * FROM gelombang WHERE gel_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_gel_kode(){
      $query = ' SELECT MAX(gel_kode) as gel_kode FROM gelombang';
      return $this->db->query($query);
   }
   function cek_jur_jml(){
      $query = 'SELECT * FROM jurusan';
      return $this->db->query($query);
   }
}