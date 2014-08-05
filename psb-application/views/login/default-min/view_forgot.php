<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('login', 'header')); ?><?php $this->load->view(view('login', 'notice')); ?>
<div class="content-layout-wrapper layout-item-0"><div class="content-layout layout-item-1"><div class="content-layout-row"><div class="layout-cell layout-item-4" style="width: 100%" >
            <?php echo form_open($post);
               //
               $template = array( 'table_open'         => '<table style="width: 100%;">',
                  'cell_start'         => '<td class = "layout-item-table-1">',
                  'cell_end'           => '</td>',
                  'cell_alt_start'     => '<td class = "layout-item-table-1">',
                  'cell_alt_end'       => '</td>',
                  'table_close'         => '</table>');
               $this->table->set_template($template);
               //
               $data['attrib'] = array( 'name' => 'data_username_email',
                                 'value' => set_value('data_username_email'),
                                 'class' => css_form_class(form_error('data_username_email')),
                                 'style' => '');
               $data['form'] = form_input($data['attrib']);
               $this->table->add_row('Username', $data['form']);
               //
               $data['attrib'] = array( 'name' => 'reset',
                               'value' => 'Reset',
                               'class' => 'button',
                               'style' => '');
               $data['form'] = form_submit($data['attrib']);
               $this->table->add_row('', $data['form']);
               //
               $this->table->add_row('', anchor('', 'Halaman Siswa').' | '.anchor('a/login/index', 'Halaman Login'));
               echo $this->table->generate();
               echo form_close();
            ?>
</div></div></div></div>
<?php $this->load->view(view('login', 'footer')); ?>