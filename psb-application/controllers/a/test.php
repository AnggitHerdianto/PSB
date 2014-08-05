<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Test extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_test');
   }
   function index($notice = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Test';
      $data['notice'] = array('', '');
      $query = $this->model_test->index();
      if($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Test belum ada! '.anchor('a/test/add', 'Tambah Test'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'deletefalse') $data['notice'] = array('red', 'ERROR : Data tidak dapat dihapus! Minimal harus memiliki 1 jenis test.');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_test'), $data);
   }
   function delete($jentest_id = 0, $do = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Test';
      $data['notice'] = array('', '');
      $data['get'] = 'a/test/delete/'.$jentest_id.'/do';
      $data['back'] = 'a/test/index';
      $query = $this->model_test->cek_jentest_id(array(decode($jentest_id)));
      if($query->num_rows() == 0) redirect('a/test/index/none');
      $query = $query->row_array();
      //jika ada yg sudah di isi maka tidak dapat dihapus
      //$cek_jentest_dipakai = $this->model_test->cek_jentest_dipakai(array(decode($jentest_id)));
      //if($cek_jentest_dipakai->num_rows() > 0) redirect('a/test/index/deletefalse');
      //jika tinggal satu tidak bisa dihapus
      $cek_test_jml = $this->model_test->cek_test_jml(array($query['jentest_gel']));
      if ($cek_test_jml->num_rows() == 1) redirect('a/test/index/deletefalse');
      $data['msg'] = 'Hapus Test "'.$query['jentest_nama'].'"?';
      if($do == 'do'){
         $query_delete = $this->model_test->delete(array($query['jentest_id']));
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($query['jentest_gel']); // generate hasil
         // end of hasil // #########################################################
         if($query_delete) redirect('a/test/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Tambah Test';
      $data['notice'] = array('', '');
      $data['post'] = 'a/test/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/test/index';
      $cek_gel_max = $this->model_test->cek_gel_max();
      $cek_gel_max = $cek_gel_max->row_array();
      $data['data_nama'] = '';
      $data['data_singkatan'] = '';
      $data['data_persen'] = '';
      $data['data_keterangan'] = '';
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|callback_add_cek_jentest_nama');
      $this->form_validation->set_rules('data_singkatan', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_persen', ' ', 'trim|required|is_test|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $jentest_nama = $this->input->post('data_nama', TRUE);
         $jentest_singkatan = $this->input->post('data_singkatan', TRUE);
         $jentest_persen = $this->input->post('data_persen', TRUE);
         $jentest_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_test->add(array($jentest_nama, $jentest_singkatan, $jentest_persen, $jentest_keterangan));
         //setelah berhasil menambah jenis test, maka tambahkan mapel ke test, untuk gelombang terkahir
         $this->model_test->add_test();
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($cek_gel_max['gel_id']); // generate hasil
         // end of hasil // #########################################################
         if($query) redirect('a/test/index/addtrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_test_data'), $data);
   }

   function edit($jentest_id = 0){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Test';
      $data['notice'] = array('', '');
      $data['post'] = 'a/test/edit/'.$jentest_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/test/index';
      $query = $this->model_test->cek_jentest_id(array(decode($jentest_id)));
      if($query->num_rows() == 0) redirect('a/test/index/none');
      $query = $query->row_array();
      $data['data_nama'] = $query['jentest_nama'];
      $data['data_singkatan'] = $query['jentest_singkatan'];
      $data['data_persen'] = $query['jentest_persen'];
      $data['data_keterangan'] = $query['jentest_keterangan'];
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[64]|xss_clean|callback_edit_cek_jentest_nama');
      $this->form_validation->set_rules('data_singkatan', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_persen', ' ', 'trim|required|is_test|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $jentest_id = $query['jentest_id'];
         $jentest_gel = $query['jentest_gel'];
         $jentest_nama = $this->input->post('data_nama', TRUE);
         $jentest_singkatan = $this->input->post('data_singkatan', TRUE);
         $jentest_persen = $this->input->post('data_persen', TRUE);
         $jentest_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_test->edit(array($jentest_gel, $jentest_nama, $jentest_singkatan, $jentest_persen, $jentest_keterangan, $jentest_id));
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($jentest_gel); // generate hasil
         // end of hasil // #########################################################
         if($query) redirect('a/test/index/edittrue');
         else redirect('a/test/index/editfalse');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_test_data'), $data);
   }
   function add_cek_jentest_nama (){
      $jentest_id = decode($this->uri->rsegment(3));
      $jentest_nama = $this->input->post('data_nama', TRUE);
      $query = $this->model_test->add_cek_jentest_nama(array($jentest_nama));
      if($query->num_rows() == 0) return TRUE;
      else{
         $this->form_validation->set_message('add_cek_jentest_nama', 'The field must contain a unique value.');
         return FALSE;
      }
   }
   function edit_cek_jentest_nama (){
      $jentest_id = decode($this->uri->rsegment(3));
      $jentest_nama = $this->input->post('data_nama', TRUE);
      $query = $this->model_test->edit_cek_jentest_nama(array($jentest_id, $jentest_nama));
      if($query->num_rows() == 0) return TRUE;
      else{
         $this->form_validation->set_message('edit_cek_jentest_nama', 'The field must contain a unique value.');
         return FALSE;
      }
   }
}