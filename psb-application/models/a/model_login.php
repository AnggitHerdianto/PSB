<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_login extends CI_Model{
   function login($data){
      $query = 'SELECT * FROM users WHERE user_username = ? AND user_password = ?';
      return $this->db->query($query, to_null($data));
   }
   function capcay($data){
      $query = 'SELECT * FROM captcha WHERE captcha_time > ? AND word = ?';
      return $this->db->query($query, to_null($data));
   }
   function forgot($data){
      $query = 'SELECT * FROM users WHERE user_username = "'.$data[0].'" OR user_email = "'.$data[0].'"';
      return $this->db->query($query, to_null($data));
   }
   function add_reset_pass($data){
      $query = 'INSERT INTO reset_pass SET reset_users = ?, reset_link = ?, reset_input = ?, reset_expired = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_reset_pass($data){
      $query = 'SELECT * FROM reset_pass WHERE reset_link = ?';
      return $this->db->query($query, to_null($data));
   }
   function edit_reset($data){
      $query = 'UPDATE users SET user_password = ? WHERE user_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function delete_reset($data){
      $query = 'DELETE FROM reset_pass WHERE reset_users = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_user_id($data){
      $query = ' SELECT * FROM users WHERE user_id = ?';
      return $this->db->query($query, to_null($data));
   }
}