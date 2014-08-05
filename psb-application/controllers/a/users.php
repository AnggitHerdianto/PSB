<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends CI_Controller{
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      date_default_timezone_set('Asia/Jakarta');
      $this->load->model('a/model_users');
   }
   function index($notice = FALSE){
      if(!is_admin()) show_404();
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Semua Pengguna';
      $data['notice'] = array('', '');
      $user_id = decode($this->session->userdata('user_id'));
      if(is_admin()) $query = $this->model_users->index_admin();
      else $query = $this->model_users->index_user(array($user_id));
      $data['query'] = $query->result_array();
      if($notice == 'deletetrue') $data['notice'] = array('green', 'SUCCES : Hapus data berhasil!');
      elseif($notice == 'edittrue') $data['notice'] = array('green', 'SUCCES : Edit data berhasil!');
      elseif($notice == 'editfalse') $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah!');
      elseif($notice == 'addtrue') $data['notice'] = array('green', 'SUCCES : Berhasil menambah data!');
      elseif($notice == 'none') $data['notice'] = array('yellow', 'NOTICE : Data tidak ada!');
      elseif($notice == 'lastuser') $data['notice'] = array('red', 'ERROR : Data tidak dapat dihapus! Minimal harus ada 1 <strong>"superadmin"</strong>');
      $this->load->view(view('a', 'view_users'), $data);
   }
   function delete($user_id = 0, $do = FALSE){
      if(!is_admin()) show_404();
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Hapus Pengguna';
      $data['notice'] = array('', '');
      $data['get'] = 'a/users/delete/'.$user_id.'/do';
      $data['back'] = 'a/users/index';
      $query = $this->model_users->cek_user_id(array(decode($user_id)));
      if($query->num_rows() == 0) redirect('a/users/index/none');
      $query = $query->row_array();
      if($query['user_pangkat'] == 'admin'){
         $cek_pangkat = $this->model_users->cek_pangkat(array('admin'));
         if($cek_pangkat->num_rows() == 1)
         redirect('a/users/index/lastuser');
      }
      $data['msg'] = 'Hapus "'.$query['user_username'].'"?';
      if($do == 'do'){
         $query = $this->model_users->delete(array($query['user_id']));
         if($query) redirect('a/users/index/deletetrue/');
         else redirect('a/users/index/deletetrue/');
      }
      $this->load->view(view('a', 'view_/view_delete'), $data);
   }
   function add(){
      if(!is_admin()) show_404();
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Tambah Pengguna';
      $data['notice'] = array('', '');
      $data['post'] = 'a/users/add';
      $data['simpan'] = 'Simpan';
      $data['back'] = 'a/users/index';
      $data['data_username'] = '';
      $data['data_email'] = '';
      $data['data_pangkat_admin'] = TRUE;
      $data['data_pangkat_user'] = FALSE;
      $data['data_nama'] = '';
      $data['data_keterangan'] = '';
      $this->form_validation->set_rules('data_username', ' ', 'trim|required|max_length[128]|alpha_dash|xss_clean|is_unique[users.user_username]');
      $this->form_validation->set_rules('data_password', ' ', 'trim|required|min_length[5]|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_passconf', ' ', 'trim|required|matches[data_password]|xss_clean');
      $this->form_validation->set_rules('data_email', ' ', 'trim|required|max_length[100]|valid_email|xss_clean|is_unique[users.user_email]');
      $this->form_validation->set_rules('data_pangkat', ' ', 'trim|required|xss_clean|decode[]');
      $this->form_validation->set_rules('data_nama', ' ', 'trim|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $user_username = $this->input->post('data_username', TRUE);
         $user_password =md5(sha1($this->input->post('data_password', TRUE)));
         $user_email = $this->input->post('data_email', TRUE);
         $user_pangkat = $this->input->post('data_pangkat', TRUE);
         $user_nama = $this->input->post('data_nama', TRUE);
         $user_tanggal = date('Y-m-d');
         $user_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_users->add(array($user_username, $user_password, $user_email, $user_pangkat, $user_nama, $user_tanggal, $user_keterangan));
         if($query)redirect('a/users/index/addtrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_users_data'), $data);
   }
   function edit($user_id = 0){
      if(!is_admin()) redirect ('a/users/profile');
      $data['url'] = array('a', __CLASS__, 'index');
      $data['judul'] = 'Edit Profil';
      $data['notice'] = array('', '');
      $data['post'] = 'a/users/edit/'.$user_id;
      $data['simpan'] = 'Update';
      $data['back'] = 'a/users/index';
      $query = $this->model_users->cek_user_id(array(decode($user_id)));
      if($query->num_rows() == 0) redirect('a/users/index/none');
      $query = $query->row_array();
      $data['data_username'] = $query['user_username'];
      $data['data_password'] = NULL;
      $data['data_passconf'] = NULL;
      $data['data_email'] = $query['user_email'];
      if($query['user_pangkat'] == 'admin' ){
         $data['data_pangkat_admin'] = TRUE;
         $data['data_pangkat_user'] = FALSE;
      }
      else{
        $data['data_pangkat_admin'] = FALSE;
         $data['data_pangkat_user'] = TRUE;
      }
      $data['data_nama'] = $query['user_nama'];
      $data['data_keterangan'] = $query['user_keterangan'];
      $data['user_pangkat'] = $query['user_pangkat'];
      $this->form_validation->set_rules('data_username', ' ', 'trim|required|max_length[128]|alpha_dash|xss_clean|callback_cek_user_username');
      if(strlen($this->input->post('data_password', TRUE)) > 0 ){
         $this->form_validation->set_rules('data_password', ' ', 'trim|required|min_length[5]|max_length[128]|xss_clean');
         $this->form_validation->set_rules('data_passconf', ' ', 'trim|required|matches[data_password]|xss_clean');
      }
      $this->form_validation->set_rules('data_email', ' ', 'trim|required|max_length[100]|valid_email|xss_clean|callback_cek_user_email');
      if(is_admin()) $this->form_validation->set_rules('data_pangkat', ' ', 'trim|required|xss_clean||decode[]|callback_cek_user_pangkat');
      $this->form_validation->set_rules('data_nama', ' ', 'trim|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $user_id = $query['user_id'];
         $user_username = $this->input->post('data_username', TRUE);
         if(strlen($this->input->post('data_password', TRUE)) > 0 ) $user_password =md5(sha1($this->input->post('data_password', TRUE)));
         else $user_password = $query['user_password'];
         $user_email = $this->input->post('data_email', TRUE);
         if(is_admin()) $user_pangkat = $this->input->post('data_pangkat', TRUE);
         else $user_pangkat = $data['user_pangkat'];
         $user_nama = $this->input->post('data_nama', TRUE);
         $user_tanggal = date('Y-m-d');
         $user_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_users->edit(array($user_username, $user_password, $user_email, $user_pangkat, $user_nama, $user_tanggal, $user_keterangan, $user_id));
         if($query) $data['notice'] = array('green', 'SUCCES : Data berhasil diupdate');
         else $data['notice'] = array('yellow', 'NOTICE : Data tidak berubah');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_users_data'), $data);
   }
   function profile (){
      $user_id = $this->encrypt->decode($this->session->userdata('user_id'));
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Profil Saya';
      $data['notice'] = array('', '');
      $data['post'] = 'a/users/profile';
      $data['simpan'] = 'Update';
      $query = $this->model_users->cek_user_id(array($user_id));
      if($query->num_rows() == 0) redirect('a/users/index/none');
      $query = $query->row_array();
      $data['data_id'] = $this->encrypt->encode($query['user_id']);
      $data['data_username'] = $query['user_username'];
      $data['data_password'] = NULL;
      $data['data_passconf'] = NULL;
      $data['data_email'] = $query['user_email'];
      if($query['user_pangkat'] == 'admin' ){
         $data['data_pangkat_admin'] = TRUE;
         $data['data_pangkat_user'] = FALSE;
      }
      else{
         $data['data_pangkat_admin'] = FALSE;
         $data['data_pangkat_user'] = TRUE;
      }
      $data['data_nama'] = $query['user_nama'];
      $data['data_keterangan'] = $query['user_keterangan'];
      $data['user_pangkat'] = $query['user_pangkat'];
      $this->form_validation->set_rules('data_username', ' ', 'trim|required|max_length[128]|alpha_dash|xss_clean|callback_cek_user_username');
      if(strlen($this->input->post('data_password', TRUE)) > 0 ){
         $this->form_validation->set_rules('data_password', ' ', 'trim|required|min_length[5]|max_length[128]|xss_clean');
         $this->form_validation->set_rules('data_passconf', ' ', 'trim|required|matches[data_password]|xss_clean');
      }
      $this->form_validation->set_rules('data_email', ' ', 'trim|required|max_length[100]|valid_email|xss_clean|callback_cek_user_email');
      if(is_admin()) $this->form_validation->set_rules('data_pangkat', ' ', 'trim|required|xss_clean||decode[]|callback_cek_user_pangkat');
      $this->form_validation->set_rules('data_nama', ' ', 'trim|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_keterangan', ' ', 'trim|max_length[255]|xss_clean');
      if($this->form_validation->run()){
         $user_id = $query['user_id'];
         $user_username = $this->input->post('data_username', TRUE);
         if(strlen($this->input->post('data_password', TRUE)) > 0 ) $user_password = md5(sha1($this->input->post('data_password', TRUE)));
         else $user_password = $query['user_password'];
         $user_email = $this->input->post('data_email', TRUE);
         if(is_admin()) $user_pangkat = $this->input->post('data_pangkat', TRUE);
         else $user_pangkat = $data['user_pangkat'];
         $user_nama = $this->input->post('data_nama', TRUE);
         $user_tanggal = date('Y-m-d');
         $user_keterangan = $this->input->post('data_keterangan', TRUE);
         $query = $this->model_users->edit(array($user_username, $user_password, $user_email, $user_pangkat, $user_nama,$user_tanggal, $user_keterangan, $user_id));
         if($query) $data['notice'] = array('green', 'SUCCES : Profil berhasil diupdate');
         else $data['notice'] = array('yellow', 'NOTICE : Profil tidak berubah');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap');
      }
      $this->load->view(view('a', 'view_users_data'), $data);
   }
   function cek_user_username(){
      $user_id = decode($this->uri->rsegment(3));
      if(strtolower($this->uri->rsegment(2)) == 'profile') $user_id = $this->encrypt->decode($this->session->userdata('user_id'));
      $user_username = $this->input->post('data_username', TRUE);
      $query = $this->model_users->cek_user_username(array($user_id, $user_username));
      if($query->num_rows() == 0) return TRUE;
      else{
         $this->form_validation->set_message('cek_user_username', 'The field must contain a unique value.');
         return FALSE;
      }
   }
   function cek_user_email(){
      $user_id = decode($this->uri->rsegment(3));
      if(strtolower($this->uri->rsegment(2)) == 'profile') $user_id = $this->encrypt->decode($this->session->userdata('user_id'));
      $user_email = $this->input->post('data_email', TRUE);
      $query = $this->model_users->cek_user_email(array($user_id, $user_email));
      if($query->num_rows() == 0) return TRUE;
      else{
         $this->form_validation->set_message('cek_user_email', 'The field must contain a unique value.');
         return FALSE;
      }
   }
   function cek_user_pangkat(){
      $user_id = decode($this->uri->rsegment(3));
      if(strtolower($this->uri->rsegment(2)) == 'profile') $user_id = $this->encrypt->decode($this->session->userdata('user_id'));
      $user_pangkat = decode($this->input->post('data_pangkat', TRUE));
      $query = $this->model_users->cek_user_id(array($user_id));
      $query = $query->row_array();
      if($query['user_pangkat'] == 'admin' AND $user_pangkat == 'user'){
         $cek_pangkat = $this->model_users->cek_pangkat(array('admin'));
         if($cek_pangkat->num_rows() == 1){
            $this->form_validation->set_message('cek_user_pangkat', 'Minimal harus ada 1 admin!');
            return FALSE;
         }
         else return TRUE;
      }
      else return TRUE;
   }
}