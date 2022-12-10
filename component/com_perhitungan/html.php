<?php 
include '../../config/conn.php';

//-- inisialisasi variabel array alternatif , dan jumlah alternatif
$alternatif = array();
$sql = 'SELECT * FROM tab_alternatif';
$data = $conn->query($sql);
while ($row = $data->fetch_object()) {
    $alternatif[] = $row->alternatif;
}
$n_subject = count($alternatif);

//-- inisialisasi variabel array kriteria dan bobot (W), dan jumlah kriteria
$kriteria = array();
$sql = 'SELECT * FROM tab_kriteria';
$data = $conn->query($sql);
while ($row = $data->fetch_object()) {
    $id_kriteria[] = $row->id_kriteria;
    $kriteria[] = $row->kriteria;
    $type[] = $row->atribut;
    $w[] = $row->bobot;
}
$n_criteria = count($kriteria);

// -- ambil nilai dari tabel
$value = array();
$sql = 'SELECT a.id_alternatif,b.id_kriteria,
  IFNULL((SELECT nilai FROM tab_nilai WHERE id_alternatif=a.id_alternatif AND id_kriteria=b.id_kriteria),0) nilai
FROM tab_alternatif a CROSS JOIN tab_kriteria b
ORDER BY a.id_alternatif,b.id_kriteria';
$data = $conn->query($sql);
while ($row = $data->fetch_object()) {
    // $a = $row->id_alternatif;
    // $k = $row->id_kriteria;
    $value[] = $row->nilai;
    // $value = $nak;
// print_r($value);
// echo "<br>";
}

// --normalisasi matriks
$limit = array();
$Q = array();
// a.)mencari nilai min-max sesuai tipe
  for ($i=0; $i<$n_criteria; $i++) { 

    // nilai max/benefit
    if ($type[$i] == "benefit") {
      $max =  $value[$i];

      for ($j=0; $j<$n_subject*$n_criteria; $j+=$n_criteria) { 
        $index = $j + $i;
        if ($max < $value[$index]) {
          $max = $value[$index];
        }
      }
      $limit[$i] = $max;
    }

    // nilai min/cost
    if ($type[$i] == "cost") {
      $min =  $value[$i];

      for ($j=0; $j<$n_subject*$n_criteria; $j+=$n_criteria) { 
        $index = $j + $i;
        if ($min > $value[$index]) {
          $min = $value[$index];
        }
      }
      $limit[$i] = $min;
    }
  }
    // echo "$limit[$i]"."<br/>";
    // print_r($min);

// b.)menghitung normalisasi
  for($i=0; $i<$n_criteria; $i++){
    if($type[$i] == "benefit"){
      for($j=0; $j<$n_subject * $n_criteria; $j+=$n_criteria){
        $index = $j + $i;
        $value[$index] = $value[$index] / $limit[$i];
      }
    } 

    else if($type[$i] == "cost"){
      for($j=0; $j<$n_subject * $n_criteria; $j+=$n_criteria){
        $index = $j + $i;
        $value[$index] = $limit[$i] / $value[$index];
      }
    }
  }
// for($i=0; $i<$n_subject; $i++){
//   for($j=0; $j<$n_criteria; $j++){
//     $index = $j + ($i * $n_criteria);
//     $N = $value[$index];
//   }
// }

// c.) Menghitung WSM, WPM, Qi
$wsm = array();
$wpm = array();
  for($i=0; $i<$n_subject; $i++){
    // step 1
    $row = 0;
    for($j=0; $j<$n_criteria; $j++){
      $weight = $w[$j] * 100;
      // -----------------------------
      $index = $j + ($i * $n_criteria);
      $col = $value[$index] * $weight / 100;
      $row += $col;
    }
      $wsm[] = $row;

    $Q[$i] = 0.5 * $row;

    // step 2
    $row = 1;
    for($j=0; $j<$n_criteria; $j++){
      $weight = $w[$j] * 100;
      // -----------------------------
      $index = $j + ($i * $n_criteria);
      $col = pow($value[$index], ($weight / 100));
      $row *= $col;
    }
      $wpm[] = $row;

    $Q[$i] = 0.5 * $row + $Q[$i];

  }

  // d.) Mengurutkan berdasarkan nilai terbesar
  for($i=0; $i<$n_subject; $i++){
    $Q[$i] = array($Q[$i], $alternatif[$i]);
  }
  sort($wsm);
  sort($wpm);
  sort($Q);

