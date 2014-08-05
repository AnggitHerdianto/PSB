<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_home extends CI_Model{
   function pie_pilihan_status($data){
      $query = '
         SELECT "Belum Dicek" AS pilihan_status, COUNT(siswa_id) AS jumlah
         FROM ( SELECT * FROM siswa WHERE siswa_gel = '.$data[0].') AS siswa
         WHERE siswa_status = "blm_dicek"
            UNION ALL
         SELECT pilihan_status AS pilihan_status, COUNT(siswa_id) AS jumlah
         FROM ( SELECT * FROM (
               SELECT siswa_id, pilihan_status FROM (SELECT * FROM siswa WHERE siswa_gel = '.$data[0].') AS siswa
               JOIN ( SELECT * FROM pilihan WHERE pilihan_status IN ("diterima", "cadangan", "ditolak")) AS pilihan ON pilihan_siswa = siswa_id
               GROUP BY siswa_id, pilihan_ke ORDER BY siswa_id ASC, pilihan_ke DESC
            ) AS hasil GROUP BY siswa_id
         ) AS hasil GROUP BY pilihan_status';
      return $this->db->query($query);
   }
   function cek_gel_max(){
      $query = 'SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1';
      return $this->db->query($query);
   }
   function cek_gel_ta_max(){
      $query = 'SELECT * FROM gelombang WHERE gel_ta = (SELECT MAX(gel_ta) FROM gelombang LIMIT 1)';
      return $this->db->query($query);
   }
   function cek_kuota_gel_max(){
      $query = 'SELECT * FROM kuota
         JOIN (SELECT * FROM gelombang WHERE gel_id = (SELECT MAX(gel_id) FROM gelombang LIMIT 1))AS gelombang ON gel_id = kuota_gel
         JOIN jurusan ON jur_id = kuota_jur';
      return $this->db->query($query);
   }
   function cek_jur(){
      $query = 'SELECT * FROM jurusan ORDER BY jur_id ASC';
      return $this->db->query($query);
   }
   function cek_mapel(){
      $query = 'SELECT * FROM mata_pelajaran WHERE mapel_gel = (SELECT MAX(gel_id) FROM gelombang) ORDER BY mapel_id ASC';
      return $this->db->query($query);
   }
   function cek_users(){
      $query = 'SELECT * FROM users ORDER BY user_id ASC';
      return $this->db->query($query);
   }
}