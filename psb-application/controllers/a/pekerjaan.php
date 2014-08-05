<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pekerjaan extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_pekerjaan');
   }
   function index($notice = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Pekerjaan';
      $data['notice'] = array('', '');
      $query = $this->model_pekerjaan->index();
      if($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Pekerjaan belum ada! '.anchor('a/pekerjaan/add', 'Tambah Pekerjaan'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_pekerjaan'), $data);
   }
   function delete($pek_id = 0, $do = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Pekerjaan';
      $data['notice'] = array('', '');
      $data['get'] = 'a/pekerjaan/delete/'.$pek_id.'/do';
      $data['back'] = 'a/pekerjaan/index';
      $query = $this->model_pekerjaan->cek_pek_id(array(decode($pek_id)));
      if($query->num_rows() == 0) redirect('a/pekerjaan/index/none');
      $query = $query->row_array();
      $data['msg'] = 'Hapus Pekerjaan "'.$query['pek_nama'].'"?';
      if($do == 'do'){
         $query = $this->model_pekerjaan->delete(array($query['pek_id']));
         if($query) redirect('a/pekerjaan/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Tambah Pekerjaan';
      $data['notice'] = array('', '');
      $data['post'] = 'a/pekerjaan/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/pekerjaan/index';
      $data['data_nama'] = '';
      $data['data_keterangan'] = '';
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|is_unique[pekerjaan.pek_nama]');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $pek_nama = $this->input->post('data_nama', TRUE);
         $pek_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_pekerjaan->add(array($pek_nama, $pek_keterangan));
         if($query) redirect('a/pekerjaan/index/addtrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_pekerjaan_data'), $data);
   }
   function edit($pek_id = 0){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Pekerjaan';
      $data['notice'] = array('', '');
      $data['post'] = 'a/pekerjaan/edit/'.$pek_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/pekerjaan/index';
      $query = $this->model_pekerjaan->cek_pek_id(array(decode($pek_id)));
      if($query->num_rows() == 0) redirect('a/pekerjaan/index/none');
      $query = $query->row_array();
      $data['data_nama'] = $query['pek_nama'];
      $data['data_keterangan'] = $query['pek_keterangan'];
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|callback_cek_pek_nama');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $pek_id = $query['pek_id'];
         $pek_nama = $this->input->post('data_nama', TRUE);
         $pek_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_pekerjaan->edit(array($pek_nama, $pek_keterangan, $pek_id));
         if($query) redirect('a/pekerjaan/index/edittrue');
         else redirect('a/pekerjaan/index/editfalse');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_pekerjaan_data'), $data);
   }
   function cek_pek_nama (){
      $pek_id = decode($this->uri->rsegment(3));
      $pek_nama = $this->input->post('data_nama', TRUE);
      $query = $this->model_pekerjaan->cek_pek_nama(array($pek_id, $pek_nama));
      if($query->num_rows() == 0) return TRUE;
      else{
         $this->form_validation->set_message('cek_pek_nama', 'The field must contain a unique value.');
         return FALSE;
      }
   }
}