<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('login', 'header')); ?><?php $this->load->view(view('login', 'notice')); ?>
<div class="content-layout-wrapper layout-item-0"><div class="content-layout layout-item-1"><div class="content-layout-row"><div class="layout-cell layout-item-4" style="width: 100%" >
            <?php if($link){
                  echo form_open($post);
                  //
                  $template = array('table_open'         => '<table style="width: 100%;">',
                     'cell_start'         => '<td class = "layout-item-table-1">',
                     'cell_end'           => '</td>',
                     'cell_alt_start'     => '<td class = "layout-item-table-1">',
                     'cell_alt_end'       => '</td>',
                     'table_close'         => '</table>');
                  $this->table->set_template($template);
                  //
                  $data['attrib'] = array('name' => 'data_password',
                                    'value' => set_value('data_password'),
                                    'class' => css_form_class(form_error('data_password')),
                                    'style' => '',
                                    'maxlength' => 128,
                                    'autocomplete' => 'off');
                  $data['form'] = form_password($data['attrib']);
                  $this->table->add_row('Password', $data['form']);
                  //
                  $data['attrib'] = array('name' => 'data_passconf',
                                    'value' => set_value('data_passconf'),
                                    'class' => css_form_class(form_error('data_passconf')),
                                    'style' => '',
                                    'maxlength' => 128,
                                    'autocomplete' => 'off');
                  $data['form'] = form_password($data['attrib']);
                  $this->table->add_row('Password Lagi', $data['form']);
                  $data['attrib'] = array( 'name' => 'reset',
                                  'value' => 'Update',
                                  'class' => 'button',
                                  'style' => '');
                  $data['form'] = form_submit($data['attrib']);
                  $this->table->add_row('', $data['form']);
                  echo $this->table->generate();
                  echo form_close();
               }
               else echo '<br> <font color="red">Link expired</font> <br> <br>';
            ?>
</div></div></div></div>
<?php $this->load->view(view('login', 'footer')); ?>