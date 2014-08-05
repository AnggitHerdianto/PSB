<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?>
<link rel="stylesheet" type="text/css" href="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/dhtmlxcalendar.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/skins/dhtmlxcalendar_dhx_web.css') ?>">
<script src="<?php echo view_external('dhtmlx', 'dhtmlxCalendar/codebase/dhtmlxcalendar.js') ?>"></script>
<script>function doOnLoad(){var e=new dhtmlXCalendarObject(["calendar_start","calendar_end"]);e.setSkin("dhx_web"),e.hideTime(),e.setDateFormat("%d-%m-%Y"),e.setPosition("right")}</script>
<script type="text/javascript" src="<?php echo view_external('tinymce', 'js/tinymce/tinymce.min.js') ?>"></script>
<script type="text/javascript">tinymce.init({selector:"textarea#tinymce",theme:"modern",width:"98%",height:300,plugins:["advlist autolink link image lists charmap print hr anchor pagebreak","searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking","save table contextmenu directionality emoticons template paste textcolor"],content_css:"css/content.css",toolbar:"insertfile undo redo | styleselect | bold underline italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor emoticons | fullscreen",style_formats:[{title:"Bold text",inline:"b"},{title:"Red text",inline:"span",styles:{color:"#ff0000"}},{title:"Red header",block:"h1",styles:{color:"#ff0000"}},{title:"Example 1",inline:"span",classes:"example1"},{title:"Example 2",inline:"span",classes:"example2"},{title:"Table styles"},{title:"Table row 1",selector:"tr",classes:"tablerow1"}]});</script>
<?php $this->load->view(view('a', 'sidebar'));?><?php $this->load->view(view('a', 'notice'));?>
<?php echo form_open($post);?>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%" >
   <?php $template = array( 'table_open'         => '<table style="width:100%">',
         'cell_start'         => '<td class = "layout-item-table-1">',
         'cell_end'           => '</td>',
         'cell_alt_start'     => '<td class = "layout-item-table-1">',
         'cell_alt_end'       => '</td>',
         'table_close'         => '</table>');
      $this->table->set_template($template);
      //
      $attrib = array(  'name' => 'data_judul',
                        'value' => set_value('data_judul', $data_judul),
                        'class' => css_form_class(form_error('data_judul')),
                        'style' => 'width: 98%; height: 25px; font-size: 25px;');
      $this->table->add_row(form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_judul', '<br><font style="color: red">', '</font>') );
      //
      $attrib = array(  'name' => 'data_isi',
                        'value' => set_value('data_isi', $data_isi),
                        'class' => css_form_class(form_error('data_isi')),
                        'style' => 'width: 98%; height: 380px;',
                        'id' => 'tinymce');
      $this->table->add_row('<br>'.form_textarea($attrib).form_error('data_isi', ' <font style="color: red">', '</font>'));
      //
      $attrib = array(  'name' => 'data_tanggal',
                        'value' => set_value('data_tanggal', $data_tanggal),
                        'class' => css_form_class(form_error('data_tanggal')),
                        'style' => 'width: 98%; max-width: 110px; text-align: center;',
                        'id' => 'calendar_start',
                        'maxlength' => 10);
      $this->table->add_row('Tanggal Input : '.form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_tanggal', ' <font style="color: red">', '</font>'));
      //
      $this->table->add_row(form_submit('save', $simpan, 'class = "button"').anchor($back, 'Kembali', 'class = "button"'));
      echo $this->table->generate();
   ?>
</div></div></div>
<?php echo form_close();?>
<?php $this->load->view(view('a', 'footer'));?>