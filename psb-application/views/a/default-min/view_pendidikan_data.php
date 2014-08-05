<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?><?php $this->load->view(view('a', 'sidebar'));?><?php $this->load->view(view('a', 'notice'));?>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%" >
   <?php echo form_open($post);
      $template = array( 'table_open'         => '<table>',
         'cell_start'         => '<td class = "layout-item-table-1">',
         'cell_end'           => '</td>',
         'cell_alt_start'     => '<td class = "layout-item-table-1">',
         'cell_alt_end'       => '</td>',
         'table_close'         => '</table>');
      $this->table->set_template($template);
      //
      $attrib = array(  'name' => 'data_nama',
                        'value' => set_value('data_nama', $data_nama),
                        'class' => css_form_class(form_error('data_nama')),
                        'style' => 'width: 90%; max-width: 300px;',
                        'maxlength' => 64 );
      $this->table->add_row('<div style = "width: 150px">'.'Pendidikan'.'</div>', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_nama', '<br><font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: SD, SMP, dll.</font>');
      //
      $attrib = array(  'name' => 'data_keterangan',
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
<?php $this->load->view(view('a', 'footer'));?>