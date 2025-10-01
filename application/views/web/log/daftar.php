<div class="limiter">
  <div class="container-login100">
    <div class="wrap-login100" style="padding:40px;">
      <div class="login100-pic js-tilt">
        <center><img src="img/logo.png" alt="IMG"></center>
      </div>
      <form class="login100-form validate-form" action="" method="post">
        <span class="login100-form-title" style="padding:40px;">
          Registrasi Akun
        </span>
        <?php
					echo $this->session->flashdata('msg');
				?>
        <div class="wrap-input100 validate-input" data-validate = "Masukkan nama anda">
          <input class="input100" type="text" placeholder="Nama Lengkap" name="nama" autofocus>
          <span class="focus-input100"></span>
          <span class="symbol-input100">
            <i class="fa fa-user" aria-hidden="true"></i>
          </span>
        </div>

        <div class="wrap-input100 validate-input"  data-validate = "Nomor NIK">
          <input class="input100" type="text" placeholder="Nomor NIK" name="no_ktp" onkeypress="return hanyaAngka(event)">
          <span class="focus-input100"></span>
          <span class="symbol-input100">
            <i class="fa fa-address-card" aria-hidden="true"></i>
          </span>
        </div>
        <div class="wrap-input100 validate-input" data-validate = "Alamat">
          <input class="input100" type="text" placeholder="Alamat" name="alamat" >
          <span class="focus-input100"></span>
          <span class="symbol-input100">
            <i class="fa fa-map-marker" aria-hidden="true"></i>
          </span>
        </div>
        <div class="wrap-input100 validate-input" data-validate = "Kontak yang bisa di hubungi">
          <input class="input100" type="text"  placeholder="kontak" name="kontak" onkeypress="return hanyaAngka(event)">
          <span class="focus-input100"></span>
          <span class="symbol-input100">
            <i class="fa fa-phone" aria-hidden="true"></i>
          </span>
        </div>
        <div class="wrap-input100 validate-input" data-validate = "Username">
          <input class="input100" type="text"  placeholder="Username" name="username">
          <span class="focus-input100"></span>
          <span class="symbol-input100">
            <i class="fa fa-user" aria-hidden="true"></i>
          </span>
        </div>
        <div class="wrap-input100 validate-input" data-validate = "Password Harus di isi">
          <input class="input100" type="password" placeholder="Password" name="password">
          <span class="focus-input100"></span>
          <span class="symbol-input100">
            <i class="fa fa-lock" aria-hidden="true"></i>
          </span>
        </div>
        <div class="wrap-input100 validate-input" data-validate = "Konfirmasi Password Harus di isi">
          <input class="input100" type="password" placeholder="Konfirmasi Password" name="password2">
          <span class="focus-input100"></span>
          <span class="symbol-input100">
            <i class="fa fa-lock" aria-hidden="true"></i>
          </span>
        </div>
      <div class="container-login100-form-btn">
          <button type="submit" name="btndaftar" class="login100-form-btn">
            Daftar
          </button>
        </div>
        <hr>
        <!-- <div class="text-center p-t-136"> -->
          <a class="txt5" href="web/login.html">
            Sudah Punya Akun
            <i class="fa fa-long-arrow-left m-l-5" aria-hidden="true"></i>
          </a>
        <!-- </div> -->
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
function hanyaAngka(evt) {
  var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))

    return false;
  return true;
}
</script>
