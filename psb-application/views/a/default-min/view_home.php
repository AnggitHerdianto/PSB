<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view(view('a', 'header'));?>
<script src="<?php echo view_external('highcharts', 'js/highcharts.js'); ?>"></script>
<script src="<?php echo view_external('highcharts', 'js/modules/exporting.js'); ?>"></script>
<script type="text/javascript">
   $(function () {
      $('#pie_container').highcharts({
            credits: {enabled: false},
            exporting: {enabled: false},
            chart: {plotBackgroundColor: null, plotBorderWidth: null, plotShadow: false},
            title: {text: ''},
            tooltip: {pointFormat: '{series.name}: <b>{point.percentage:.2f} %</b>'},
            plotOptions: {
                pie: {
                  allowPointSelect: true, cursor: 'pointer',
                  dataLabels: { enabled: true, color: '#000000', connectorColor: '#000000', format: '<b>{point.name}</b><br>{point.y} <?php echo $pie_satuan;?>'},
                  showInLegend: true
                }
           },
           series: [{
               type: 'pie', name: '<?php echo $pie_series;?>',
               data: [
                    <?php foreach ($pie_pilihan_status as $key => $row){
                         if($key == 0){
                           echo "{ name: '".$row['pilihan_status']."',";
                           echo "y: ".$row['jumlah'].",";
                           echo "sliced: true,";
                           echo "selected: true,";
                           echo "color: '#910000' }";
                         }
                         else{ 
                            echo ",";
                            echo "['".$row['pilihan_status']."',".$row['jumlah']." ]";
                         }
                      }
                   ?>
               ]
           }]
       });
   });
