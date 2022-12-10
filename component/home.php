<!-- page content -->
<div class="col" role="main">
  <div class="">
    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="x_panel">
          <div class="x_title" style="text-transform: capitalize;">
            <h4 >Selamat datang, <?php echo $_SESSION['namalengkap'];?></h4>
            <div class="clearfix"></div>
          </div>

          <!-- table kolom -->
          <div class="x_content" style="/*border: 2px solid red*/;" >
          <?php if ($_SESSION['leveluser'] == 'admin'){ ?>
            <h3>ALUR SISTEM PENDUKUNG KEPUTUSAN <br> PENENTUAN RESELLER TERBAIK MENGGUNAKAN METODE WASPAS</h3> 
          <?php } ?>
          <?php if ($_SESSION['leveluser'] == 'user'){ ?>
            <h3 style="color: #363535;">SISTEM PENDUKUNG KEPUTUSAN <br> PENENTUAN RESELLER TERBAIK MENGGUNAKAN METODE WASPAS</h3> 
          <?php } ?>

              <div class="divider-dashed"></div>
            <div class="y_content">
              <?php if ($_SESSION['leveluser'] == 'admin'){ ?>
              <img src="build/images/beranda_admin.png" alt="img_error in home.php/21">
            <?php } ?>

            <?php if ($_SESSION['leveluser'] == 'user'){ ?>
              <div style=" margin-left: 15%; width: 70%;">
              <br>
              <h4 style="color: #363535;">Sistem Pendukung Keputusan Menggunakan Metode WASPAS Merupakan sebuah sistem untuk membantu menentukan keputusan <i>reseller</i> terbaik di lingkup industri DJ Foundation. Serta dengan didukung metode <i>(weight aggregate sum product assesment)</i> WASPAS yang dapat meminimalisir kesalahan sehingga data yang dihasilkan menjadi lebih akurat.</h4>
              <img src="build/images/beranda_user.png" style="width: 240px;">
              </div>
            <?php } ?>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
<!-- /page content -->