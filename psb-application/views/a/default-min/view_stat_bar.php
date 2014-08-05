<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?>
<script src="<?php echo view_external('highcharts', 'js/highcharts.js'); ?>"></script>
<script src="<?php echo view_external('highcharts', 'js/modules/exporting.js'); ?>"></script>
<script type="text/javascript">
   $(function () {
      $('#container').highcharts({
         credits: {enabled: false},
         chart: { type: 'bar'},
         title: {text: '<?php echo $judul;?>'},
         subtitle: {text: '<?php echo $sub_judul;?>'},
         xAxis: { categories: [<?php foreach ($x_axis as $key => $row) {
               if($key == 0) echo "";
               else echo ",";
               echo $row; }?>]
         },
         yAxis: {min: 0, title: {text: 'Jumlah (<?php echo $satuan; ?>)'}},
         tooltip: { headerFormat: '<span style="font-size:11px">{point.key}</span><table>',
            pointFormat:'<tr><td style="color:{series.color}; padding:0;" class="layout-item-table-1">{series.name} : </td>'+'<td style="padding:0" class="layout-item-table-1"><b>{point.y} <?php echo $satuan; ?></b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
         },
         series: [<?php foreach ($cek_jumlah as $key => $row){
                  if($key == 0) echo "";
                  else echo ",";
                  echo "{ name: '".$row['nama']."',";
                  echo "data: [".rtrim($row['jumlah'], ",")."] }";
         }?>]
      });
   });
</script>
<?php $this->load->view(view('a', 'sidebar'));?><?php $this->load->view(view('a', 'notice'));?>
<div class="content-layout"><div class="content-layout-row"><div class="layout-cell layout-item-0" style="width: 100%" >
<?php echo $link;?><div id="container" style="min-width: 300px; min-height: 480px; margin: 1 solid black;"></div></div></div></div>
<?php $this->load->view(view('a', 'footer'));?>