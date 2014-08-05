<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report extends CI_Controller{
   function __construct(){
      parent::__construct();
      //if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_report');
   }
   function download($gel_ta = null, $type = null, $gel_id = null, $jur_id = null, $file = null){
      //gel_ta
      if($gel_ta == null OR !is_numeric($gel_ta) OR strlen($gel_ta) > 4) show_404();
      //gel_id
      $gel_id = decode($gel_id);
      $cek_gel_id = $this->model_report->cek_gel_id(array($gel_id));
      $cek_gel_id = $cek_gel_id->row_array();
      if(count($cek_gel_id) == 0 OR !is_numeric($gel_id) OR $gel_ta != $cek_gel_id['gel_ta']) show_404();
      //jur_id
      if($jur_id == null) show_404();
      else{
         $jur_id = decode($jur_id);
         if($jur_id == null OR !is_numeric($jur_id) OR $jur_id < 0) show_404();
         elseif($jur_id == 0){
            $judul = $cek_gel_id['gel_ta'].' - '.$cek_gel_id['gel_nama'];
            $judul_sheet = $cek_gel_id['gel_nama'];
         }
         elseif($jur_id > 0){
            $cek_jur_id = $this->model_report->cek_jur_id (array($jur_id));
            $cek_jur_id = $cek_jur_id->row_array();
            if(count($cek_jur_id) == 0) show_404();
            else{
               $judul = $cek_gel_id['gel_ta'].' - '.$cek_gel_id['gel_nama'].' - '.$cek_jur_id['jur_nama'];
               $judul_sheet = $cek_jur_id['jur_nama'];
            }
         }
      }
      //file
      if($file == null OR strlen($file) <= 4) show_404();
      else{
         $file_pecah = explode('.', $file);
         if($file_pecah[count($file_pecah)-1] != $type) show_404();
      }
      //cek jumlah mapel
      $cek_mapel = $this->model_report->cek_mapel(array($gel_id));
      $cek_mapel = $cek_mapel->result_array();
      //nama kolom test
      $cek_jentest = $this->model_report->cek_jentest(array($gel_id));
      $cek_jentest = $cek_jentest->result_array();
      //baca database
      $this->load->library('export');
      if($jur_id == 0){
         //index
         $query = $this->model_report->index(array($gel_id, $cek_mapel, $cek_jentest));
         $query = $query->result_array();
         //download file
         if(strtolower($type) == 'pdf') $this->export->to_pdf('index', $query, $file, $judul, $cek_mapel, $cek_jentest);
         elseif(strtolower($type) == 'xls') $this->export->to_xls($query, $file, $judul, $judul_sheet, $cek_jentest);
         else show_404 ();
      }
      else{
         //hasil
         $query = $this->model_report->hasil(array($gel_id, $jur_id, $cek_mapel, $cek_jentest));
         $query = $query->result_array();
         //download file
         if(strtolower($type) == 'pdf') $this->export->to_pdf('hasil', $query, $file, $judul, $cek_mapel, $cek_jentest);
         elseif(strtolower($type) == 'xls') $this->export->to_xls($query, $file, $judul, $judul_sheet, $cek_jentest);
         else show_404 ();
      }
   }
   function index(){
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Daftar Semua Siswa';
      $data['notice'] = array('', '');
      //cek gel ta
      $cek_ta_jml = $this->model_report->cek_ta_jml();
      if($cek_ta_jml->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Laporan belum ada!');
      $cek_ta_jml = $cek_ta_jml->result_array();
      //hasil
      $hasil = '<ul>';
      foreach ($cek_ta_jml as $key => $row){
            $hasil = $hasil.'<li>'.$row['gel_ta'];
            $cek_gel_ta = $this->model_report->cek_gel_ta(array($row['gel_ta']));
            $cek_gel_ta = $cek_gel_ta->result_array();
            $hasil = $hasil.'<p>';
               $hasil = $hasil.'<b>Excel : </b>';
                  foreach ($cek_gel_ta as $key => $baris){
                     if($key == 0) $batas = '';
                     else $batas = ' | ';
                     $gel_ta = $row['gel_ta'];
                     $gel_id = encode($baris['gel_id']);
                     $jur_id = encode(0);
                     $file = $row['gel_ta'].'-'.$baris['gel_nama'].'.xls';
                     $file = str_replace(' ', '_', trim($file));
                     $hasil = $hasil.$batas.anchor('a/report/download/'.$gel_ta.'/'.'xls/'.$gel_id.'/'.$jur_id.'/'.$file, $baris['gel_nama']);
                  }
            $hasil = $hasil.'</p>';

            $hasil = $hasil.'<p>';
               $hasil = $hasil.'<b>PDF : </b>';
                  foreach ($cek_gel_ta as $key => $baris){
                     if($key == 0) $batas = '';
                     else $batas = ' | ';
                     $gel_ta = $row['gel_ta'];
                     $gel_id = encode($baris['gel_id']);
                     $jur_id = encode(0);
                     $file = $row['gel_ta'].'-'.$baris['gel_nama'].'.pdf';
                     $file = str_replace(' ', '_', trim($file));
                     $hasil = $hasil.$batas.anchor('a/report/download/'.$gel_ta.'/'.'pdf/'.$gel_id.'/'.$jur_id.'/'.$file, $baris['gel_nama']);
                  }
            $hasil = $hasil.'</p>';
         $hasil = $hasil.'</li>';
      }
      $hasil = $hasil.'</ul>';
      $data['hasil'] = $hasil;
      $this->load->view(view('a', 'view_report'), $data);
   }
   function hasil($notice = FALSE){
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Daftar Hasil Seleksi';
      $data['notice'] = array('', '');
      //cek gel ta
      $cek_ta_jml = $this->model_report->cek_ta_jml();
      if($cek_ta_jml->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Laporan belum ada!');
      $cek_ta_jml = $cek_ta_jml->result_array();
      //hasil
      $hasil = '<ul>';
      foreach ($cek_ta_jml as $key => $row){
         $hasil = $hasil.'<li>'.$row['gel_ta'];
            $cek_gel_ta = $this->model_report->cek_gel_ta(array($row['gel_ta']));
            $cek_gel_ta = $cek_gel_ta->result_array();
            $hasil = $hasil.'<ul>';
               foreach ($cek_gel_ta as $key => $baris){
                  $hasil = $hasil.'<li>'.$baris['gel_nama'];
                     $cek_jur_gel = $this->model_report->cek_jur_gel(array($baris['gel_id']));
                     $cek_jur_gel = $cek_jur_gel->result_array();
                     $hasil = $hasil.'<p>';
                        $hasil = $hasil.'<b>Excel : </b>';
                        foreach ($cek_jur_gel as $key => $lajur){
                           if($key == 0) $batas = '';
                           else $batas = ' | ';
                           $gel_ta = $row['gel_ta'];
                           $gel_id = encode($baris['gel_id']);
                           $jur_id = encode($lajur['jur_id']);
                           $file = $row['gel_ta'].'-'.$baris['gel_nama'].'-'.$lajur['jur_nama'].'.xls';
                           $file = str_replace(' ', '_', trim($file));
                           $hasil = $hasil.$batas.anchor('a/report/download/'.$gel_ta.'/'.'xls/'.$gel_id.'/'.$jur_id.'/'.$file, $lajur['jur_nama']);
                        }
                     $hasil = $hasil.'</p>';

                     $hasil = $hasil.'<p>';
                        $hasil = $hasil.'<b>PDF : </b>';
                        foreach ($cek_jur_gel as $key => $lajur){
                           if($key == 0) $batas = '';
                           else $batas = ' | ';
                           $gel_ta = $row['gel_ta'];
                           $gel_id = encode($baris['gel_id']);
                           $jur_id = encode($lajur['jur_id']);
                           $file = $row['gel_ta'].'-'.$baris['gel_nama'].'-'.$lajur['jur_nama'].'.pdf';
                           $file = str_replace(' ', '_', trim($file));
                           $hasil = $hasil.$batas.anchor('a/report/download/'.$gel_ta.'/'.'pdf/'.$gel_id.'/'.$jur_id.'/'.$file, $lajur['jur_nama']);
                        }
                     $hasil = $hasil.'</p>';
                  $hasil = $hasil.'</li>';
               }
            $hasil = $hasil.'</ul>';
         $hasil = $hasil.'</li>';
      }
      $hasil = $hasil.'</ul>';
      $data['hasil'] = $hasil;
      $this->load->view(view('a', 'view_report'), $data);
   }
}