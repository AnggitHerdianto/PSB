<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setting extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      if(!is_admin()) show_404();
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_setting');
   }
   function index($notice = FALSE){
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Informasi Sekolah';
      $data['notice'] = array('', '');
      $data['post'] = 'a/setting/index';
      $data['simpan'] = 'Simpan';
      $query = $this->model_setting->cek_setting();
      $query = $query->result_array();
      foreach ($query as $row){
         if($row['setting_nama'] == 'web_judul') $data['web_judul'] = $row['setting_value'];
         elseif($row['setting_nama'] == 'sekolah_nama') $data['sekolah_nama'] = $row['setting_value'];
         elseif($row['setting_nama'] == 'sekolah_alamat') $data['sekolah_alamat'] = $row['setting_value'];
         elseif($row['setting_nama'] == 'sekolah_telpon') $data['sekolah_telpon'] = $row['setting_value'];
         elseif($row['setting_nama'] == 'sekolah_email') $data['sekolah_email'] = $row['setting_value'];
      }
      $this->form_validation->set_rules('web_judul', ' ', 'trim|required|max_length[255]|xss_clean');
      $this->form_validation->set_rules('sekolah_nama', ' ', 'trim|required|max_length[255]|xss_clean');
      $this->form_validation->set_rules('sekolah_telpon', ' ', 'trim|max_length[255]|xss_clean');
      $this->form_validation->set_rules('sekolah_email', ' ', 'trim|max_length[255]|xss_clean|valid_email');
      $this->form_validation->set_rules('sekolah_alamat', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $web_judul = $this->input->post('web_judul', TRUE);
         $sekolah_nama = $this->input->post('sekolah_nama', TRUE);
         $sekolah_telpon = $this->input->post('sekolah_telpon', TRUE);
         $sekolah_email = $this->input->post('sekolah_email', TRUE);
         $sekolah_alamat = $this->input->post('sekolah_alamat', TRUE);
         $query = $this->model_setting->edit(array('web_judul', $web_judul));
         $query = $this->model_setting->edit(array('sekolah_nama', $sekolah_nama));
         $query = $this->model_setting->edit(array('sekolah_telpon', $sekolah_telpon));
         $query = $this->model_setting->edit(array('sekolah_email', $sekolah_email));
         $query = $this->model_setting->edit(array('sekolah_alamat', $sekolah_alamat));
         $data['notice'] = array('green', 'SECCES : Update berhasil');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_setting'), $data);
   }
}