<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Gelombang extends CI_Controller{
   function __construct(){
      parent::__construct();
      if (!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_gelombang');
   }
   function index($notice = FALSE){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Gelombang';
      $data['notice'] = array('', '');
      $query = $this->model_gelombang->index();
      if ($query->num_rows == 0) $data['notice'] = array('yellow', 'NOTICE : Gelombang belum ada! ' . anchor('a/gelombang/add', 'Tambah Gelombang'));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'deletefalse') $data['notice'] = array('red', 'ERROR : Data tidak dapat dihapus! Minimal harus memiliki 1 gelombang.');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      $this->load->view(view('a', 'view_gelombang'), $data);
   }
   function delete($gel_id = 0, $do = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Gelombang';
      $data['notice'] = array('', '');
      $data['get'] = 'a/gelombang/delete/'.$gel_id.'/do';
      $data['back'] = 'a/gelombang/index';
      //cek apakah gel ada
      $query = $this->model_gelombang->cek_gel_id(array(decode($gel_id)));
      if ($query->num_rows() == 0) redirect('a/gelombang/index/none');
      //jika tinggal satu tidak bisa dihapus
      $cek_gel_jml = $this->model_gelombang->index(array(decode($gel_id)));
      if ($cek_gel_jml->num_rows() == 1) redirect('a/gelombang/index/deletefalse');
      //
      $query = $query->row_array();
      $data['msg'] = 'Hapus "'.$query['gel_ta'].' - '.$query['gel_nama'].'"?';
      if ($do == 'do') {
         $query = $this->model_gelombang->delete(array($query['gel_id']));
         if ($query)redirect('a/gelombang/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      $data['doOnLoad'] = TRUE;
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Tambah Gelombang';
      $data['notice'] = array('', '');
      $data['post'] = 'a/gelombang/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/gelombang/index';
      $cek_jur_jml = $this->model_gelombang->cek_jur_jml();
      if ($cek_jur_jml->num_rows() == 0) $cek_jur_jml = 1;
      elseif ($cek_jur_jml->num_rows() > 3) $cek_jur_jml = 3;
      else $cek_jur_jml = $cek_jur_jml->num_rows();
      $data['data_ta'] = date('Y');
      $data['data_nama'] = '';
      $data['data_tanggal_mulai'] = date('d-m-Y', time() + (7 * 86400));
      $data['data_tanggal_selesai'] = date('d-m-Y', time() + (14 * 86400));
      $data['data_jumlah_pilihan'] = $cek_jur_jml;
      $data['pilih'] = 1;
      $data['data_keterangan'] = '';
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_tanggal_mulai', ' ', 'trim|required|max_length[10]|xss_clean|is_date');
      $this->form_validation->set_rules('data_tanggal_selesai', ' ', 'trim|required|max_length[10]|xss_clean|is_date|callback_cek_gel_tgl_selesai');
      $this->form_validation->set_rules('data_jumlah_pilihan', ' ', 'trim|required|decode[]|numeric|is_equal[1, 2, 3]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if ($this->form_validation->run()){
         $cek_gel_kode = $this->model_gelombang->cek_gel_kode();
         if($cek_gel_kode->num_rows() == 0) $gel_kode = (date('y') * 100) + 1;
         else{
            $cek_gel_kode = $cek_gel_kode->row_array();
            if(substr($cek_gel_kode['gel_kode'], 0, 2) < date('y')) $gel_kode = (date('y') * 100) + 1;
            else $gel_kode = (date('y') * 100) + (substr($cek_gel_kode['gel_kode'], 2, 4) + 1);
         }
         $gel_ta = date('Y');
         $gel_nama = $this->input->post('data_nama', TRUE);
         $gel_tanggal_mulai = date('Y-m-d', strtotime($this->input->post('data_tanggal_mulai', TRUE)));
         $gel_tanggal_selesai = date('Y-m-d', strtotime($this->input->post('data_tanggal_selesai', TRUE)));
         $gel_jumlah_pilihan = $this->input->post('data_jumlah_pilihan', TRUE);
         $gel_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_gelombang->add(array($gel_ta, $gel_kode, $gel_nama, $gel_tanggal_mulai, $gel_tanggal_selesai,$gel_jumlah_pilihan, $gel_keterangan));
         if ($query){
            $this->model_gelombang->add_mapel();
            $this->model_gelombang->add_test();
            redirect('a/gelombang/index/addtrue');
         }
      }
      else{ if (css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_gelombang_data'), $data);
   }
   function edit($gel_id = 0){
      $data['doOnLoad'] = TRUE;
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Jurusan';
      $data['notice'] = array('', '');
      $data['post'] = 'a/gelombang/edit/'.$gel_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/gelombang/index';
      $query = $this->model_gelombang->cek_gel_id(array(decode($gel_id)));
      if ($query->num_rows() == 0)redirect('a/gelombang/index/none');
      $cek_jur_jml = $this->model_gelombang->cek_jur_jml();
      if ($cek_jur_jml->num_rows() == 0) $cek_jur_jml = 1;
      elseif ($cek_jur_jml->num_rows() > 3) $cek_jur_jml = 3;
      else $cek_jur_jml = $cek_jur_jml->num_rows();
      $query = $query->row_array();
      $data['data_ta'] = $query['gel_ta'];
      $data['data_nama'] = $query['gel_nama'];
      $data['data_tanggal_mulai'] = date('d-m-Y', strtotime($query['gel_tanggal_mulai']));
      $data['data_tanggal_selesai'] = date('d-m-Y', strtotime($query['gel_tanggal_selesai']));
      $data['data_jumlah_pilihan'] = $cek_jur_jml;
      $data['pilih'] = $query['gel_jumlah_pilihan'];
      $data['data_keterangan'] = $query['gel_keterangan'];
      $this->form_validation->set_rules('data_nama', ' ', 'trim|required|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_tanggal_mulai', ' ', 'trim|required|max_length[10]|xss_clean|is_date');
      $this->form_validation->set_rules('data_tanggal_selesai', ' ', 'trim|required|max_length[10]|xss_clean|is_date|callback_cek_gel_tgl_selesai');
      $this->form_validation->set_rules('data_jumlah_pilihan', ' ', 'trim|required|decode[]|numeric|is_equal[1, 2, 3]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if ($this->form_validation->run()){
         $gel_id = $query['gel_id'];
         $gel_ta = $query['gel_ta'];
         $gel_nama = $this->input->post('data_nama', TRUE);
         $gel_tanggal_mulai = date('Y-m-d', strtotime($this->input->post('data_tanggal_mulai', TRUE)));
         $gel_tanggal_selesai = date('Y-m-d', strtotime($this->input->post('data_tanggal_selesai', TRUE)));
         $gel_jumlah_pilihan = $this->input->post('data_jumlah_pilihan', TRUE);
         $gel_keterangan = $this->input->post('data_keterangan', TRUE);
         //update
         $query = $this->model_gelombang->edit(array($gel_ta, $gel_nama, $gel_tanggal_mulai, $gel_tanggal_selesai, $gel_jumlah_pilihan, $gel_keterangan, $gel_id));
            // start of hasil // #########################################################
            $this->load->helper('hasil'); //load helper
            generate($gel_id); // generate hasil
            // end of hasil // #########################################################
         //setelah update mengisi pilihan agar tidak null, disi dengan pilihan pertama
         $this->model_gelombang->edit_add(array($gel_id, $gel_jumlah_pilihan));
         //
         if ($query) redirect('a/gelombang/index/edittrue');
         else redirect('a/gelombang/index/editfalse');
      }
      else{
         if (css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_gelombang_data'), $data);
   }
   function cek_gel_tgl_selesai(){
      $gel_tanggal_mulai = strtotime($this->input->post('data_tanggal_mulai', TRUE));
      $gel_tanggal_selesai = strtotime($this->input->post('data_tanggal_selesai', TRUE));
      if ($gel_tanggal_mulai <= $gel_tanggal_selesai) return TRUE;
      else{
         $this->form_validation->set_message('cek_gel_tgl_selesai', 'Harus lebih akhir dari tanggal mulai.');
         return FALSE;
      }
   }
}