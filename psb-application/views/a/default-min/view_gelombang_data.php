<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?>
<link rel="stylesheet" type="text/css" href="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/dhtmlxcalendar.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_web.css') ?>">
<script src="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/dhtmlxcalendar.js') ?>"></script>
<script>function doOnLoad(){var e=new dhtmlXCalendarObject(["calendar_start","calendar_end"]);e.setSkin("dhx_web"),e.hideTime(),e.setDateFormat("%d-%m-%Y"),e.setPosition("bottom")}</script>
<?php $this->load->view(view('a', 'sidebar'));?><?php $this->load->view(view('a', 'notice'));?>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%" >
    <?php echo form_open($post);
      $template = array('table_open' => '<table>',
          'cell_start' => '<td class = "layout-item-table-1">',
          'cell_end' => '</td>',
          'cell_alt_start' => '<td class = "layout-item-table-1">',
          'cell_alt_end' => '</td>',
          'table_close' => '</table>');
      $this->table->set_template($template);
      //
      $attrib = array('name' => 'data_ta',
          'value' => set_value($data_ta, $data_ta),
          'class' => css_form_class(form_error('data_ta')),
          'style' => 'width: 90%; max-width: 80px; text-align: center; font-weight: bold',
          'disabled' => 'disabled');
      $this->table->add_row('<div style = "width: 150px">'.'Tahun Ajaran'.'</div>', form_input($attrib).'<br>' .'<font style="font-style: italic; color: #C1C1C1">Tahun ajaran berdasarkan tahun berjalan</font>');
      //
      $attrib = array('name' => 'data_nama',
          'value' => set_value('data_nama', $data_nama),
          'class' => css_form_class(form_error('data_nama')),
          'style' => 'width: 90%; max-width: 300px;',
          'maxlength' => 128);
      $this->table->add_row('Gelombang', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_nama', '<br><font style="color: red">', '</font>') .'<br>' .'<font style="font-style: italic; color: #C1C1C1">Contoh : Gelombang 2, Gelombang III, dll. </font>');
      //
      $attrib = array('name' => 'data_tanggal_mulai',
          'value' => set_value('data_tanggal_mulai', $data_tanggal_mulai),
          'class' => css_form_class(form_error('data_tanggal_mulai')),
          'style' => 'width: 90%; max-width: 80px;',
          'id' => 'calendar_start',
          'maxlength' => 10);
      $this->table->add_row('Tanggal Mulai', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_tanggal_mulai', ' <font style="color: red">', '</font>') .'<br>' .'<font style="font-style: italic; color: #C1C1C1">Contoh: 30-12-2013, 02-03-2012, dll.</font>');
      //
      $attrib = array('name' => 'data_tanggal_selesai',
          'value' => set_value('data_tanggal_selesai', $data_tanggal_selesai),
          'class' => css_form_class(form_error('data_tanggal_selesai')),
          'style' => 'width: 90%; max-width: 80px;',
          'id' => 'calendar_end',
          'maxlength' => 10);
      $this->table->add_row('Tanggal Selesai', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_tanggal_selesai', ' <font style="color: red">', '</font>') .'<br>' .'<font style="font-style: italic; color: #C1C1C1">Contoh: 30-12-2013, 02-03-2012, dll.</font>');
      //
      $drop_down = '<select name="data_jumlah_pilihan" style = "width: 80px; text-align: center">';
      for ($i = 1; $i <= $data_jumlah_pilihan; $i++) {
         if ($i == $pilih) $hasil = TRUE;
         else $hasil = FALSE;
         $drop_down = $drop_down.'<option value="'.encode($i).'" '. set_select('data_jumlah_pilihan', $i, $hasil) . '>'.$i.'</option>';
      }
      $drop_down = $drop_down . '</select>';
      $this->table->add_row('Banyak Pilihan', $drop_down.form_error('data_jumlah_pilihan', ' <font style="color: red">', '</font>') .'<br>' .'<font style="font-style: italic; color: #C1C1C1">Jumlah jurusan yang dapat dipilih saat pendaftaran</font>');
      //
      $attrib = array('name' => 'data_keterangan',
          'value' => set_value('data_keterangan', $data_keterangan),
          'class' => css_form_class(form_error('data_keterangan')),
          'style' => 'width: 90%; max-width: 300px;',
          'maxlength' => 255);
      $this->table->add_row('Keterangan', form_textarea($attrib).form_error('data_keterangan', ' <font style="color: red">', '</font>'));
      //
      $this->table->add_row('', form_submit('save', $simpan, 'class = "button"').anchor($back, 'Kembali', 'class = "button"'));
      echo $this->table->generate();
      echo form_close();
   ?>
</div></div></div>
<?php $this->load->view(view('a', 'footer')); ?>