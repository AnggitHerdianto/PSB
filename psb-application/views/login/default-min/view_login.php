<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('login', 'header')); ?><?php $this->load->view(view('login', 'notice')); ?>
<div class="content-layout-wrapper layout-item-0"><div class="content-layout layout-item-1"><div class="content-layout-row"><div class="layout-cell layout-item-4" style="width: 100%" >
            <?php echo form_open($post);
               //
               $template = array( 'table_open'         => '<table style="width: 100%;">',
                  'cell_start'         => '<td class = "layout-item-table-1">', //layout-item-table-1
                  'cell_end'           => '</td>',
                  'cell_alt_start'     => '<td class = "layout-item-table-1">',
                  'cell_alt_end'       => '</td>',
                  'table_close'         => '</table>');
               $this->table->set_template($template);
               //
               $data['attrib'] = array('name' => 'data_username',
                                 'value' => set_value('data_username'),
                                 'class' => css_form_class(form_error('data_username')),
                                 'style' => '');
               $data['form'] = form_input($data['attrib']);
               $this->table->add_row('Username', $data['form']);
               //
               $data['attrib'] = array('name' => 'data_password',
                                 'value' => '',
                                 'class' => css_form_class(form_error('data_password')),
                                 'style' => '',
                                 'maxlength' => 128,
                                 'autocomplete' => 'off');
               $data['form'] = form_password($data['attrib']);
               $this->table->add_row('Password', $data['form']);
               //
               $data['attrib'] = array('name' => 'data_capcay',
                                 'value' => '',
                                 'class' => css_form_class(form_error('data_capcay')),
                                 'style' => '',
                                 'maxlength' => 8,
                                 'style' => 'max-width: 80px;',
                                 'autocomplete' => 'off');
               $data['form'] = form_input($data['attrib']);
               $this->table->add_row('', '<div style="float: left; padding-left: 0px">'.$capcay.'</div><div style="float: left; padding-top:5px">'.$data['form'].'</div>');
               //
               $data['attrib'] = array('name' => 'login',
                               'value' => 'Login',
                               'class' => 'button',
                               'style' => '');
               $data['form'] = form_submit($data['attrib']);
               $this->table->add_row('', array('style' => 'text-align: left', 'data' => $data['form']));
               //
               $this->table->add_row('', anchor('', 'Halaman Siswa').' | '.anchor('a/login/forgot', 'Lupa Password'));
               echo $this->table->generate();
               echo form_close();
            ?>
</div></div></div></div>
<?php $this->load->view(view('login', 'footer')); ?>