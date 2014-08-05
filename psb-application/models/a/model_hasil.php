<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_hasil extends CI_Model{
   function index($data){
      if(count($data[3]) == 0){
         @$test_kolom = 'null AS test_kolom_kosong';
         @$test_persen = '0';
         @$test_persen_jml = '0';
         @$test = 'null AS test_kosong';
      }
      else{
         foreach ($data[3] AS $key=>$row){
           if($key == 0){
              $tanda_koma = '';
              $tanda_plus = '';
           }
           else{
              $tanda_koma = ', ';
              $tanda_plus = ' + ';
           }
           @$test_kolom = $test_kolom.$tanda_koma.'test_'.$row['jentest_id'];
           @$test_persen = $test_persen.$tanda_plus.'persen_'.$row['jentest_id'];
           @$test_persen_jml = $test_persen_jml.$tanda_plus.'(test_'.$row['jentest_id'].' * persen_'.$row['jentest_id'].' / 100)';
           @$test = $test.$tanda_koma.
               'SUM(IF(jentest_id = '.$row['jentest_id'].', IFNULL(test_nilai, 0), 0)) AS test_'.$row['jentest_id'].', '.
               'SUM(IF(jentest_id = '.$row['jentest_id'].', IFNULL(jentest_persen, 0), 0)) AS persen_'.$row['jentest_id'].' ';
         };
      }
      $query = 'SELECT siswa.*, pilihan.*, nilai_uan, prestasi.*, '.$test_kolom.',
               ROUND(((nilai_uan_prestasi / (SELECT COUNT(*) FROM mata_pelajaran WHERE mapel_gel = '.$data[0].') * (100 - ('.$test_persen.')) / 100) + '.$test_persen_jml.') / (0.1), 2) AS nilai_akhir
         FROM (SELECT * FROM gelombang WHERE gel_id = '.$data[0].') AS gelombang
         JOIN (SELECT * FROM siswa WHERE siswa_status = "sdh_dicek") AS siswa ON siswa_gel = gel_id
         JOIN (SELECT * FROM (SELECT * FROM pilihan WHERE pilihan_status IN ("diterima", "cadangan", "ditolak") AND pilihan_jur = '.$data[1].' ORDER BY pilihan_siswa ASC, pilihan_ke DESC) AS hasil GROUP BY pilihan_siswa) AS pilihan ON pilihan_siswa = siswa_id
         ### nilai sudah ditambahnkan prestasi ###
         JOIN (SELECT nilai_siswa, nilai_uan, nilai_uan + IFNULL(prestasi_nilai, 0) AS nilai_uan_prestasi
               FROM (SELECT nilai_siswa, SUM(IFNULL(nilai_uan, 0)) AS nilai_uan FROM nilai GROUP BY nilai_siswa) AS nilai
               JOIN prestasi ON prestasi_siswa = nilai_siswa
         ) AS nilai ON nilai_siswa = siswa_id
         JOIN prestasi ON prestasi_siswa = siswa_id
         JOIN (SELECT test_siswa, COUNT(test_siswa) AS jentest_bagi, '.$test.' FROM test JOIN jenis_test ON jentest_id = test_jentest GROUP BY test_siswa) AS test ON test_siswa = siswa_id
         WHERE 1 > 0';
      if(strlen($data[2]) > 0) $query = $query.' '.' AND (siswa_no_pendaftaran LIKE "%'.$data[2].'%" OR siswa_nama LIKE "%'.$data[2].'%" OR siswa_nama_panggilan LIKE "%'.$data[2].'%")';
      $query = $query.' '.'ORDER BY nilai_akhir DESC, siswa_tanggal_lahir ASC, siswa_id ASC';
      return $this->db->query($query, $data);
   }
   function pilihan_status($data){ // untuk membuat status menjadi "kosong"
      $query = 'UPDATE siswa JOIN pilihan ON pilihan_siswa = siswa_id SET pilihan_status = "kosong" WHERE siswa_gel = ?';
      $query = $this->db->query($query, to_null($data));
   }
   function generate_cek($data){ // untuk mengecek apakah masih ada data yang belum dicek
      $query = 'SELECT "hasil" AS hasil
         FROM (SELECT * FROM gelombang WHERE gel_id = '.$data[0].') AS gelombang
         JOIN siswa ON siswa_gel = gel_id
         JOIN (SELECT * FROM pilihan WHERE pilihan_ke = '.($data[1]-1).' AND pilihan_status IN ("ditolak")) AS pilihan_satu ON pilihan_satu.pilihan_siswa = siswa_id
         JOIN (SELECT * FROM pilihan WHERE pilihan_ke = '.$data[1].' AND pilihan_status = "kosong") AS pilihan_dua ON pilihan_dua.pilihan_siswa = pilihan_satu.pilihan_siswa
         JOIN kuota ON kuota_gel = gel_id
         JOIN jurusan ON jur_id = kuota_jur AND jur_id = pilihan_dua.pilihan_jur';
      return $this->db->query($query, to_null($data));
   }
   function generate_satu($data){
      $query = 'SET @no = 0';
      $query = $this->db->query($query);
      $query = 'UPDATE pilihan
         JOIN ( SELECT @no := @no+1 AS nomer, hasil.*
            FROM (
               SELECT siswa.*, pilihan.*, kuota.*,
               ROUND(((nilai_uan / (SELECT COUNT(*) FROM mata_pelajaran WHERE mapel_gel = '.$data[0].') * (100 - jentest_persen) / 100) + test_nilai) / (0.1), 2) AS nilai_akhir
               FROM (SELECT * FROM gelombang WHERE gel_id = '.$data[0].' #gel_id ###################################
                  ) AS gelombang
               JOIN (SELECT * FROM siswa WHERE siswa_status = "sdh_dicek") AS siswa ON siswa_gel = gel_id
               JOIN (SELECT * FROM pilihan WHERE pilihan_ke = '.$data[1].' #pilihan_ke ###################################
                  ) AS pilihan ON pilihan_siswa = siswa_id
               JOIN (SELECT * FROM jurusan WHERE jur_id = '.$data[2].' #jur_id ###################################
                  ) AS jurusan ON jur_id = pilihan_jur
               JOIN kuota ON kuota_jur = jur_id AND kuota_gel = gel_id
               ### nilai uan sudah ditambahnkan prestasi ###
               JOIN (
                  SELECT nilai_siswa, nilai_uan + IFNULL(prestasi_nilai, 0) AS nilai_uan
                  FROM (SELECT nilai_siswa, SUM(IFNULL(nilai_uan, 0)) AS nilai_uan FROM nilai GROUP BY nilai_siswa) AS nilai
                  JOIN prestasi ON prestasi_siswa = nilai_siswa
               ) AS nilai ON nilai_siswa = siswa_id
               JOIN (SELECT test_siswa, COUNT(test_siswa) AS jentest_bagi,
                  SUM(IFNULL(jentest_persen, 0)) AS jentest_persen,
                  SUM(IFNULL(test_nilai, 0) * jentest_persen / 100) AS test_nilai
                  FROM test JOIN jenis_test ON jentest_id = test_jentest GROUP BY test_siswa
               ) AS test ON test_siswa = siswa_id ORDER BY nilai_akhir DESC, siswa_tanggal_lahir ASC, siswa_id ASC
            ) AS hasil
         ) AS hasil USING(pilihan_id)
         SET pilihan.pilihan_status = 
            CASE
               WHEN hasil.nomer <= hasil.kuota_jumlah THEN "diterima"
               WHEN hasil.nomer > hasil.kuota_jumlah AND hasil.nomer <= (hasil.kuota_jumlah + hasil.kuota_cadangan) THEN "cadangan"
               ELSE "ditolak" 
            END';
      $query = $this->db->query($query, to_null($data));
   }
   function generate_dua($data){
      $query = 'SET @no = 0';
      $query = $this->db->query($query);
      $query = 'UPDATE pilihan
         JOIN (
            SELECT @no := @no + 1 AS nomer, hasil.*
            FROM (
               SELECT siswa.*, pilihan.*, kuota.*,
               ROUND(((nilai_uan / (SELECT COUNT(*) FROM mata_pelajaran WHERE mapel_gel = '.$data[0].') * (100 - jentest_persen) / 100) + test_nilai) / (0.1), 2) AS nilai_akhir
               FROM (SELECT * FROM gelombang WHERE gel_id = '.$data[0].' #gel_id ###################################
                  ) AS gelombang
               JOIN (SELECT * FROM siswa WHERE siswa_status = "sdh_dicek") AS siswa ON siswa_gel = gel_id
               JOIN (SELECT * FROM pilihan WHERE pilihan_status IN ("diterima", "cadangan")) AS pilihan ON pilihan_siswa = siswa_id
               JOIN (SELECT * FROM jurusan WHERE jur_id = '.$data[2].' #looping two #jur_id ###################################
                  ) AS jurusan ON jur_id = pilihan_jur
               JOIN kuota ON kuota_jur = jur_id AND kuota_gel = gel_id
               ### nilai sudah ditambahkan prestasi ###
               JOIN (
                  SELECT nilai_siswa, nilai_uan + IFNULL(prestasi_nilai, 0) AS nilai_uan
                  FROM (SELECT nilai_siswa, SUM(IFNULL(nilai_uan, 0)) AS nilai_uan FROM nilai GROUP BY nilai_siswa) AS nilai
                  JOIN prestasi ON prestasi_siswa = nilai_siswa
               ) AS nilai ON nilai_siswa = pilihan_siswa
               JOIN (SELECT test_siswa, COUNT(test_siswa) AS jentest_bagi,
                  SUM(IFNULL(jentest_persen, 0)) AS jentest_persen,
                  SUM(IFNULL(test_nilai, 0) * jentest_persen / 100) AS test_nilai
                  FROM test JOIN jenis_test ON jentest_id = test_jentest GROUP BY test_siswa
               ) AS test ON test_siswa = siswa_id
                  UNION ############################################################################################
               SELECT siswa.*, pilihan_dua.*, kuota.*,
               ROUND(((nilai_uan  / (SELECT COUNT(*) FROM mata_pelajaran WHERE mapel_gel = '.$data[0].') * (100 - jentest_persen) / 100) + test_nilai) / (0.1), 2) AS nilai_akhir
               FROM (SELECT * FROM gelombang WHERE gel_id = '.$data[0].' #gel_id ###################################
               ) AS gelombang
               JOIN (SELECT * FROM siswa WHERE siswa_status = "sdh_dicek") AS siswa ON siswa_gel = gel_id
               JOIN (SELECT * FROM pilihan WHERE pilihan_ke = '.($data[1]-1).' #pilihan_ke ###################################
                  AND pilihan_status IN ("ditolak")
               ) AS pilihan_satu ON pilihan_satu.pilihan_siswa = siswa_id
               JOIN (SELECT * FROM pilihan WHERE pilihan_jur = '.$data[2].' #looping two ###################################
                  AND pilihan_ke = '.$data[1].' #pilihan_ke ###################################
                  AND pilihan_status IN ("kosong")
               ) AS pilihan_dua ON pilihan_dua.pilihan_siswa = pilihan_satu.pilihan_siswa
               JOIN jurusan ON jur_id = pilihan_dua.pilihan_jur
               JOIN kuota ON kuota_jur = jur_id AND kuota_gel = gel_id
               ### nilai sudah ditambahkan prestasi ###
               JOIN (
                  SELECT nilai_siswa, nilai_uan + IFNULL(prestasi_nilai, 0) AS nilai_uan
                  FROM (SELECT nilai_siswa, SUM(IFNULL(nilai_uan, 0)) AS nilai_uan FROM nilai GROUP BY nilai_siswa) AS nilai
                  JOIN prestasi ON prestasi_siswa = nilai_siswa
               ) AS nilai ON nilai_siswa = pilihan_dua.pilihan_siswa
               JOIN (
                  SELECT test_siswa, COUNT(test_siswa) AS jentest_bagi,
                  SUM(IFNULL(jentest_persen, 0)) AS jentest_persen,
                  SUM(IFNULL(test_nilai, 0) * jentest_persen / 100) AS test_nilai
                  FROM test JOIN jenis_test ON jentest_id = test_jentest GROUP BY test_siswa
               ) AS test ON test_siswa = siswa_id
               ORDER BY nilai_akhir DESC, siswa_tanggal_lahir ASC, siswa_id ASC #penting untuk order ###################################
            ) AS hasil
         ) AS hasil USING(pilihan_id)
         SET pilihan.pilihan_status =
            CASE
               WHEN hasil.nomer <= hasil.kuota_jumlah THEN "diterima"
               WHEN hasil.nomer > hasil.kuota_jumlah AND hasil.nomer <= (hasil.kuota_jumlah + hasil.kuota_cadangan) THEN "cadangan"
               ELSE "ditolak"
            END';
      $query = $this->db->query($query, to_null($data));
   }
   function cek_gel_id($data){
      $query = 'SELECT * FROM gelombang WHERE gel_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_gel_max(){
      $query = 'SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1';
      return $this->db->query($query);
   }
   function cek_gel_ta(){
      $query = 'SELECT * FROM gelombang WHERE gel_ta = (SELECT MAX(gel_ta) FROM gelombang)';
      return $this->db->query($query);
   }
   function cek_jur($data){
      $query = 'SELECT jurusan.* FROM jurusan JOIN kuota ON kuota_jur = jur_id JOIN gelombang ON gel_id = kuota_gel WHERE kuota_jumlah > 0 AND gel_id = ? ORDER BY jur_id ASC';
      return $this->db->query($query, to_null($data));
   }
   function cek_jentest($data){
      $query = 'SELECT * FROM jenis_test WHERE jentest_gel = ? ORDER BY jentest_id ASC';
      return $this->db->query($query, to_null($data));
   }
}