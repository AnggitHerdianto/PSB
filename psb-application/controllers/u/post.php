<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Post extends CI_Controller{
   function __construct(){
      parent::__construct();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('u/model_post');
      $this->load->helper('text');
      if(!is_login()) $this->output->cache(5);
   }
   function index(){
      $data['url'] = array('u', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Pengumuman';
      $data['notice'] = array('', '');
      $query = $this->model_post->index();
      if($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Belum ada tulisan!');
      $query = $query->result_array();
      foreach ($query as $key=>$row){
         if(is_login() AND is_admin()) $query[$key]['post_edit'] = anchor('a/post/edit/'.encode($row['post_id']), 'Edit');
         else $query[$key]['post_edit'] = '';
         $query[$key]['post_link'] = 'u/post/read/'.$row['post_link'];
         $query[$key]['post_isi'] = word_limiter(strip_tags($row['post_isi']), 75);
         $query[$key]['post_tanggal'] = date('d M Y', strtotime($row['post_tanggal']));
      }
      $data['post'] = $query;
      $this->load->view(view('u', 'view_post_index'), $data);
   }
   function read($post_link = FALSE){
      $data['url'] = array('u', __CLASS__, __FUNCTION__);
      //$data['judul'] = 'Pengumuman';
      $data['notice'] = array('', '');
      if(!$post_link) redirect('u/post/index');
      $query = $this->model_post->read(array($post_link));
      if($query->num_rows == 0) show_404 ();
      else $query = $query->row_array();
      $data['judul'] = $query['post_judul'];
      $data['post_judul'] = $query['post_judul'];
      $data['post_isi'] = $query['post_isi'];
      if(is_login() AND is_admin()) $data['post_edit'] = anchor('a/post/edit/'.encode($query['post_id']), 'Edit');
      else $data['post_edit'] = '';
      $data['post_tanggal'] = date('d M Y', strtotime($query['post_tanggal']));
      $this->load->view(view('u', 'view_post_read'), $data);
   }
}