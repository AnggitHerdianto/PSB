<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Agama extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_agama');
   }
   function index($notice = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Agama';
      $data['notice'] = array('', '');
      $query = $this->model_agama->index();
      if($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Agama belum ada! '.anchor('a/agama/add', 'Tambah Agama'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_agama'), $data);
   }
   function delete($agama_id = 0, $do = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Agama';
      $data['notice'] = array('', '');
      $data['get'] = 'a/agama/delete/'.$agama_id.'/do';
      $data['back'] = 'a/agama/index';
      $query = $this->model_agama->cek_agama_id(array(decode($agama_id)));
      if($query->num_rows() == 0) redirect('a/agama/index/none');
      $query = $query->row_array();
      $data['msg'] = 'Hapus Agama "'.$query['agama_nama'].'"?';
      if($do == 'do'){
         $query = $this->model_agama->delete(array($query['agama_id']));
         if($query) redirect('a/agama/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Tambah Agama';
      $data['notice'] = array('', '');
      $data['post'] = 'a/agama/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/agama/index';
      $data['data_nama'] = '';
      $data['data_keterangan'] = '';
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|is_unique[agama.agama_nama]');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $agama_nama = $this->input->post('data_nama', TRUE);
         $agama_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_agama->add(array($agama_nama, $agama_keterangan));
         if($query) redirect('a/agama/index/addtrue');
      }
      else {
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_agama_data'), $data);
   }
   function edit($agama_id = 0){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Agama';
      $data['notice'] = array('', '');
      $data['post'] = 'a/agama/edit/'.$agama_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/agama/index';
      $query = $this->model_agama->cek_agama_id(array(decode($agama_id)));
      if($query->num_rows() == 0) redirect('a/agama/index/none');
      $query = $query->row_array();
      $data['data_nama'] = $query['agama_nama'];
      $data['data_keterangan'] = $query['agama_keterangan'];
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|callback_cek_agama_nama');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()) {
         $agama_id = $query['agama_id'];
         $agama_nama = $this->input->post('data_nama', TRUE);
         $agama_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_agama->edit(array($agama_nama, $agama_keterangan, $agama_id));
         if($query) redirect('a/agama/index/edittrue');
         else redirect('a/agama/index/editfalse');
      }
      else { 
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_agama_data'), $data);
   }
   function cek_agama_nama(){
      $agama_id = decode($this->uri->rsegment(3));
      $agama_nama = $this->input->post('data_nama', TRUE);
      $query = $this->model_agama->cek_agama_nama(array($agama_id, $agama_nama));
      if($query->num_rows() == 0) return TRUE;
      else{
         $this->form_validation->set_message('cek_agama_nama', 'The field must contain a unique value.');
         return FALSE;
      }
   }
}