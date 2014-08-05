<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mapel extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_mapel');
   }
   function index($notice = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Mata Pelajaran';
      $data['notice'] = array('', '');
      $query = $this->model_mapel->index();
      if($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Mata pelajaran belum ada! '.anchor('a/mapel/add', 'Tambah Mata Pelajaran'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'deletefalse') $data['notice'] = array('red', 'ERROR : Data tidak dapat dihapus! Minimal harus memiliki 1 mata pelajaran.');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_mapel'), $data);
   }

   function delete($mapel_id = 0, $do = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Mata Pelajaran';
      $data['notice'] = array('', '');
      $data['get'] = 'a/mapel/delete/'.$mapel_id.'/do';
      $data['back'] = 'a/mapel/index';
      $query = $this->model_mapel->cek_mapel_id(array(decode($mapel_id)));
      if($query->num_rows() == 0) redirect('a/mapel/index/none');
      $query = $query->row_array();
      //jika ada yg sudah di isi maka tidak dapat dihapus
      //$cek_mapel_dipakai = $this->model_mapel->cek_mapel_dipakai(array(decode($mapel_id)));
      //if($cek_mapel_dipakai->num_rows() > 0) redirect('a/mapel/index/deletefalse');
      //jika tinggal satu tidak bisa dihapus
      $cek_mapel_jml = $this->model_mapel->cek_mapel_jml(array($query['mapel_gel']));
      if ($cek_mapel_jml->num_rows() == 1) redirect('a/mapel/index/deletefalse');
      $data['msg'] = 'Hapus Mata Pelajaran "'.$query['mapel_nama'].'"?';
      if($do == 'do'){
         $query_delete = $this->model_mapel->delete(array($query['mapel_id']));
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($query['mapel_gel']); // generate hasil
         // end of hasil // #########################################################
         if($query_delete) redirect('a/mapel/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Tambah Mata Pelajaran';
      $data['notice'] = array('', '');
      $data['post'] = 'a/mapel/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/mapel/index';
      $cek_gel_max = $this->model_mapel->cek_gel_max();
      $cek_gel_max = $cek_gel_max->row_array();
      $data['data_nama'] = '';
      $data['data_singkatan'] = '';
      $data['data_keterangan'] = '';
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|callback_add_cek_mapel_nama');
      $this->form_validation->set_rules('data_singkatan', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $mapel_nama = $this->input->post('data_nama', TRUE);
         $mapel_singkatan = $this->input->post('data_singkatan', TRUE);
         $mapel_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_mapel->add(array($mapel_nama, $mapel_singkatan, $mapel_keterangan));
         //setelah berhasil menambah mapel, maka tambahkan mapel ke nilai, untuk gelombang terkahir
         $this->model_mapel->add_nilai();
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($cek_gel_max['gel_id']); // generate hasil
         // end of hasil // #########################################################
         if($query) redirect('a/mapel/index/addtrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_mapel_data'), $data);
   }
   function edit($mapel_id = 0){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Mata Pelajaran';
      $data['notice'] = array('', '');
      $data['post'] = 'a/mapel/edit/'.$mapel_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/mapel/index';
      $query = $this->model_mapel->cek_mapel_id(array(decode($mapel_id)));
      if($query->num_rows() == 0) redirect('a/mapel/index/none');
      $query = $query->row_array();
      $data['data_nama'] = $query['mapel_nama'];
      $data['data_singkatan'] = $query['mapel_singkatan'];
      $data['data_keterangan'] = $query['mapel_keterangan'];
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|callback_edit_cek_mapel_nama');
      $this->form_validation->set_rules('data_singkatan', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $mapel_id = $query['mapel_id'];
         $mapel_gel = $query['mapel_gel'];
         $mapel_nama = $this->input->post('data_nama', TRUE);
         $mapel_singkatan = $this->input->post('data_singkatan', TRUE);
         $mapel_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_mapel->edit(array($mapel_gel, $mapel_nama, $mapel_singkatan, $mapel_keterangan, $mapel_id));
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($mapel_gel); // generate hasil
         // end of hasil // #########################################################
         if($query) redirect('a/mapel/index/edittrue');
         else redirect('a/mapel/index/editfalse');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_mapel_data'), $data);
   }
   function add_cek_mapel_nama (){
      $mapel_id = decode($this->uri->rsegment(3));
      $mapel_nama = $this->input->post('data_nama', TRUE);
      $query = $this->model_mapel->add_cek_mapel_nama(array($mapel_nama));
      if($query->num_rows() == 0) return TRUE;
      else {
         $this->form_validation->set_message('add_cek_mapel_nama', 'The field must contain a unique value.');
         return FALSE;
      }
   }
   function edit_cek_mapel_nama (){
      $mapel_id = decode($this->uri->rsegment(3));
      $mapel_nama = $this->input->post('data_nama', TRUE);
      $query = $this->model_mapel->edit_cek_mapel_nama(array($mapel_id, $mapel_nama));
      if($query->num_rows() == 0) return TRUE;
      else {
         $this->form_validation->set_message('edit_cek_mapel_nama', 'The field must contain a unique value.');
         return FALSE;
      }
   }
}