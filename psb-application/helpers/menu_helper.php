<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('menu_horizontal')){
   function menu_horizontal($position = 'left'){
      $CI =& get_instance();
      $CI->load->model('a/model_users');
      $user_id = $CI->encrypt->decode($CI->session->userdata('user_id'));
      $cek_nama = $CI->model_users->cek_user_id($user_id);
      $cek_nama = $cek_nama->row_array();
      if(strlen($cek_nama['user_nama']) > 0) $menu_nama = $cek_nama['user_nama'];
      else $menu_nama = $cek_nama['user_username'];
      if(strlen($menu_nama) > 20) $menu_nama = substr($menu_nama, 0, 20).'...';
      $menu_horizontal['left'] = array(
         array('u/home/index', 'Halaman Siswa'),
         array('a/hasil/index', 'Hasil Seleksi'),
         array('a/siswa/add', 'Tambah Siswa')
      );
      $menu_horizontal['left_user'] = array(
         array('a/home/index', 'Halaman Admin',
            array('a/home/index', 'Beranda'),
            array('a/siswa/index', 'Semua Siswa'),
            array('a/hasil/index', 'Hasil Seleksi')
            ),
          array('a/hasil/index', 'Hasil Seleksi'),
          array('a/siswa/add', 'Tambah Siswa')
      );
      $menu_horizontal['right'] = array(
         array('a/users/profile', 'Halo, '.$cek_nama['user_username'],
            array('a/users/profile', '<font style="color: green">'.$menu_nama.'</font>'),
            array('a/users/profile', '<font style="color: green">'.'Profil Saya'.'</font>'),
            array('a/login/logout/'.encode(time()), '<font style="color: red">'.'Keluar'.'</font>')
         )
      );
      return hmenu($menu_horizontal[$position]);
   }
}
if (! function_exists('menu_vertical')){
    function menu_vertical($url = array('#', '#', '#')){
      $folder = strtolower($url[0]); //folder
      $con = strtolower($url[1]); //controler
      $func = strtolower($url[2]); //fungsi
      if(is_admin())
         $menu_vertical['a'] = array(
            array('a/home/index', 'Beranda'),
            array('a/siswa/index', 'Pendaftaran',
               array('a/gelombang/index', 'Gelombang'),
               array('a/kuota/index', 'Kuota Jurusan'),
               array('a/siswa/index', 'Semua Siswa'),
               array('a/hasil/index', 'Hasil Seleksi'),
               array('a/siswa/add', 'Tambah Siswa')
               ),
            array('a/pendidikan/index', 'Pengaturan',
               array('a/pendidikan/index', 'Pendidikan'),
               array('a/pekerjaan/index', 'Pekerjaan'),
               array('a/agama/index', 'Agama'),
               array('a/prov/index', 'Provinsi'),
               array('a/jurusan/index', 'Jurusan'),
               array('a/mapel/index', 'Mata Pelajaran'),
               array('a/test/index', 'Jenis Test')
               ),
            array('a/stat/index', 'Statistik',
               array('a/stat/index', 'Pendidikan'),
               array('a/stat/pekerjaan', 'Pekerjaan'),
               array('a/stat/agama', 'Agama'),
               array('a/stat/jenis_kelamin', 'Jenis Kelamin'),
               array('a/stat/hasil_tahun', 'Hasil Seleksi'),
               array('a/stat/total_tahun', 'Total Pendaftar')
               ),
            array('a/report/index', 'Laporan',
               array('a/report/index', 'Semua Siswa'),
               array('a/report/hasil', 'Hasil Seleksi')
               ),
            array('a/post/index', 'Pengumuman',
               array('a/post/index', 'Semua Tulisan'),
               array('a/post/add', 'Tambah Tulisan')
               ),
            array('a/users/index', 'Pengguna',
               array('a/users/index', 'Semua'),
               array('a/users/add', 'Tambah'),
               array('a/users/profile', 'Profil Saya'),
               array('a/login/logout/'.encode(time()), 'Keluar')
               ),
            array('a/setting/index', 'Informasi',
               array('a/setting/index', 'Sekolah'),
               array('a/link/index', 'Tautan Terkait'),
               array('a/about/index', 'Pengembang'),
               array('a/about/software', 'Sofware')
               )
         );
      else
         $menu_vertical['a'] = array(
            array('a/home/index', 'Beranda'),
            array('a/siswa/index', 'Pendaftaran',
               array('a/siswa/index', 'Semua Siswa'),
               array('a/hasil/index', 'Hasil Seleksi'),
               array('a/siswa/add', 'Tambah Siswa')
               ),
            array('a/report/index', 'Laporan',
               array('a/report/index', 'Semua Siswa'),
               array('a/report/hasil', 'Hasil Seleksi')
               ),
            array('a/stat/index', 'Statistik',
               array('a/stat/index', 'Pendidikan'),
               array('a/stat/pekerjaan', 'Pekerjaan'),
               array('a/stat/agama', 'Agama'),
               array('a/stat/jenis_kelamin', 'Jenis Kelamin'),
               array('a/stat/hasil_tahun', 'Hasil Seleksi'),
               array('a/stat/total_tahun', 'Total Pendaftar')
               ),
            array('a/users/profile', 'Pengguna',
               array('a/users/profile', 'Profil Saya'),
               array('a/login/logout/'.encode(time()), 'Keluar')
               ),
            array('a/setting/index', 'Informasi',
               array('a/about/index', 'Pengembang'),
               array('a/about/software', 'Sofware')
               )
         );
      $menu_vertical['u'] = array(
         array('u/home/index', 'Beranda'),
         array('u/hasil/index', 'Hasil Seleksi'),
         array('u/siswa/index', 'Pendaftaran')
      );
      return vmenu($menu_vertical[$folder], $folder, $con, $func);
    }
}
function hmenu($hmenu){
   $result = '';
   for($i = 0; $i < count($hmenu); $i++){
      if(count($hmenu[$i]) == 2){
         $result = $result.'<li>';
         $result = $result.anchor($hmenu[$i][0], $hmenu[$i][1]);
         $result = $result.'</li>';
      }
      else{
         $result = $result.'<li>';
         $result = $result.anchor($hmenu[$i][0], $hmenu[$i][1]);
         $result = $result.'<ul>';
         for($j = 2; $j < count($hmenu[$i]); $j++){
            $result = $result.'<li style="width: 210px">';
            $result = $result.anchor($hmenu[$i][$j][0], $hmenu[$i][$j][1]);
            $result = $result.'</li>';
         }
         $result = $result.'</ul>';
         $result = $result.'</li>';
      }
   }
   return $result;
}
function vmenu($vmenu, $folder, $con, $func){
   $url = $folder.'/'.$con.'/'.$func;
   $result = '';
   for($i = 0; $i < count($vmenu); $i++){
      $submenu = '';
      $count = 0;
      if(count($vmenu[$i]) == 2){
         if($url == $vmenu[$i][0]) {$class = 'active';}
         else {$class = '';}
         $result = $result.'<li>';
         $result = $result.anchor($vmenu[$i][0], $vmenu[$i][1], 'class="'.$class.'"');
         $result = $result.'</li>';
      }
      else{
         for($j = 2; $j < count($vmenu[$i]); $j++){
            if($url == $vmenu[$i][$j][0]) {$class = 'active'; $count =  1;}
            else {$class = '';}
            $submenu = $submenu.'<li>';
            $submenu = $submenu.anchor($vmenu[$i][$j][0], $vmenu[$i][$j][1], 'class="'.$class.'"');
            $submenu = $submenu.'</li>';
         }
         if($count > 0) {$class = 'active';}
         else {$class = '';}
         $result = $result.'<li>';
         $result = $result.anchor($vmenu[$i][0], $vmenu[$i][1], 'class="'.$class.'"');
         $result = $result.'<ul class="'.$class.'">';
         $result = $result.$submenu;
         $result = $result.'</ul>';
         $result = $result.'</li>';
      }
   }
   return $result;
}