<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class About extends CI_Controller {
   function __construct(){
      parent::__construct();
      if(!is_login()) redirect('a/login/index/loginfalse/'.encode(uri_string()));
      date_default_timezone_set('Asia/Jakarta');
   }
   function index(){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Informasi Pengembang';
      $data['notice'] = array('', '');
      $data['msg'] = 'Sofware ini dibuat oleh R. Anggit Herdianto<br><br>'.
      '<strong>Git</strong> : '.anchor('http://github.com/AnggitHerdianto', 'http://github.com/AnggitHerdianto', 'target=_BLANK').'<br>'.
      '<strong>FaceBook</strong> : '.anchor('http://fb.com/AnggitHerdianto', 'http://fb.com/AnggitHerdianto', 'target=_BLANK').'<br>'.
      '<strong>Twitter</strong> : '.anchor('http://twitter.com/AnggitHerdianto', 'http://twitter.com/AnggitHerdianto', 'target=_BLANK').'<br>'
      ;
      $this->load->view(view('a', 'view_about'), $data);
   }
   function software(){
      $data['url'] = array('a', __CLASS__, __FUNCTION__);
      $data['judul'] = 'Informasi Software';
      $data['notice'] = array('', '');
      $data['msg'] = 'Sofware Penerimaan Siswa Baru';
      $this->load->view(view('a', 'view_about'), $data);
   }
}