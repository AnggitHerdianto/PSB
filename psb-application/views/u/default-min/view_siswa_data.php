<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('u', 'header')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/dhtmlxcalendar.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_web.css')?>">
<script src="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/dhtmlxcalendar.js') ?>"></script>
<script>function doOnLoad(){var e=new dhtmlXCalendarObject(["calendar"]);e.setSkin("dhx_web"),e.hideTime(),e.setDateFormat("%d-%m-%Y"),e.setPosition("bottom")}</script>
<?php $this->load->view(view('u', 'sidebar')); ?>
<div class="layout-cell content"><article class="post article">
<div class="postmetadataheader"><h2 class="postheader"><?php echo $judul; ?></h2></div>
<div class="postcontent postcontent-0 clearfix">
<?php $this->load->view(view('u', 'notice')); ?>
<?php echo form_open($post);?>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 50%;" >
         <?php // data siswa////////////////////////////////////////////////////////////////////////////////////////
            echo '<h4>Data Siswa</h4>';
            $template = array( 'table_open'         => '<table>',
               'cell_start'         => '<td class = "layout-item-table-1">', // class = "layout-item-table-1"
               'cell_end'           => '</td>',
               'cell_alt_start'     => '<td class = "layout-item-table-1">',
               'cell_alt_end'       => '</td>',
               'table_close'         => '</table>');
            $this->table->set_template($template);
            //
            $attrib = array('name' => 'data_nama',
                              'value' => set_value('data_nama', $data_nama),
                              'class' => css_form_class(form_error('data_nama')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 128);
            $this->table->add_row('<div style = "width: 150px">'.'Nama Lengkap'.'</div>', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_nama', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: Siti Dewi, Novita, dll.</font>');
            //
            $attrib = array('name' => 'data_nama_panggilan',
                              'value' => set_value('data_nama_panggilan', $data_nama_panggilan),
                              'class' => css_form_class(form_error('data_nama_panggilan')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 32);
            $this->table->add_row('Nama Panggilan', form_input($attrib).form_error('data_nama_panggilan', ' <font style="color: red">', '</font>'));
            //
            $radio_l = array('name' => 'data_jenis_kelamin',
                              'value' => encode('l'),
                              'checked' => set_radio('data_jenis_kelamin', 'l', $data_jenis_kelamin_l));
            $radio_p = array('name' => 'data_jenis_kelamin',
                              'value' => encode('p'),
                              'checked' => set_radio('data_jenis_kelamin', 'p', $data_jenis_kelamin_p));
            $this->table->add_row('Jenis Kelamin',form_radio($radio_l).'Laki-Laki'.form_radio($radio_p).'Perempuan'.form_error('data_jenis_kelamin', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'data_tempat_lahir',
                              'value' => set_value('data_tempat_lahir', $data_tempat_lahir),
                              'class' => css_form_class(form_error('data_tempat_lahir')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 64);
            $this->table->add_row('Tempat Lahir', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_tempat_lahir', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: Kulon Progo, Sleman, dll.</font>');
            //
            $attrib = array('name' => 'data_tanggal_lahir',
                              'value' => set_value('data_tanggal_lahir', $data_tanggal_lahir),
                              'class' => css_form_class(form_error('data_tanggal_lahir')),
                              'style' => 'width: 90%; max-width: 80px;',
                              'id' => 'calendar',
                              'maxlength' => 10);
            $this->table->add_row('Tanggal Lahir', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_tanggal_lahir', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 31-12-1995, 02-03-1996, dll.</font>');
            //
            $attrib = array('name' => 'data_sekolah_asal',
                              'value' => set_value('data_sekolah_asal', $data_sekolah_asal),
                              'class' => css_form_class(form_error('data_sekolah_asal')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 128);
            $this->table->add_row('Asal Sekolah', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_sekolah_asal', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: SMP N 1 Mungkid, dll.</font>');
            //
            $attrib = array('name' => 'data_sekolah_alamat',
                              'value' => set_value('data_sekolah_alamat', $data_sekolah_alamat),
                              'class' => css_form_class(form_error('data_sekolah_alamat')),
                              'style' => 'width: 90%; max-width: 300px; height: 45px;',
                              'maxlength' => 255);
            $this->table->add_row('Alamat Sekolah', form_textarea($attrib).form_error('data_sekolah_alamat', ' <font style="color: red">', '</font>'));
            //
            $drop_down = '<select name="data_agama" style = "width: 90%; max-width: 300px;">';
            foreach ($data_agama as $row){
               if ($row['agama_id'] == $pilih_agama) $hasil = TRUE;
               else $hasil = FALSE;
               $drop_down = $drop_down .'<option value="'.encode($row['agama_id']).'" '. set_select('data_agama', $row['agama_id'], $hasil) . '>'.$row['agama_nama'].'</option>';
            }
            $drop_down = $drop_down.'</select>';
            $this->table->add_row('Agama', $drop_down.form_error('data_agama', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'data_suku',
                              'value' => set_value('data_suku', $data_suku),
                              'class' => css_form_class(form_error('data_suku')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 64);
            $this->table->add_row('Suku Bangsa', form_input($attrib).form_error('data_suku', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: Jawa, Aceh, Bali, dll.</font>');
            //
            $attrib = array('name' => 'data_anak_ke',
                              'value' => set_value('data_anak_ke', $data_anak_ke),
                              'class' => css_form_class(form_error('data_anak_ke')),
                              'style' => 'width: 90%; max-width: 80px;',
                              'maxlength' => 2);
            $this->table->add_row('Anak Ke-', form_input($attrib).form_error('data_anak_ke', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 1, 2, 3, dll.</font>');
            //
            $attrib = array('name' => 'data_jumlah_saudara',
                              'value' => set_value('data_jumlah_saudara', $data_jumlah_saudara),
                              'class' => css_form_class(form_error('data_jumlah_saudara')),
                              'style' => 'width: 90%; max-width: 80px;',
                              'maxlength' => 2);
            $this->table->add_row('Jumlah Saudara', form_input($attrib).form_error('data_jumlah_saudara', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 1, 2, 3, dll.</font>');
            //
            $attrib = array('name' => 'data_alamat',
                              'value' => set_value('data_alamat', $data_alamat),
                              'class' => css_form_class(form_error('data_alamat')),
                              'style' => 'width: 90%; max-width: 300px; height: 45px;',
                              'maxlength' => 255);
            $this->table->add_row('Alamat Rumah', form_textarea($attrib).form_error('data_alamat', ' <font style="color: red">', '</font>'));
            //
            $drop_down = '<select name="data_prov" style = "width: 90%; max-width: 300px;">';
            foreach ($data_prov as $row){
               if ($row['prov_id'] == $pilih_prov) $hasil = TRUE;
               else $hasil = FALSE;
               $drop_down = $drop_down .
               '<option value="'.encode($row['prov_id']).'" '. set_select('data_prov', $row['prov_id'], $hasil) . '>'.$row['prov_nama'].'</option>';
            }
            $drop_down = $drop_down.'</select>';
            $this->table->add_row('Provinsi', $drop_down.form_error('data_prov', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'data_kabupaten',
                              'value' => set_value('data_kabupaten', $data_kabupaten),
                              'class' => css_form_class(form_error('data_kabupaten')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 64);
            $this->table->add_row('Kabupaten', form_input($attrib).form_error('data_kabupaten', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'data_kecamatan',
                              'value' => set_value('data_kecamatan', $data_kecamatan),
                              'class' => css_form_class(form_error('data_kecamatan')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 64);
            $this->table->add_row('Kecamatan', form_input($attrib).form_error('data_kecamatan', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'data_kode_pos',
                              'value' => set_value('data_kode_pos', $data_kode_pos),
                              'class' => css_form_class(form_error('data_kode_pos')),
                              'style' => 'width: 90%; max-width: 80px;',
                              'maxlength' => 16);
            $this->table->add_row('Kode Pos', form_input($attrib).form_error('data_kode_pos', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 55651, 55611, dll.</font>');
            //
            $attrib = array('name' => 'data_alamat_pos',
                              'value' => set_value('data_alamat_pos', $data_alamat_pos),
                              'class' => css_form_class(form_error('data_alamat_pos')),
                              'style' => 'width: 90%; max-width: 300px; height: 45px;',
                              'maxlength' => 255);
            $this->table->add_row('Alamat Pos', form_textarea($attrib).form_error('data_alamat_pos', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'data_hp',
                              'value' => set_value('data_hp', $data_hp),
                              'class' => css_form_class(form_error('data_hp')),
                              'style' => 'width: 90%; max-width: 150px;',
                              'maxlength' => 16);
            $this->table->add_row('Nomor HP', form_input($attrib).form_error('data_hp', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 085777888999, dll.</font>');
            //
            $attrib = array('name' => 'data_telepon',
                              'value' => set_value('data_telepon', $data_telepon),
                              'class' => css_form_class(form_error('data_telepon')),
                              'style' => 'width: 90%; max-width: 150px;',
                              'maxlength' => 16);
            $this->table->add_row('Nomor Telp. Rumah', form_input($attrib).form_error('data_telepon', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 0274555666, dll.</font>');
            //
            $attrib = array('name' => 'data_email',
                              'value' => set_value('data_email', $data_email),
                              'class' => css_form_class(form_error('data_email')),
                              'style' => 'width: 90%; max-width: 150px;',
                              'maxlength' => 128);
            $this->table->add_row('E-Mail', form_input($attrib).form_error('data_email', ' <font style="color: red">', '</font>'));
            //
            $radio_none = array('name' => 'data_gol_darah', 'value' => encode('none'), 'checked' => set_radio('data_gol_darah', 'none', $data_gol_darah_none));
            $radio_a = array('name' => 'data_gol_darah', 'value' => encode('u'), 'checked' => set_radio('data_gol_darah', 'u', $data_gol_darah_a));
            $radio_b = array('name' => 'data_gol_darah', 'value' => encode('b'), 'checked' => set_radio('data_gol_darah', 'b', $data_gol_darah_b));
            $radio_o = array('name' => 'data_gol_darah', 'value' => encode('o'), 'checked' => set_radio('data_gol_darah', 'o', $data_gol_darah_o));
            $radio_ab = array('name' => 'data_gol_darah', 'value' => encode('ab'), 'checked' => set_radio('data_gol_darah', 'ab', $data_gol_darah_ab));
            $this->table->add_row('Golongan Darah',
                     form_radio($radio_a).'u'.
                     form_radio($radio_b).'B'.
                     form_radio($radio_o).'O'.
                     form_radio($radio_ab).'AB'.
                     form_radio($radio_none).'Tidak Tahu'.
                     form_error('data_gol_darah', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'data_tinggi_badan',
                              'value' => set_value('data_tinggi_badan', $data_tinggi_badan),
                              'class' => css_form_class(form_error('data_tinggi_badan')),
                              'style' => 'width: 90%; max-width: 80px;',
                              'maxlength' => 16);
            $this->table->add_row('Tinggi Badan', form_input($attrib).' Cm'.form_error('data_tinggi_badan', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 160, 150.60, dll.</font>');
            //
            $attrib = array('name' => 'data_berat_badan',
                              'value' => set_value('data_berat_badan', $data_berat_badan),
                              'class' => css_form_class(form_error('data_berat_badan')),
                              'style' => 'width: 90%; max-width: 80px;',
                              'maxlength' => 16);
            $this->table->add_row('Berat Badan', form_input($attrib).' Kg'.form_error('data_berat_badan', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 70, 40.50, dll.</font>');
            //
            $attrib = array('name' => 'data_penyakit',
                              'value' => set_value('data_penyakit', $data_penyakit),
                              'class' => css_form_class(form_error('data_penyakit')),
                              'style' => 'width: 90%; max-width: 300px; height: 45px;',
                              'maxlength' => 255);
            $this->table->add_row('Riwayat Penyakit', form_textarea($attrib).form_error('data_penyakit', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'data_keterangan',
                              'value' => set_value('data_keterangan', $data_keterangan),
                              'class' => css_form_class(form_error('data_keterangan')),
                              'style' => 'width: 90%; max-width: 300px; height: 45px;',
                              'maxlength' => 255);
            $this->table->add_row('Keterangan Lain', form_textarea($attrib).form_error('data_keterangan', ' <font style="color: red">', '</font>'));
            echo $this->table->generate();
         ?>
      </div>
      <div class="layout-cell layout-item-0" style="width: 50%;" >
         <?php // nilai uan////////////////////////////////////////////////////////////////////////////////////////
            echo '<h4>Nilai UAN</h4>';
            $template = array( 'table_open'         => '<table>',
               'cell_start'         => '<td class = "layout-item-table-1">', // class = "layout-item-table-1"
               'cell_end'           => '</td>',
               'cell_alt_start'     => '<td class = "layout-item-table-1">',
               'cell_alt_end'       => '</td>',
               'table_close'         => '</table>');
            $this->table->set_template($template);
            //
            foreach($nilai_mapel as $row){
               $attrib = array('name' => 'nilai_mapel['.$row['mapel_id'].']',
                                 'value' => set_value('nilai_mapel['.$row['mapel_id'].']', $row['nilai_uan']),
                                 'class' => css_form_class(form_error('nilai_mapel['.$row['mapel_id'].']')),
                                 'style' => 'width: 90%; max-width: 80px;',
                                 'maxlength' => 6);
               $this->table->add_row(
                    '<div style = "width: 150px">'.$row['mapel_nama'].'</div>',
                     form_input($attrib).'<font style="color: red;"> * </font>'.
                     form_error('nilai_mapel['.$row['mapel_id'].']', ' <font style="color: red">', '</font>')
               );
            }
            //
            $this->table->add_row('', '<font style="font-style: italic; color: #C1C1C1">Contoh: 8, 9.6, 10, dll.</font>');
            echo $this->table->generate();
            // nilai prestasi////////////////////////////////////////////////////////////////////////////////////////
            echo '<h4>Nilai Prestasi</h4>';
            $template = array( 'table_open'         => '<table>',
               'cell_start'         => '<td class = "layout-item-table-1">', // class = "layout-item-table-1"
               'cell_end'           => '</td>',
               'cell_alt_start'     => '<td class = "layout-item-table-1">',
               'cell_alt_end'       => '</td>',
               'table_close'         => '</table>');
            $this->table->set_template($template);
            //
            $attrib = array('name' => 'nilai_prestasi',
                              'value' => set_value('nilai_prestasi', $nilai_prestasi),
                              'class' => css_form_class(form_error('nilai_prestasi')),
                              'style' => 'width: 90%; max-width: 80px;',
                              'maxlength' => 64);
               $this->table->add_row(
                    '<div style = "width: 150px">Nilai Prestasi</div>',
                     form_input($attrib).
                     form_error('nilai_prestasi', ' <font style="color: red">', '</font>')
               );
            //
            $this->table->add_row('', '<font style="font-style: italic; color: #C1C1C1">Contoh: 3, 4.6, 10, dll.</font>');
            echo $this->table->generate();
            // pilihan jurusan////////////////////////////////////////////////////////////////////////////////////////
            echo '<h4>Pilihan Jurusan</h4>';
            $template = array('table_open'         => '<table>',
               'cell_start'         => '<td class = "layout-item-table-1">', // class = "layout-item-table-1"
               'cell_end'           => '</td>',
               'cell_alt_start'     => '<td class = "layout-item-table-1">',
               'cell_alt_end'       => '</td>',
               'table_close'         => '</table>');
            $this->table->set_template($template);
            //
            for($i = 1; $i <= $pilihan_jumlah_pilihan; $i++){
               $drop_down = '<select name="pilihan_jur['.$i.']" style = "width: 100%; max-width: 150px;">';
               foreach ($pilihan_jur as $row){
                  if ($row['jur_id'] == @$pilih_pilihan[$i]) $hasil = TRUE;
                  else $hasil = FALSE;
                  $drop_down = $drop_down .'<option value="'.encode($row['jur_id']).'" '. set_select('pilihan_jur['.$i.']', $row['jur_id'], $hasil) . '>'.$row['jur_nama'].'</option>';
               }
               $drop_down = $drop_down.'</select>';
               $this->table->add_row('<div style = "width: 150px">'.'Pilihan '.$i.'</div>', $drop_down.form_error('pilihan_jur['.$i.']', ' <font style="color: red">', '</font>'));
            }
            //$this->table->add_row('', '<font style="font-style: italic; color: #C1C1C1">Jurusan yang dapat dipilih</font>');
            echo $this->table->generate();
            // data orang tua / wali////////////////////////////////////////////////////////////////////////////////////////
            echo '<h4>Orang Tua/Wali</h4>';
            $template = array('table_open'         => '<table>',
               'cell_start'         => '<td class = "layout-item-table-1">', // class = "layout-item-table-1"
               'cell_end'           => '</td>',
               'cell_alt_start'     => '<td class = "layout-item-table-1">',
               'cell_alt_end'       => '</td>',
               'table_close'         => '</table>');
            $this->table->set_template($template);
            // ayah
            $attrib = array('name' => 'ot_nama_ayah',
                              'value' => set_value('ot_nama_ayah', $ot_nama_ayah),
                              'class' => css_form_class(form_error('ot_nama_ayah')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 128);
            $this->table->add_row('<div style = "width: 150px">'.'Nama Ayah'.'</div>', form_input($attrib).form_error('ot_nama_ayah', ' <font style="color: red">', '</font>'));
            //
            $drop_down = '<select name="ot_pend_ayah" style = "width: 90%; max-width: 150px;">';
            foreach ($ot_pend_ayah as $row){
               if ($row['pend_id'] == $pilih_pend_ayah) $hasil = TRUE;
               else $hasil = FALSE;
               $drop_down = $drop_down .'<option value="'.encode($row['pend_id']).'" '. set_select('ot_pend_ayah', $row['pend_id'], $hasil) . '>'.$row['pend_nama'].'</option>';
            }
            $drop_down = $drop_down.'</select>';
            $this->table->add_row('Pendidikan Ayah', $drop_down.form_error('ot_pend_ayah', ' <font style="color: red">', '</font>'));
            //
            $drop_down = '<select name="ot_pek_ayah" style = "width: 90%; max-width: 150px;">';
            foreach ($ot_pek_ayah as $row){
               if ($row['pek_id'] == $pilih_pek_ayah) $hasil = TRUE;
               else $hasil = FALSE;
               $drop_down = $drop_down .'<option value="'.encode($row['pek_id']).'" '. set_select('ot_pek_ayah', $row['pek_id'], $hasil).'>'.$row['pek_nama'].'</option>';
            }
            $drop_down = $drop_down.'</select>';
            $this->table->add_row('Pekerjaan Ayah', $drop_down.form_error('ot_pek_ayah', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'ot_gaji_ayah',
                              'value' => set_value('ot_gaji_ayah', $ot_gaji_ayah),
                              'class' => css_form_class(form_error('ot_gaji_ayah')),
                              'style' => 'width: 90%; max-width: 150px;',
                              'maxlength' => 16);
            $this->table->add_row('Penghasilan Ayah', form_input($attrib).' Rupiah'.form_error('ot_gaji_ayah', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 1000000, 1500000.00, dll.</font>');
            //
            $attrib = array('name' => 'ot_hp_ayah',
                              'value' => set_value('ot_hp_ayah', $ot_hp_ayah),
                              'class' => css_form_class(form_error('ot_hp_ayah')),
                              'style' => 'width: 90%; max-width: 150px;',
                              'maxlength' => 16);
            $this->table->add_row('Nomor HP Ayah', form_input($attrib).form_error('ot_hp_ayah', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 085777888999, dll.</font>');
            // ibu
            $attrib = array('name' => 'ot_nama_ibu',
                              'value' => set_value('ot_nama_ibu', $ot_nama_ibu),
                              'class' => css_form_class(form_error('ot_nama_ibu')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 128);
            $this->table->add_row('Nama Ibu', form_input($attrib).form_error('ot_nama_ibu', ' <font style="color: red">', '</font>'));
            //
            $drop_down = '<select name="ot_pend_ibu" style = "width: 90%; max-width: 150px;">';
            foreach ($ot_pend_ibu as $row){
               if ($row['pend_id'] == $pilih_pend_ibu) $hasil = TRUE;
               else $hasil = FALSE;
               $drop_down = $drop_down .'<option value="'.encode($row['pend_id']).'" '. set_select('ot_pend_ibu', $row['pend_id'], $hasil) . '>'.$row['pend_nama'].'</option>';
            }
            $drop_down = $drop_down.'</select>';
            $this->table->add_row('Pendidikan Ibu', $drop_down.form_error('ot_pend_ibu', ' <font style="color: red">', '</font>'));
            //
            $drop_down = '<select name="ot_pek_ibu" style = "width: 90%; max-width: 150px;">';
            foreach ($ot_pek_ibu as $row){
               if ($row['pek_id'] == $pilih_pek_ibu) $hasil = TRUE;
               else $hasil = FALSE;
               $drop_down = $drop_down .'<option value="'.encode($row['pek_id']).'" '. set_select('ot_pek_ibu', $row['pek_id'], $hasil).'>'.$row['pek_nama'].'</option>';
            }
            $drop_down = $drop_down.'</select>';
            $this->table->add_row('Pekerjaan Ibu', $drop_down.form_error('ot_pek_ibu', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'ot_gaji_ibu',
                              'value' => set_value('ot_gaji_ibu', $ot_gaji_ibu),
                              'class' => css_form_class(form_error('ot_gaji_ibu')),
                              'style' => 'width: 90%; max-width: 150px;',
                              'maxlength' => 16);
            $this->table->add_row('Penghasilan Ibu', form_input($attrib).' Rupiah'.form_error('ot_gaji_ibu', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 1000000, 1500000.00, dll.</font>');
            //
            $attrib = array('name' => 'ot_hp_ibu',
                              'value' => set_value('ot_hp_ibu', $ot_hp_ibu),
                              'class' => css_form_class(form_error('ot_hp_ibu')),
                              'style' => 'width: 90%; max-width: 150px;',
                              'maxlength' => 16);
            $this->table->add_row('Nomor HP Ibu', form_input($attrib).form_error('ot_hp_ibu', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 085777888999, dll.</font>');
            //
            $attrib = array('name' => 'ot_alamat_ortu',
                              'value' => set_value('ot_alamat_ortu', $ot_alamat_ortu),
                              'class' => css_form_class(form_error('ot_alamat_ortu')),
                              'style' => 'width: 90%; max-width: 300px; height: 65px;',
                              'maxlength' => 255);
            $this->table->add_row('Alamat Orang Tua', form_textarea($attrib).form_error('ot_alamat_ortu', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'ot_nama_wali',
                              'value' => set_value('ot_nama_wali', $ot_nama_wali),
                              'class' => css_form_class(form_error('ot_nama_wali')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 128);
            $this->table->add_row('Nama Wali', form_input($attrib).form_error('ot_nama_wali', ' <font style="color: red">', '</font>'));
            // wali
            $drop_down = '<select name="ot_pend_wali" style = "width: 90%; max-width: 150px;">';
            foreach ($ot_pend_wali as $row){
               if ($row['pend_id'] == $pilih_pend_wali) $hasil = TRUE;
               else $hasil = FALSE;
               $drop_down = $drop_down .'<option value="'.encode($row['pend_id']).'" '. set_select('ot_pend_wali', $row['pend_id'], $hasil) . '>'.$row['pend_nama'].'</option>';
            }
            $drop_down = $drop_down.'</select>';
            $this->table->add_row('Pendidikan Wali', $drop_down.form_error('ot_pend_wali', ' <font style="color: red">', '</font>'));
            //
            $drop_down = '<select name="ot_pek_wali" style = "width: 90%; max-width: 150px;">';
            foreach ($ot_pek_wali as $row){
               if ($row['pek_id'] == $pilih_pek_wali) $hasil = TRUE;
               else $hasil = FALSE;
               $drop_down = $drop_down .'<option value="'.encode($row['pek_id']).'" '. set_select('ot_pek_wali', $row['pek_id'], $hasil).'>'.$row['pek_nama'].'</option>';
            }
            $drop_down = $drop_down.'</select>';
            $this->table->add_row('Pekerjaan Wali', $drop_down.form_error('ot_pek_wali', ' <font style="color: red">', '</font>'));
            //
            $attrib = array('name' => 'ot_gaji_wali',
                              'value' => set_value('ot_gaji_wali', $ot_gaji_wali),
                              'class' => css_form_class(form_error('ot_gaji_wali')),
                              'style' => 'width: 90%; max-width: 150px;',
                              'maxlength' => 16);
            $this->table->add_row('Penghasilan Wali', form_input($attrib).' Rupiah'.form_error('ot_gaji_wali', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 1000000, 1500000.00, dll.</font>');
            //
            $attrib = array('name' => 'ot_hp_wali',
                              'value' => set_value('ot_hp_wali', $ot_hp_wali),
                              'class' => css_form_class(form_error('ot_hp_wali')),
                              'style' => 'width: 90%; max-width: 150px;;',
                              'maxlength' => 16);
            $this->table->add_row('Nomor HP Wali', form_input($attrib).form_error('ot_hp_wali', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 085777888999, dll.</font>');
            //
            $attrib = array('name' => 'ot_alamat_wali',
                              'value' => set_value('ot_alamat_wali', $ot_alamat_wali),
                              'class' => css_form_class(form_error('ot_alamat_wali')),
                              'style' => 'width: 90%; max-width: 300px; height: 65px;',
                              'maxlength' => 255);
            $this->table->add_row('Alamat Wali', form_textarea($attrib).form_error('ot_alamat_wali', ' <font style="color: red">', '</font>'));
            //
            $radio_ortu = array('name' => 'ot_status_asuh',
                              'value' => encode('ortu'),
                              'checked' => set_radio('ot_status_asuh', 'ortu', $ot_status_asuh_ortu));
            $radio_wali = array('name' => 'ot_status_asuh',
                              'value' => encode('wali'),
                              'checked' => set_radio('ot_status_asuh', 'wali', $ot_status_asuh_wali));
            $this->table->add_row('Status Asuh', form_radio($radio_ortu).'Orang Tua'.form_radio($radio_wali).'Wali'.form_error('ot_status_asuh', ' <font style="color: red">', '</font>'));
            echo $this->table->generate();
         ?>
</div></div></div>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 50%" ></div><div class="layout-cell layout-item-0" style="width: 50%" >
      <p><?php echo form_submit('save', $simpan, 'class = "button" onclick = "return confirm ('."'Apa semua data sudah benar?'".')"'); 
      //echo anchor($back, 'Kembali', 'class = "button"');?></p> 
</div></div></div>
<?php echo form_close();?>
</div></article></div><?php $this->load->view(view('u', 'footer')); ?>