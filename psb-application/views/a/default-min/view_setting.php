<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?><?php $this->load->view(view('a', 'sidebar'));?><?php $this->load->view(view('a', 'notice'));?>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%" >
   <?php echo form_open($post);
      $template = array( 'table_open'         => '<table>',
         'cell_start'         => '<td class = "layout-item-table-1">',
         'cell_end'           => '</td>',
         'cell_alt_start'     => '<td class = "layout-item-table-1">',
         'cell_alt_end'       => '</td>',
         'table_close'         => '</table>' );
      $this->table->set_template($template);
      //
      $attrib = array(  'name' => 'web_judul',
                        'value' => set_value('web_judul', $web_judul),
                        'class' => css_form_class(form_error('web_judul')),
                        'style' => 'width: 90%; max-width: 300px;',
                        'maxlength' => 255 );
      $this->table->add_row('<div style = "width: 150px">'.'Judul Web'.'</div>', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('web_judul', '<br><font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: Penerimaan Siswa Baru</font>' );
      //
      $attrib = array(  'name' => 'sekolah_nama',
                        'value' => set_value('sekolah_nama', $sekolah_nama),
                        'class' => css_form_class(form_error('sekolah_nama')),
                        'style' => 'width: 90%; max-width: 300px;',
                        'maxlength' => 255);
      $this->table->add_row('<div style = "width: 150px">'.'Nama Sekolah'.'</div>', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('sekolah_nama', '<br><font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: SMK 1, SMK 2, dll.</font>');
      //
      $attrib = array(  'name' => 'sekolah_telpon',
                        'value' => set_value('sekolah_telpon', $sekolah_telpon),
                        'class' => css_form_class(form_error('sekolah_telpon')),
                        'style' => 'width: 90%; max-width: 300px;',
                        'maxlength' => 255);
      $this->table->add_row('<div style = "width: 150px">'.'No. Telpon'.'</div>', form_input($attrib).form_error('sekolah_telpon', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: (0274)123456, 123456, dll.</font>');
      //
      $attrib = array( 'name' => 'sekolah_email',
               'value' => set_value('sekolah_email', $sekolah_email),
               'class' => css_form_class(form_error('sekolah_email')),
               'style' => 'width: 90%; max-width: 300px;',
               'maxlength' => 255);
      $this->table->add_row('<div style = "width: 150px">'.'Email'.'</div>', form_input($attrib).form_error('sekolah_email', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: smk@gmail.com, smk@yahoo.com, dll.</font>');
      //
      $attrib = array(  'name' => 'sekolah_alamat',
                        'value' => set_value('sekolah_alamat', $sekolah_alamat),
                        'class' => css_form_class(form_error('sekolah_alamat')),
                        'style' => 'width: 90%; max-width: 300px;',
                        'maxlength' => 255 );
      $this->table->add_row('<div style = "width: 150px">'.'Alamat Sekolah'.'</div>', form_textarea($attrib).form_error('sekolah_alamat', ' <font style="color: red">', '</font>'));
      //
      $this->table->add_row('', form_submit('save', $simpan, 'class = "button"'));
      echo $this->table->generate();
      echo form_close();
   ?>
</div></div></div>
<?php $this->load->view(view('a', 'footer'));?>