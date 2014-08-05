<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('u', 'header')); ?><?php $this->load->view(view('u', 'sidebar')); ?>
   <div class="layout-cell content">
   <article class="post article"><div class="postmetadataheader"><h2 class="postheader"><?php echo $judul; ?></h2></div>
      <div class="postcontent postcontent-0 clearfix">
         <link rel="stylesheet" href="<?php echo view_external('tablesorter', 'themes/blue/style.css');?>">
         <?php $this->load->view(view('u', 'notice')); ?>
         <div class="content-layout" style="margin-bottom: 5px; margin-top: 5px;"><div class="content-layout-row">
               <div class="layout-cell layout-item-0" style="width: 50%;" ><?php foreach ($filter_gel as $row) echo $row;?></div>
               <div class="layout-cell layout-item-0" style="width: 50%; float: right;" >
                  <?php echo form_open($post_cari, 'class="search"');?>
                  <?php echo form_hidden('data_jur', encode($pilih_jur));?>
                  <?php $attrib = array( 'name' => 'data_cari',
                        'value' => set_value('data_cari', $data_cari),
                        'class' => css_form_class(form_error('data_cari')));
                     echo form_input($attrib);
                  ?>
                  <?php echo form_submit('search', 'Search', 'class="search-button"'); ?>
                  <?php echo form_close();?>
               </div>
         </div></div>
         <div class="content-layout" style="margin-bottom: 5px; margin-top: 5px;"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 50%;">
                  <?php echo form_open($post_jur);?>
                  <?php $drop_down = '<select name="data_jur" style="width: 200px;">';
                     foreach ($data_jur as $row){
                        if ($row['jur_id'] == $pilih_jur) $hasil = TRUE;
                        else $hasil = FALSE;
                        $drop_down = $drop_down .'<option value="'.encode($row['jur_id']).'" '. set_select('data_jur', $row['jur_id'], $hasil) . '>'.$row['jur_nama'].'</option>';
                     }
                     $drop_down = $drop_down.'</select>';
                     echo $drop_down;
                  ?>
                  <?php echo form_submit('jurusan', 'Jurusan', 'class = "button" style = "width: 40px"');?>
                  <?php echo form_close();?>
               </div>
         <div class="layout-cell layout-item-0" style="width: 50%; float: right;" ></div>
         </div></div>
         <div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%" >
                  <?php $template = array(
                        'table_open'         => '<table class="tablesorter" style="width: 100%;">',
                        'cell_start'         => '',
                        'cell_end'           => '',
                        'cell_alt_start'     => '',
                        'cell_alt_end'       => '',
                        'table_close'         => '</table>');
                     $this->table->set_template($template);
                     //heading
                     $set_heading_1 = array('NO', 'PEND', 'NAMA', 'JK', 'UAN', 'Prestasi');
                     foreach ($cek_jentest AS $key=>$row) $set_heading_2[$key] = $row['jentest_singkatan'];
                     $set_heading_3 = array('Total', 'HASIL');
                     $this->table->set_heading(array_merge($set_heading_1, $set_heading_2, $set_heading_3));
                     //content
                     foreach ($query as $key => $row) {
                        $no = $key + 1;
                        $siswa_no_pendaftaran = anchor('a/siswa/detil/'.$row['siswa_id'], $row['siswa_no_pendaftaran']);
                        $siswa_no_pendaftaran = $row['siswa_no_pendaftaran'];
                        if($row['pilihan_status'] == 'diterima') $pilihan_status = '<span style="color: #05A322">Diterima</span>';
                        elseif($row['pilihan_status'] == 'cadangan') $pilihan_status = '<span style="color: #B8B200">Cadangan</span>';
                        elseif($row['pilihan_status'] == 'ditolak') $pilihan_status = '<span style="color: #FF0505">Ditolak</span>';
                        $isi_1 = array('<td style="text-align: center;">'.$no.'</td>',
                              '<td style="text-align: center;">'.$siswa_no_pendaftaran.'</td>',
                              '<td style="text-align: left;">'.$row['siswa_nama'].'</td>',
                              '<td style="text-align: center;">'.strtoupper($row['siswa_jenis_kelamin']).'</td>',
                              '<td style="text-align: center;">'.$row['nilai_uan'].'</td>',
                              '<td style="text-align: center;">'.$row['prestasi_nilai'].'</td>'
                        );
                        foreach ($cek_jentest AS $key=>$baris) $isi_2[$key] = '<td style="text-align: center;">'.$row['test_'.$baris['jentest_id']].'</td>';
                        $isi_3 = array('<td style="text-align: center;">'.$row['nilai_akhir'].'</td>',
                              '<td style="text-align: center;">'.$pilihan_status.'</td>');
                        $this->table->add_row(array_merge($isi_1, $isi_2, $isi_3));
                     }
                     echo $this->table->generate();
                  ?>
         </div></div></div>
</div></article></div>
<?php $this->load->view(view('u', 'footer')); ?>