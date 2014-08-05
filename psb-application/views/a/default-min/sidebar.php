<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
   </head>
      <?php if(@$doOnLoad) { ?><body onload="doOnLoad();"><?php } else { ?><body><?php }?>
         <nav class="nav" style="position: fixed; width: 100%;">
            <ul class="hmenu" style="float: left"><?php echo menu_horizontal('left');?></ul>
            <ul class="hmenu" style="float: right"><?php echo menu_horizontal('right');?></ul>
         </nav>
   		<div id="main"><div class="sheet clearfix">
         <div class="layout-wrapper" id="navStyle" style="padding-top: 32px;"><div class="content-layout"><div class="content-layout-row">
         <div class="layout-cell sidebar1" id="sidebarStyle" style="height: 650px;"><div class="vmenublock clearfix"><div class="vmenublockcontent"><ul class="vmenu">
         <?php echo menu_vertical($url);?></ul></div></div></div><div class="layout-cell content"><article class="post article"><div class="postmetadataheader"><h2 class="postheader">
         <?php echo $judul; ?></h2></div><div class="postcontent postcontent-0 clearfix">