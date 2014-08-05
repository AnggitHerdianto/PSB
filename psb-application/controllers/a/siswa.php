<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Siswa extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_siswa');
   }
   function index($notice = FALSE,  $gel_id = FALSE, $hapus_cari = FALSE, $status = FALSE, $cari = FALSE){
      //destroy session
      if(strtolower($hapus_cari) == 'do'){
         $this->session->unset_userdata('a_siswa_index_status');
         $this->session->unset_userdata('a_siswa_index_cari');
      }
      if($gel_id === FALSE OR decode($gel_id) != $this->session->userdata('a_siswa_index_gelombang')){
         $this->session->unset_userdata('a_siswa_index_gelombang');
         $this->session->unset_userdata('a_siswa_index_status');
         $this->session->unset_userdata('a_siswa_index_cari');
      }
      // gelombang
      if($gel_id === FALSE){
         $cek_gel_max = $this->model_siswa->cek_gel_max();
         $cek_gel_max = $cek_gel_max->row_array();
         $gel_id = $cek_gel_max['gel_id']; // jika parameter kosong maka ambil gel_id paling akhir
      }
      else $gel_id = (int) decode($gel_id);
      //daftar gelombang tahun max
      $cek_gel_ta = $this->model_siswa->cek_gel_ta(); // gelombang tahun max
      $cek_gel_ta_max = $cek_gel_ta->row_array();
      $cek_gel_ta = $cek_gel_ta->result_array();
      if($gel_id == 0) $link_text = '<b><u>'.'Semua'.'</u></b>';
      else $link_text = 'Semua';
      //judul
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Semua Siswa - '.$cek_gel_ta_max['gel_ta'];
      $data['notice'] = array('', '');
      //print
      $data['filter_gel'][0] = anchor('a/siswa/index/false/'.encode(0).'/do', $link_text);
      foreach ($cek_gel_ta as $row){
         if($gel_id == $row['gel_id']) $link_text = '<b><u>'.$row['gel_nama'].'</u></b>';
         else $link_text = $row['gel_nama'];
         $data['filter_gel'][$row['gel_id']] = ' | '.anchor('a/siswa/index/false/'.encode($row['gel_id']).'/do', $link_text);
      }
      // input
      $this->form_validation->set_rules('data_status', ' ', 'trim|required|xss_clean|decode[]|is_equal[semua, sdh_dicek, blm_dicek]');
      $this->form_validation->set_rules('data_cari', ' ', 'xss_clean');
      if($this->form_validation->run()){
         $status = $this->input->post('data_status', TRUE);
         $cari = $this->input->post('data_cari', TRUE);
         $this->session->set_userdata(array('a_siswa_index_gelombang'=>$gel_id, 'a_siswa_index_status'=>$status, 'a_siswa_index_cari'=>$cari));
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      // status
      if($status === FALSE){
         if($this->session->userdata('a_siswa_index_status')) $status = $this->session->userdata('a_siswa_index_status');
         else $status = 'semua';
      }
      $data['status'] = $status;
      $data['pilih_status']['semua'] = FALSE;
      $data['pilih_status']['sdh_dicek'] = FALSE;
      $data['pilih_status']['blm_dicek'] = FALSE;
      if($status == 'semua') $data['pilih_status']['semua'] = TRUE;
      elseif($status == 'sdh_dicek') $data['pilih_status']['sdh_dicek'] = TRUE;
      elseif($status == 'blm_dicek') $data['pilih_status']['blm_dicek'] = TRUE;
      $data['post'] = 'a/siswa/index/false/'.encode($gel_id);
      // search
      if($cari === FALSE){ // jika tidk ada post, ambil dari default get
         if($this->session->userdata('a_siswa_index_cari')) $cari = $this->session->userdata('a_siswa_index_cari');
         else $cari = '';
      }
      else $cari = trim($cari);
      $data['post_cari'] = 'a/siswa/index/false/'.encode($gel_id);
      $data['data_cari'] = $cari;
      $query = $this->model_siswa->index(array($gel_id, $status, $cari));
      //cek kuota
      $cek_jur = $this->model_siswa->cek_jur();
      $cek_jur = $cek_jur->result_array();
      if($query->num_rows == 0 AND strlen($cari) > 0) $data['notice'] = array('yellow', 'NOTICE : Hasil pencarian untuk <b>"'.$cari.'"</b> tidak ditemukan!');
      elseif(count($cek_jur) == 0) $data['notice'] = array('red', 'ERROR : Jurusan belum memiliki kuota! '.anchor('a/kuota/add', 'Tambah Kuota'));
      elseif($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Data siswa belum ada! '.anchor('a/siswa/add', 'Tambah Siswa'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_siswa'), $data);
   }
   function delete($siswa_id = 0, $url = FALSE, $do = FALSE){ // url = back setelah berhasil delete
      $kelas = explode ('/', decode($url));
      $data['url'] = array('a', strtolower($kelas[1]), 'index');
      $data['judul'] = 'Hapus Siswa';
      $data['notice'] = array('', '');
      $data['get'] = 'a/siswa/delete/'.$siswa_id.'/'.$url.'/'.'do';
      $data['back'] = decode($url);
      $query = $this->model_siswa->cek_siswa_id(array(decode($siswa_id)));
      if($query->num_rows() == 0) redirect('a/siswa/index/none');
      $query = $query->row_array();
      $data['msg'] = 'Hapus Siswa "'.$query['siswa_nama'].'"?';
      if($do == 'do'){
         $hapus = $this->model_siswa->delete(array($query['siswa_id']));
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($query['siswa_gel']); // generate hasil
         // end of hasil // #########################################################
         if($hapus){
            $url = explode ('/', decode($url));
            if(count($url) >= 3) $url[3] = 'deletetrue';
            $url = implode('/', $url);
            redirect($url);
         }
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add($notice = FALSE){
      $data['doOnLoad'] = TRUE;
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      //$data['judul'] = 'Tambah Siswa'; // pindah bawah
      $data['notice'] = array('', '');
      $data['post'] = 'a/siswa/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/siswa/index';
      $cek_agama = $this->model_siswa->cek_agama();
      $cek_agama = $cek_agama->result_array();
      $cek_prov = $this->model_siswa->cek_prov();
      $cek_prov = $cek_prov->result_array();
      $cek_pend = $this->model_siswa->cek_pend();
      $cek_pend = $cek_pend->result_array();
      $cek_pek = $this->model_siswa->cek_pek();
      $cek_pek = $cek_pek->result_array();
      $cek_gel_max = $this->model_siswa->cek_gel_max();
      $cek_gel_max = $cek_gel_max->row_array();
      $cek_mapel = $this->model_siswa->cek_mapel();
      $cek_mapel = $cek_mapel->result_array();
      $cek_jentest = $this->model_siswa->cek_jentest();
      $cek_jentest = $cek_jentest->result_array();
      $cek_jur = $this->model_siswa->cek_jur();
      $cek_jur = $cek_jur->result_array();
      if(count($cek_jur) == 0) $data['notice'] = array('red', 'ERROR : Jurusan belum memiliki kuota! '.anchor('a/kuota/add', 'Tambah Kuota'));
      // judul
      $data['judul'] = 'Tambah Siswa - '.$cek_gel_max['gel_ta'].' - '.$cek_gel_max['gel_nama'];
      // data siswa
      $data['data_nama'] = '';
      $data['data_nama_panggilan'] = '';
      $data['data_jenis_kelamin_l'] = TRUE;
      $data['data_jenis_kelamin_p'] = FALSE;
      $data['data_tempat_lahir'] = '';
      $data['data_tanggal_lahir'] = date('d-m-Y', time() - (16 * 360 * 86400));
      $data['data_agama'] = $cek_agama;
      $data['pilih_agama'] = 1;
      $data['data_sekolah_asal'] = '';
      $data['data_sekolah_alamat'] = '';
      $data['data_suku'] = '';
      $data['data_anak_ke'] = '';
      $data['data_jumlah_saudara'] = '';
      $data['data_kecamatan'] = '';
      $data['data_kabupaten'] = '';
      $data['data_prov'] = $cek_prov;
      $data['pilih_prov'] = 1;
      $data['data_kode_pos'] = '';
      $data['data_alamat'] = '';
      $data['data_alamat_pos'] = '';
      $data['data_telepon'] = '';
      $data['data_hp'] = '';
      $data['data_email'] = '';
      $data['data_gol_darah_none'] = TRUE;
      $data['data_gol_darah_a'] = FALSE;
      $data['data_gol_darah_b'] = FALSE;
      $data['data_gol_darah_o'] = FALSE;
      $data['data_gol_darah_ab'] = FALSE;
      $data['data_tinggi_badan'] = '';
      $data['data_berat_badan'] = '';
      $data['data_penyakit'] = '';
      $data['data_keterangan'] = '';
      $data['data_status_sdh_dicek'] = TRUE;
      $data['data_status_blm_dicek'] = FALSE;
      // orang tua
      $data['ot_nama_ayah'] = ''; // ayah
      $data['ot_pend_ayah'] = $cek_pend;
      $data['pilih_pend_ayah'] = 2;
      $data['ot_pek_ayah'] = $cek_pek;
      $data['pilih_pek_ayah'] = 1;
      $data['ot_gaji_ayah'] = '';
      $data['ot_hp_ayah'] = '';
      $data['ot_nama_ibu'] = ''; //ibu
      $data['ot_pend_ibu'] = $cek_pend;
      $data['pilih_pend_ibu'] = 2;
      $data['ot_pek_ibu'] = $cek_pek;
      $data['pilih_pek_ibu'] = 1;
      $data['ot_gaji_ibu'] = '';
      $data['ot_hp_ibu'] = '';
      $data['ot_alamat_ortu'] = '';
      $data['ot_nama_wali'] = ''; //wali
      $data['ot_pend_wali'] = $cek_pend;
      $data['pilih_pend_wali'] = 2;
      $data['ot_pek_wali'] = $cek_pek;
      $data['pilih_pek_wali'] = 1;
      $data['ot_gaji_wali'] = '';
      $data['ot_hp_wali'] = '';
      $data['ot_alamat_wali'] = '';
      $data['ot_status_asuh_ortu'] = TRUE;
      $data['ot_status_asuh_wali'] = FALSE;
      // nilai uan
      $data['nilai_mapel'] = $cek_mapel;
      // nilai prestasi
      $data['nilai_prestasi'] = '';
      // nilai test
      $data['test_jentest'] = $cek_jentest;
      // pilih jurusan
      $data['pilihan_jumlah_pilihan'] = $cek_gel_max['gel_jumlah_pilihan'];
      $data['pilihan_jur'] = $cek_jur;
      foreach ($cek_jur as $key => $row) $data['pilih_pilihan'][$key + 1] = $row['jur_id'];
      // data siswa
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_nama_panggilan', ' ', 'trim|max_length[32]|xss_clean');
      $this->form_validation->set_rules('data_jenis_kelamin', ' ', 'trim|xss_clean|decode[]|is_equal[l, p]');
      $this->form_validation->set_rules('data_tempat_lahir', ' ', 'trim|required|max_length[64]|xss_clean');
      $this->form_validation->set_rules('data_tanggal_lahir', ' ', 'trim|required|max_length[10]|xss_clean|is_date');
      $this->form_validation->set_rules('data_sekolah_asal', ' ', 'trim|required|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_sekolah_alamat', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_agama', ' ', 'trim|xss_clean|decode[]|is_in[agama.agama_id]');
      $this->form_validation->set_rules('data_suku', ' ', 'trim|max_length[64]|xss_clean');
      $this->form_validation->set_rules('data_anak_ke', ' ', 'trim|numeric|max_length[2]|less_than[99]|xss_clean');//
      $this->form_validation->set_rules('data_jumlah_saudara', ' ', 'trim|numeric|max_length[2]|less_than[99]|xss_clean');//
      $this->form_validation->set_rules('data_alamat', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_prov', ' ', 'trim|xss_clean|decode[]|is_in[provinsi.prov_id]');
      $this->form_validation->set_rules('data_kabupaten', ' ', 'trim|max_length[64]|xss_clean');
      $this->form_validation->set_rules('data_kecamatan', ' ', 'trim|max_length[64]|xss_clean');
      $this->form_validation->set_rules('data_kode_pos', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_alamat_pos', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_telepon', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_hp', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_email', ' ', 'trim|valid_email|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_gol_darah', ' ', 'trim|xss_clean|decode[]|is_equal[a, b, ab, o, none]');
      $this->form_validation->set_rules('data_tinggi_badan', ' ', 'trim|max_length[16]|xss_clean');//
      $this->form_validation->set_rules('data_berat_badan', ' ', 'trim|max_length[16]|xss_clean');//
      $this->form_validation->set_rules('data_penyakit', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_status', ' ', 'trim|xss_clean|decode[]|is_equal[sdh_dicek, blm_dicek]');
      // orang tua
      $this->form_validation->set_rules('ot_status_asuh', ' ', 'trim|xss_clean|decode[]|is_equal[ortu, wali]');
      $this->form_validation->set_rules('ot_nama_ayah', ' ', 'trim|max_length[255]|xss_clean'); // ayah
      $this->form_validation->set_rules('ot_pend_ayah', ' ', 'trim|xss_clean|decode[]|is_in[pendidikan.pend_id]');
      $this->form_validation->set_rules('ot_pek_ayah', ' ', 'trim|xss_clean|decode[]|is_in[pekerjaan.pek_id]');
      $this->form_validation->set_rules('ot_gaji_ayah', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_hp_ayah', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_nama_ibu', ' ', 'trim|max_length[255]|xss_clean'); // ibu
      $this->form_validation->set_rules('ot_pend_ibu', ' ', 'trim|xss_clean|decode[]|is_in[pendidikan.pend_id]');
      $this->form_validation->set_rules('ot_pek_ibu', ' ', 'trim|xss_clean|decode[]|is_in[pekerjaan.pek_id]');
      $this->form_validation->set_rules('ot_gaji_ibu', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_hp_ibu', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_alamat_ortu', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('ot_nama_wali', ' ', 'trim|max_length[255]|xss_clean'); // wali
      $this->form_validation->set_rules('ot_pend_wali', ' ', 'trim|xss_clean|decode[]|is_in[pendidikan.pend_id]');
      $this->form_validation->set_rules('ot_pek_wali', ' ', 'trim|xss_clean|decode[]|is_in[pekerjaan.pek_id]');
      $this->form_validation->set_rules('ot_gaji_wali', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_hp_wali', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_alamat_wali', ' ', 'trim|max_length[255]|xss_clean');
      // nilai uan
      foreach($cek_mapel as $row) {
         $this->form_validation->set_rules('nilai_mapel['.$row['mapel_id'].']', ' ', 'trim|required|is_nilai|xss_clean');
         $jumlah_mapel = @$jumlah_mapel + 1;
      }
      //nilai prestasi
      $this->form_validation->set_rules('nilai_prestasi', ' ', 'trim|is_nilai|xss_clean');
      // nilai test
      foreach($cek_jentest as $row) $this->form_validation->set_rules('test_jentest['.$row['jentest_id'].']', ' ', 'trim|is_nilai['.$jumlah_mapel.']|xss_clean');
      // pilih jurusan
      for($i = 1; $i <= $cek_gel_max['gel_jumlah_pilihan']; $i++)$this->form_validation->set_rules('pilihan_jur['.$i.']', ' ', 'trim|required|xss_clean|decode[]|is_in[jurusan.jur_id]');
      if($this->form_validation->run()){
         $siswa_gel = $cek_gel_max['gel_id'];
         $cek_siswa_max = $this->model_siswa->cek_siswa_max();
         if($cek_siswa_max->num_rows() == 0) $siswa_no_pendaftaran = ($cek_gel_max['gel_kode'] * 10000) + 1;
         else{
            $cek_siswa_max = $cek_siswa_max->row_array();
            $siswa_no_pendaftaran = ($cek_gel_max['gel_kode'] * 10000) + (substr($cek_siswa_max['siswa_no_pendaftaran'], 4, 4) + 1);
         }
         // siswa
         $siswa_nama = $this->input->post('data_nama', TRUE);
         $siswa_nama_panggilan = $this->input->post('data_nama_panggilan', TRUE);
         $siswa_jenis_kelamin = $this->input->post('data_jenis_kelamin', TRUE);
         $siswa_tempat_lahir = $this->input->post('data_tempat_lahir', TRUE);
         $siswa_tanggal_lahir = date('Y-m-d', strtotime($this->input->post('data_tanggal_lahir', TRUE)));
         $siswa_sekolah_asal = $this->input->post('data_sekolah_asal', TRUE);
         $siswa_sekolah_alamat = $this->input->post('data_sekolah_alamat', TRUE);
         $siswa_agama = $this->input->post('data_agama', TRUE);
         $siswa_suku = $this->input->post('data_suku', TRUE);
         $siswa_anak_ke = $this->input->post('data_anak_ke', TRUE);
         $siswa_jumlah_saudara = $this->input->post('data_jumlah_saudara', TRUE);
         $siswa_alamat = $this->input->post('data_alamat', TRUE);
         $siswa_prov = $this->input->post('data_prov', TRUE);
         $siswa_kabupaten = $this->input->post('data_kabupaten', TRUE);
         $siswa_kecamatan = $this->input->post('data_kecamatan', TRUE);
         $siswa_kode_pos = $this->input->post('data_kode_pos', TRUE);
         $siswa_alamat_pos = $this->input->post('data_alamat_pos', TRUE);
         $siswa_telepon = $this->input->post('data_telepon', TRUE);
         $siswa_hp = $this->input->post('data_hp', TRUE);
         $siswa_email = $this->input->post('data_email', TRUE);
         $siswa_gol_darah = $this->input->post('data_gol_darah', TRUE);
         $siswa_tinggi_badan = $this->input->post('data_tinggi_badan', TRUE);
         $siswa_berat_badan = $this->input->post('data_berat_badan', TRUE);
         $siswa_penyakit = $this->input->post('data_penyakit', TRUE);
         $siswa_tanggal_daftar = date('Y-m-d');
         $siswa_tanggal_ulang = NULL;
         $siswa_status = $this->input->post('data_status', TRUE);
         $siswa_keterangan = $this->input->post('data_keterangan', TRUE);
         $query_siswa = $this->model_siswa->add_siswa(array($siswa_gel, $siswa_no_pendaftaran, $siswa_nama, $siswa_nama_panggilan, $siswa_jenis_kelamin, $siswa_tempat_lahir, $siswa_tanggal_lahir, $siswa_sekolah_asal, $siswa_sekolah_alamat, $siswa_agama, $siswa_suku, $siswa_anak_ke, $siswa_jumlah_saudara, $siswa_alamat, $siswa_prov, $siswa_kabupaten, $siswa_kecamatan, $siswa_kode_pos, $siswa_alamat_pos, $siswa_telepon, $siswa_hp, $siswa_email, $siswa_gol_darah, $siswa_tinggi_badan, $siswa_berat_badan, $siswa_penyakit, $siswa_tanggal_daftar, $siswa_tanggal_ulang, $siswa_status, $siswa_keterangan));
         $siswa_id = $this->db->insert_id();
         // orang tua
         $ot_nama_ayah = $this->input->post('ot_nama_ayah', TRUE);
         $ot_pend_ayah = $this->input->post('ot_pend_ayah', TRUE);
         $ot_pek_ayah = $this->input->post('ot_pek_ayah', TRUE);
         $ot_gaji_ayah = $this->input->post('ot_gaji_ayah', TRUE);
         $ot_hp_ayah = $this->input->post('ot_hp_ayah', TRUE);
         $ot_nama_ibu = $this->input->post('ot_nama_ibu', TRUE);
         $ot_pend_ibu = $this->input->post('ot_pend_ibu', TRUE);
         $ot_pek_ibu = $this->input->post('ot_pek_ibu', TRUE);
         $ot_gaji_ibu = $this->input->post('ot_gaji_ibu', TRUE);
         $ot_hp_ibu = $this->input->post('ot_hp_ibu', TRUE);
         $ot_alamat_ortu = $this->input->post('ot_alamat_ortu', TRUE);
         $ot_nama_wali = $this->input->post('ot_nama_wali', TRUE);
         $ot_pend_wali = $this->input->post('ot_pend_wali', TRUE);
         $ot_pek_wali = $this->input->post('ot_pek_wali', TRUE);
         $ot_gaji_wali = $this->input->post('ot_gaji_wali', TRUE);
         $ot_hp_wali = $this->input->post('ot_hp_wali', TRUE);
         $ot_alamat_wali = $this->input->post('ot_alamat_wali', TRUE);
         $ot_status_asuh = $this->input->post('ot_status_asuh', TRUE);
         $query_ot = $this->model_siswa->add_orang_tua(array($siswa_id, $ot_nama_ayah, $ot_pend_ayah, $ot_pek_ayah, $ot_gaji_ayah, $ot_hp_ayah, $ot_nama_ibu, $ot_pend_ibu, $ot_pek_ibu, $ot_gaji_ibu, $ot_hp_ibu,$ot_alamat_ortu, $ot_nama_wali, $ot_pend_wali, $ot_pek_wali, $ot_gaji_wali, $ot_hp_wali, $ot_alamat_wali, $ot_status_asuh));
         // nilai
         $nilai_mapel = $this->input->post('nilai_mapel', TRUE);
         foreach($cek_mapel as $row) $query_nilai = $this->model_siswa->add_nilai(array($siswa_id, $row['mapel_id'], $nilai_mapel[$row['mapel_id']]));
         // nilai prestasi
         $nilai_prestasi = $this->input->post('nilai_prestasi', TRUE);
         $query_prestasi = $this->model_siswa->add_prestasi(array($siswa_id, $nilai_prestasi));
         // test
         $test_jentest = $this->input->post('test_jentest', TRUE);
         foreach($cek_jentest as $row)$query_test = $this->model_siswa->add_test(array($siswa_id, $row['jentest_id'], $test_jentest[$row['jentest_id']]));
         // pilihan
         $x = $this->input->post('pilihan_jur', TRUE);
         $pilihan_jur[1] = NULL;
         $pilihan_jur[2] = NULL;
         $pilihan_jur[3] = NULL;
         for($i = 1; $i <= 3; $i++){
            if($i <= count($x)) $pilihan_jur[$i] = $x[$i];
            $query_pilihan = $this->model_siswa->add_pilihan(array($siswa_id, $pilihan_jur[$i], $i));
         }
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($cek_gel_max['gel_id']); // generate hasil
         // end of hasil // #########################################################
         redirect('a/siswa/add/addtrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      if($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Tambah data berhasil!');
      $this->load->view(view('a', 'view_siswa_data'), $data);
   }
   function edit($siswa_id = 0, $url = FALSE){
      $data['doOnLoad'] = TRUE;
      if(!$url or strlen($url) == 0) $url = encode('a/siswa/index');
      $kelas = explode ('/', decode($url));
      $data['url'] = array('a', strtolower($kelas[1]), 'index');
      $data['judul'] = 'Edit Siswa';
      $data['notice'] = array('', '');
      $data['simpan'] = 'Update';
      $data['post'] = 'a/siswa/edit/'.$siswa_id.'/'.$url;
      $data['back'] = decode($url);
      $siswa_id = decode($siswa_id);
      $cek_siswa_ot = $this->model_siswa->cek_siswa_ot(array($siswa_id));
      if($cek_siswa_ot->num_rows() == 0) redirect('a/siswa/index/none');
      $cek_siswa_ot= $cek_siswa_ot->row_array();
      $cek_agama = $this->model_siswa->cek_agama();
      $cek_agama = $cek_agama->result_array();
      $cek_prov = $this->model_siswa->cek_prov();
      $cek_prov = $cek_prov->result_array();
      $cek_pend = $this->model_siswa->cek_pend();
      $cek_pend = $cek_pend->result_array();
      $cek_pek = $this->model_siswa->cek_pek();
      $cek_pek = $cek_pek->result_array();
      $cek_gel_id = $this->model_siswa->cek_gel_id($cek_siswa_ot['siswa_gel']);
      $cek_gel_id = $cek_gel_id->row_array();
      //$cek_mapel = $this->model_siswa->cek_mapel(); //////////
      //$cek_mapel = $cek_mapel->result_array(); //////////
      $cek_jur = $this->model_siswa->cek_jur(); //////////
      $cek_jur = $cek_jur->result_array();//////////
      //$cek_jentest = $this->model_siswa->cek_jentest(); //////////
      //$cek_jentest = $cek_jentest->result_array(); //////////
      if(count($cek_jur) == 0)$data['notice'] = array('red', 'ERROR : Jurusan belum memiliki kuota! '.anchor('a/kuota/index', 'Tambah Kuota'));
      // data siswa
      $data['data_nama'] = $cek_siswa_ot['siswa_nama'];
      $data['data_nama_panggilan'] = $cek_siswa_ot['siswa_nama_panggilan'];
      $data['data_jenis_kelamin_l'] = FALSE;
      $data['data_jenis_kelamin_p'] = FALSE;
      if($cek_siswa_ot['siswa_jenis_kelamin'] == 'l') $data['data_jenis_kelamin_l'] = TRUE;
      else $data['data_jenis_kelamin_p'] = TRUE;
      $data['data_tempat_lahir'] = $cek_siswa_ot['siswa_tempat_lahir'];
      $data['data_tanggal_lahir'] = date('d-m-Y', strtotime($cek_siswa_ot['siswa_tanggal_lahir']));
      $data['data_agama'] = $cek_agama;
      $data['pilih_agama'] = $cek_siswa_ot['siswa_agama'];
      $data['data_sekolah_asal'] = $cek_siswa_ot['siswa_sekolah_asal'];
      $data['data_sekolah_alamat'] = $cek_siswa_ot['siswa_sekolah_alamat'];
      $data['data_suku'] = $cek_siswa_ot['siswa_suku'];
      $data['data_anak_ke'] = $cek_siswa_ot['siswa_anak_ke'];
      $data['data_jumlah_saudara'] = $cek_siswa_ot['siswa_jumlah_saudara'];
      $data['data_kecamatan'] = $cek_siswa_ot['siswa_kecamatan'];
      $data['data_kabupaten'] = $cek_siswa_ot['siswa_kabupaten'];
      $data['data_prov'] = $cek_prov;
      $data['pilih_prov'] = $cek_siswa_ot['siswa_prov'];
      $data['data_kode_pos'] = $cek_siswa_ot['siswa_kode_pos'];
      $data['data_alamat'] = $cek_siswa_ot['siswa_alamat'];
      $data['data_alamat_pos'] = $cek_siswa_ot['siswa_alamat_pos'];
      $data['data_telepon'] = $cek_siswa_ot['siswa_telepon'];
      $data['data_hp'] = $cek_siswa_ot['siswa_hp'];
      $data['data_email'] = $cek_siswa_ot['siswa_email'];
      $data['data_gol_darah_none'] = FALSE;
      $data['data_gol_darah_a'] = FALSE;
      $data['data_gol_darah_b'] = FALSE;
      $data['data_gol_darah_o'] = FALSE;
      $data['data_gol_darah_ab'] = FALSE;
      if($cek_siswa_ot['siswa_gol_darah'] == 'none') $data['data_gol_darah_none'] = TRUE;
      elseif($cek_siswa_ot['siswa_gol_darah'] == 'a') $data['data_gol_darah_a'] = TRUE;
      elseif($cek_siswa_ot['siswa_gol_darah'] == 'b') $data['data_gol_darah_b'] = TRUE;
      elseif($cek_siswa_ot['siswa_gol_darah'] == 'o') $data['data_gol_darah_o'] = TRUE;
      elseif($cek_siswa_ot['siswa_gol_darah'] == 'ab') $data['data_gol_darah_ab'] = TRUE;
      $data['data_tinggi_badan'] = $cek_siswa_ot['siswa_tinggi_badan'];
      $data['data_berat_badan'] = $cek_siswa_ot['siswa_berat_badan'];
      $data['data_penyakit'] = $cek_siswa_ot['siswa_penyakit'];
      $data['data_keterangan'] = $cek_siswa_ot['siswa_keterangan'];
      $data['data_status_sdh_dicek'] = FALSE;
      $data['data_status_blm_dicek'] = FALSE;
      if($cek_siswa_ot['siswa_status'] == 'sdh_dicek') $data['data_status_sdh_dicek'] = TRUE;
      elseif($cek_siswa_ot['siswa_status'] == 'blm_dicek') $data['data_status_blm_dicek'] = TRUE;
      // orang tua
      $data['ot_nama_ayah'] = $cek_siswa_ot['ot_nama_ayah']; // ayah
      $data['ot_pend_ayah'] = $cek_pend;
      $data['pilih_pend_ayah'] = $cek_siswa_ot['ot_pend_ayah'];
      $data['ot_pek_ayah'] = $cek_pek;
      $data['pilih_pek_ayah'] = $cek_siswa_ot['ot_pek_ayah'];
      $data['ot_gaji_ayah'] = $cek_siswa_ot['ot_gaji_ayah'];
      $data['ot_hp_ayah'] = $cek_siswa_ot['ot_hp_ayah'];
      $data['ot_nama_ibu'] = $cek_siswa_ot['ot_nama_ibu']; //ibu
      $data['ot_pend_ibu'] = $cek_pend;
      $data['pilih_pend_ibu'] = $cek_siswa_ot['ot_pend_ibu'];
      $data['ot_pek_ibu'] = $cek_pek;
      $data['pilih_pek_ibu'] = $cek_siswa_ot['ot_pek_ibu'];
      $data['ot_gaji_ibu'] = $cek_siswa_ot['ot_gaji_ibu'];
      $data['ot_hp_ibu'] = $cek_siswa_ot['ot_hp_ibu'];
      $data['ot_alamat_ortu'] = $cek_siswa_ot['ot_alamat_ortu'];
      $data['ot_nama_wali'] = $cek_siswa_ot['ot_nama_wali']; //wali
      $data['ot_pend_wali'] = $cek_pend;
      $data['pilih_pend_wali'] = $cek_siswa_ot['ot_pend_wali'];
      $data['ot_pek_wali'] = $cek_pek;
      $data['pilih_pek_wali'] = $cek_siswa_ot['ot_pek_wali'];
      $data['ot_gaji_wali'] = $cek_siswa_ot['ot_gaji_wali'];
      $data['ot_hp_wali'] = $cek_siswa_ot['ot_hp_wali'];
      $data['ot_alamat_wali'] = $cek_siswa_ot['ot_alamat_wali'];
      $data['ot_status_asuh_ortu'] = FALSE;
      $data['ot_status_asuh_wali'] = FALSE;
      if($cek_siswa_ot['ot_status_asuh'] == 'ortu') $data['ot_status_asuh_ortu'] = TRUE;
      elseif($cek_siswa_ot['ot_status_asuh'] == 'wali') $data['ot_status_asuh_wali'] = TRUE;
      $cek_siswa_nilai = $this->model_siswa->cek_siswa_nilai(array($siswa_id));
      $cek_siswa_nilai = $cek_siswa_nilai->result_array();
      $cek_siswa_prestasi = $this->model_siswa->cek_siswa_prestasi(array($siswa_id));
      $cek_siswa_prestasi = $cek_siswa_prestasi->row_array();
      $cek_siswa_test = $this->model_siswa->cek_siswa_test(array($siswa_id));
      $cek_siswa_test = $cek_siswa_test->result_array();
      $cek_siswa_pilihan = $this->model_siswa->cek_siswa_pilihan(array($siswa_id, $cek_gel_id['gel_jumlah_pilihan']));
      $cek_siswa_pilihan = $cek_siswa_pilihan->result_array();
      // nilai uan
      $data['nilai_mapel'] = $cek_siswa_nilai;
      // nilai prestasi
      $data['nilai_prestasi'] = $cek_siswa_prestasi['prestasi_nilai'];
      // nilai test
      $data['test_jentest'] = $cek_siswa_test;
      // pilih jurusan
      $data['pilihan_jumlah_pilihan'] = $cek_gel_id['gel_jumlah_pilihan'];
      $data['pilihan_jur'] = $cek_jur;
      foreach ($cek_siswa_pilihan as $key => $row) $data['pilih_pilihan'][$key + 1] = $row['jur_id'];
      // data siswa
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_nama_panggilan', ' ', 'trim|max_length[32]|xss_clean');
      $this->form_validation->set_rules('data_jenis_kelamin', ' ', 'trim|xss_clean|decode[]|is_equal[l, p]');
      $this->form_validation->set_rules('data_tempat_lahir', ' ', 'trim|required|max_length[64]|xss_clean');
      $this->form_validation->set_rules('data_tanggal_lahir', ' ', 'trim|required|max_length[10]|xss_clean|is_date');
      $this->form_validation->set_rules('data_sekolah_asal', ' ', 'trim|required|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_sekolah_alamat', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_agama', ' ', 'trim|xss_clean|decode[]|is_in[agama.agama_id]');
      $this->form_validation->set_rules('data_suku', ' ', 'trim|max_length[64]|xss_clean');
      $this->form_validation->set_rules('data_anak_ke', ' ', 'trim|numeric|max_length[2]|less_than[99]|xss_clean');//
      $this->form_validation->set_rules('data_jumlah_saudara', ' ', 'trim|numeric|max_length[2]|less_than[99]|xss_clean');//
      $this->form_validation->set_rules('data_alamat', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_prov', ' ', 'trim|xss_clean|decode[]|is_in[provinsi.prov_id]');
      $this->form_validation->set_rules('data_kabupaten', ' ', 'trim|max_length[64]|xss_clean');
      $this->form_validation->set_rules('data_kecamatan', ' ', 'trim|max_length[64]|xss_clean');
      $this->form_validation->set_rules('data_kode_pos', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_alamat_pos', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_telepon', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_hp', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('data_email', ' ', 'trim|valid_email|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_gol_darah', ' ', 'trim|xss_clean|decode[]|is_equal[a, b, ab, o, none]');
      $this->form_validation->set_rules('data_tinggi_badan', ' ', 'trim|max_length[16]|xss_clean');//
      $this->form_validation->set_rules('data_berat_badan', ' ', 'trim|max_length[16]|xss_clean');//
      $this->form_validation->set_rules('data_penyakit', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('data_status', ' ', 'trim|xss_clean|decode[]|is_equal[sdh_dicek, blm_dicek]');
      // orang tua
      $this->form_validation->set_rules('ot_status_asuh', ' ', 'trim|xss_clean|decode[]|is_equal[ortu, wali]');
      $this->form_validation->set_rules('ot_nama_ayah', ' ', 'trim|max_length[255]|xss_clean'); // ayah
      $this->form_validation->set_rules('ot_pend_ayah', ' ', 'trim|xss_clean|decode[]|is_in[pendidikan.pend_id]');
      $this->form_validation->set_rules('ot_pek_ayah', ' ', 'trim|xss_clean|decode[]|is_in[pekerjaan.pek_id]');
      $this->form_validation->set_rules('ot_gaji_ayah', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_hp_ayah', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_nama_ibu', ' ', 'trim|max_length[255]|xss_clean'); // ibu
      $this->form_validation->set_rules('ot_pend_ibu', ' ', 'trim|xss_clean|decode[]|is_in[pendidikan.pend_id]');
      $this->form_validation->set_rules('ot_pek_ibu', ' ', 'trim|xss_clean|decode[]|is_in[pekerjaan.pek_id]');
      $this->form_validation->set_rules('ot_gaji_ibu', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_hp_ibu', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_alamat_ortu', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('ot_nama_wali', ' ', 'trim|max_length[255]|xss_clean'); // wali
      $this->form_validation->set_rules('ot_pend_wali', ' ', 'trim|xss_clean|decode[]|is_in[pendidikan.pend_id]');
      $this->form_validation->set_rules('ot_pek_wali', ' ', 'trim|xss_clean|decode[]|is_in[pekerjaan.pek_id]');
      $this->form_validation->set_rules('ot_gaji_wali', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_hp_wali', ' ', 'trim|max_length[16]|xss_clean');
      $this->form_validation->set_rules('ot_alamat_wali', ' ', 'trim|max_length[255]|xss_clean');
      // nilai uan
      foreach($cek_siswa_nilai as $row) {
         $this->form_validation->set_rules('nilai_mapel['.$row['mapel_id'].']', ' ', 'trim|required|is_nilai|xss_clean');
         $jumlah_mapel = @$jumlah_mapel + 1;
      }
      // nilai prestasi
      $this->form_validation->set_rules('nilai_prestasi', ' ', 'trim|is_nilai|xss_clean');
      // nilai test
      foreach($cek_siswa_test as $row) $this->form_validation->set_rules('test_jentest['.$row['jentest_id'].']', ' ', 'trim|is_nilai['.$jumlah_mapel.']|xss_clean');
      // pilih jurusan
      for($i = 1; $i <= $cek_gel_id['gel_jumlah_pilihan']; $i++) $this->form_validation->set_rules('pilihan_jur['.$i.']', ' ', 'trim|required|xss_clean|decode[]|is_in[jurusan.jur_id]');
      if($this->form_validation->run()){
         // siswa //$siswa_id = // ambil dari atas
         $siswa_nama = $this->input->post('data_nama', TRUE);
         $siswa_nama_panggilan = $this->input->post('data_nama_panggilan', TRUE);
         $siswa_jenis_kelamin = $this->input->post('data_jenis_kelamin', TRUE);
         $siswa_tempat_lahir = $this->input->post('data_tempat_lahir', TRUE);
         $siswa_tanggal_lahir = date('Y-m-d', strtotime($this->input->post('data_tanggal_lahir', TRUE)));
         $siswa_sekolah_asal = $this->input->post('data_sekolah_asal', TRUE);
         $siswa_sekolah_alamat = $this->input->post('data_sekolah_alamat', TRUE);
         $siswa_agama = $this->input->post('data_agama', TRUE);
         $siswa_suku = $this->input->post('data_suku', TRUE);
         $siswa_anak_ke = $this->input->post('data_anak_ke', TRUE);
         $siswa_jumlah_saudara = $this->input->post('data_jumlah_saudara', TRUE);
         $siswa_alamat = $this->input->post('data_alamat', TRUE);
         $siswa_prov = $this->input->post('data_prov', TRUE);
         $siswa_kabupaten = $this->input->post('data_kabupaten', TRUE);
         $siswa_kecamatan = $this->input->post('data_kecamatan', TRUE);
         $siswa_kode_pos = $this->input->post('data_kode_pos', TRUE);
         $siswa_alamat_pos = $this->input->post('data_alamat_pos', TRUE);
         $siswa_telepon = $this->input->post('data_telepon', TRUE);
         $siswa_hp = $this->input->post('data_hp', TRUE);
         $siswa_email = $this->input->post('data_email', TRUE);
         $siswa_gol_darah = $this->input->post('data_gol_darah', TRUE);
         $siswa_tinggi_badan = $this->input->post('data_tinggi_badan', TRUE);
         $siswa_berat_badan = $this->input->post('data_berat_badan', TRUE);
         $siswa_penyakit = $this->input->post('data_penyakit', TRUE);
         $siswa_tanggal_ulang = date('Y-m-d');
         $siswa_status = $this->input->post('data_status', TRUE);
         $siswa_keterangan = $this->input->post('data_keterangan', TRUE);
         $query_siswa = $this->model_siswa->edit_siswa(array($siswa_nama, $siswa_nama_panggilan, $siswa_jenis_kelamin, $siswa_tempat_lahir, $siswa_tanggal_lahir, $siswa_sekolah_asal, $siswa_sekolah_alamat, $siswa_agama, $siswa_suku, $siswa_anak_ke, $siswa_jumlah_saudara, $siswa_alamat, $siswa_prov, $siswa_kabupaten, $siswa_kecamatan, $siswa_kode_pos, $siswa_alamat_pos, $siswa_telepon, $siswa_hp, $siswa_email, $siswa_gol_darah, $siswa_tinggi_badan, $siswa_berat_badan, $siswa_penyakit, $siswa_tanggal_ulang, $siswa_status, $siswa_keterangan, $siswa_id));
         // orang tua
         $ot_nama_ayah = $this->input->post('ot_nama_ayah', TRUE);
         $ot_pend_ayah = $this->input->post('ot_pend_ayah', TRUE);
         $ot_pek_ayah = $this->input->post('ot_pek_ayah', TRUE);
         $ot_gaji_ayah = $this->input->post('ot_gaji_ayah', TRUE);
         $ot_hp_ayah = $this->input->post('ot_hp_ayah', TRUE);
         $ot_nama_ibu = $this->input->post('ot_nama_ibu', TRUE);
         $ot_pend_ibu = $this->input->post('ot_pend_ibu', TRUE);
         $ot_pek_ibu = $this->input->post('ot_pek_ibu', TRUE);
         $ot_gaji_ibu = $this->input->post('ot_gaji_ibu', TRUE);
         $ot_hp_ibu = $this->input->post('ot_hp_ibu', TRUE);
         $ot_alamat_ortu = $this->input->post('ot_alamat_ortu', TRUE);
         $ot_nama_wali = $this->input->post('ot_nama_wali', TRUE);
         $ot_pend_wali = $this->input->post('ot_pend_wali', TRUE);
         $ot_pek_wali = $this->input->post('ot_pek_wali', TRUE);
         $ot_gaji_wali = $this->input->post('ot_gaji_wali', TRUE);
         $ot_hp_wali = $this->input->post('ot_hp_wali', TRUE);
         $ot_alamat_wali = $this->input->post('ot_alamat_wali', TRUE);
         $ot_status_asuh = $this->input->post('ot_status_asuh', TRUE);
         $query_ot = $this->model_siswa->edit_orang_tua(array($ot_nama_ayah, $ot_pend_ayah, $ot_pek_ayah, $ot_gaji_ayah, $ot_hp_ayah, $ot_nama_ibu, $ot_pend_ibu, $ot_pek_ibu, $ot_gaji_ibu, $ot_hp_ibu,$ot_alamat_ortu, $ot_nama_wali, $ot_pend_wali, $ot_pek_wali, $ot_gaji_wali, $ot_hp_wali, $ot_alamat_wali, $ot_status_asuh, $siswa_id));
         // nilai
         $nilai_mapel = $this->input->post('nilai_mapel', TRUE);
         foreach($cek_siswa_nilai as $row) $query_nilai = $this->model_siswa->edit_nilai(array($nilai_mapel[$row['mapel_id']], $siswa_id, $row['mapel_id']));
         // nilai prestasi
         $nilai_prestasi = $this->input->post('nilai_prestasi', TRUE);
         $query_prestasi = $this->model_siswa->edit_prestasi(array($nilai_prestasi, $siswa_id));
         // test
         $test_jentest = $this->input->post('test_jentest', TRUE);
         foreach($cek_siswa_test as $row) $query_test = $this->model_siswa->edit_test(array($test_jentest[$row['jentest_id']], $siswa_id, $row['jentest_id']));
         // pilihan
         $x = $this->input->post('pilihan_jur', TRUE);
         $pilihan_jur[1] = NULL;
         $pilihan_jur[2] = NULL;
         $pilihan_jur[3] = NULL;
         for($i = 1; $i <= 3; $i++){
            if($i <= count($x)) $pilihan_jur[$i] = $x[$i];
            $query_pilihan = $this->model_siswa->edit_pilihan(array($pilihan_jur[$i], 'kosong', $siswa_id, $i));
         }
         // start of hasil // #######################################################
         $this->load->helper('hasil'); //load helper
         generate($cek_gel_id['gel_id']); // generate hasil
         // end of hasil // #########################################################
         $data['notice'] = array('green', 'SUCCES : Update data berhasil! '.anchor(decode($url), 'Kembali'));
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
         $notice = 'editfalse';
         echo validation_errors();
      }
      $this->load->view(view('a', 'view_siswa_data'), $data);
   }
}