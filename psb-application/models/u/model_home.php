<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_home extends CI_Model {
   function index() {
      $query = 'SELECT * FROM post LEFT JOIN users ON user_id = post_user ORDER BY post_id DESC';
      return $this->db->query($query);
   }
   function cek_gel_ta_max() {
      $query = 'SELECT * FROM gelombang WHERE gel_ta = (SELECT MAX(gel_ta) FROM gelombang LIMIT 1)';
      return $this->db->query($query);
   }
   function cek_kuota_gel_max() {
      $query = 'SELECT * FROM kuota
      JOIN (SELECT * FROM gelombang WHERE gel_id = (SELECT MAX(gel_id) FROM gelombang LIMIT 1))AS gelombang ON gel_id = kuota_gel JOIN jurusan ON jur_id = kuota_jur';
      return $this->db->query($query);
   }
   function cek_jur() {
      $query = 'SELECT * FROM jurusan ORDER BY jur_id ASC';
      return $this->db->query($query);
   }
}