<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_post extends CI_Model {
   function index() {
      $query = 'SELECT * FROM post LEFT JOIN users ON user_id = post_user ORDER BY post_id DESC';
      return $this->db->query($query);
   }
   function read($data) {
      $query = 'SELECT * FROM post WHERE post_link = ?';
      return $this->db->query($query, to_null($data));
   }
}