<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Link extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_link');
      $this->load->helper('text');
   }
   function index($notice = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Tautan Terkait';
      $data['notice'] = array('', '');
      $query = $this->model_link->index();
      if($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Tautan belum ada! '.anchor('a/link/add', 'Tambah Tautan'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_link'), $data);
   }
   function delete($link_id = 0, $do = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Tautan';
      $data['notice'] = array('', '');
      $data['get'] = 'a/link/delete/'.$link_id.'/do';
      $data['back'] = 'a/link/index';
      $query = $this->model_link->cek_link_id(array(decode($link_id)));
      if($query->num_rows() == 0) redirect('a/link/index/none');
      $query = $query->row_array();
      $data['msg'] = 'Hapus Tautan "'.$query['link_text'].'"?';
      if($do == 'do'){
         $query = $this->model_link->delete(array($query['link_id']));
         if($query) redirect('a/link/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Tambah Tautan';
      $data['notice'] = array('', '');
      $data['post'] = 'a/link/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/link/index';
      $data['data_text'] = '';
      $data['data_url'] = '';
      $this->form_validation->set_rules('data_text', ' ', 'trim|required|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_url', ' ', 'trim|required|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $link_text = $this->input->post('data_text', TRUE);
         $link_url= prep_url($this->input->post('data_url', TRUE));
         $query = $this->model_link->add(array($link_text, $link_url));
         if($query) redirect('a/link/index/addtrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_link_data'), $data);
   }
   function edit($link_id = 0){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Tautan';
      $data['notice'] = array('', '');
      $data['post'] = 'a/link/edit/'.$link_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/link/index';
      $query = $this->model_link->cek_link_id(array(decode($link_id)));
      if($query->num_rows() == 0) redirect('a/link/index/none');
      $query = $query->row_array();
      $data['data_text'] = $query['link_text'];
      $data['data_url'] = $query['link_url'];
      $this->form_validation->set_rules('data_text', ' ', 'trim|required|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_url', ' ', 'trim|required|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $link_id = $query['link_id'];
         $link_text = $this->input->post('data_text', TRUE);
         $link_url= $this->input->post('data_url', TRUE);
         $query = $this->model_link->edit(array($link_text, $link_url, $link_id));
         if($query) redirect('a/link/index/edittrue');
         else redirect('a/link/index/editfalse');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_link_data'), $data);
   }
}