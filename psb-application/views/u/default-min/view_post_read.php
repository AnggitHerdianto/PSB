<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('u', 'header')); ?><?php $this->load->view(view('u', 'sidebar')); ?>
<div class="layout-cell content"><article class="post article">
<div class="postmetadataheader"><h2 class="postheader"><?php echo $judul; ?></h2></div>
<div class="postcontent postcontent-0 clearfix">
   <?php $this->load->view(view('u', 'notice')); ?>
      <div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%;" >
         <label style="color: #FA681E; float: left; padding-top: 0px; padding-bottom: 0px; margin-top: 0px; margin-bottom: 0px;">
            <?php echo 'Ditulis tanggal : '.$post_tanggal.' '.$post_edit?>
         </label><br>
            <?php echo $post_isi; ?>
      </div></div></div>
</div></article></div>
<?php $this->load->view(view('u', 'footer')); ?>