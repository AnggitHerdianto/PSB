<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller{
   function __construct(){
      parent::__construct();
      date_default_timezone_set('Asia/Jakarta');
      $this->expiration = 120;
   }
   function index($notice = FALSE, $url = FALSE){
      if(is_login()) redirect ('a/home/index');
      ////////////////////// START CAPCAY //////////////////////
      $this->load->helper('captcha');
      $vals = array(
         'font_path' => './psb-content/fonts/texb.ttf',
         'img_path' => './psb-content/captcha/', 
         'img_url' => base_url().'psb-content/captcha/', 
         'img_width' => 145, 
         'img_height' =>30, 
         'expiration' => $this->expiration
      );
      $cap = create_captcha($vals);
      $data = array(
         'captcha_time' => $cap['time'], 
         'ip_address' => $this->input->ip_address(), 
         'word' => strtolower($cap['word'])
      );
      $query = $this->db->insert_string('captcha', $data);
      $this->db->query($query);
      $data['capcay'] = $cap['image'];
      $this->db->query("DELETE FROM captcha WHERE captcha_time < ".(time()-($this->expiration))); //hapus capcay expired
      ////////////////////// END CAPCAY //////////////////////
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Halaman Login';
      $data['notice'] = array('', '');
      $data['post'] = 'a/login/index/'.$notice.'/'.$url;
      $this->form_validation->set_rules('data_username', 'Username', 'trim|required|max_length[128]|alpha_dash|xss_clean');
      $this->form_validation->set_rules('data_password', 'Password', 'trim|required|min_length[5]|max_length[128]|xss_clean');
      $cek_capcay = $this->form_validation->set_rules('data_capcay', 'Captcha', 'trim|required|max_length[8]|xss_clean|callback_cek_capcay');
      if($this->form_validation->run()){
         $user_username = $this->input->post('data_username', TRUE);
         $user_password = md5(sha1($this->input->post('data_password', TRUE)));
         if(login($user_username, $user_password) AND $cek_capcay){
            if(strlen(decode($url)) > 0) redirect(decode($url));
            else redirect('a/home/index');
         }
         else {$data['notice'] = array('red', 'ERROR : Data tidak lengkap');}
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap!');
         elseif($notice == 'logouttrue') $data['notice'] = array('yellow', 'NOTICE : Sekarang anda keluar!');
         elseif($notice == 'loginfalse') $data['notice'] = array('yellow', 'NOTICE : Saat ini anda tidak login!');
         elseif($notice == 'email') $data['notice'] = array('green', 'SUCCESS : Silahkan cek email untuk mereset password!');
         elseif($notice == 'resettrue') $data['notice'] = array('green', 'SUCCESS : Password berhasil diubah, silahkan login!');
      }
      $this->load->view(view('login', 'view_login'), $data);
   }
   function cek_capcay(){
      $user_expiration = time()-($this->expiration); 
      $user_capcay = strtolower($this->input->post('data_capcay', TRUE));
      if (capcay($user_expiration, $user_capcay)) {
         return TRUE;
      }
      else{
         $this->form_validation->set_message('cek_capcay', 'SALAH');
         return FALSE;
      }
   }
   function logout(){
      if(!is_login()) redirect('a/login/index/loginfalse');
      if(logout()) redirect('a/login/index/logouttrue');
   }
   function forgot($notice = FALSE){
      if(is_login()) redirect ('a/home/index');
      $this->load->model('a/model_login');
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Lupa Password';
      $data['notice'] = array('', '');
      $data['post'] = 'a/login/forgot/';
      $this->form_validation->set_rules('data_username_email', ' ', 'trim|required|max_length[128]|alpha_dash|xss_clean');
      if($this->form_validation->run()){
         $user_username_email = $this->input->post('data_username_email', TRUE);
         $query = $this->model_login->forgot(array($user_username_email));
         if($query->num_rows() == 1){
            $query = $query->row_array();
            $reset_users = $query['user_id'];
            $reset_link = encode(encode(sha1(encode(md5(encode($query['user_id'].'-'.time()))))));
            $reset_input = date('Y-m-d H:i:s', time()); //hari ini
            $reset_expired = date('Y-m-d H:i:s', time() + (1 * 24 * 60 * 60)); //1hari
            $add_reset_pass = $this->model_login->add_reset_pass(array($reset_users, $reset_link, $reset_input, $reset_expired));
            // kirim email
            $this->load->library('email');
            $this->email->from(setting('sekolah_email'), setting('sekolah_nama'));
            $this->email->to($query['user_email']);
            $this->email->subject(setting('web_judul'));
            $this->email->message('Reset Password = '.base_url().'a/login/reset/'.$reset_link);
            $this->email->send(); //echo $this->email->print_debugger();
            redirect('a/login/index/email');
         }
         else $data['notice'] = array('yellow', 'NOTICE : Username tidak ditemukan!');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : Data tidak lengkap!');
      }
      $this->load->view(view('login', 'view_forgot'), $data);
   }
   function reset($id = FALSE){
      if(is_login()) redirect ('a/home/index');
      $this->load->model('a/model_login');
      $query = $this->model_login->cek_reset_pass(array($id));
      $query = $query->row_array();
      if(count($query) == 0) show_404();
      else{
         $reset_input = strtotime($query['reset_input']);
         $reset_now = time();
         $reset_expired = strtotime($query['reset_expired']);
         if($reset_input <= $reset_now AND $reset_now <= $reset_expired) $data['link'] = TRUE;
         else $data['link'] = FALSE;
      }
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Update Password';
      $data['notice'] = array('', '');
      $data['post'] = 'a/login/reset/'.$id;
      $this->form_validation->set_rules('data_password', ' ', 'trim|required|min_length[5]|max_length[128]|xss_clean');
      $this->form_validation->set_rules('data_passconf', ' ', 'trim|required|matches[data_password]|xss_clean');
      if($this->form_validation->run() AND $data['link']){
         $user_password = md5(sha1($this->input->post('data_password', TRUE)));
         $this->model_login->edit_reset(array($user_password, $query['reset_users']));
         $this->model_login->delete_reset(array($query['reset_users']));
         redirect('a/login/index/resettrue');
      }
      else{
         if(css_notice(validation_errors())) $data['notice'] = array('red', 'ERROR : ERROR : Data tidak lengkap!');
      }
      $this->load->view(view('login', 'view_reset'), $data);
   }
}