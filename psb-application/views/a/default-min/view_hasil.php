<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?>
<link rel="stylesheet" href="<?php echo view_external('tablesorter', 'themes/blue/style.css');?>">
<script type="text/javascript" src="<?php echo view_external('tablesorter', 'jquery.tablesorter.min.js')?>"></script>
<script type="text/javascript">$(document).ready(function(){$("#tsorter").tablesorter({widgets:["zebra"],headers:{10:{sorter:!1}}})});</script>
<link rel="stylesheet" type="text/css" href="<?php echo view_external('dhtmlx', 'dhtmlxMessage/codebase/skins/dhtmlxmessage_dhx_web.css');?>">
<script type="text/javascript" src="<?php echo view_external('dhtmlx', 'dhtmlxMessage/codebase/dhtmlxmessage.js');?>"></script>
<script type="text/javascript">
      function hapus(id, data_nama, url){
      dhtmlx.confirm({title:'Konfirmasi Hapus', ok:'Hapus', cancel:'Batal', text:'Hapus "' + data_nama + '"?',
         callback:function(result){if(result) window.location = '<?php echo site_url('a/siswa/delete');?>' + '/' + id + '/' + url + '/do';}
      });return false;}
</script>
<?php $this->load->view(view('a', 'sidebar'));?><?php $this->load->view(view('a', 'notice'));?>
<div class="content-layout" style="margin-bottom: 5px; margin-top: 5px;"><div class="content-layout-row">
<div class="layout-cell layout-item-0" style="width: 50%;" ><?php foreach ($filter_gel as $row) echo $row; ?></div>
<div class="layout-cell layout-item-0" style="width: 50%; float: right;" >
   <?php echo form_open($post_cari, 'class="search"');?>
   <?php echo form_hidden('data_jur', encode($pilih_jur));?>
   <?php $attrib = array('name' => 'data_cari',
         'value' => set_value('data_cari', $data_cari),
         'class' => css_form_class(form_error('data_cari')));
      echo form_input($attrib);
   ?>
   <?php echo form_submit('search', 'Search', 'class="search-button"'); ?>
   <?php echo form_close();?>
</div></div></div>
<div class="content-layout" style="margin-bottom: 5px; margin-top: 5px;"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 50%;">
   <?php echo form_open($post_jur);
      $drop_down = '<select name="data_jur" style="width: 200px;">';
      foreach ($data_jur as $row){
         if ($row['jur_id'] == $pilih_jur) $hasil = TRUE;
         else $hasil = FALSE;
         $drop_down = $drop_down .
         '<option value="'.encode($row['jur_id']).'" '. set_select('data_jur', $row['jur_id'], $hasil) . '>'.$row['jur_nama'].'</option>';
      }
      $drop_down = $drop_down.'</select>';
      echo $drop_down;
   ?>
   <?php echo form_submit('jurusan', 'Jurusan', 'class = "button" style = "width: 40px"');?>
   <?php echo form_close();?>
</div><div class="layout-cell layout-item-0" style="width: 50%; float: right;" ></div></div></div>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%" >
   <?php $template = array('table_open'         => '<table id="tsorter" class="tablesorter" style="width: 100%;">',
         'cell_start'         => '',
         'cell_end'           => '',
         'cell_alt_start'     => '',
         'cell_alt_end'       => '',
         'table_close'         => '</table>');
      $this->table->set_template($template);
      //heading
      $set_heading_1 = array('NO', 'No. Pend.', 'Nama', 'JK', 'UAN', 'Prestasi');
      foreach ($cek_jentest AS $key=>$row) $set_heading_2[$key] = $row['jentest_singkatan'];
      $set_heading_3 = array('Total', 'Status', 'Aksi');
      $this->table->set_heading(array_merge($set_heading_1, $set_heading_2, $set_heading_3));
      //content
      foreach ($query as $key => $row){
         $no = $key + 1;
         $id = encode($row['siswa_id']);
         $data_nama = str_replace(array('"', "'"), '`', $row['siswa_nama']);
         $url = encode(uri_string());
         $attrib_delete = array('onClick' => "return hapus('".$id."', '".$data_nama."', '".$url."')",
            'class' => 'button',
            'style' => 'width: 50px; height: 20px; line-height: 20px; padding-left: 0px; padding-right: 0px;');
         $attrib_edit = array('class' => 'button',
            'style' => 'width: 50px; height: 20px; line-height: 20px; padding-left: 0px; padding-right: 0px;');
         $delete = site_url('/').'a/siswa/delete/'.encode($row['siswa_id']).'/'.encode(uri_string());
         $edit = site_url('/').'a/siswa/edit/'.encode($row['siswa_id']).'/'.encode(uri_string());
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
               '<td style="text-align: center;">'.$pilihan_status.'</td>',
               '<td style="width: 110px; vertical-align: middle;">'.anchor($delete, 'Hapus', $attrib_delete).anchor($edit, 'Edit', $attrib_edit).'</td>');
         $this->table->add_row(array_merge($isi_1, $isi_2, $isi_3));
      }
      echo $this->table->generate();
   ?>
</div></div></div>
<?php $this->load->view(view('a', 'footer'));?>