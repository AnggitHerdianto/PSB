<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_post extends CI_Model{
   function index(){
      $query = 'SELECT * FROM post LEFT JOIN users ON user_id = post_user ORDER BY post_id DESC';
      return $this->db->query($query);
   }
   function delete($data){
      $query = 'DELETE FROM post WHERE post_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add($data){
      $query = 'INSERT INTO post SET post_judul = ?, post_link = ?, post_isi = ?, post_user = ?, post_tanggal = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit($data){
      $query = 'UPDATE post SET post_judul = ?, post_link = ?, post_isi = ?, post_user = ?, post_tanggal = ? WHERE post_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_post_id($data){
      $query = 'SELECT * FROM post WHERE post_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_post_link($data){
      $query = 'SELECT * FROM post WHERE post_link = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_post_link_id($data){
      $query = 'SELECT * FROM post WHERE post_id != ? AND post_link = ?';
      return $this->db->query($query, to_null($data));
   }
}