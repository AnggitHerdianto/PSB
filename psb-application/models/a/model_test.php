<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_test extends CI_Model {
   function index() {
      $query = 'SELECT * FROM jenis_test JOIN gelombang ON gel_id = jentest_gel WHERE gel_id = (SELECT MAX(gel_id) FROM gelombang) ORDER BY jentest_id ASC';
      return $this->db->query($query);
   }
   function delete($data) {
      $query = 'DELETE FROM jenis_test WHERE jentest_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_test_jml($data) {
      $query = 'SELECT * FROM jenis_test WHERE jentest_gel = ?';
      return $this->db->query($query, to_null($data));
   }
   function add($data) {
      $query = 'INSERT INTO jenis_test SET jentest_gel = (SELECT MAX(gel_id) FROM gelombang), jentest_nama = ?, jentest_singkatan = ?, jentest_persen = ?, jentest_keterangan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit($data) {
      $query = 'UPDATE jenis_test SET jentest_gel = ?, jentest_nama = ?, jentest_singkatan = ?, jentest_persen = ?, jentest_keterangan = ? WHERE jentest_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_jentest_id($data) {
      $query = 'SELECT * FROM jenis_test WHERE jentest_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function add_cek_jentest_nama($data) {
      $query = 'SELECT * FROM jenis_test WHERE jentest_nama = ? AND jentest_gel = (SELECT MAX(gel_id) FROM gelombang)';
      return $this->db->query($query, to_null($data));
   }
   function edit_cek_jentest_nama($data) {
      $query = 'SELECT * FROM jenis_test WHERE jentest_id != ? AND jentest_nama = ? AND jentest_gel = (SELECT MAX(gel_id) FROM gelombang)';
      return $this->db->query($query, to_null($data));
   }
   function cek_jentest_dipakai($data) {
      $query = 'SELECT * FROM (SELECT * FROM jenis_test WHERE jentest_id = ?) AS jenis_test JOIN (SELECT * FROM test WHERE test_nilai > 0) AS test ON test_jentest = jentest_id';
      return $this->db->query($query, to_null($data));
   }
   function add_test() {
      $query = 'INSERT INTO test (test_siswa, test_jentest, test_nilai)
      SELECT siswa_id, jentest_id, NULL FROM (SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1 ) AS gelombang
      JOIN siswa ON siswa_gel = gel_id
      JOIN (SELECT * FROM jenis_test ORDER BY jentest_id DESC LIMIT 1) AS jenis_test
      WHERE (siswa_id, jentest_id) NOT IN (SELECT test_siswa, test_jentest FROM test JOIN siswa ON siswa_id = test_siswa JOIN (SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1) AS gelombang ON gel_id = siswa_gel)';
      $query = $this->db->query($query);
      return $this->db->affected_rows() > 0;
   }
   function cek_gel_max() {
      $query = 'SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1';
      return $this->db->query($query);
   }
}