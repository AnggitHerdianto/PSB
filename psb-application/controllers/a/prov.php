<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Prov extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_prov');
   }
   function index($notice = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Provinsi';
      $data['notice'] = array('', '');
      $query = $this->model_prov->index();
      if($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Provinsi belum ada! '.anchor('a/prov/add', 'Tambah Provinsi'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_prov'), $data);
   }
   function delete($prov_id = 0, $do = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Provinsi';
      $data['notice'] = array('', '');
      $data['get'] = 'a/prov/delete/'.$prov_id.'/do';
      $data['back'] = 'a/prov/index';
      $query = $this->model_prov->cek_prov_id(array(decode($prov_id)));
      if($query->num_rows() == 0) redirect('a/prov/index/none');
      $query = $query->row_array();
      $data['msg'] = 'Hapus Provinsi "'.$query['prov_nama'].'"?';
      if($do == 'do'){
         $query = $this->model_prov->delete(array($query['prov_id']));
         if($query) redirect('a/prov/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Tambah Provinsi';
      $data['notice'] = array('', '');
      $data['post'] = 'a/prov/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/prov/index';
      $data['data_nama'] = '';
      $data['data_pulau'] = '';
      $data['data_keterangan'] = '';
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|is_unique[provinsi.prov_nama]');
      $this->form_validation->set_rules('data_pulau', ' ', 'trim|max_length[64]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $prov_nama = $this->input->post('data_nama', TRUE);
         $prov_pulau = $this->input->post('data_pulau', TRUE);
         $prov_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_prov->add(array($prov_nama, $prov_pulau, $prov_keterangan));
         if($query) redirect('a/prov/index/addtrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_prov_data'), $data);
   }
   function edit($prov_id = 0){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Provinsi';
      $data['notice'] = array('', '');
      $data['post'] = 'a/prov/edit/'.$prov_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/prov/index';
      $query = $this->model_prov->cek_prov_id(array(decode($prov_id)));
      if($query->num_rows() == 0) redirect('a/prov/index/none');
      $query = $query->row_array();
      $data['data_nama'] = $query['prov_nama'];
      $data['data_pulau'] = $query['prov_pulau'];
      $data['data_keterangan'] = $query['prov_keterangan'];
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|callback_cek_prov_nama');
      $this->form_validation->set_rules('data_pulau', ' ', 'trim|max_length[64]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $prov_id = $query['prov_id'];
         $prov_nama = $this->input->post('data_nama', TRUE);
         $prov_pulau = $this->input->post('data_pulau', TRUE);
         $prov_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_prov->edit(array($prov_nama, $prov_pulau, $prov_keterangan, $prov_id));
         if($query) redirect('a/prov/index/edittrue');
         else redirect('a/prov/index/editfalse');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_prov_data'), $data);
   }
   function cek_prov_nama (){
      $prov_id = decode($this->uri->rsegment(3));
      $prov_nama = $this->input->post('data_nama', TRUE);
      $query = $this->model_prov->cek_prov_nama(array($prov_id, $prov_nama));
      if($query->num_rows() == 0) return TRUE;
      else{
         $this->form_validation->set_message('cek_prov_nama', 'The field must contain a unique value.');
         return FALSE;
      }
   }
}