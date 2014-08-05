<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?><?php $this->load->view(view('a', 'sidebar'));?><?php $this->load->view(view('a', 'notice'));?>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%" >
   <?php echo form_open($post);
      $template = array('table_open'         => '<table>',
         'cell_start'         => '<td class = "layout-item-table-1">',
         'cell_end'           => '</td>',
         'cell_alt_start'     => '<td class = "layout-item-table-1">',
         'cell_alt_end'       => '</td>',
         'table_close'         => '</table>');
      $this->table->set_template($template);
      //
      $attrib = array(  'name' => 'data_gel',
                        'value' => set_value($data_gel, $data_gel),
                        'class' => css_form_class(form_error('data_gel')),
                        'style' => 'width: 90%; max-width: 300px; text-align: center; font-weight: bold',
                        'disabled' => 'disabled');
      $this->table->add_row('<div style = "width: 150px">'.'Gelombang'.'</div>', form_input($attrib).form_error('data_gel', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Berdasarkan gelombang terakhir</font>');
      //
      if($do == 'edit'){
         $attrib = array(  'name' => 'data_jur',
                           'value' => set_value($data_jur, $data_jur),
                           'class' => css_form_class(form_error('data_jur')),
                           'style' => 'width: 90%; max-width: 300px; text-align: center; font-weight: bold',
                           'disabled' => 'disabled');
         $this->table->add_row('<div style = "width: 150px">'.'Gelombang'.'</div>',form_input($attrib).form_error('data_jur', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Berdasarkan gelombang terakhir</font>');
      }
      else{
         $drop_down = '<select name="data_jur" style = "width: 90%; max-width: 300px;">';
         foreach ($data_jur as $row){
            if ($row['jur_id'] == $pilih) $hasil = TRUE;
            else $hasil = FALSE;
            $drop_down = $drop_down .
            '<option value="'.encode($row['jur_id']).'" '. set_select('data_jur', $row['jur_id'], $hasil) . '>'.$row['jur_nama'].'</option>';
         }
         $drop_down = $drop_down.'</select>';
         $this->table->add_row('Jurusan', $drop_down.form_error('data_jur', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Jurusan yang sudah memiliki kuota tidak ditampilkan</font>');
      }
      //
      $attrib = array(  'name' => 'data_jumlah',
                        'value' => set_value('data_jumlah', $data_jumlah),
                        'class' => css_form_class(form_error('data_jumlah')),
                        'style' => 'width: 90%; max-width: 80px; text-align: center;',
                        'id' => 'calendar',
                        'maxlength' => 4);
      $this->table->add_row( 'Kuota Siswa', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_jumlah', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 60, 150, dll.</font>');
      //
      $attrib = array(  'name' => 'data_cadangan',
                        'value' => set_value('data_cadangan', $data_cadangan),
                        'class' => css_form_class(form_error('data_cadangan')),
                        'style' => 'width: 90%; max-width: 80px; text-align: center;',
                        'id' => 'calendar',
                        'maxlength' => 4);
      $this->table->add_row('Cadangan', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_cadangan', ' <font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: 10, 15, dll.</font>');
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