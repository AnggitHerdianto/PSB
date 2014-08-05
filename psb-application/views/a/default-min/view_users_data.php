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
            $attrib = array(  'name' => 'data_username',
                              'value' => set_value('data_username', $data_username),
                              'class' => css_form_class(form_error('data_username')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 128);
            $this->table->add_row('<div style = "width: 150px">'.'Username'.'</div>', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_username', '<br><font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Hanya 0-9, a-z, A-Z, _, -</font>');
            //
            $attrib = array(  'name' => 'data_password',
                              'value' => '',
                              'class' => css_form_class(form_error('data_password')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 128,
                              'autocomplete' => 'off');
            $this->table->add_row('Password', form_password($attrib).'<font style="color: red;"> * </font>'.form_error('data_password', '<br><font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Minimal 5 karakter</font>');
            //
            $attrib = array(  'name' => 'data_passconf',
                              'value' => '',
                              'class' => css_form_class(form_error('data_passconf')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 128,
                              'autocomplete' => 'off');
            $this->table->add_row('', form_password($attrib).'<font style="color: red;"> * </font>'.form_error('data_passconf', '<br><font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Konfirmasi password</font>');
            //
            $attrib = array(  'name' => 'data_email',
                              'value' => set_value('data_email', $data_email),
                              'class' => css_form_class(form_error('data_email')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 100);
            $this->table->add_row('Email', form_input($attrib).'<font style="color: red;"> * </font>'.form_error('data_email', '<br><font style="color: red">', '</font>').'<br>'.'<font style="font-style: italic; color: #C1C1C1">Contoh: admin@domain.com, user@domain.com, dll.</font>');
            //////////////////////////////////////////////////////////////////////////
            $radio_admin = array( 'name' => 'data_pangkat',
                              'value' => encode('admin'),
                              'checked' => set_radio('data_pangkat', 'admin', $data_pangkat_admin),
                              'class' => '',
                              'style' => '');
            $radio_user = array( 'name' => 'data_pangkat',
                              'value' => encode('user'),
                              'checked' => set_radio('data_pangkat', 'user', $data_pangkat_user),
                              'class' => '',
                              'style' => '');
            if(is_admin())
               $this->table->add_row('Jabatan', form_radio($radio_admin).'Super Admin'.form_radio($radio_user).'Admin'.form_error('data_pangkat', ' <font style="color: red">', '</font>'));
            //////////////////////////////////////////////////////////////////////////
            $attrib = array(  'name' => 'data_nama',
                              'value' => set_value('data_nama', $data_nama),
                              'class' => css_form_class(form_error('data_nama')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 128);
            $this->table->add_row('Nama', form_input($attrib).form_error('data_tanggal', ' <font style="color: red">', '</font>'));
            //
            $attrib = array(  'name' => 'data_keterangan',
                              'value' => set_value('data_keterangan', $data_keterangan),
                              'class' => css_form_class(form_error('data_keterangan')),
                              'style' => 'width: 90%; max-width: 300px;',
                              'maxlength' => 255);
            $this->table->add_row('Keterangan', form_textarea($attrib).form_error('data_keterangan', ' <font style="color: red">', '</font>'));
            //////////////////////////////////////////////////////////////////////////
            $submit = form_submit('update', $simpan, 'class = "button"');
            if($url[2] == 'profile') $back = '';
            else $back = anchor($back, 'Kembali', 'class = "button"');
            //////////////////////////////////////////////////////////////////////////
            $this->table->add_row('', $submit.$back);
            echo $this->table->generate();
            echo form_close();
         ?>
</div></div></div>
<?php $this->load->view(view('a', 'footer'));?>