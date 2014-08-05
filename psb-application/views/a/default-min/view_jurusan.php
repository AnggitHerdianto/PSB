<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?>
<link rel="stylesheet" href="<?php echo view_external('tablesorter', 'themes/blue/style.css');?>">
<script type="text/javascript" src="<?php echo view_external('tablesorter', 'jquery.tablesorter.min.js')?>"></script>
<script type="text/javascript">$(document).ready(function(){$("#tsorter").tablesorter({widgets:["zebra"],headers:{5:{sorter:!1}}})});</script>
<link rel="stylesheet" type="text/css" href="<?php echo view_external('dhtmlx', 'dhtmlxMessage/codebase/skins/dhtmlxmessage_dhx_web.css');?>">
<script type="text/javascript" src="<?php echo view_external('dhtmlx', 'dhtmlxMessage/codebase/dhtmlxmessage.js');?>"></script>
<script type="text/javascript">function hapus(a,n){return dhtmlx.confirm({title:"Konfirmasi Hapus",ok:"Hapus",cancel:"Batal",text:'Hapus "'+n+'"?',callback:function(n){n&&(window.location=a)}}),!1}</script>
<?php $this->load->view(view('a', 'sidebar'));?><?php $this->load->view(view('a', 'notice'));?>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%" >
    <?php $template = array('table_open'         => '<table id="tsorter" class="tablesorter" style="width: 100%;">',
         'cell_start'         => '',
         'cell_end'           => '',
         'cell_alt_start'     => '',
         'cell_alt_end'       => '',
         'table_close'         => '</table>');
      $this->table->set_template($template);
      echo '<p>'.anchor('a/jurusan/add', 'Tambah', 'class = "button"').'</p>';
      $this->table->set_heading('Jurusan', 'Singkatan', 'Nomor SK', 'Tgl. Input', 'Keterangan', 'Aksi');
      foreach ($query as $row){
          $url = site_url('/').'a/jurusan/delete/'.encode($row['jur_id']).'/do';
          $data_nama = 'Jurusan '.$row['jur_nama'];
          $attrib_delete = array('onClick' => "return hapus('".$url."', '".str_replace(array('"', "'"), '`', $data_nama)."')",
            'class' => 'button',
            'style' => 'width: 50px; height: 20px; line-height: 20px; padding-left: 0px; padding-right: 0px;');
          $attrib_edit = array( 'class' => 'button',
             'style' => 'width: 50px; height: 20px; line-height: 20px; padding-left: 0px; padding-right: 0px;');
          $delete = site_url('/').'a/jurusan/delete/'.encode($row['jur_id']);
          $edit = site_url('/').'a/jurusan/edit/'.encode($row['jur_id']);
          $this->table->add_row( '<td style="text-align: left;">'.$row['jur_nama'].'</td>',
            '<td style="text-align: center;">'.$row['jur_singkatan'].'</td>',
            '<td style="text-align: center;">'.$row['jur_no_sk'].'</td>',
            '<td style="text-align: center;">'.date('d-m-Y', strtotime($row['jur_tanggal'])).'</td>',
            '<td style="text-align: left;">'.$row['jur_keterangan'].'</td>',
            '<td style="width: 110px; vertical-align: middle;">'.anchor($delete, 'Hapus', $attrib_delete).anchor($edit, 'Edit', $attrib_edit).'</td>');
      }
      echo $this->table->generate();
   ?>
</div></div></div>
<?php $this->load->view(view('a', 'footer'));?>