</script>
<?php $this->load->view(view('a', 'sidebar'));?>
<div class="content-layout-wrapper layout-item-1">
   <div class="content-layout layout-item-2">
      <div class="content-layout-row">
         <div class="layout-cell" style="width: 50%" >
            <?php if(count($cek_gel_ta_max) > 0) { ?>
            <div style="width: 95%; margin-top: 5px; margin-bottom: 5px;
            border-top: 1px dotted #7993AF; border-bottom: 1px dotted #7993AF; border-left: 1px dotted #7993AF; border-right: 1px dotted #7993AF;
            padding-top: 7px; padding-bottom: 7px; padding-left: 7px; padding-right: 7px;">
                <?php echo '<b>Gelombang Tahun '.$cek_gel_ta_max[0]['gel_ta'].' :</b>';
                  echo '<ul>';
                  foreach ($cek_gel_ta_max as $key=>$row){
                     if(is_admin()) $link = ' - '.anchor('a/gelombang/edit/'.encode($row['gel_id']), 'Edit');
                     else $link = '';
                     echo '<li>'.$row['gel_nama'].$link.'</li>';
                  }
                  echo '</ul>';
                  if(is_admin()) echo anchor('a/gelombang/add', 'Tambah').' | '.anchor('a/gelombang/index', 'Selengkapnya');
               ?>
            </div>
            <?php } ?>
            <?php if(count($cek_kuota_gel_max) > 0) {?>
            <div style="width: 95%; margin-top: 5px; margin-bottom: 5px;
            border-top: 1px dotted #7993AF; border-bottom: 1px dotted #7993AF; border-left: 1px dotted #7993AF; border-right: 1px dotted #7993AF;
            padding-top: 7px; padding-bottom: 7px; padding-left: 7px; padding-right: 7px;">
               <?php echo '<b>Kuota Gelombang Berjalan ('.$cek_kuota_gel_max[0]['gel_ta'].' - '.$cek_kuota_gel_max[0]['gel_nama'].') : </b>';
                  echo '<ul>';
                  foreach ($cek_kuota_gel_max as $key=>$row){
                     if(is_admin()) $link = ' - '.anchor('a/kuota/edit/'.encode($row['kuota_id']), 'Edit');
                     else $link = '';
                     echo '<li>'.$row['jur_nama'].$link;
                     echo '<ul>'.'<li>Kuota : <font style="color: green;">'.$row['kuota_jumlah'].'</font></li>'.'<li>Cadangan : <font style="color: red;">'.$row['kuota_cadangan'].'</font></li>'.'</ul>';
                     echo '</li>';
                  }
                  echo '</ul>';
                  if(is_admin()) echo anchor('a/kuota/add', 'Tambah').' | '.anchor('a/kuota/index', 'Selengkapnya');
               ?>
            </div>
            <?php } ?>
            <?php if(count($cek_jur) > 0) { ?>
            <div style="width: 95%; margin-top: 5px; margin-bottom: 5px;
            border-top: 1px dotted #7993AF; border-bottom: 1px dotted #7993AF; border-left: 1px dotted #7993AF; border-right: 1px dotted #7993AF;
            padding-top: 7px; padding-bottom: 7px; padding-left: 7px; padding-right: 7px;">
               <?php echo '<b>Jurusan :</b>';
                  echo '<br>';
                  foreach($cek_jur as $key=>$row){
                     if($key == 0) $batas = '';
                     else $batas = ', ';
                     echo $batas.$row['jur_nama'];
                  }
                  if(is_admin()){
                     echo '<br>';
                     echo anchor('a/jurusan/add', 'Tambah').' | '.anchor('a/jurusan/index', 'Selengkapnya');
                  }
               ?>
            </div>
            <?php } ?>
            <?php if(count($cek_mapel) > 0) { ?>
            <div style="width: 95%; margin-top: 5px; margin-bottom: 5px;
            border-top: 1px dotted #7993AF; border-bottom: 1px dotted #7993AF; border-left: 1px dotted #7993AF; border-right: 1px dotted #7993AF;
            padding-top: 7px; padding-bottom: 7px; padding-left: 7px; padding-right: 7px;">
               <?php echo '<b>Mata Pelajaran :</b>';
                  echo '<br>';
                  foreach($cek_mapel as $key=>$row){
                     if($key == 0) $batas = '';
                     else $batas = ', ';
                     echo $batas.$row['mapel_nama'];
                  }
                  if(is_admin()){
                     echo '<br>';
                     echo anchor('a/mapel/add', 'Tambah').' | '.anchor('a/mapel/index', 'Selengkapnya');
                  }
               ?>
            </div>
            <?php } ?>
         </div>
         <div class="layout-cell" style="width: 50%" >
            <?php if(count($pie_pilihan_status) > 1) { ?>
              <div style="width: 95%; margin-top: 5px; margin-bottom: 5px;
                border-top: 1px dotted #7993AF; border-bottom: 1px dotted #7993AF; border-left: 1px dotted #7993AF; border-right: 1px dotted #7993AF;
                padding-top: 7px; padding-bottom: 7px; padding-left: 7px; padding-right: 7px;">
                 <?php echo $pie_judul; ?><div id="pie_container"></div><?php echo anchor('a/stat/hasil_tahun_gelombang', 'Selengkapnya');?>
              </div>
            <?php } if(is_admin()) {?>
              <div style="width: 95%; margin-top: 5px; margin-bottom: 5px;
              border-top: 1px dotted #7993AF; border-bottom: 1px dotted #7993AF; border-left: 1px dotted #7993AF; border-right: 1px dotted #7993AF;
              padding-top: 7px; padding-bottom: 7px; padding-left: 7px; padding-right: 7px;">
                 <?php echo '<b>Pengguna :</b>';
                    echo '<br>';
                    foreach($cek_users as $key=>$row) {
                       if($key == 0) $batas = '';
                       else $batas = ', ';
                       if($this->encrypt->decode($this->session->userdata('user_id')) == $row['user_id']) echo $batas.'<b><font style="color: green;">'.$row['user_username'].'</font></b>';
                       else echo $batas.$row['user_username'];
                    }
                    echo '<br>';
                    echo anchor('a/users/add', 'Tambah').' | '.anchor('a/users/index', 'Selengkapnya');
                 ?>
              </div>
            <?php } ?>
         </div>
      </div>
   </div>
</div>
<?php $this->load->view(view('a', 'footer'));?>