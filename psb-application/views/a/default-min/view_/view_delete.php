<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?><?php $this->load->view(view('a', 'sidebar'));?><?php $this->load->view(view('a', 'notice'));?>
<div class="content-layout-wrapper layout-item-1"><div class="content-layout layout-item-2"><div class="content-layout-row">
   <div class="layout-cell layout-item-4" style="width: 50%" ><p><?php echo $msg; ?></p></div>
   <div class="layout-cell layout-item-0" style="width: 50%" ></div>
</div></div></div>
<div class="content-layout"><div class="content-layout-row">
   <div class="layout-cell layout-item-0" style="width: 30%" ></div>
   <div class="layout-cell layout-item-0" style="width: 20%" ><?php echo anchor($get, 'Hapus', 'class = "button"'); echo anchor($back, 'Back', 'class = "button"');?></div>
   <div class="layout-cell layout-item-0" style="width: 50%" ></div>
</div></div>
<?php $this->load->view(view('a', 'footer'));?>