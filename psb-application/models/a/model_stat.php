<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_stat extends CI_Model {
   function index($data) {
      $ta = 'CONCAT(';
      foreach ($data[0] as $key => $row) {
         if ($key == 0) $tanda = '';
         else $tanda = ', ",",';
         $ta = $ta . $tanda . 'SUM(IF(gel_ta = ' . $row['gel_ta'] . ', 1, 0))';
      }
      $ta = $ta . ') AS jumlah';
      $query = 'SELECT pend_nama AS nama, ' . $ta . ' FROM pendidikan
      LEFT JOIN (SELECT * FROM gelombang
         JOIN (SELECT * FROM (
               SELECT siswa.* FROM siswa JOIN pilihan ON pilihan_siswa = siswa_id WHERE pilihan_status IN ("diterima") ORDER BY siswa_id ASC, pilihan_ke DESC
             ) AS siswa GROUP BY siswa_id
         ) AS siswa ON siswa_gel = gel_id
         JOIN orang_tua ON ot_siswa = siswa_id
      ) AS hasil ON ' . $data[1] . ' = pend_id
      GROUP BY pend_nama ORDER BY pend_id';
      return $this->db->query($query);
   }
   function pek_ortu($data) {
      $ta = 'CONCAT(';
      foreach ($data[0] as $key => $row) {
         if ($key == 0) $tanda = '';
         else $tanda = ', ",",';
         $ta = $ta . $tanda . 'SUM(IF(gel_ta = ' . $row['gel_ta'] . ', 1, 0))';
      }
      $ta = $ta . ') AS jumlah';
      $query = 'SELECT pek_nama AS nama, ' . $ta . ' FROM pekerjaan
      LEFT JOIN (SELECT * FROM gelombang
         JOIN (SELECT * FROM (
               SELECT siswa.* FROM siswa JOIN pilihan ON pilihan_siswa = siswa_id WHERE pilihan_status IN ("diterima") ORDER BY siswa_id ASC, pilihan_ke DESC
             ) AS siswa GROUP BY siswa_id
         ) AS siswa ON siswa_gel = gel_id
         JOIN orang_tua ON ot_siswa = siswa_id
      ) AS hasil ON ' . $data[1] . ' = pek_id
      GROUP BY pek_nama ORDER BY pek_nama';
      return $this->db->query($query);
   }
   function agama($data) {
      $ta = 'CONCAT(';
      foreach ($data[0] as $key => $row) {
         if ($key == 0) $tanda = '';
         else $tanda = ', ",",';
         $ta = $ta . $tanda . 'SUM(IF(gel_ta = ' . $row['gel_ta'] . ', 1, 0))';
      }
      $ta = $ta . ') AS jumlah';
      $query = 'SELECT agama_nama AS nama, ' . $ta . ' FROM agama
      LEFT JOIN (SELECT * FROM gelombang
         JOIN (SELECT * FROM (
               SELECT siswa.* FROM siswa JOIN pilihan ON pilihan_siswa = siswa_id WHERE pilihan_status IN ("diterima") ORDER BY siswa_id ASC, pilihan_ke DESC
             ) AS siswa GROUP BY siswa_id
         ) AS siswa ON siswa_gel = gel_id
         JOIN orang_tua ON ot_siswa = siswa_id
      ) AS hasil ON siswa_agama = agama_id
      GROUP BY agama_nama ORDER BY agama_nama';
      return $this->db->query($query);
   }
   function jenis_kelamin($data) {
      $ta = 'CONCAT(';
      foreach ($data[0] as $key => $row) {
         if ($key == 0) $tanda = '';
         else $tanda = ', ",",';
         $ta = $ta . $tanda . 'SUM(IF(gel_ta = ' . $row['gel_ta'] . ', 1, 0))';
      }
      $ta = $ta . ') AS jumlah';
      $query = 'SELECT UCASE(siswa_jenis_kelamin) AS nama, ' . $ta . ' FROM gelombang
      JOIN (SELECT * FROM (
            SELECT siswa.* FROM siswa JOIN pilihan ON pilihan_siswa = siswa_id WHERE pilihan_status IN ("diterima") ORDER BY siswa_id ASC, pilihan_ke DESC
         ) AS siswa GROUP BY siswa_id
      ) AS siswa ON siswa_gel = gel_id
      GROUP BY siswa_jenis_kelamin';
      return $this->db->query($query);
   }
   function hasil_tahun($data) {
      $ta = 'CONCAT(';
      foreach ($data[0] as $key => $row) {
         if ($key == 0) $tanda = '';
         else $tanda = ', ",",';
         $ta = $ta . $tanda . 'SUM(IF(gel_ta = ' . $row['gel_ta'] . ', 1, 0))';
      }
      $ta = $ta . ') AS jumlah';
      $query = 'SELECT pilihan_status AS nama, ' . $ta . ' FROM (
         SELECT * FROM (
            SELECT * FROM gelombang
            JOIN (SELECT * FROM siswa WHERE siswa_status = "sdh_dicek") AS siswa ON siswa_gel = gel_id
            JOIN (SELECT * FROM pilihan WHERE pilihan_status IN ("diterima", "cadangan", "ditolak")) AS pilihan ON pilihan_siswa = siswa_id GROUP BY siswa_id ASC, pilihan_ke DESC
         ) AS hasil GROUP BY siswa_id
      ) AS hasil GROUP BY pilihan_status
      UNION ALL
      SELECT "Belum Dicek" AS nama, ' . $ta . ' FROM gelombang
      JOIN (SELECT * FROM siswa WHERE siswa_status = "blm_dicek") AS siswa ON siswa_gel = gel_id';
      return $this->db->query($query);
   }
   function hasil_gelombang($data) {
      $ta = 'CONCAT(';
      foreach ($data[0] as $key => $row) {
         if ($key == 0) $tanda = '';
         else $tanda = ', ",",';
         $ta = $ta . $tanda . 'SUM(IF(gel_ta = ' . $row['gel_ta'] . ' AND gel_id = ' . $row['gel_id'] . ', 1, 0))';
      }
      $ta = $ta . ') AS jumlah';
      $query = 'SELECT pilihan_status AS nama, ' . $ta . ' FROM (
         SELECT * FROM (
            SELECT * FROM gelombang
            JOIN (SELECT * FROM siswa WHERE siswa_status = "sdh_dicek") AS siswa ON siswa_gel = gel_id
            JOIN (SELECT * FROM pilihan WHERE pilihan_status IN ("diterima", "cadangan", "ditolak") ) AS pilihan ON pilihan_siswa = siswa_id GROUP BY siswa_id ASC, pilihan_ke DESC
         ) AS hasil GROUP BY siswa_id
      ) AS hasil GROUP BY pilihan_status
      UNION ALL
      SELECT "Belum Dicek" AS nama, ' . $ta . ' FROM gelombang
      JOIN (SELECT * FROM siswa WHERE siswa_status = "blm_dicek") AS siswa ON siswa_gel = gel_id';
      return $this->db->query($query);
   }
   function total_tahun($data) {
      $ta = 'CONCAT(';
      foreach ($data[0] as $key => $row) {
         if ($key == 0) $tanda = '';
         else $tanda = ', ",",';
         $ta = $ta . $tanda . 'SUM(IF(gel_ta = ' . $row['gel_ta'] . ', 1, 0))';
      }
      $ta = $ta . ') AS jumlah';
      $query = 'SELECT "Tahun Ajaran" AS nama, ' . $ta . ' FROM gelombang JOIN siswa ON siswa_gel = gel_id';
      return $this->db->query($query);
   }
   function cek_ta() {
      $query = 'SELECT * FROM (SELECT * FROM gelombang GROUP BY gel_ta ORDER BY gel_ta DESC LIMIT 10) AS hasil ORDER BY gel_ta ASC';
      return $this->db->query($query);
   }
   function cek_ta_max() {
      $query = 'SELECT * FROM gelombang WHERE gel_ta = (SELECT MAX(gel_ta) FROM gelombang)';
      return $this->db->query($query);
   }
   function cek_gel() {
      $query = 'SELECT * FROM gelombang';
      return $this->db->query($query);
   }
}