// -------------------------------------------------------------------------------
// -rumus untuk matriks keputusan
  //-- inisialisasi variabel array id_alternatif+alternatif untuk matriks keputusan
$alternatif1 = array();
$sql = 'SELECT * FROM tab_alternatif';
$data = $conn->query($sql);
while ($row = $data->fetch_object()) {
    $alternatif1[$row->id_alternatif] = $row->alternatif;
}
//-- inisialisasi variabel array id_kriteria+kriteria untuk matriks keputusan
$kriteria1 = array();
$sql = 'SELECT * FROM tab_kriteria';
$data = $conn->query($sql);
while ($row = $data->fetch_object()) {
    $kriteria1[$row->id_kriteria] = array($row->kriteria, $row->atribut);
}
//-- ambil nilai dari tabel_nilai untuk matriks keputusan
$nilai1 = array();
$sql = 'SELECT * FROM tab_nilai ORDER BY id_alternatif, id_kriteria';
$data = $conn->query($sql);
while ($row = $data->fetch_object()) {
    $i = $row->id_alternatif;
    $j = $row->id_kriteria;
    $aij = $row->nilai;

    $nilai1[$i][$j] = $aij;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak PDF</title>
</head>
<body>
  <table border="0" cellpadding="4"  style="text-align:center;">
    <thead>
      <tr>
        <td style="font-size: 14px;"><b>Laporan Data Reseller Terbaik di DJ Foundation</b></td>
      </tr>
    </thead>
  </table>

  <br> <br>
<!-- WSM,WPM,Qi -->
               <table border="1" cellpadding="5"  style="text-align:center;">
                <thead>
                  <tr>
                    <th colspan="6">  <h2>Menampilkan Data Hasil Nilai Akhir</h2>
<?php for($i = $n_subject-1; $i >= $n_subject -1; $i--){ ?>              
            <text>Dari hasil perhitungan dipilih <b> <?php echo $Q[$i][1] ?></b> sebagai alternatif Reseller Terbaik dengan <b>nilai utilitas (Qi)</b> sebesar <b><?php echo round($Q[$i][0],3); ?></b>.</text><br>
<?php } ?>    
                    </th>
                  </tr>
                  <tr>
                    <th width="20px">No.</th>
                    <th width="113px">Alternatif</th>
                    <th>Lorem, ipsum. (WSM)</th>
                    <th>Lorem, ipsum. (WPM)</th>
                    <th>Nilai utilitas (Qi)</th>
                    <th>Ranking</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 0;
                  for($i = $n_subject-1; $i >= 0; $i--){ 
                    $no++;
                  ?>
                  <tr>
                    <td width="20px"><?php echo $no; ?></td>
                    <td width="113px" style="text-align: left;"><?php echo $Q[$i][1]; ?></td>
                    <td><?php echo round($wsm[$i],3); ?></td>
                    <td><?php echo round($wpm[$i],3); ?></td>
                    <td><?php echo round($Q[$i][0],3); ?></td>
                    <td><?php echo $n_subject - $i; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
<!-- WSM,WPM,QI -->
<br> <br> <br> <br>
<table style=" text-align: right;">
  <tr>
    <td>
    <p>Semarang, <?php echo date("d-m-Y"); ?></p>
    <text>Penanggung jawab</text>
    </td>
  </tr>
<br> <br> <br> <br>
  <tr>
    <td>
      <p>Admin Staff Marketing</p>
    </td>
  </tr>
</table>

</body>
</html>