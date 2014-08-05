<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Export{
   function to_pdf($report, $query, $file, $judul, $cek_mapel_jml, $cek_jentest){
      require_once APPPATH.'third_party/fpdf/fpdf.php';
      $fpdf = new FPDF();
      if($report == 'index'){
         $data_1 = array(array('p'=>'20', 'kolom'=>'No Pendaftaran', 'alias'=>'No Pend', 'align'=>'C'),
            array('p'=>'80', 'kolom'=>'Nama Lengkap', 'alias'=>'Nama', 'align'=>'L'),
            array('p'=>'8', 'kolom'=>'Jenis Kelamin', 'alias'=>'JK', 'align'=>'C')
         );
         foreach ($cek_mapel_jml as $key=>$row) $data_2[$key] = array('p'=>'15', 'kolom'=>$row['mapel_nama'], 'alias'=>$row['mapel_singkatan'], 'align'=>'C');
         $data_2_2 = array(array('p'=>'25', 'kolom'=>'Prestasi', 'alias'=>'Prestasi', 'align'=>'C'));
         foreach ($cek_jentest as $key=>$row) $data_3[$key] = array('p'=>'15', 'kolom'=>$row['jentest_nama'], 'alias'=>$row['jentest_singkatan'], 'align'=>'C');
         $data_4 = array(array('p'=>'30', 'kolom'=>'Pilihan', 'alias'=>'Pilihan', 'align'=>'C'),
            array('p'=>'25', 'kolom'=>'Siswa Status', 'alias'=>'Status', 'align'=>'C'),
         );
         $data = array_merge($data_1, $data_2, $data_2_2, $data_3, $data_4);
      }
      elseif($report == 'hasil'){
         $data_1 = array(array('p'=>'20', 'kolom'=>'No Pendaftaran', 'alias'=>'No Pend', 'align'=>'C'),
            array('p'=>'90', 'kolom'=>'Nama Lengkap', 'alias'=>'Nama', 'align'=>'L'),
            array('p'=>'8', 'kolom'=>'Jenis Kelamin', 'alias'=>'JK', 'align'=>'C')
         );
         foreach ($cek_mapel_jml as $key=>$row) $data_2[$key] = array('p'=>'15', 'kolom'=>$row['mapel_nama'], 'alias'=>$row['mapel_singkatan'], 'align'=>'C');
         $data_2_2 = array(array('p'=>'25', 'kolom'=>'Prestasi', 'alias'=>'Prestasi', 'align'=>'C'));
         foreach ($cek_jentest as $key=>$row) $data_3[$key] = array('p'=>'15', 'kolom'=>$row['jentest_nama'], 'alias'=>$row['jentest_singkatan'], 'align'=>'C');
         $data_4 = array(array('p'=>'20', 'kolom'=>'Total', 'alias'=>'Total', 'align'=>'C'),
            array('p'=>'25', 'kolom'=>'Hasil', 'alias'=>'Hasil', 'align'=>'C'),
         );
         $data = array_merge($data_1, $data_2, $data_2_2, $data_3, $data_4);
      }
      //add page
      $kertas_ukuran = array(215, 330);
      $kertas_posisi = 'L';
      $fpdf->AddPage($kertas_posisi, $kertas_ukuran);
      //judul laporan
      $fpdf->SetFont('Helvetica','B', 18);
      $fpdf->Cell(0, 10, setting('web_judul'), 0, 1, 'C');
      $fpdf->SetFont('Helvetica','B', 20);
      $fpdf->Cell(0, 10, setting('sekolah_nama'), 0, 1, 'C');
      $fpdf->SetFont('Helvetica','B','14');
      $fpdf->Cell(0, 10, $judul, 0, 1, 'C');
      $fpdf->SetTitle($judul);
      //header table
      $fpdf->SetDrawColor(156, 175, 196);
      $fpdf->SetFont('Helvetica','B','11');
      $fpdf->SetFillColor(105, 133, 165);
      $fpdf->SetTextColor(255, 255, 255);
      $fpdf->Cell(15, 8, 'No', 1, 0, 'C', TRUE);
      foreach ($data as $key=>$row) $fpdf->Cell($row['p'], 8, $row['alias'], 1, 0, 'C', TRUE);
      $fpdf->Ln();
      //isi tabel
      $fpdf->SetDrawColor(156, 175, 196);
      $fpdf->SetFont('Helvetica','','11');
      $fpdf->SetFillColor(240, 240, 246);
      $fpdf->SetTextColor(0, 0, 0);
      foreach ($query as $key=>$row){
         $fpdf->Cell(15, 6, $key + 1, 1, 0, 'C', FALSE);
         foreach ($data as $key=>$baris) $fpdf->Cell($baris['p'], 6, $row[$baris['kolom']], 1, 0, $baris['align'], FALSE);
         $fpdf->Ln();
      }
      $baris_isi = count($query) + 6; // jumlah baris isi ditambah header
      $baris_ket = count($data_2) + count($data_3) + 2; // jumlah bari keterangan di tambah spasi
      if((($baris_isi + $baris_ket) % 30) <= $baris_ket) $fpdf->AddPage($kertas_posisi, $kertas_ukuran);
      $fpdf->Ln();
      foreach ($data_2 as $key=>$row){
         $fpdf->Cell(40, 8, $row['alias'], 1, 0, 'L', TRUE);
         $fpdf->Cell(95, 8, $row['kolom'], 1, 0, 'L', TRUE);
         $fpdf->Ln();
      };
      $fpdf->Ln();
      foreach ($data_3 as $key=>$row){
         $fpdf->Cell(40, 8, $row['alias'], 1, 0, 'L', TRUE);
         $fpdf->Cell(95, 8, $row['kolom'], 1, 0, 'L', TRUE);
         $fpdf->Ln();
      };
      $fpdf->Output($file, 'I'); // I = Open on browser, D = Force download
   }
   function to_xls($query, $file, $judul, $judul_sheet, $cek_jentest){
      require_once APPPATH.'third_party/phpexcel/Classes/PHPExcel.php';
      $phpexcel = new PHPExcel();
      $header = array();
      foreach ($query as $row){foreach ($row as $key=>$val){if (!in_array($key, $header)) $header[] = $key;}}
      $phpexcel->getProperties()->setCreator(setting('sekolah_nama'))
                            ->setLastModifiedBy(setting('sekolah_nama'))
                            ->setTitle(setting('web_judul'))
                            ->setSubject(setting('web_judul'))
                            ->setDescription(setting('web_judul'))
                            ->setKeywords(setting('web_judul'))
                            ->setCategory(setting('web_judul'));
      $phpexcel->getActiveSheet()->fromArray(array(setting('web_judul')),  NULL, 'A1');
      $phpexcel->getActiveSheet()->fromArray(array(setting('sekolah_nama')),  NULL, 'A2');
      $phpexcel->getActiveSheet()->fromArray(array($judul),  NULL, 'A3');
      $phpexcel->getActiveSheet()->fromArray($header,  NULL, 'A5');
      $phpexcel->getActiveSheet()->fromArray($query,  NULL, 'A6');
      $judul_sheet = substr($judul_sheet, 0, 31);
      $phpexcel->getActiveSheet()->setTitle($judul_sheet);
      $phpexcel->setActiveSheetIndex(0);
      // Redirect output to a client’s web browser (Excel5)
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename='.$file);
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');
      // If you're serving to IE over SSL, then the following may be needed
      header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
      header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header ('Pragma: public'); // HTTP/1.0
      $objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
      $objWriter->save('php://output');
   }
}