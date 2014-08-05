<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Kuota extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_kuota');
   }
   function index($notice = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Kuota';
      $data['notice'] = array('', '');
      $data['button_add'] = TRUE;
      $cek_jur_sisa = $this->model_kuota->cek_jur_sisa();
      if($cek_jur_sisa->num_rows() == 0) $data['button_add'] = FALSE;
      $query = $this->model_kuota->index();
      if($query->num_rows == 0){
         $cek_gel_max = $this->model_kuota->cek_gel_max();
         if($cek_gel_max->num_rows == 0){
            $data['notice'] = array('yellow', 'NOTICE : Gelombang belum ada! Silahkan '.anchor('a/gelombang/add', 'Tambah Gelombang').' terlebih dahulu!');
            $data['button_add'] = FALSE;
         }
         else{
            $cek_gel_max = $cek_gel_max->row_array();
            $cek_gel_max = $cek_gel_max['gel_ta'].' - '.$cek_gel_max['gel_nama'];
            $data['notice'] = array('yellow', 'NOTICE : Kuota untuk "'.$cek_gel_max.'" belum ada! '.anchor('a/kuota/add', 'Tambah Kuota'));
         }
         $cek_jur = $this->model_kuota->cek_jur();
         if($cek_jur->num_rows == 0){ //jurusan belum ada
            $data['notice'] = array('red', 'ERROR : Jurusan belum ada! Silahkan '.anchor('a/jurusan/add', 'Tambah Jurusan').' terlebih dahulu!');
            $data['button_add'] = FALSE;
         }
      }
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_kuota'), $data);
   }
   function delete($kuota_id = 0, $do = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Kuota';
      $data['notice'] = array('', '');
      $data['get'] = 'a/kuota/delete/'.$kuota_id.'/do';
      $data['back'] = 'a/kuota/index';
      $query = $this->model_kuota->cek_kuota_id(array(decode($kuota_id)));
      if($query->num_rows() == 0) redirect('a/kuota/index/none');
      $query = $query->row_array();
      $data['msg'] = 'Hapus Kuota "'.$query['gel_ta'].' - '.$query['gel_nama'].' - '.$query['jur_nama'].'"?';
      if($do == 'do'){
         $query_delete = $this->model_kuota->delete(array($query['kuota_id']));
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($query['kuota_gel']); // generate hasil
         // end of hasil // #########################################################
         if($query_delete) redirect('a/kuota/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Tambah Kuota';
      $data['notice'] = array('', '');
      $data['post'] = 'a/kuota/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/kuota/index';
      $cek_jur_sisa = $this->model_kuota->cek_jur_sisa();
      if($cek_jur_sisa->num_rows() == 0) redirect ('a/kuota/index'); //semua jurusan sudah memiliki kuota
      $cek_jur_sisa = $cek_jur_sisa->result_array();
      $cek_gel_max = $this->model_kuota->cek_gel_max();
      if($cek_gel_max->num_rows() == 0) redirect ('a/kuota/index'); // gelombang belum ada
      $cek_gel_max = $cek_gel_max->row_array();
      $data['do'] = 'add';
      $data['data_gel'] = $cek_gel_max['gel_ta'].' - '.$cek_gel_max['gel_nama'];
      $data['data_jur'] = $cek_jur_sisa;
      $data['pilih'] = 0;
      $data['data_jumlah'] = 30;
      $data['data_cadangan'] = 10;
      $data['data_keterangan'] = '';
      $this->form_validation->set_rules('data_jur', ' ', 'trim|required|decode[]|numeric|xss_clean|is_in[jurusan.jur_id]');
      $this->form_validation->set_rules('data_jumlah', ' ', 'trim|required|max_length[4]|numeric|xss_clean');
      $this->form_validation->set_rules('data_cadangan', ' ', 'trim|required|less_than[21]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $kuota_gel = $cek_gel_max['gel_id'];
         $kuota_jur = $this->input->post('data_jur', TRUE);
         $kuota_jumlah = $this->input->post('data_jumlah', TRUE);
         $kuota_cadangan = $this->input->post('data_cadangan', TRUE);
         $kuota_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_kuota->add(array($kuota_gel, $kuota_jur, $kuota_jumlah, $kuota_cadangan, $kuota_keterangan));
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($kuota_gel); // generate hasil
         // end of hasil // #########################################################
         if($query) redirect('a/kuota/index/addtrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_kuota_data'), $data);
   }
   function edit($kuota_id = 0){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Kuota';
      $data['notice'] = array('', '');
      $data['post'] = 'a/kuota/edit/'.$kuota_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/kuota/index';
      $query = $this->model_kuota->cek_kuota_id(array(decode($kuota_id)));
      if($query->num_rows() == 0) redirect('a/kuota/index/none');
      $query = $query->row_array();
      $data['do'] = 'edit';
      $data['data_gel'] = $query['gel_ta'].' - '.$query['gel_nama'];
      $data['data_jur'] = $query['jur_nama'];
      $data['data_jumlah'] = $query['kuota_jumlah'];
      $data['data_cadangan'] = $query['kuota_cadangan'];
      $data['data_keterangan'] = $query['kuota_keterangan'];
      $this->form_validation->set_rules('data_jumlah', ' ', 'trim|required|max_length[4]|numeric|xss_clean');
      $this->form_validation->set_rules('data_cadangan', ' ', 'trim|required|less_than[21]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $kuota_id = $query['kuota_id'];
         $kuota_gel = $query['gel_id'];
         $kuota_jur = $query['jur_id'];
         $kuota_jumlah = $this->input->post('data_jumlah', TRUE);
         $kuota_cadangan = $this->input->post('data_cadangan', TRUE);
         $kuota_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_kuota->edit(array($kuota_gel, $kuota_jur, $kuota_jumlah, $kuota_cadangan, $kuota_keterangan, $kuota_id));
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($kuota_gel); // generate hasil
         // end of hasil // #########################################################
         if($query) redirect('a/kuota/index/edittrue');
         else redirect('a/kuota/index/editfalse');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_kuota_data'), $data);
   }
}