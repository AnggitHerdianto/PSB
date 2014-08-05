<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if($notice[0] == 'red') {?>
<div class="content-layout-wrapper layout-item-0"><div class="content-layout layout-item-1"><div class="content-layout-row">
<div class="layout-cell layout-item-3" style="width: 100%" ><div style="text-align: center"><?php echo $notice[1]; ?></div></div>
</div></div></div>
<?php }?>
<?php if($notice[0] == 'green') {?>
<div class="content-layout-wrapper layout-item-0"><div class="content-layout layout-item-1"><div class="content-layout-row">
<div class="layout-cell layout-item-5" style="width: 100%" ><div style="text-align: center"><?php echo $notice[1]; ?></div></div>
</div></div></div>
<?php }?>
<?php if($notice[0] == 'yellow') {?>
<div class="content-layout-wrapper layout-item-0"><div class="content-layout layout-item-1"><div class="content-layout-row">
<div class="layout-cell layout-item-2" style="width: 100%" ><div style="text-align: center"><?php echo $notice[1]; ?></div></div>
</div></div></div>
<?php }?>