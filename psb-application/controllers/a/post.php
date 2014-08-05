<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Post extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_post');
   }
   function index($notice = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Tulisan';
      $data['notice'] = array('', '');
      $query = $this->model_post->index();
      if($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Tulisan belum ada! '.anchor('a/post/add', 'Tambah Tulisan'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_post'), $data);
   }
   function delete($post_id = 0, $do = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Tulisan';
      $data['notice'] = array('', '');
      $data['get'] = 'a/post/delete/'.$post_id.'/do';
      $data['back'] = 'a/post/index';
      $query = $this->model_post->cek_post_id(array(decode($post_id)));
      if($query->num_rows() == 0) redirect('a/post/index/none');
      $query = $query->row_array();
      $data['msg'] = 'Hapus Tulisan "'.$query['post_judul'].'"?';
      if($do == 'do') {
         $query = $this->model_post->delete(array($query['post_id']));
         if($query) redirect('a/post/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      $data['doOnLoad'] = TRUE;
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Tambah Tulisan';
      $data['notice'] = array('', '');
      $data['post'] = 'a/post/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/post/index';
      $data['data_judul'] = '';
      $data['data_tanggal'] = date('d-m-Y', time());
      $data['data_isi'] = '';
      $this->form_validation->set_rules('data_judul', ' ', 'trim|required|xss_clean');
      $this->form_validation->set_rules('data_tanggal', ' ', 'trim|required|max_length[10]|xss_clean|is_date');
      $this->form_validation->set_rules('data_isi', ' ', 'trim');
      if($this->form_validation->run()){
         $post_judul = $this->input->post('data_judul', TRUE);
         $post_link = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $post_judul));
         $post_link_new = $post_link;
         $i = 0;
         do{
            $i = $i + 1;
            $cek_post_link = $this->model_post->cek_post_link(array($post_link_new));
            if($cek_post_link->num_rows() > 0) $post_link_new = $post_link.'-'.$i;
            else{
               $i = 0;
               $post_link = $post_link_new;
            }
         } while($i > 0);
         $post_isi = $this->input->post('data_isi', FALSE);
         $post_user = $this->encrypt->decode(($this->session->userdata('user_id')));
         $post_tanggal = date('Y-m-d', strtotime($this->input->post('data_tanggal', TRUE)));
         $query = $this->model_post->add(array($post_judul, $post_link, $post_isi, $post_user, $post_tanggal));
         if($query) redirect('a/post/index/addtrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_post_data'), $data);
   }
   function edit($post_id = 0){
      $data['doOnLoad'] = TRUE;
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Tulisan';
      $data['notice'] = array('', '');
      $data['post'] = 'a/post/edit/'.$post_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/post/index';
      $query = $this->model_post->cek_post_id(array(decode($post_id)));
      if($query->num_rows() == 0) redirect('a/post/index/none');
      $query = $query->row_array();
      $data['data_judul'] = $query['post_judul'];
      $data['data_isi'] = $query['post_isi'];
      $data['data_tanggal'] = date('d-m-Y', strtotime($query['post_tanggal']));
      $this->form_validation->set_rules('data_judul', ' ', 'trim|required|xss_clean');
      $this->form_validation->set_rules('data_tanggal', ' ', 'trim|required|max_length[10]|xss_clean|is_date');
      $this->form_validation->set_rules('data_isi', ' ', 'trim');
      if($this->form_validation->run()){
         $post_id = $query['post_id'];
         $post_judul = $this->input->post('data_judul', TRUE);
         $post_link = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $post_judul));
         $post_link_new = $post_link;
         $i = 0;
         do{
            $i = $i + 1;
            $cek_post_link = $this->model_post->cek_post_link_id(array($post_id, $post_link_new));
            if($cek_post_link->num_rows() > 0) $post_link_new = $post_link.'-'.$i;
            else{
               $i = 0;
               $post_link = $post_link_new;
            }
         } while($i > 0);
         $post_isi = $this->input->post('data_isi', FALSE);
         $post_user = $this->encrypt->decode(($this->session->userdata('user_id')));
         $post_tanggal = date('Y-m-d', strtotime($this->input->post('data_tanggal', TRUE)));
         $query = $this->model_post->edit(array($post_judul, $post_link, $post_isi, $post_user, $post_tanggal, $post_id));
         if($query) $data['notice'] = array('green', 'SUCCES : Edit tulisan berhasil! '.anchor('u/post/read/'.$post_link, 'Lihat Tulisan', 'target = _blank'));
         else $data['notice'] = array('yellow', 'NOTICE : Tulisan tidak berubah! '.anchor('u/post/read/'.$post_link, 'Lihat Tulisan', 'target = _blank'));
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_post_data'), $data);
   }
}