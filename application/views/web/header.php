<!DOCTYPE html>
<?php
$menu 		= strtolower($this->uri->segment(1));
$sub_menu = strtolower($this->uri->segment(2));
$sub_menu3 = strtolower($this->uri->segment(3));
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
	<title><?= $judul_web; ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta content="CV. ESOTECHNO, <?php echo $this->Mcrud->judul_web(); ?>" name="description" />
	<meta content="CV. ESOTECHNO, <?php echo $this->Mcrud->judul_web(); ?>" name="keywords" />
	<meta content="CV. ESOTECHNO - Anwar-kun" name="author" />
	<base href="<?php echo base_url(); ?>">
	<link rel="shortcut icon" href="assets/favicon.png" type="image/x-icon" />
  <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,300,700&amp;subset=all' rel='stylesheet' type='text/css'>
  <link href="assets/web/plugins/socicon/socicon.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/plugins/bootstrap-social/bootstrap-social.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/plugins/animate/animate.min.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <link href="assets/web/plugins/revo-slider/css/settings.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/plugins/revo-slider/css/layers.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/plugins/revo-slider/css/navigation.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/plugins/cubeportfolio/css/cubeportfolio.min.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/plugins/owl-carousel/assets/owl.carousel.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/plugins/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/plugins/slider-for-bootstrap/css/slider.css" rel="stylesheet" type="text/css"/>

  <link href="assets/web/plugins/ilightbox/css/ilightbox.css" rel="stylesheet" type="text/css"/>

  <link href="assets/web/demos/default/css/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="assets/web/demos/default/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
  <link href="assets/web/demos/default/css/themes/default.css" rel="stylesheet" id="style_theme" type="text/css"/>
  <link href="assets/web/demos/default/css/custom.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="assets/web/dataTables/css/jquery.dataTables.min.css">
  <!-- END THEME STYLES -->

</head>
<body class="c-layout-header-fixed c-layout-header-mobile-fixed">
  <!-- BEGIN: LAYOUT/HEADERS/HEADER-1 -->
  <!-- BEGIN: HEADER -->
  <header class="c-layout-header c-layout-header-4 c-layout-header-default-mobile" data-minimize-offset="80">
    <div class="c-navbar">
      <div class="container">
        <!-- BEGIN: BRAND -->
        <div class="c-navbar-wrapper clearfix">
          <div class="c-brand c-pull-left">
            <a ><a href="./" class="c-logo" style="font-size:2em;font-weight:600;margin-top:30px;">
              SIPARIS</a><span style="color: blue;font-size:1em;"> Sistem Pengaduan Notaris</span>
            </a>
            <button class="c-hor-nav-toggler" type="button" data-target=".c-mega-menu">
              <span class="c-line"></span>

              <span class="c-line"></span>
              <span class="c-line"></span>
            </button>
            <button class="c-topbar-toggler" type="button">
              <i class="fa fa-ellipsis-v"></i>
            </button>
            <!-- <button class="c-search-toggler" type="button">
              <i class="fa fa-search"></i>
            </button>
            <button class="c-cart-toggler" type="button">
              <i class="icon-handbag"></i> <span class="c-cart-number c-theme-bg">2</span>
            </button> -->
          </div>

          <!-- Dropdown menu toggle on mobile: c-toggler class can be applied to the link arrow or link itself depending on toggle mode -->
          <nav class="c-mega-menu c-mega-menu-onepage c-pull-right c-mega-menu-dark c-mega-menu-dark-mobile c-fonts-uppercase c-fonts-bold" data-onepage-animation-speed="700">
            <ul class="nav navbar-nav c-theme-nav">
              <li class="<?php if($menu=='' OR $menu=='web' AND $sub_menu==''){echo "c-active ";} ?>c-menu-type-classic">
                <a href="" class="c-link">Home<span class="c-arrow c-toggler"></span></a>
              <!--</li>
              <li class="<?php if($menu=='pengaduan' AND $sub_menu==''){echo "c-active ";} ?>c-menu-type-classic">
                <a href="pengaduan" class="c-link">Pengaduan<span class="c-arrow c-toggler"></span></a>
              </li>
              <li class="<?php if($menu=='pengaduan' AND $sub_menu=='cek'){echo "c-active ";} ?>">
                <a href="pengaduan/cek" class="c-link">Tracking<span class="c-arrow c-toggler"></span></a>
              </li>-->
              <li class="c-menu-type-classic">
                <a href="web/login.html" class="c-link">LOGIN<span class="c-arrow c-toggler"></span></a>
              </li>
            </ul>

          </nav>
          <!-- END: MEGA MENU --><!-- END: LAYOUT/HEADERS/MEGA-MENU -->
          <!-- END: HOR NAV -->
        </div>
        <!-- BEGIN: LAYOUT/HEADERS/QUICK-CART -->
      </div>
    </div>
  </header>
  <!-- END: HEADER --><!-- END: LAYOUT/HEADERS/HEADER-1 -->
