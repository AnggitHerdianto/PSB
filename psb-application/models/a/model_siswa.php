<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Model_siswa extends CI_Model{
   function index($data){
      $query = 'SELECT siswa.*, nilai.*, prestasi.*, test.*, pilihan.* FROM siswa
         JOIN (SELECT * FROM gelombang WHERE gel_ta = (SELECT MAX(gel_ta) FROM gelombang)) AS gelombang ON gel_id = siswa_gel
         JOIN prestasi ON prestasi_siswa = siswa_id
         JOIN (SELECT nilai_siswa, SUM(nilai_uan) AS nilai_uan FROM nilai GROUP BY nilai_siswa ) AS nilai ON nilai_siswa = siswa_id
         JOIN (SELECT test_siswa, IFNULL(SUM(test_nilai), 0) AS test_nilai FROM test GROUP BY test_siswa ) AS test ON test_siswa = siswa_id
         JOIN (SELECT pilihan_siswa, pilihan_ke, GROUP_CONCAT(jur_singkatan ORDER BY pilihan_ke ASC SEPARATOR " - ") AS jur_singkatan FROM jurusan
            JOIN pilihan ON pilihan_jur = jur_id
            JOIN siswa ON siswa_id = pilihan_siswa
            JOIN gelombang ON gel_id = siswa_gel
            WHERE pilihan_ke <= gel_jumlah_pilihan GROUP BY pilihan_siswa
         ) AS pilihan ON pilihan_siswa = siswa_id WHERE 1 > 0';
      if($data[0] > 0) $query = $query.' '.' AND gel_id = '.$data[0].' ';
      if($data[1] != 'semua') $query = $query.' '.' AND siswa_status = "'.$data[1].'" ';
      if(strlen($data[2]) > 0) $query = $query.' '.' AND (siswa_no_pendaftaran LIKE "%'.$data[2].'%" OR siswa_nama LIKE "%'.$data[2].'%" OR siswa_nama_panggilan LIKE "%'.$data[2].'%")';
      $query = $query.' '.' ORDER BY siswa_no_pendaftaran ASC';
      return $this->db->query($query, to_null($data));
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
   function delete($data){
      $query = 'DELETE FROM siswa WHERE siswa_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add_siswa($data){
      $query = 'INSERT INTO siswa SET siswa_gel = ?, siswa_no_pendaftaran = ?, siswa_nama = ?, siswa_nama_panggilan = ?, siswa_jenis_kelamin = ?, siswa_tempat_lahir = ?, siswa_tanggal_lahir = ?, siswa_sekolah_asal = ?, siswa_sekolah_alamat = ?, siswa_agama = ?, siswa_suku = ?, siswa_anak_ke = ?, siswa_jumlah_saudara = ?, siswa_alamat = ?, siswa_prov = ?, siswa_kabupaten = ?, siswa_kecamatan = ?, siswa_kode_pos = ?, siswa_alamat_pos = ?, siswa_telepon = ?, siswa_hp = ?, siswa_email = ?, siswa_gol_darah = ?, siswa_tinggi_badan = ?, siswa_berat_badan = ?, siswa_penyakit = ?, siswa_tanggal_daftar = ?, siswa_tanggal_ulang = ?, siswa_status = ?, siswa_keterangan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add_orang_tua($data){
      $query = 'INSERT INTO orang_tua SET ot_siswa = ?, ot_nama_ayah = ?, ot_pend_ayah = ?, ot_pek_ayah = ?, ot_gaji_ayah = ?, ot_hp_ayah = ?, ot_nama_ibu = ?, ot_pend_ibu = ?, ot_pek_ibu = ?, ot_gaji_ibu = ?, ot_hp_ibu = ?, ot_alamat_ortu = ?, ot_nama_wali = ?, ot_pend_wali = ?, ot_pek_wali = ?, ot_gaji_wali = ?, ot_hp_wali = ?, ot_alamat_wali = ?, ot_status_asuh = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add_nilai($data){
      $query = 'INSERT INTO nilai SET nilai_siswa = ?, nilai_mapel = ?, nilai_uan = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add_prestasi($data){
      $query = 'INSERT INTO prestasi SET prestasi_siswa = ?, prestasi_nilai = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add_test($data){
      $query = 'INSERT INTO test SET test_siswa = ?, test_jentest = ?, test_nilai = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function add_pilihan($data){
      $query = 'INSERT INTO pilihan SET pilihan_siswa = ?, pilihan_jur = ?, pilihan_ke = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit_siswa($data){
      $query = 'UPDATE siswa SET siswa_nama = ?, siswa_nama_panggilan = ?, siswa_jenis_kelamin = ?, siswa_tempat_lahir = ?, siswa_tanggal_lahir = ?, siswa_sekolah_asal = ?, siswa_sekolah_alamat = ?, siswa_agama = ?, siswa_suku = ?, siswa_anak_ke = ?, siswa_jumlah_saudara = ?, siswa_alamat = ?, siswa_prov = ?, siswa_kabupaten = ?, siswa_kecamatan = ?, siswa_kode_pos = ?, siswa_alamat_pos = ?, siswa_telepon = ?, siswa_hp = ?, siswa_email = ?, siswa_gol_darah = ?, siswa_tinggi_badan = ?, siswa_berat_badan = ?, siswa_penyakit = ?, siswa_tanggal_ulang = ?, siswa_status = ?, siswa_keterangan = ? WHERE siswa_id = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit_orang_tua($data){
      $query = 'UPDATE orang_tua SET ot_nama_ayah = ?, ot_pend_ayah = ?, ot_pek_ayah = ?, ot_gaji_ayah = ?, ot_hp_ayah = ?, ot_nama_ibu = ?, ot_pend_ibu = ?, ot_pek_ibu = ?, ot_gaji_ibu = ?, ot_hp_ibu = ?, ot_alamat_ortu = ?, ot_nama_wali = ?, ot_pend_wali = ?, ot_pek_wali = ?, ot_gaji_wali = ?, ot_hp_wali = ?, ot_alamat_wali = ?, ot_status_asuh = ? WHERE ot_siswa = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit_nilai($data){
      $query = 'UPDATE nilai SET nilai_uan = ? WHERE nilai_siswa = ? AND nilai_mapel = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit_prestasi($data){
      $query = 'UPDATE prestasi SET prestasi_nilai = ? WHERE prestasi_siswa = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit_test($data){
      $query = 'UPDATE test SET test_nilai = ? WHERE test_siswa = ? AND test_jentest = ?';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function edit_pilihan($data){
      $query = 'UPDATE pilihan SET pilihan_jur = ?, pilihan_status = ? WHERE pilihan_siswa = ? AND pilihan_ke = ? ';
      $query = $this->db->query($query, to_null($data));
      return $this->db->affected_rows() > 0;
   }
   function cek_siswa_ot($data){
      $query = 'SELECT siswa.*, orang_tua.* FROM siswa JOIN orang_tua ON ot_siswa = siswa_id WHERE siswa_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_siswa_prestasi($data){
      $query = 'SELECT * FROM prestasi WHERE prestasi_siswa = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_siswa_nilai($data){
      $query = ' SELECT siswa.*, mata_pelajaran.*, nilai.* FROM siswa JOIN nilai ON nilai_siswa = siswa_id JOIN mata_pelajaran ON mapel_id = nilai_mapel WHERE siswa_id = ? ORDER BY mapel_id ASC';
      return $this->db->query($query, to_null($data));
   }
   function cek_siswa_test($data){
      $query = 'SELECT siswa.*, jenis_test.*, test.* FROM siswa JOIN test ON test_siswa = siswa_id JOIN jenis_test ON jentest_id = test_jentest WHERE siswa_id = ? ORDER BY jentest_id ASC';
      return $this->db->query($query, to_null($data));
   }
   function cek_siswa_pilihan($data){
      $query = 'SELECT siswa.*, pilihan.*, jurusan.* FROM siswa JOIN pilihan ON pilihan_siswa = siswa_id LEFT JOIN jurusan ON jur_id = pilihan_jur WHERE siswa_id = ? AND pilihan_ke <= ? ORDER BY siswa_id, pilihan_ke';
      return $this->db->query($query, to_null($data));
   }
   function cek_siswa_id($data){
      $query = 'SELECT * FROM siswa WHERE siswa_id = ?';
      return $this->db->query($query, to_null($data));
   }
   function cek_siswa_max(){
      $query = 'SELECT MAX(siswa_no_pendaftaran) AS siswa_no_pendaftaran FROM siswa JOIN (SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1) AS gelombang ON gel_kode = LEFT(siswa_no_pendaftaran, 4) LIMIT 1';
      return $this->db->query($query);
   }
   function cek_agama(){
      $query = 'SELECT * FROM agama ORDER BY agama_nama ASC';
      return $this->db->query($query);
   }
   function cek_prov(){
      $query = 'SELECT * FROM provinsi ORDER BY prov_nama ASC';
      return $this->db->query($query);
   }
   function cek_pend(){
      $query = 'SELECT * FROM pendidikan ORDER BY pend_id ASC';
      return $this->db->query($query);
   }
   function cek_pek(){
      $query = 'SELECT * FROM (SELECT * FROM pekerjaan WHERE pek_nama NOT LIKE "%lain%" ORDER BY pek_nama ASC) AS a
          UNION SELECT * FROM (SELECT * FROM pekerjaan WHERE pek_nama LIKE "%lain%" ORDER BY pek_nama ASC) AS b';
      return $this->db->query($query);
   }
   function cek_mapel(){
      $query = 'SELECT mapel_id, mapel_gel, mapel_nama, NULL AS nilai_uan, mapel_keterangan FROM mata_pelajaran WHERE mapel_gel = (SELECT MAX(mapel_gel) FROM mata_pelajaran) ORDER BY mapel_id ASC';
      return $this->db->query($query);
   }
   function cek_jur(){
      $query = 'SELECT jurusan.* FROM jurusan JOIN kuota ON kuota_jur = jur_id JOIN (SELECT * FROM gelombang ORDER BY gel_id DESC LIMIT 1) AS gelombang ON gel_id = kuota_gel WHERE kuota_jumlah > 0 ORDER BY jur_id ASC';
      return $this->db->query($query);
   }
   function cek_jentest(){
      $query = 'SELECT jentest_id, jentest_nama, jentest_persen, NULL AS test_nilai, jentest_keterangan FROM jenis_test WHERE jentest_gel = (SELECT MAX(jentest_gel) FROM jenis_test)ORDER BY jentest_id ASC';
      return $this->db->query($query);
   }
}