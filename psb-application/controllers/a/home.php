<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_home');
   }
   function index(){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['notice'] = array('', '');
      $data['judul'] = 'Beranda';
      //pie
      $data['pie_series'] = 'Hasil Seleksi';
      $data['pie_satuan'] = 'orang';
      $cek_gel_max = $this->model_home->cek_gel_max();
      $cek_gel_max = $cek_gel_max->row_array();
      $data['pie_judul'] = '<b>Statistik Hasil Seleksi ('.$cek_gel_max['gel_ta'].' - '.$cek_gel_max['gel_nama'].') :</b>';
      $pie_pilihan_status = $this->model_home->pie_pilihan_status(array($cek_gel_max['gel_id']));
      $pie_pilihan_status = $pie_pilihan_status->result_array();
      foreach ($pie_pilihan_status as $key=>$row) $pie_pilihan_status[$key]['pilihan_status'] = ucfirst($row['pilihan_status']);
      $data['pie_pilihan_status'] = $pie_pilihan_status;
      //gel tahun terakhir
      $cek_gel_ta_max = $this->model_home->cek_gel_ta_max();
      $data['cek_gel_ta_max'] = $cek_gel_ta_max->result_array();
      //kuota gel terkahir
      $cek_kuota_gel_max = $this->model_home->cek_kuota_gel_max();
      $data['cek_kuota_gel_max'] = $cek_kuota_gel_max->result_array();
      //jurusan
      $cek_jur = $this->model_home->cek_jur();
      $data['cek_jur'] = $cek_jur->result_array();
      //mapel
      $cek_mapel = $this->model_home->cek_mapel();
      $data['cek_mapel'] = $cek_mapel->result_array();
      //user
      $cek_users = $this->model_home->cek_users();
      $data['cek_users'] = $cek_users->result_array();
      $this->load->view(view('a', 'view_home'), $data);
   }
}