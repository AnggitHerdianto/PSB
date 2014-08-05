<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('u', 'header')); ?><?php $this->load->view(view('u', 'sidebar')); ?>
<div class="layout-cell content"><article class="post article">
<div class="postmetadataheader"><h2 class="postheader"><?php echo $judul; ?></h2></div>
<div class="postcontent postcontent-0 clearfix">
<?php $this->load->view(view('u', 'notice')); ?>
</div></article></div>
<?php $this->load->view(view('u', 'footer')); ?>