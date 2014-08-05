<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?>
<link rel="stylesheet" type="text/css" href="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/dhtmlxcalendar.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_web.css')?>">
<script src="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/dhtmlxcalendar.js') ?>"></script>
<script>function doOnLoad(){var e=new dhtmlXCalendarObject(["calendar"]);e.setSkin("dhx_web"),e.hideTime(),e.setDateFormat("%Y-%m-%d"),e.setPosition("bottom")}</script>
<?php $this->load->view(view('a', 'sidebar'));?><?php $this->load->view(view('a', 'notice'));?>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%" >
   <?php echo form_open($post); $template = array(
         'table_open'         => '<table>',
         'cell_start'         => '<td class = "layout-item-table-1">',
         'cell_end'           => '</td>',
         'cell_alt_start'     => '<td class = "layout-item-table-1">',
         'cell_alt_end'       => '</td>',
         'table_close'         => '</table>' );
      $this->table->set_template($template);
      //
      $attrib = array(  'name' => 'data_nama',
                        'value' => set_value('data_nama', $data_nama),
                        'class' => css_form_class(form_error('data_nama')),
                        'style' => 'width: 90%; max-width: 300px;',
                        'maxlength' => 128 );
      $this->table->add_row( '<div style = "width: 150px">'.'Mata Pelajaran'.'</div>', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_nama', '<br><font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: Matematika, IPA, dll.</font>' );
      //
      $attrib = array(  'name' => 'data_singkatan',
                        'value' => set_value('data_singkatan', $data_singkatan),
                        'class' => css_form_class(form_error('data_singkatan')),
                        'style' => 'width: 90%; max-width: 300px;',
                        'maxlength' => 128 );
      $this->table->add_row( '<div style = "width: 150px">'.'Singkatan'.'</div>', form_input($attrib).form_error('data_singkatan', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: MAT, IPA, dll.</font>' );
      //
      $attrib = array(  'name' => 'data_keterangan',
                        'value' => set_value('data_keterangan', $data_keterangan),
                        'class' => css_form_class(form_error('data_keterangan')),
                        'style' => 'width: 90%; max-width: 300px;',
                        'maxlength' => 255 );
      $this->table->add_row('Keterangan', form_textarea($attrib).form_error('data_keterangan', ' <font style="color: red">', '</font>') );
      //
      $this->table->add_row('', form_submit('save', $simpan, 'class = "button"').anchor($back, 'Kembali', 'class = "button"'));
      echo $this->table->generate();
      echo form_close();
   ?>
</div></div></div>
<?php $this->load->view(view('a', 'footer'));?>