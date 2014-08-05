<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pendidikan extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_pendidikan');
   }
   function index($notice = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Pendidikan';
      $data['notice'] = array('', '');
      $query = $this->model_pendidikan->index();
      if($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Pendidikan belum ada! '.anchor('a/pendidikan/add', 'Tambah Pendidikan'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_pendidikan'), $data);
   }
   function delete($pend_id = 0, $do = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Pendidikan';
      $data['notice'] = array('', '');
      $data['get'] = 'a/pendidikan/delete/'.$pend_id.'/do';
      $data['back'] = 'a/pendidikan/index';
      $query = $this->model_pendidikan->cek_pend_id(array(decode($pend_id)));
      if($query->num_rows() == 0) redirect('a/pendidikan/index/none');
      $query = $query->row_array();
      $data['msg'] = 'Hapus Pendidikan "'.$query['pend_nama'].'"?';
      if($do == 'do'){
         $query = $this->model_pendidikan->delete(array($query['pend_id']));
         if($query) redirect('a/pendidikan/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Tambah Pendidikan';
      $data['notice'] = array('', '');
      $data['post'] = 'a/pendidikan/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/pendidikan/index';
      $data['data_nama'] = '';
      $data['data_keterangan'] = '';
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|is_unique[pendidikan.pend_nama]');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $pend_nama = $this->input->post('data_nama', TRUE);
         $pend_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_pendidikan->add(array($pend_nama, $pend_keterangan));
         if($query) redirect('a/pendidikan/index/addtrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_pendidikan_data'), $data);
   }
   function edit($pend_id = 0){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Pendidikan';
      $data['notice'] = array('', '');
      $data['post'] = 'a/pendidikan/edit/'.$pend_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/pendidikan/index';
      $query = $this->model_pendidikan->cek_pend_id(array(decode($pend_id)));
      if($query->num_rows() == 0) redirect('a/pendidikan/index/none');
      $query = $query->row_array();
      $data['data_nama'] = $query['pend_nama'];
      $data['data_keterangan'] = $query['pend_keterangan'];
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|callback_cek_pend_nama');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $pend_id = $query['pend_id'];
         $pend_nama = $this->input->post('data_nama', TRUE);
         $pend_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_pendidikan->edit(array($pend_nama, $pend_keterangan, $pend_id));
         if($query) redirect('a/pendidikan/index/edittrue');
         else redirect('a/pendidikan/index/editfalse');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_pendidikan_data'), $data);
   }

   function cek_pend_nama (){
      $pend_id = decode($this->uri->rsegment(3));
      $pend_nama = $this->input->post('data_nama', TRUE);
      $query = $this->model_pendidikan->cek_pend_nama(array($pend_id, $pend_nama));
      if($query->num_rows() == 0) return TRUE;
      else{
         $this->form_validation->set_message('cek_pend_nama', 'The field must contain a unique value.');
         return FALSE;
      }
   }
}