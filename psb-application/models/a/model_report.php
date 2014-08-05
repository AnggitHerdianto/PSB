<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_report extends CI_Model {
   function index($data) {
      if (count($data[1]) == 0) {@$mapel_kolom = 'null AS mapel_kolom_kosong'; @$mapel_jml = '0'; @$mapel = 'null AS mapel_kosong';}
      else {foreach ($data[1] AS $key => $row) {
            if ($key == 0) {$tanda_koma = ''; $tanda_plus = '';}
            else {$tanda_koma = ', '; $tanda_plus = ' + ';}
            @$mapel_kolom = $mapel_kolom . $tanda_koma . 'mapel_' . $row['mapel_id'] . ' AS "' . $row['mapel_nama'] . '"';
            @$mapel_jml = $mapel_jml . $tanda_plus . 'mapel_' . $row['mapel_id'];
            @$mapel = $mapel . $tanda_koma . 'SUM(IF(mapel_id = ' . $row['mapel_id'] . ', IFNULL(nilai_uan, 0), 0)) AS mapel_' . $row['mapel_id'];
      }}
      if (count($data[2]) == 0) {@$test_kolom = 'null AS test_kolom_kosong'; @$test_jml = '0'; @$test = 'null AS test_kosong';}
      else {foreach ($data[2] AS $key => $row) {
            if ($key == 0) {$tanda_koma = ''; $tanda_plus = '';}
            else {$tanda_koma = ', '; $tanda_plus = ' + ';}
            @$test_kolom = $test_kolom . $tanda_koma . 'test_' . $row['jentest_id'] . ' AS "' . $row['jentest_nama'] . '"';
            @$test = $test . $tanda_koma . 'SUM(IF(jentest_id = ' . $row['jentest_id'] . ', IFNULL(test_nilai, 0), 0)) AS test_' . $row['jentest_id'];
      }}
      $query = 'SELECT
      gel_ta AS "Tahun Ajaran",
      gel_nama AS "Gelombang",
      siswa_no_pendaftaran AS "No Pendaftaran",
      siswa_nama AS "Nama Lengkap",
      siswa_nama_panggilan AS "Nama Panggilan",
      UCASE(siswa_jenis_kelamin) AS "Jenis Kelamin",
      CONCAT(siswa_tempat_lahir, ", ", DATE_FORMAT(siswa_tanggal_lahir, "%d-%m-%Y")) AS "Tempat / Tanggal Lahir",
      agama_nama AS "Agama",
      siswa_suku AS "Suku",
      siswa_sekolah_asal AS "Sekolah Asal",
      siswa_sekolah_alamat AS "Alamat Sekolah Asal",
      siswa_jumlah_saudara AS "Jumlah Saudara Kandung",
      siswa_alamat AS "Alamat Rumah",
      prov_nama AS "Provinsi",
      siswa_kabupaten AS "Kabupaten",
      siswa_kecamatan AS "Kecamatan",
      siswa_kode_pos AS "Kode Pos",
      siswa_alamat_pos AS "Alamat Pos",
      siswa_telepon AS "No Telp.",
      siswa_hp AS "No HP",
      siswa_email AS "Email",
      IF(siswa_gol_darah = "none", "Tidak Diketahui", siswa_gol_darah) AS "Golongan Darah",
      siswa_anak_ke AS "Anak Ke",
      siswa_tinggi_badan AS "Tinggi Badan",
      siswa_berat_badan AS "Berat Badan",
      siswa_penyakit AS "Riwayat Penyakit",
      siswa_tanggal_daftar AS "Tanggal Daftar",
      siswa_keterangan AS "Keterangan Tambahan",
      ot_nama_ayah AS "Nama Ayah",
      pend_ayah.pend_nama AS "Pendidikan Ayah",
      pek_ayah.pek_nama AS "Pekerjaan Ayah",
      ot_gaji_ayah AS "Penghasilan Ayah",
      ot_hp_ayah AS "HP Ayah",
      ot_nama_ibu AS "Nama Ibu",
      pend_ibu.pend_nama AS "Pendidikan Ibu",
      pek_ibu.pek_nama AS "Pekerjaan Ibu",
      ot_gaji_ibu AS "Penghasilan Ibu",
      ot_hp_ibu AS "HP Ibu",
      ot_alamat_ortu AS "Alamat Orang Tua",
      ot_nama_wali AS "Nama Wali",
      pend_wali.pend_nama AS "Pendidikan Wali",
      pek_wali.pek_nama AS "Pekerjaan Wali",
      ot_gaji_wali AS "Penghasilan Wali",
      ot_hp_wali AS "HP Wali",
      ot_alamat_ortu AS "Alamat Wali",
      ' . $mapel_kolom . ',
      (' . $mapel_jml . ') AS "Total Nilai UAN",
      IFNULL(prestasi_nilai, 0) AS "Prestasi",
      ' . $test_kolom . ',
      pilihan.jur_singkatan AS "Pilihan",
      IF(siswa_status = "sdh_dicek", "SDH DICEK", "BLM DICEK") AS "Siswa Status"
      FROM (SELECT * FROM gelombang WHERE gel_id = ' . $data[0] . ') AS gelombang
      JOIN siswa ON siswa_gel = gel_id
      JOIN orang_tua ON ot_siswa = siswa_id
      LEFT JOIN pendidikan AS pend_ayah ON pend_ayah.pend_id = ot_pend_ayah
      LEFT JOIN pekerjaan AS pek_ayah ON pek_ayah.pek_id = ot_pek_ayah
      LEFT JOIN pendidikan AS pend_ibu ON pend_ibu.pend_id = ot_pend_ibu
      LEFT JOIN pekerjaan AS pek_ibu ON pek_ibu.pek_id = ot_pek_ibu
      LEFT JOIN pendidikan AS pend_wali ON pend_wali.pend_id = ot_pend_wali
      LEFT JOIN pekerjaan AS pek_wali ON pek_wali.pek_id = ot_pek_wali
      LEFT JOIN agama ON agama_id = siswa_agama
      LEFT JOIN provinsi ON prov_id = siswa_prov
      JOIN (SELECT pilihan_siswa, GROUP_CONCAT(jur_singkatan ORDER BY pilihan_ke ASC SEPARATOR " - ") AS jur_singkatan FROM jurusan
         JOIN pilihan ON pilihan_jur = jur_id
         JOIN siswa ON siswa_id = pilihan_siswa
         JOIN gelombang ON gel_id = siswa_gel
         WHERE pilihan_ke <= gel_jumlah_pilihan GROUP BY pilihan_siswa
      ) AS pilihan ON pilihan_siswa = siswa_id
      JOIN (SELECT nilai_siswa, ' . $mapel . ' FROM nilai
         JOIN mata_pelajaran ON mapel_id = nilai_mapel GROUP BY nilai_siswa
      ) AS nilai ON nilai_siswa = siswa_id
      JOIN prestasi ON prestasi_siswa = siswa_id
      JOIN (SELECT test_siswa, COUNT(test_siswa) AS jentest_bagi, ' . $test . ' FROM test
         JOIN jenis_test ON jentest_id = test_jentest GROUP BY test_siswa
      ) AS test ON test_siswa = siswa_id
      ORDER BY siswa_id ASC';
      return $this->db->query($query, $data);
   }
   function hasil($data) {
      if (count($data[2]) == 0) {@$mapel_kolom = 'null AS mapel_kolom_kosong'; @$mapel_jml = '0'; @$mapel = 'null AS mapel_kosong';}
      else {foreach ($data[2] AS $key => $row) {
            if ($key == 0) {$tanda_koma = ''; $tanda_plus = '';}
            else {$tanda_koma = ', '; $tanda_plus = ' + ';}
            @$mapel_kolom = $mapel_kolom . $tanda_koma . 'mapel_' . $row['mapel_id'] . ' AS "' . $row['mapel_nama'] . '"';
            @$mapel_jml = $mapel_jml . $tanda_plus . 'mapel_' . $row['mapel_id'];
            @$mapel = $mapel . $tanda_koma . 'SUM(IF(mapel_id = ' . $row['mapel_id'] . ', IFNULL(nilai_uan, 0), 0)) AS mapel_' . $row['mapel_id'];
      }}
      if (count($data[3]) == 0) {@$test_kolom = 'null AS test_kolom_kosong'; @$test_jml = '0'; @$test = 'null AS test_kosong';}
      else {foreach ($data[3] AS $key => $row) {
            if ($key == 0) {$tanda_koma = ''; $tanda_plus = '';}
            else {$tanda_koma = ', '; $tanda_plus = ' + ';}
            @$test_kolom = $test_kolom . $tanda_koma . 'test_' . $row['jentest_id'] . ' AS "' . $row['jentest_nama'] . '"';
            @$test_persen = $test_persen . $tanda_plus . 'persen_' . $row['jentest_id'];
            @$test_persen_jml = $test_persen_jml . $tanda_plus . '(test_' . $row['jentest_id'] . ' * persen_' . $row['jentest_id'] . ' / 100)';
            @$test = $test . $tanda_koma . 'SUM(IF(jentest_id = ' . $row['jentest_id'] . ', IFNULL(test_nilai, 0), 0)) AS test_' . $row['jentest_id'] . ', ' . 'SUM(IF(jentest_id = ' . $row['jentest_id'] . ', IFNULL(jentest_persen, 0), 0)) AS persen_' . $row['jentest_id'] . ' ';
      }}
      $query = 'SELECT
      gel_ta AS "Tahun Ajaran",
      gel_nama AS "Gelombang",
      siswa_no_pendaftaran AS "No Pendaftaran",
      siswa_nama AS "Nama Lengkap",
      siswa_nama_panggilan AS "Nama Panggilan",
      UCASE(siswa_jenis_kelamin) AS "Jenis Kelamin",
      CONCAT(siswa_tempat_lahir, ", ", DATE_FORMAT(siswa_tanggal_lahir, "%d-%m-%Y")) AS "Tempat / Tanggal Lahir",
      agama_nama AS "Agama",
      siswa_suku AS "Suku",
      siswa_sekolah_asal AS "Sekolah Asal",
      siswa_sekolah_alamat AS "Alamat Sekolah Asal",
      siswa_jumlah_saudara AS "Jumlah Saudara Kandung",
      siswa_alamat AS "Alamat Rumah",
      prov_nama AS "Provinsi",
      siswa_kabupaten AS "Kabupaten",
      siswa_kecamatan AS "Kecamatan",
      siswa_kode_pos AS "Kode Pos",
      siswa_alamat_pos AS "Alamat Pos",
      siswa_telepon AS "No Telp.",
      siswa_hp AS "No HP",
      siswa_email AS "Email",
      IF(siswa_gol_darah = "none", "Tidak Diketahui", siswa_gol_darah) AS "Golongan Darah",
      siswa_anak_ke AS "Anak Ke",
      siswa_tinggi_badan AS "Tinggi Badan",
      siswa_berat_badan AS "Berat Badan",
      siswa_penyakit AS "Riwayat Penyakit",
      siswa_tanggal_daftar AS "Tanggal Daftar",
      siswa_keterangan AS "Keterangan Tambahan",
      ot_nama_ayah AS "Nama Ayah",
      pend_ayah.pend_nama AS "Pendidikan Ayah",
      pek_ayah.pek_nama AS "Pekerjaan Ayah",
      ot_gaji_ayah AS "Penghasilan Ayah",
      ot_hp_ayah AS "HP Ayah",
      ot_nama_ibu AS "Nama Ibu",
      pend_ibu.pend_nama AS "Pendidikan Ibu",
      pek_ibu.pek_nama AS "Pekerjaan Ibu",
      ot_gaji_ibu AS "Penghasilan Ibu",
      ot_hp_ibu AS "HP Ibu",
      ot_alamat_ortu AS "Alamat Orang Tua",
      ot_nama_wali AS "Nama Wali",
      pend_wali.pend_nama AS "Pendidikan Wali",
      pek_wali.pek_nama AS "Pekerjaan Wali",
      ot_gaji_wali AS "Penghasilan Wali",
      ot_hp_wali AS "HP Wali",
      ot_alamat_ortu AS "Alamat Wali",
      ' . $mapel_kolom . ',
      (' . $mapel_jml . ') AS "Total Nilai UAN",
      IFNULL(prestasi_nilai, 0) AS "Prestasi",
      ' . $test_kolom . ',
      ROUND(((((' . $mapel_jml . ') + IFNULL(prestasi_nilai, 0)) / (SELECT COUNT(*) FROM mata_pelajaran WHERE mapel_gel = '.$data[0].') * (100 - (' . $test_persen . ')) / 100) + ' . $test_persen_jml . ') / (0.1), 2) AS "Total",
      UCASE(pilihan_status) AS "Hasil"
      FROM (SELECT * FROM gelombang WHERE gel_id = ' . $data[0] . ') AS gelombang
      JOIN (SELECT * FROM siswa WHERE siswa_status = "sdh_dicek") AS siswa ON siswa_gel = gel_id
      JOIN orang_tua ON ot_siswa = siswa_id
      JOIN (SELECT * FROM (
            SELECT * FROM pilihan WHERE pilihan_status IN ("diterima", "cadangan", "ditolak") AND pilihan_jur = ' . $data[1] . ' ORDER BY pilihan_siswa ASC, pilihan_ke DESC
         ) AS hasil GROUP BY pilihan_siswa
      ) AS pilihan ON pilihan_siswa = siswa_id
      LEFT JOIN pendidikan AS pend_ayah ON pend_ayah.pend_id = ot_pend_ayah
      LEFT JOIN pekerjaan AS pek_ayah ON pek_ayah.pek_id = ot_pek_ayah
      LEFT JOIN pendidikan AS pend_ibu ON pend_ibu.pend_id = ot_pend_ibu
      LEFT JOIN pekerjaan AS pek_ibu ON pek_ibu.pek_id = ot_pek_ibu
      LEFT JOIN pendidikan AS pend_wali ON pend_wali.pend_id = ot_pend_wali
      LEFT JOIN pekerjaan AS pek_wali ON pek_wali.pek_id = ot_pek_wali
      LEFT JOIN agama ON agama_id = siswa_agama
      LEFT JOIN provinsi ON prov_id = siswa_prov
      JOIN (SELECT nilai_siswa, ' . $mapel . ' FROM nilai
         JOIN mata_pelajaran ON mapel_id = nilai_mapel GROUP BY nilai_siswa
      ) AS nilai ON nilai_siswa = siswa_id
      JOIN prestasi ON prestasi_siswa = siswa_id
      JOIN (SELECT test_siswa, COUNT(test_siswa) AS jentest_bagi, ' . $test . ' FROM test
         JOIN jenis_test ON jentest_id = test_jentest GROUP BY test_siswa
      ) AS test ON test_siswa = siswa_id
      ORDER BY total DESC, siswa_tanggal_lahir ASC, siswa_id ASC';
      return $this->db->query($query, $data);
   }
   function cek_ta_jml() {
      $query = 'SELECT gelombang.* FROM gelombang
      JOIN (SELECT * FROM siswa GROUP BY siswa_gel) AS siswa ON siswa_gel = gel_id
      JOIN (SELECT * FROM kuota GROUP BY kuota_gel) AS kuota ON kuota_gel = gel_id
      GROUP BY gel_ta ORDER BY gel_ta DESC';
      return $this->db->query($query);
   }
   function cek_gel_ta($data) {
      $query = 'SELECT * FROM (SELECT * FROM gelombang WHERE gel_ta = ?) AS gelombang
      JOIN (SELECT * FROM siswa GROUP BY siswa_gel) AS siswa ON siswa_gel = gel_id
      JOIN (SELECT * FROM kuota GROUP BY kuota_gel) AS kuota ON kuota_gel = gel_id
      GROUP BY gel_id ORDER BY gel_id ASC';
      return $this->db->query($query, to_null($data));
   }
   function cek_jur_gel($data) {
      $query = 'SELECT jurusan.* FROM jurusan
      JOIN (SELECT * FROM kuota WHERE kuota_jumlah > 0) AS kuota ON kuota_jur = jur_id
      JOIN (SELECT * FROM gelombang WHERE gel_id = ?) AS gelombang ON gel_id = kuota_gel';
      return $this->db->query($query, to_null($data));
   }
   function cek_gel_id($data) {
      $query = 'SELECT * FROM gelombang WHERE gel_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_jur_id($data) {
      $query = 'SELECT * FROM jurusan WHERE jur_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_mapel($data) {
      $query = 'SELECT * FROM mata_pelajaran WHERE mapel_gel = ? ORDER BY mapel_id ASC';
      return $this->db->query($query, to_null($data));
   }
   function cek_jentest($data) {
      $query = 'SELECT * FROM jenis_test WHERE jentest_gel = ? ORDER BY jentest_id ASC';
      return $this->db->query($query, to_null($data));
   }
}