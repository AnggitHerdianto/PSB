<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stat extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login())redirect('a/login/index/loginfalse/'.encode(uri_string()));
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_stat');
   }
   function index($detail = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Statistik Pendidikan Orang Tua';
      $data['satuan'] = 'orang';
      $data['notice'] = array('', '');
      $data['legend'] = 'true';
      if($detail == FALSE OR !in_array(strtolower($detail), array('ayah', 'ibu', 'wali'))){
         $data['sub_judul'] = 'Berisikan data pendidikan ayah per tahun ajaran';
         $detail = 'ot_pend_ayah';
      }
      else{
         $data['sub_judul'] = 'Berisikan data pendidikan '.$detail.' per tahun ajaran';
         $detail = 'ot_pend_'.strtolower($detail);
      }
      $data['link'] = FALSE;
      if(strtolower($detail) == 'ot_pend_ayah') $data['link'] = $data['link'].anchor('a/stat/index/ayah', '<b>Pendidikan Ayah</b>').' | ';
      else $data['link'] = $data['link'].anchor('a/stat/index/ayah', 'Pendidikan Ayah').' | ';
      if(strtolower($detail) == 'ot_pend_ibu') $data['link'] = $data['link'].anchor('a/stat/index/ibu', '<b>Pendidikan Ibu</b>').' | ';
      else $data['link'] = $data['link'].anchor('a/stat/index/ibu', 'Pendidikan Ibu').' | ';
      if(strtolower($detail) == 'ot_pend_wali') $data['link'] = $data['link'].anchor('a/stat/index/wali', '<b>Pendidikan Wali</b>');
      else $data['link'] = $data['link'].anchor('a/stat/index/wali', 'Pendidikan Wali');
      $cek_ta = $this->model_stat->cek_ta();
      $cek_ta = $cek_ta->result_array();
      foreach ($cek_ta as $key=>$row) $data['x_axis'][$key] = $row['gel_ta'];
      $cek_jumlah = $this->model_stat->index(array($cek_ta, $detail));
      $cek_jumlah = $cek_jumlah->result_array();
      $data['cek_jumlah'] = $cek_jumlah;
      if(count($cek_jumlah) == 0) $data['notice'] = array('yellow', 'NOTICE : Belum ada data');
      $this->load->view(view('a', 'view_stat_col'), $data);
   }
   function pekerjaan($detail = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Statistik Pekerjaan Orang Tua';
      $data['satuan'] = 'orang';
      $data['notice'] = array('', '');
      $data['legend'] = 'true';
      if($detail == FALSE OR !in_array(strtolower($detail), array('ayah', 'ibu', 'wali'))){
         $data['sub_judul'] = 'Berisikan data pekerjaan ayah per tahun ajaran';
         $detail = 'ot_pek_ayah';
      }
      else{
         $data['sub_judul'] = 'Berisikan data pekerjaan '.$detail.' per tahun ajaran';
         $detail = 'ot_pek_'.strtolower($detail);
      }
      $data['link'] = FALSE;
      if(strtolower($detail) == 'ot_pek_ayah') $data['link'] = $data['link'].anchor('a/stat/pekerjaan/ayah', '<b>Pekerjaan Ayah</b>').' | ';
      else $data['link'] = $data['link'].anchor('a/stat/pekerjaan/ayah', 'Pekerjaan Ayah').' | ';
      if(strtolower($detail) == 'ot_pek_ibu') $data['link'] = $data['link'].anchor('a/stat/pekerjaan/ibu', '<b>Pekerjaan Ibu</b>').' | ';
      else $data['link'] = $data['link'].anchor('a/stat/pekerjaan/ibu', 'Pekerjaan Ibu').' | ';
      if(strtolower($detail) == 'ot_pek_wali') $data['link'] = $data['link'].anchor('a/stat/pekerjaan/wali', '<b>Pekerjaan Wali</b>');
      else $data['link'] = $data['link'].anchor('a/stat/pekerjaan/wali', 'Pekerjaan Wali');
      $cek_ta = $this->model_stat->cek_ta();
      $cek_ta = $cek_ta->result_array();
      foreach ($cek_ta as $key=>$row) $data['x_axis'][$key] = $row['gel_ta'];
      $cek_jumlah = $this->model_stat->pek_ortu(array($cek_ta, $detail));
      $cek_jumlah = $cek_jumlah->result_array();
      $data['cek_jumlah'] = $cek_jumlah;
      if(count($cek_jumlah) == 0) $data['notice'] = array('yellow', 'NOTICE : Belum ada data');
      $this->load->view(view('a', 'view_stat_col'), $data);
   }
   function agama($detail = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Statistik Agama Siswa';
      $data['sub_judul'] = 'Berisikan data agama siswa per tahun ajaran';
      $data['satuan'] = 'orang';
      $data['notice'] = array('', '');
      $data['legend'] = 'true';
      $data['link'] = '';
      $cek_ta = $this->model_stat->cek_ta();
      $cek_ta = $cek_ta->result_array();
      foreach ($cek_ta as $key=>$row) $data['x_axis'][$key] = $row['gel_ta'];
      $cek_jumlah = $this->model_stat->agama(array($cek_ta));
      $cek_jumlah = $cek_jumlah->result_array();
      $data['cek_jumlah'] = $cek_jumlah;
      if(count($cek_jumlah) == 0) $data['notice'] = array('yellow', 'NOTICE : Belum ada data');
      $this->load->view(view('a', 'view_stat_col'), $data);
   }
   function jenis_kelamin($detail = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Statistik Jenis Kelamin Siswa';
      $data['sub_judul'] = 'Berisikan data jenis kelamin siswa per tahun ajaran';
      $data['satuan'] = 'orang';
      $data['notice'] = array('', '');
      $data['legend'] = 'true';
      $data['link'] = '';
      $cek_ta = $this->model_stat->cek_ta();
      $cek_ta = $cek_ta->result_array();
      foreach ($cek_ta as $key=>$row) $data['x_axis'][$key] = $row['gel_ta'];
      $cek_jumlah = $this->model_stat->jenis_kelamin(array($cek_ta));
      $cek_jumlah = $cek_jumlah->result_array();
      $data['cek_jumlah'] = $cek_jumlah;
      if(count($cek_jumlah) == 0) $data['notice'] = array('yellow', 'NOTICE : Belum ada data');
      $this->load->view(view('a', 'view_stat_col'), $data);
   }
   function hasil_tahun($detail = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Statistik Hasil Seleksi';
      $data['sub_judul'] = 'Berisikan data hasil seleksi siswa per tahun ajaran';
      $data['satuan'] = 'orang';
      $data['notice'] = array('', '');
      $data['legend'] = 'true';
      $data['link'] = anchor('a/stat/hasil_tahun', '<b>Data Per Tahun</b>').' | '.
                      anchor('a/stat/hasil_gelombang', 'Data Per Gelomban');
      $cek_ta = $this->model_stat->cek_ta();
      $cek_ta = $cek_ta->result_array();
      foreach ($cek_ta as $key=>$row) $data['x_axis'][$key] = $row['gel_ta'];
      $cek_jumlah = $this->model_stat->hasil_tahun(array($cek_ta));
      $cek_jumlah = $cek_jumlah->result_array();
      foreach ($cek_jumlah as $key=>$row) $cek_jumlah[$key]['nama'] = ucfirst($row['nama']); 
      $data['cek_jumlah'] = $cek_jumlah;
      if(count($cek_jumlah) == 0) $data['notice'] = array('yellow', 'NOTICE : Belum ada data');
      $this->load->view(view('a', 'view_stat_col_stack'), $data);
   }
   function hasil_gelombang($detail = FALSE){
      $data['url'] = array('a', __CLASS__, 'hasil_tahun');
      $data['judul'] = 'Statistik Hasil Seleksi';
      $data['sub_judul'] = 'Berisikan data hasil seleksi siswa per gelombang (Diterima, Cadangan, Ditolak)';
      $data['satuan'] = 'orang';
      $data['notice'] = array('', '');
      $data['legend'] = 'true';
      $data['link'] = anchor('a/stat/hasil_tahun', 'Data Per Tahun').' | '.
                      anchor('a/stat/hasil_gelombang', '<b>Data Per Gelomban</b>');
      $cek_gel = $this->model_stat->cek_gel();
      $cek_gel = $cek_gel->result_array();
      foreach ($cek_gel as $key=>$row) $data['x_axis'][$key] = "'".$row['gel_ta']."-".$row['gel_nama']."'";
      $cek_jumlah = $this->model_stat->hasil_gelombang(array($cek_gel));
      $cek_jumlah = $cek_jumlah->result_array();
      foreach ($cek_jumlah as $key=>$row) $cek_jumlah[$key]['nama'] = ucfirst($row['nama']);
      $data['cek_jumlah'] = $cek_jumlah;
      if(count($cek_jumlah) == 0) $data['notice'] = array('yellow', 'NOTICE : Belum ada data');
      $this->load->view(view('a', 'view_stat_col_stack_rotate'), $data);
   }
   function hasil_tahun_gelombang($detail = FALSE){
      $cek_ta_max = $this->model_stat->cek_ta_max();
      $cek_ta_max = $cek_ta_max->result_array();
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Statistik Hasil Seleksi Tahun '.$cek_ta_max[0]['gel_ta'];
      $data['sub_judul'] = 'Berisikan data hasil seleksi siswa per gelombang tahun '.$cek_ta_max[0]['gel_ta'];
      $data['satuan'] = 'orang';
      $data['notice'] = array('', '');
      $data['legend'] = 'true';
      $data['link'] = '';
      foreach ($cek_ta_max as $key=>$row)$data['x_axis'][$key] = "'".$row['gel_nama']."'";
      $cek_jumlah = $this->model_stat->hasil_gelombang(array($cek_ta_max));
      $cek_jumlah = $cek_jumlah->result_array();
      foreach ($cek_jumlah as $key=>$row)$cek_jumlah[$key]['nama'] = ucfirst($row['nama']);
      $data['cek_jumlah'] = $cek_jumlah;
      if(count($cek_jumlah) == 0) $data['notice'] = array('yellow', 'NOTICE : Belum ada data');
      $this->load->view(view('a', 'view_stat_col_stack'), $data);
   }
   function total_tahun($detail = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Statistik Total Pendaftar';
      $data['sub_judul'] = 'Berisikan data total pendaftar per tahun ajaran';
      $data['satuan'] = 'orang';
      $data['notice'] = array('', '');
      $data['legend'] = 'true';
      $data['link'] = '';
      $cek_ta = $this->model_stat->cek_ta();
      $cek_ta = $cek_ta->result_array();
      $data['x_axis'] = $cek_ta;
      foreach ($cek_ta as $key=>$row) $data['x_axis'][$key] = $row['gel_ta'];
      $cek_jumlah = $this->model_stat->total_tahun(array($cek_ta));
      $cek_jumlah = $cek_jumlah->result_array();
      $data['cek_jumlah'] = $cek_jumlah;
      if(count($cek_jumlah) == 0) $data['notice'] = array('yellow', 'NOTICE : Belum ada data');
      $this->load->view(view('a', 'view_stat_line'), $data);
   }
}