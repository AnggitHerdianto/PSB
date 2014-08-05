<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jurusan extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_jurusan');
   }
   function index($notice = FALSE) {
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Jurusan';
      $data['notice'] = array('', '');
      $query = $this->model_jurusan->index();
      if($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Jurusan belum ada! '.anchor('a/jurusan/add', 'Tambah Jurusan'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'deletefalse') $data['notice'] = array('red', 'ERROR : Data tidak dapat dihapus! Jurusan yang sudah digunakan tidak dapat dihapus.');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_jurusan'), $data);
   }
   function delete($jur_id = 0, $do = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Jurusan';
      $data['notice'] = array('', '');
      $data['get'] = 'a/jurusan/delete/'.$jur_id.'/do';
      $data['back'] = 'a/jurusan/index';
      $query = $this->model_jurusan->cek_jur_id(array(decode($jur_id)));
      if($query->num_rows() == 0) redirect('a/jurusan/index/none');
      $cek_jur_dipakai = $this->model_jurusan->cek_jur_dipakai(array(decode($jur_id)));
      if($cek_jur_dipakai->num_rows() > 0) redirect('a/jurusan/index/deletefalse');
      $query = $query->row_array();
      $data['msg'] = 'Hapus Jurusan "'.$query['jur_nama'].'"?';
      if($do == 'do'){
         $query = $this->model_jurusan->delete(array($query['jur_id']));
         // jika berhail hapus maka update banyak pilihan pada gelombang terkahir
         // $this->model_jurusan->gel_jumlah_pilihan();
         if($query) redirect('a/jurusan/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      $data['doOnLoad'] = TRUE;
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Tambah Jurusan';
      $data['notice'] = array('', '');
      $data['post'] = 'a/jurusan/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/jurusan/index';
      $data['data_nama'] = '';
      $data['data_singkatan'] = '';
      $data['data_no_sk'] = '';
      $data['data_tanggal'] = date('d-m-Y');
      $data['data_keterangan'] = '';
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[128]|xss_clean|is_unique[jurusan.jur_nama]');
      $this->form_validation->set_rules('data_singkatan', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_no_sk', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_tanggal', ' ', 'trim|required|max_length[10]|xss_clean|callback_cek_jur_tanggal');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $jur_nama = $this->input->post('data_nama', TRUE);
         $jur_singkatan = $this->input->post('data_singkatan', TRUE);
         $jur_no_sk = $this->input->post('data_no_sk', TRUE);
         $jur_tanggal = date('Y-m-d', strtotime($this->input->post('data_tanggal', TRUE)));
         $jur_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_jurusan->add(array($jur_nama, $jur_singkatan, $jur_no_sk, $jur_tanggal, $jur_keterangan));
         if($query) redirect('a/jurusan/index/addtrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_jurusan_data'), $data);
   }
   function edit($jur_id = 0){
      $data['doOnLoad'] = TRUE;
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Jurusan';
      $data['notice'] = array('', '');
      $data['post'] = 'a/jurusan/edit/'.$jur_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/jurusan/index';
      $query = $this->model_jurusan->cek_jur_id(array(decode($jur_id)));
      if($query->num_rows() == 0) redirect('a/jurusan/index/none');
      $query = $query->row_array();
      $data['data_nama'] = $query['jur_nama'];
      $data['data_singkatan'] = $query['jur_singkatan'];
      $data['data_no_sk'] = $query['jur_no_sk'];
      $data['data_tanggal'] = date('d-m-Y', strtotime($query['jur_tanggal']));
      $data['data_keterangan'] = $query['jur_keterangan'];
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[128]|xss_clean|callback_cek_jur_nama');
      $this->form_validation->set_rules('data_singkatan', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_no_sk', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_tanggal', ' ', 'trim|required|max_length[10]|xss_clean|callback_cek_jur_tanggal');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $jur_id = $query['jur_id'];
         $jur_nama = $this->input->post('data_nama', TRUE);
         $jur_singkatan = $this->input->post('data_singkatan', TRUE);
         $jur_no_sk = $this->input->post('data_no_sk', TRUE);
         $jur_tanggal = date('Y-m-d', strtotime($this->input->post('data_tanggal', TRUE)));
         $jur_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_jurusan->edit(array($jur_nama, $jur_singkatan, $jur_no_sk, $jur_tanggal, $jur_keterangan, $jur_id));
         if($query) redirect('a/jurusan/index/edittrue');
         else redirect('a/jurusan/index/editfalse');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_jurusan_data'), $data);
   }
   function cek_jur_tanggal ($tanggal){
      $tanggal = explode('-', $tanggal);
      if( count($tanggal) == 3 and is_numeric($tanggal[0]) and is_numeric($tanggal[1]) and is_numeric($tanggal[2]) and strlen($tanggal[2]) == 4 and checkdate($tanggal[1], $tanggal[0], $tanggal[2])) return TRUE;
      else{
          $this->form_validation->set_message('cek_jur_tanggal', 'Invalid date.');
          return FALSE;
      }
   }
   function cek_jur_nama (){
      $jur_id = decode($this->uri->rsegment(3));
      $jur_nama = $this->input->post('data_nama', TRUE);
      $query = $this->model_jurusan->cek_jur_nama(array($jur_id, $jur_nama));
      if($query->num_rows() == 0) return TRUE;
      else{
         $this->form_validation->set_message('cek_jur_nama', 'The field must contain a unique value.');
         return FALSE;
      }
   }
}