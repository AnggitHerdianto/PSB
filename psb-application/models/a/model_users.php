<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_users extends CI_Model {
   function index_admin() {
      $query = 'SELECT * FROM users ORDER BY user_pangkat ASC, user_id ASC';
      return $this->db->query($query);
   }
   function index_user($data) {
      $query = 'SELECT * FROM users WHERE user_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function add($data) {
      $query = 'INSERT INTO users SET user_username = ?, user_password = ?, user_email = ?, user_pangkat = ?, user_nama = ?, user_tanggal = ?, user_keterangan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function delete($data) {
      $query = 'DELETE FROM users WHERE user_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit($data) {
      $query = 'UPDATE users SET user_username = ?, user_password = ?, user_email = ?, user_pangkat = ?, user_nama = ?, user_tanggal = ?, user_keterangan = ? WHERE user_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_user_id($data) {
      $query = 'SELECT * FROM users WHERE user_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_user_username($data) {
      $query = 'SELECT * FROM users WHERE user_id != ? AND user_username = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_user_email($data) {
      $query = 'SELECT * FROM users WHERE user_id != ? AND user_email = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_pangkat($data) {
      $query = 'SELECT * FROM users WHERE user_pangkat = ?';
      return $this->db->query($query, to_null($data));
   }
}