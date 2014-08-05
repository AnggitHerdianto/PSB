### PSB
Software Penerimaan Siswa Baru untuk SMK

### VERSI
220

### FRAMEWORK
CodeIgniter

### PETUNJUK INSTALASI
1. Extract semua file dan letakan diserver.

2. Buat database kosong kemudian restore file "PSB.sql" ke databse yang telah dibuat tadi.

3. Buka file "/psb-application/config/database.php", kemuadian rubah baris di bawah ini sesuai dengan konfigurasi server.
   
   $db['default']['hostname'] = 'localhost';

   $db['default']['username'] = 'root';
   
   $db['default']['password'] = '';
   
   $db['default']['database'] = 'psb';
   
   ...

4. Buka alamat berikut ini untuk login "contoh.com/psb-admin".

5. Untuk login pertama kali, masukan username "admin" dan password "admin".

### JIKA HALAMAN NOT FOUND
Software ini dioptimalkan menggunakan web server apache dan memanfaatkan file .htacces untuk mengatur url agar terlihat lebih rapi. Jika halaman tidak bisa dibuka ada kemungkinan modul rewrite di apache tidak aktif, untuk itu solusinya paling mudah adalah sebagai berikut:

1. Hapus file /.htaccess

2. Buka file /psb-application/config/config.php 

3. Kemudian rubah baris $config['index_page'] = ''; menjadi $config['index_page'] = 'index.php';

### PENGEMBANG
Anggit Herdianto 

[Facebook](http://fb.com/AnggitHerdianto) 

[Twitter](http://twitter.com/AnggitHerdianto)
