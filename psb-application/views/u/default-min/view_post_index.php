<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('u', 'header')); ?><?php $this->load->view(view('u', 'sidebar')); ?>
<div class="layout-cell content"><article class="post article"><div class="postcontent postcontent-0 clearfix">
   <?php $this->load->view(view('u', 'notice')); ?><?php foreach ($post as $key=>$row) {?>
       <div class="content-layout" ><div class="content-layout-row" ><div class="layout-cell layout-item-0" style="width: 100%">
            <h2><?php echo anchor($row['post_link'], $row['post_judul'], 'style="font-size:18px;"');?></h2>
            <label style="color: #FA681E; padding-top: 0px; padding-bottom: 0px; margin-top: 0px; margin-bottom: 0px;">
               <?php echo 'Ditulis tanggal : '.$row['post_tanggal'].' '.$row['post_edit']?>
            </label>
            <p style="text-align: justify;"><?php echo $row['post_isi'].' '.anchor($row['post_link'], '(Selengkapnya)');?></p>
      </div></div></div>
   <?php }?>
</div></article></div>
<?php $this->load->view(view('u', 'footer')); ?>