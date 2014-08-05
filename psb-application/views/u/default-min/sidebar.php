<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
	</head>
      <?php if(@$doOnLoad) { ?><body onload="doOnLoad();"><?php } else { ?><body><?php }?>
      <?php if(is_login()) { ?>
         <nav class="nav" style=" position: fixed; width: 100%;">
            <ul class="hmenu" style="float: left"><?php echo menu_horizontal('left_user');?></ul>
            <ul class="hmenu" style="float: right"><?php echo menu_horizontal('right');?></ul>
         </nav>
         <div id="navStyle" style="padding-top: 32px;"></div>
      <?php } ?>
<div id="main"><div class="sheet clearfix">
<header class="header">
   <div class="shapes"><div class="object1918266594" data-left="3%"></div></div>
   <h1 class="headline" data-left="24%"><?php echo anchor('u/home/index', setting('web_judul')); ?></h1>
   <h2 class="slogan" data-left="20%"><?php echo anchor('u/home/index', setting('sekolah_nama')); ?></h2>
</header>
<div style="border-bottom: 1px solid #ffffff;"></div><div class="layout-wrapper"><div class="content-layout"><div class="content-layout-row">
<div class="layout-cell sidebar1" id="sidebarStyle" style="height: 530px;"><div class="vmenublock clearfix"><div class="vmenublockcontent"><ul class="vmenu">
<?php echo menu_vertical($url)?>
</ul></div></div></div>
