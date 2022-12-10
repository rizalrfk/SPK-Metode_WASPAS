<?php 
session_start();
include 'config/conn.php';

//inisial variabel array alternatif
$alternatif = array();
$sql = 'SELECT * FROM alternatif';
$data = $koneksi->query($sql);
while ($row = $data->fetch_object()) {
  $alternatif[$row->id_alternatif] = $row->nama_alternatif;
}

//inisial variabel array kriteria dan bobot (W)
$kriteria = array();
$sql = 'SELECT * FROM kriteria';
$data = $koneksi->query($sql);
while ($row = $data->fetch_object()) {
  $kriteria[$row->id_kriteria] = array ($row->kriteria, $row->atribut);
  $w[$row->id_kriteria] = $row->bobot;
}

//inisial variable array optimum alternatif

?>

<div class="col" role="main">
  <div class="">
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title" style="text-transform: capitalize;">
            <h2 ><b>Tabel Data Hasil</b> <small></small></h2>
            <div class="clearfix"></div>
          </div><br>

          <div class="x_content">
          <table class="table table-striped table-bordered">
            <thead>
            <tr>
              <th class="text-center" width="10">No</th>
              <th class="text-center" width="100">ID alternatif</th>
              <th class="text-center">Jenis alternatif</th>
              <th class="text-center">WSM</th>
              <th class="text-center">WPM</th>
              <th class="text-center">Nilai Akhir</th>
              <th class="text-center">Ranking</th>
            </tr>
        <!-- data --> 
            </thead>
            <tbody>
              <?php 
                  
                  // mencari sum id alternatif
                  $querysum= mysqli_query($conn,"SELECT COUNT(id_alternatif) FROM alternatif");
                  $id_alter= mysqli_fetch_array($querysum);
                  $jumlah = $id_alter[0];

                  // mencari count alternatif
                  $queryalter= mysqli_query($conn,"SELECT id_alternatif FROM alternatif");
                  $alter= mysqli_fetch_assoc($queryalter);
                  

                  // mencari max-min C1
                  $querymaxc1= mysqli_query($conn,"SELECT MAX(c1) FROM alternatif");
                  $querymaxc01=mysqli_fetch_array($querymaxc1);
                  $maxc1 = $querymaxc01[0];
                  
                  $queryminc1= mysqli_query($conn,"SELECT MIN(c1) FROM alternatif");
                  $queryminc01=mysqli_fetch_array($queryminc1);
                  $minc1 = $queryminc01[0];
                  
                  // mencari max-min C2
                  $querymaxc2= mysqli_query($conn,"SELECT MAX(c2) FROM alternatif");
                  $querymaxc02=mysqli_fetch_array($querymaxc2);
                  $maxc2 = $querymaxc02[0];
                  
                  $queryminc2= mysqli_query($conn,"SELECT MIN(c2) FROM alternatif");
                  $queryminc02=mysqli_fetch_array($queryminc2);
                  $minc2 = $queryminc02[0];
                  
                  // mencari max-min C3
                  $querymaxc3= mysqli_query($conn,"SELECT MAX(c3) FROM alternatif");
                  $querymaxc03=mysqli_fetch_array($querymaxc3);
                  $maxc3 = $querymaxc03[0];
                  
                  $queryminc3= mysqli_query($conn,"SELECT MIN(c3) FROM alternatif");
                  $queryminc03=mysqli_fetch_array($queryminc3);
                  $minc3 = $queryminc03[0];
                  
                  // mencari max-min C4
                  $querymaxc4= mysqli_query($conn,"SELECT MAX(c4) FROM alternatif");
                  $querymaxc04=mysqli_fetch_array($querymaxc4);
                  $maxc4 = $querymaxc04[0];
                  
                  $queryminc4= mysqli_query($conn,"SELECT MIN(c4) FROM alternatif");
                  $queryminc04=mysqli_fetch_array($queryminc4);
                  $minc4 = $queryminc04[0];

                  // bobot1
                  $querybobot1 = mysqli_query($conn,"SELECT bobot FROM kriteria WHERE id_kriteria ='CR001' ");
                  $resultbobot1=mysqli_fetch_array($querybobot1);
                  $b1 = $resultbobot1[0];

                  // bobot2
                  $querybobot2 = mysqli_query($conn,"SELECT bobot FROM kriteria WHERE id_kriteria ='CR002' ");
                  $resultbobot2=mysqli_fetch_array($querybobot2);
                  $b2 = $resultbobot2[0];

                  // bobot3
                  $querybobot3 = mysqli_query($conn,"SELECT bobot FROM kriteria WHERE id_kriteria ='CR003' ");
                  $resultbobot3=mysqli_fetch_array($querybobot3);
                  $b3 = $resultbobot3[0];

                  // bobot4
                  $querybobot4 = mysqli_query($conn,"SELECT bobot FROM kriteria WHERE id_kriteria ='CR004' ");
                  $resultbobot4=mysqli_fetch_array($querybobot4);
                  $b4 = $resultbobot4[0];


                   
                  //nilai 
                  $querynilai = mysqli_query($conn,"SELECT * FROM alternatif");
                  while ($barisnilai=mysqli_fetch_assoc($querynilai)) {
                  

                    //normalisasi
                    $c1 = $minc1/($barisnilai['c1']);
                    $c2 = $minc2/($barisnilai['c2']);
                    $c3 = ($barisnilai['c3'])/$maxc3;
                    $c4 = $minc4/($barisnilai['c4']);

                    //rumus wsm,wpm,wi,rank
                    $query_wsm  = ($c1*$b1)+($c2*$b2)+($c3*$b3)+($c4*$b4);
                    $query_wpm  = (pow($c1, $b1))*(pow($c2, $b2))*(pow($c3, $b3))*(pow($c4, $b4));
                    $query_qi   = ($query_wsm*0.5)+($query_wpm*0.5);
                    
                    // konversi ke substr
                    $wsm = substr($query_wsm,0,4);
                    $wpm = substr($query_wpm,0,4);
                    $qi = substr($query_qi,0,4);

                    // Ranking
                    $rank[] = $qi;
                    ksort($rank);
                	
                	echo end($rank);
                	echo "<br>";
                    print_r($rank);
                    echo "<br>";                
                   ?>
       	
            <tr>   
              <td></td>
              <td><?php echo $barisnilai["id_alternatif"];?></td>
              <td><?php echo $barisnilai["alternatif"];?></td>
              <td><?php echo $wsm; ?></td>
              <td><?php echo $wpm; ?></td>
              <td><?php echo $qi; ?></td>
              <td><?php echo end($rank); ?></td>
            </tr>
        
            <?php } return $rank;?>
            
            </tbody>
          </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<br> <br> <br> <br> <br>