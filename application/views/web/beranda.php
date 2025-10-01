<div class="c-layout-page">
  <!-- BEGIN: PAGE CONTENT -->
<?php $this->load->view('web/slide'); ?>

<!-- BEGIN: CONTENT/STEPS/STEPS-1 -->
<div class="c-content-box c-size-md c-bg-white">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="c-content-title-1 c-margin-b-60">
          <h3 class="c-center c-font-uppercase c-font-bold">
            Bagaimana Cara Melapor ?
          </h3>
          <div class="c-line-center"></div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3 col-sm-12 wow animate fadeInRight" data-wow-delay="1s">
        <div class="c-content-step-1 c-opt-1">
          <div class="c-icon"><span class="c-hr c-hr-first"><span class="c-content-line-icon c-icon-23 c-theme"></span></span></div>
          <div class="c-title c-font-20 c-font-bold c-font-uppercase">Daftar Akun</div>
          <div class="c-description c-font-17">
            Daftarkan diri Anda, isikan data sesuai dengan identitas Anda
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-12 wow animate fadeInUp" data-wow-delay="2s">
        <div class="c-content-step-1 c-opt-1">
          <div class="c-icon"><span class="c-hr"><span class="c-content-line-icon c-icon-13 c-theme"></span></span></div>
          <div class="c-title c-font-20 c-font-bold c-font-uppercase">Login Akun</div>
          <div class="c-description c-font-17">
            Silahkan login terlebih dahulu sebelum membuat aspirasi atau aduan
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-12 wow animate fadeInDown" data-wow-delay="3s">
        <div class="c-content-step-1 c-opt-1">
          <div class="c-icon"><span class="c-hr c-hr-last"><span class="c-content-line-icon c-icon-47 c-theme"></span></span></div>
          <div class="c-title c-font-20 c-font-bold c-font-uppercase">Buat Pengaduan</div>
          <div class="c-description c-font-17">
            Laporkan Pengaduan Anda dan lampirkan data pendukungnya
          </div>
        </div>
      </div>
	  <div class="col-md-3 col-sm-12 wow animate fadeInLeft" data-wow-delay="4s">
        <div class="c-content-step-1 c-opt-1">
          <div class="c-icon"><span class="c-hr c-hr-last"><span class="c-content-line-icon c-icon-27 c-theme"></span></span></div>
          <div class="c-title c-font-20 c-font-bold c-font-uppercase">Tindak Lanjut</div>
          <div class="c-description c-font-17">
            Anda dapat memonitor seluruh progress pengaduan Anda (Kerahasiaan Anda Terjamin)
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
               $this->db->join('tbl_data_user','tbl_data_user.id_user=tbl_pengaduan.user');
               $this->db->join('tbl_kategori','tbl_kategori.id_kategori=tbl_pengaduan.id_kategori');
               $this->db->join('tbl_sub_kategori','tbl_sub_kategori.id_sub_kategori=tbl_pengaduan.id_sub_kategori');
               $this->db->order_by('id_pengaduan','DESC');
               $this->db->limit(6);
$v_pengaduan = $this->db->get('tbl_pengaduan');
//if ($v_pengaduan->num_rows()!=0): ?>
<!--<div class="c-content-box c-size-md c-bg-grey-1">
  <div class="container">
    <div class="c-content-blog-post-card-1-slider" data-slider="owl">
      <div class="c-content-title-1">
        <h3 class="c-center c-font-uppercase c-font-bold">PENGADUAN</h3>
        <div class="c-line-center c-theme-bg"></div>
      </div>
      <div class="owl-carousel owl-theme c-theme c-owl-nav-center" data-items="6" data-slide-speed="8000" data-rtl="false">
        <?php foreach ($v_pengaduan->result() as $key => $value):
          $foto_profile = "assets/panel/img/user-default.jpg";
          $foto_k = $value->foto;
          if ($foto_k!='') {
            if(file_exists("$foto_k")){
              $foto_profile = $foto_k;
            }
          }
          ?>
          <!--<div class="item">
            <div class="c-content-testimonial-3 c-option-default">
              <div class="c-content">
                <p><?php echo $value->isi_pengaduan; ?></p>
                <p>Kategori : <a href="javascript:;"><?php echo $value->nama_kategori; ?></a></p>
              </div>
              <div class="c-person">
                <img src="<?php echo $foto_profile; ?>" class="img-responsive">
                <div class="c-person-detail c-font-uppercase">
                  <h4 class="c-name"><?php echo $value->nama; ?></h4>
                </div>
              </div>
            </div>
          </div>-->
         <!--  <div class="item">
            <a href="#"><img src="images/512.png" alt=""/>Masalah Jalan</a>
          </div> -->
          <?php endforeach; ?>
     <!-- </div>

        <center>
          <a href="pengaduan" class="btn btn-primary">Tampilkan Semua</a>
        </cneter>
    </div>
  </div>
</div>-->
<?php //endif; ?>
</div>
