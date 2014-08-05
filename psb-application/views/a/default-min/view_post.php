<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?>
<link rel="stylesheet" type="text/css" href="<?php echo view_external('tablesorter', 'themes/blue/style.css');?>">
<script type="text/javascript" src="<?php echo view_external('tablesorter', 'jquery.tablesorter.min.js')?>"></script>
<script type="text/javascript">$(document).ready(function(){$("#tsorter").tablesorter({widgets:["zebra"],headers:{2:{sorter:!1}}})});</script>
<link rel="stylesheet" type="text/css" href="<?php echo view_external('dhtmlx', 'dhtmlxMessage/codebase/skins/dhtmlxmessage_dhx_web.css');?>">
<script type="text/javascript" src="<?php echo view_external('dhtmlx', 'dhtmlxMessage/codebase/dhtmlxmessage.js');?>"></script>
<script type="text/javascript">function hapus(a,n){return dhtmlx.confirm({title:"Konfirmasi Hapus",ok:"Hapus",cancel:"Batal",text:'Hapus "'+n+'"?',callback:function(n){n&&(window.location=a)}}),!1}</script>
<?php $this->load->view(view('a', 'sidebar'));?><?php $this->load->view(view('a', 'notice'));?>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%" >
   <?php $template = array(
         'table_open'         => '<table id="tsorter" class="tablesorter" style="width: 100%;">',
         'cell_start'         => '',
         'cell_end'           => '',
         'cell_alt_start'     => '',
         'cell_alt_end'       => '',
         'table_close'         => '</table>');
      $this->table->set_template($template);
      echo '<p>'.anchor('a/post/add', 'Tambah', 'class = "button"').'</p>';
      $this->table->set_heading('Judul', 'Tanggal Dibuat', 'Aksi');
      foreach ($query as $row){
         $url = site_url('/').'a/post/delete/'.encode($row['post_id']).'/do';
         $data_judul = 'Post '.$row['post_judul'];
         $attrib_delete = array( 'onClick' => "return hapus('".$url."', '".str_replace(array('"', "'"), '`', $data_judul)."')",
            'class' => 'button',
            'style' => 'width: 50px; height: 20px; line-height: 20px; padding-left: 0px; padding-right: 0px;');
         $attrib_edit = array( 'class' => 'button',
            'style' => 'width: 50px; height: 20px; line-height: 20px; padding-left: 0px; padding-right: 0px;');
         $attrib_lihat = array('target'=>'_blank',
            'class' => 'button',
            'style' => 'width: 50px; height: 20px; line-height: 20px; padding-left: 0px; padding-right: 0px;');
         $delete = site_url('/').'a/post/delete/'.encode($row['post_id']);
         $edit = site_url('/').'a/post/edit/'.encode($row['post_id']);
         $lihat = site_url('/').'u/post/read/'.$row['post_link'];
         $this->table->add_row( '<td style="text-align: left;">'.$row['post_judul'].'</td>',
            '<td style="text-align: center;">'.date('d-m-Y', strtotime($row['post_tanggal'])).'</td>',
            '<td style="width: 165px; vertical-align: middle;">'.anchor($delete, 'Hapus', $attrib_delete).anchor($edit, 'Edit', $attrib_edit).anchor($lihat, 'Lihat', $attrib_lihat).'</td>'
         );
      }
      echo $this->table->generate();
   ?>
</div></div></div>
<?php $this->load->view(view('a', 'footer'));?>