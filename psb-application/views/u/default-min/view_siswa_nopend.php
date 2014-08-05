<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('u', 'header')); ?><?php $this->load->view(view('u', 'sidebar')); ?>
<div class="layout-cell content"><article class="post article">
<div class="postmetadataheader"><h2 class="postheader"><?php echo $judul; ?></h2></div>
<div class="postcontent postcontent-0 clearfix">
   <?php $this->load->view(view('u', 'notice')); ?>
      <div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%;" >
         <?php
            $template = array( 'table_open'         => '<table>',
               'cell_start'         => '<td class = "layout-item-table-1">', // class = "layout-item-table-1"
               'cell_end'           => '</td>',
               'cell_alt_start'     => '<td class = "layout-item-table-1">',
               'cell_alt_end'       => '</td>',
               'table_close'         => '</table>');
            $this->table->set_template($template);
            $this->table->add_row('<font style="font-size: 24pt">No. Pend.</font>', '<font style="font-size: 24pt"> : </font>', '<font style="font-size: 24pt">'.$siswa_no_pendaftaran.'</font>');
            $this->table->add_row('<font style="font-size: 24pt">Nama</font>', '<font style="font-size: 24pt"> : </font>', '<font style="font-size: 24pt">'.$siswa_nama.'</font>');
            $this->table->add_row('<font style="font-size: 24pt">Tanggal Lahir</font>', '<font style="font-size: 24pt"> : </font>', '<font style="font-size: 24pt">'.$siswa_tanggal_lahir.'</font>');
            echo $this->table->generate();
            echo anchor($back, 'Daftar Lagi', 'class = "button" onclick = "return confirm ('."'Apa Nomer Pendaftaran sudah disimpan?'".')"');
         ?>
      </div></div></div>
</div></article></div>
<?php $this->load->view(view('u', 'footer')); ?>