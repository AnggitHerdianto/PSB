<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Hasil extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_hasil'); //for ($i=1; $i <= 5 ; $i++) {$this->load->helper('hasil'); generate($i);}
   }
   function index($notice = FALSE,  $gel_id = FALSE, $hapus_cari = FALSE){ //, $jur_id = FALSE, $cari = FALSE
      //destroy session
      $jur_id = FALSE; $cari = FALSE; //jur_id dan cari dipindah disini, karena jika dimasukan default menyebabkan eror
      if(strtolower($hapus_cari) == 'do'){
         $this->session->unset_userdata('a_hasil_index_jurusan');
         $this->session->unset_userdata('a_hasil_index_cari');
      }
      if($gel_id === FALSE OR decode($gel_id) != $this->session->userdata('a_hasil_index_gelombang')){
         $this->session->unset_userdata('a_hasil_index_gelombang');
         $this->session->unset_userdata('a_hasil_index_jurusan');
         $this->session->unset_userdata('a_hasil_index_cari');
      }
      // gelombang
      if($gel_id === FALSE){
         $cek_gel_max = $this->model_hasil->cek_gel_max();
         $cek_gel_max = $cek_gel_max->row_array();
         $gel_id = $cek_gel_max['gel_id']; // jika parameter kosong maka ambil gel_id paling akhir
      }
      else $gel_id = (int)decode($gel_id);
      //daftar gelombang tahun max
      $cek_gel_ta = $this->model_hasil->cek_gel_ta(); // gelombang tahun max
      $cek_gel_ta_max = $cek_gel_ta->row_array();
      $cek_gel_ta = $cek_gel_ta->result_array();
      if(count($cek_gel_ta) == 0) $data['filter_gel'][0] = "";
      //judul
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Hasil Seleksi - '.$cek_gel_ta_max['gel_ta'];
      $data['notice'] = array('', '');
      foreach ($cek_gel_ta as $key => $row){
         if($gel_id == $row['gel_id']) $link_text = '<b><u>'.$row['gel_nama'].'</u></b>';
         else $link_text = $row['gel_nama'];
         if($key == 0) $batas = '';
         else $batas = ' | ';
         $data['filter_gel'][$row['gel_id']] = $batas.anchor('a/hasil/index/false/'.encode($row['gel_id']).'/do', $link_text);
      }
      // input
      $this->form_validation->set_rules('data_jur', ' ', 'trim|required|xss_clean|decode[]|is_in[jurusan.jur_id]');
      $this->form_validation->set_rules('data_cari', ' ', 'xss_clean');
      if($this->form_validation->run()){
         $jur_id = $this->input->post('data_jur', TRUE);
         $cari = $this->input->post('data_cari', TRUE);
         $this->session->set_userdata(array('a_hasil_index_gelombang'=>$gel_id, 'a_hasil_index_jurusan'=>$jur_id, 'a_hasil_index_cari'=>$cari));
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      // jurusan
      $cek_jur = $this->model_hasil->cek_jur(array($gel_id));
      $default_jur = $cek_jur->row_array();
      $cek_jur = $cek_jur->result_array();
      $data['data_jur'] = $cek_jur;
      if(count($cek_jur) == 0){
         $data['pilih_jur'] = '';
         $jur_id = 0;
      }
      else{
         if($jur_id === FALSE){ //jika tidak ada post, ambil dari default get
            if($this->session->userdata('a_hasil_index_jurusan')) $jur_id = $this->session->userdata('a_hasil_index_jurusan');
            else $jur_id = $default_jur['jur_id'];
         }
         $data['pilih_jur'] = $jur_id;
      }
      $data['post_jur'] = 'a/hasil/index/false/'.encode($gel_id);
      // search
      if($cari === FALSE){ // jika tidk ada post, ambil dari default get
        if($this->session->userdata('a_hasil_index_cari')) $cari = $this->session->userdata('a_hasil_index_cari');
        else $cari = '';
      }
      else $cari = trim($cari);
      $data['post_cari'] = 'a/hasil/index/false/'.encode($gel_id);
      $data['data_cari'] = $cari;
      //jenis test
      $cek_jentest = $this->model_hasil->cek_jentest(array($gel_id));
      $cek_jentest = $cek_jentest->result_array();
      if(count($cek_jentest) == 0) $data['cek_jentest'][0] = array('jentest_singkatan'=> '');
      else $data['cek_jentest'] = $cek_jentest;
      //parameter hasil
      $query = $this->model_hasil->index(array( $gel_id, $jur_id, $cari, $cek_jentest));
      if($query->num_rows == 0 AND strlen($cari) > 0) $data['notice'] = array('yellow', 'NOTICE : Hasil pencarian untuk <b>"'.$cari.'"</b> tidak ditemukan!');
      elseif($jur_id == 0) $data['notice'] = array('red', 'ERROR : Jurusan belum memiliki kuota! '.anchor('a/kuota/add', 'Tambah Kuota'));
      elseif($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Hasil seleksi belum ada! '.anchor('a/siswa/add', 'Tambah Siswa'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_hasil'), $data);
   }
}