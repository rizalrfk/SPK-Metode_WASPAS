<?php
include '../../config/conn.php';
if ($_GET['aksi'] == '') {

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
  for ($i = 0; $i < $n_criteria; $i++) {

    // nilai max/benefit
    if ($type[$i] == "benefit") {
      $max =  $value[$i];

      for ($j = 0; $j < $n_subject * $n_criteria; $j += $n_criteria) {
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

      for ($j = 0; $j < $n_subject * $n_criteria; $j += $n_criteria) {
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
  for ($i = 0; $i < $n_criteria; $i++) {
    if ($type[$i] == "benefit") {
      for ($j = 0; $j < $n_subject * $n_criteria; $j += $n_criteria) {
        $index = $j + $i;
        $value[$index] = $value[$index] / $limit[$i];
      }
    } else if ($type[$i] == "cost") {
      for ($j = 0; $j < $n_subject * $n_criteria; $j += $n_criteria) {
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
  for ($i = 0; $i < $n_subject; $i++) {
    // step 1
    $row = 0;
    for ($j = 0; $j < $n_criteria; $j++) {
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
    for ($j = 0; $j < $n_criteria; $j++) {
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
  for ($i = 0; $i < $n_subject; $i++) {
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

  <div class="col" role="main">
    <div class="">
      <div class="clearfix"></div>
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title" style="text-transform: capitalize;">
              <h2> <b>Data <?php echo $_GET['module']; ?></b> <small></small></h2>
              <div class="clearfix"></div>
            </div> <br>

            <div class="x_content">

              <!-- Hasil Ranking -->
              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab"><b>Data Reseller</b></a></li>
              </ul>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4>Menampilkan Informasi Data Reseller</h4>
                  <p>Tabel dibawah adalah data dari reseller DJ Foundation yang di susun dalam tabel</p>
                </div>
                <div class="panel-body">

                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="text-center" width="10">No</th>
                        <th class="text-center" width="80">ID alternatif</th>
                        <th class="text-center">Nama Reseller</th>
                        <th class="text-center">Alamat</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 0;
                      $query = mysqli_query($conn, "SELECT * FROM tab_alternatif");
                      while ($row = mysqli_fetch_assoc($query)) {
                        $no++;
                      ?>
                        <tr>
                          <td><?php echo $no; ?></td>
                          <td><?php echo $row['id_alternatif']; ?></td>
                          <td class="text-left"><?php echo $row['alternatif']; ?></td>
                          <td class="text-left"><?php echo $row['alamat']; ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- Hasil Ranking -->

              <!-- matrik keputusan -->
              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab"><b>Matriks Keputusan</b></a></li>
              </ul>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4>Menampilkan Data Matriks Keputusan</h4>
                  <p>Matriks keputusan merupakan kumpulan data kriteria, dan alternatif yang disusun dalam bentuk tabel matriks</p>
                </div>
                <div class="panel-body">

                  <p class="text-left">Tabel data matriks keputusan</p>

                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <!-- row1 -->
                      <tr>
                        <th colspan="2" class="text-center">Kategori</th>
                        <?php for ($i = 0; $i < $n_criteria; $i++) { ?>
                          <th class="text-center"><?= $type[$i]; ?></th>
                        <?php } ?>
                      </tr>
                      <!-- row1 -->
                      <!-- row2 -->
                      <tr>
                        <th colspan="2" class="text-center">Bobot</th>
                        <?php for ($i = 0; $i < $n_criteria; $i++) { ?>
                          <th class="text-center"><?= $w[$i]; ?></th>
                        <?php } ?>
                      </tr>
                      <!-- row2 -->
                      <!-- row3 -->
                      <tr>
                        <th class="text-center" style="width: 20px;">No.</th>
                        <th class="text-center">Alternatif</th>
                        <?php for ($i = 0; $i < $n_criteria; $i++) { ?>
                          <th class="text-center"><?= $id_kriteria[$i] . "<br> (" . $kriteria[$i] . ")"; ?></th>
                        <?php } ?>
                      </tr>
                      <!-- row3 -->
                    </thead>
                    <tbody>
                      <?php
                      $no = 0;
                      foreach ($alternatif1 as $key => $values) :
                        $no++;
                      ?>
                        <tr style="text-align:center;">
                          <td><?= $no; ?></td>
                          <td><?= $values; ?></td>
                          <?php foreach ($kriteria1 as $key2 => $values2) :  ?>
                            <td><?= @$nilai1[$key][$key2]; ?></td>
                          <?php endforeach ?>
                        </tr>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- matrik keputusan -->

              <!-- Matriks normalisasi -->
              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab"><b>Matriks Normalisasi</b></a></li>
              </ul>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4>Menampilkan Data Matriks Hasil Normalisasi</h4>
                  <p>Matriks hasil normalisasi memuat hasil perhitungan normalisasi dari data matriks keputusan</p>
                </div>
                <div class="panel-body">

                  <p class="text-left">Tabel data normalisasi</p>

                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <!-- row1 -->
                      <tr>
                        <th colspan="2" class="text-center">Kategori</th>
                        <?php for ($i = 0; $i < $n_criteria; $i++) { ?>
                          <th class="text-center"><?= $type[$i]; ?></th>
                        <?php } ?>
                      </tr>
                      <!-- row1 -->
                      <!-- row2 -->
                      <tr>
                        <th colspan="2" class="text-center">Bobot</th>
                        <?php for ($i = 0; $i < $n_criteria; $i++) { ?>
                          <th class="text-center"><?= $w[$i]; ?></th>
                        <?php } ?>
                      </tr>
                      <!-- row2 -->
                      <!-- row3 -->
                      <tr>
                        <th class="text-center" style="width: 20px;">No.</th>
                        <th class="text-center">Alternatif</th>
                        <?php for ($i = 0; $i < $n_criteria; $i++) { ?>
                          <th class="text-center"><?= $id_kriteria[$i] . "<br> (" . $kriteria[$i] . ")"; ?></th>
                        <?php } ?>
                      </tr>
                      <!-- row3 -->
                    </thead>

                    <tbody>
                      <?php
                      $no = 0;
                      for ($i = 0; $i < $n_subject; $i++) {
                        $no++;
                      ?>
                        <tr style="text-align:center;">
                          <td><?= $no; ?></td>
                          <td><?= $alternatif[$i]; ?></td>
                          <?php
                          for ($j = 0; $j < $n_criteria; $j++) {
                            $index = $j + ($i * $n_criteria);
                            $N = $value[$index];
                          ?>
                            <td><?= round($N, 3); ?></td>
                          <?php } ?>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- Matriks normalisasi -->

              <!-- WSM,WPM,Qi -->
              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab"><b>Nilai Akhir & Ranking</b></a></li>
              </ul>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4>Menampilkan Data Hasil Nilai Akhir</h4>
                  <p>Data alternatif dengan nilai utilitas (Qi) terbesar merupakan <b> Reseller Terbaik </b></p>
                </div>
                <div class="panel-body">

                  <?php for ($i = $n_subject - 1; $i >= $n_subject - 1; $i--) { ?>
                    <p>Dari hasil perhitungan dipilih <b> <?php echo $Q[$i][1] ?></b> sebagai alternatif Reseller Terbaik dengan <b>nilai utilitas (Qi)</b> sebesar <b><?php echo round($Q[$i][0], 3); ?></b>.</p><br>
                  <?php } ?>

                  <table id="datatable" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Alternatif</th>
                        <th class="text-center">Weight Sum Model (WSM)</th>
                        <th class="text-center">Weight Product Model (WPM)</th>
                        <th class="text-center">Nilai Quality (Qi)</th>
                        <th class="text-center">Ranking</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 0;
                      for ($i = $n_subject - 1; $i >= 0; $i--) {
                        $no++;
                      ?>
                        <tr style="text-align:center;">
                          <td><?php echo $no; ?></td>
                          <td><?php echo $Q[$i][1]; ?></td>
                          <td><?php echo round($wsm[$i], 3); ?></td>
                          <td><?php echo round($wpm[$i], 3); ?></td>
                          <td><?php echo round($Q[$i][0], 3); ?></td>
                          <td><?php echo $n_subject - $i; ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- WSM,WPM,QI -->

              <!-- button cetak pdf -->
              <!-- <?php if ($_SESSION['leveluser'] == 'admin') { ?>
  <a  class="btn btn-success btn-sm" href="component/com_perhitungan/print.php" style="width: 15%;"><i class="glyphicon glyphicon-print"></i> Cetak PDF</a>
<?php } ?> -->
              <!-- button cetak pdf -->


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php } ?>