<!DOCTYPE html>
<?php
$cek = $user->row();
$nama = $cek->nama_lengkap;
$username = $cek->username;

$level = $cek->level;
$foto = "img/user/user-default.jpg";
if ($level == 'user') {
    $d_k = $this->db->get_where('tbl_data_user', array('id_user' => $cek->id_user))->row();
    $foto_k = $d_k->foto;
    if ($foto_k != '') {
        if (file_exists("$foto_k")) {
            $foto = $foto_k;
        }
    }
}

$menu = strtolower($this->uri->segment(1));
$sub_menu = strtolower($this->uri->segment(2));
$sub_menu3 = strtolower($this->uri->segment(3));
?>

<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title><?= $judul_web; ?></title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
    <meta content="<?php echo $this->Mcrud->judul_web(); ?>" name="description"/>
    <meta content="CV. Esotechno" name="author"/>
    <meta name="keywords" content="CV. Esotechno, <?php echo $this->Mcrud->judul_web(); ?>">
    <base href="<?php echo base_url(); ?>"/>
    <link rel="shortcut icon" href="assets/favicon.png" type="image/x-icon"/>
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="assets/panel/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/ionicons/css/ionicons.min.css" rel="stylesheet"/>
    <link href="assets/panel/css/animate.min.css" rel="stylesheet"/>
    <link href="assets/panel/css/style.min.css" rel="stylesheet"/>
    <link href="assets/panel/css/style-responsive.min.css" rel="stylesheet"/>
    <link href="assets/panel/css/theme/default.css" rel="stylesheet" id="theme"/>
    <link href="assets/panel/css/style-gue.css" rel="stylesheet">
    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- ================== BEGIN PAGE LEVEL CSS STYLE ================== -->
    <link href="assets/panel/plugins/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/bootstrap-calendar/css/bootstrap_calendar.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/gritter/css/jquery.gritter.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/morris/morris.css" rel="stylesheet"/>
    <!-- ================== END PAGE LEVEL CSS STYLE ================== -->

    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link href="assets/panel/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css"
          rel="stylesheet"/>
    <link href="assets/panel/plugins/parsley/src/parsley.css" rel="stylesheet"/>
    <!-- ================== END PAGE LEVEL STYLE ================== -->

    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link href="assets/panel/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/ionRangeSlider/css/ion.rangeSlider.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/ionRangeSlider/css/ion.rangeSlider.skinNice.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/password-indicator/css/password-indicator.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/bootstrap-combobox/css/bootstrap-combobox.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/jquery-tag-it/css/jquery.tagit.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/select2/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css"
          rel="stylesheet"/>
    <link href="assets/panel/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.css" rel="stylesheet"/>
    <link href="assets/panel/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-fontawesome.css"
          rel="stylesheet"/>
    <link href="assets/panel/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-glyphicons.css"
          rel="stylesheet"/>
    <!-- ================== END PAGE LEVEL STYLE ================== -->

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="assets/panel/plugins/pace/pace.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ================== END BASE JS ================== -->
    <link rel="stylesheet" type="text/css" href="assets/fancybox/jquery.fancybox.css">
    <link rel="stylesheet" type="text/css" href="assets/mycss/custom-tabs.css">
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/fancybox/jquery.fancybox.js"></script>
    <!-- header.php -->


</head>
<body>

<style type="text/css"></style>
<!-- begin #page-loader -->
<div id="page-loader" class="fade in"><span class="spinner"></span></div>
<!-- end #page-loader -->

<!-- begin #page-container -->
<div id="page-container" class="fade page-sidebar-fixed page-header-fixed in"> <!--page-sidebar-minified-->
    <!-- begin #header -->
    <div id="header" class="header navbar navbar-default navbar-fixed-top">
        <!-- begin container-fluid -->
        <div class="container-fluid">
            <!-- begin mobile sidebar expand / collapse button -->
            <div class="navbar-header">
                <a href="" class="navbar-brand"><span class="navbar-logo"><i class="fa fa-vcard"></i></span> &nbsp;<b>Panel</b> <?php echo ucwords($level); ?>
                </a>
                <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- end mobile sidebar expand / collapse button -->

            <!-- begin header navigation right -->
            <ul class="nav navbar-nav navbar-right">
                <li  class="dropdown">
                    <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle icon" aria-expanded="false">
                        <i class="ion-ios-bell"></i>
                        <span class="label" id="jml_notif_bell">0</span>
                    </a>
                    <ul class="dropdown-menu media-list pull-right animated fadeInDown" id="notif_bell"></ul>
                </li>
                <li class="dropdown navbar-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<span class="user-image online">
								<img src="<?php echo $foto; ?>" alt=""/>
							</span>
                        <span class="hidden-xs"><?php echo ucwords($nama); ?></span> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu animated fadeInLeft">
                        <li class="arrow"></li>
                        <li<?php if ($menu == 'profile') {
                            echo " class='active'";
                        } ?>><a href="profile.html"><?php if ($level == 'user') {
                                    echo "Lengkapi ";
                                } ?>Profile</a></li>
                        <li<?php if ($menu == 'ubah_pass') {
                            echo " class='active'";
                        } ?>><a href="ubah_pass.html">Ubah Password</a></li>

                        <?php
                        if ($this->session->userdata('level') == "notaris") {
                            ?>
                            <li <?php if ($menu == 'dossier_pribadi') {
                                echo " class='active'";
                            } ?>><a href="users/dossier_pribadi.html">Dossier Pribadi</a></li>
                            <?php
                        }
                        ?>
                        <!-- <li><a href="javascript:;"><span class="badge badge-danger pull-right">2</span> Inbox</a></li> -->
                        <!-- <li><a href="javascript:;">Calendar</a></li> -->
                        <!-- <li><a href="javascript:;">Setting</a></li> -->
                        <li class="divider"></li>
                        <li><a href="web/logout.html">Keluar</a></li>
                    </ul>
                </li>
            </ul>
            <!-- end header navigation right -->
        </div>
        <!-- end container-fluid -->
    </div>
    <!-- end #header -->

    <!-- begin #sidebar -->
    <div id="sidebar" class="sidebar">
        <!-- begin sidebar scrollbar -->
        <div data-scrollbar="true" data-height="100%">
            <!-- begin sidebar user -->
            <ul class="nav">
                <li class="nav-profile">
                    <div class="image">
                        <a href="profile"><img src="<?php echo $foto; ?>" alt=""/></a>
                    </div>
                    <div class="info">
                        <?php echo ucwords($nama); ?>
                        <small>@<?php echo strtolower($username); ?></small>
                    </div>
                </li>
            </ul>
            <!-- end sidebar user -->
            <!-- begin sidebar nav -->
            <ul class="nav">
                <!-- MENU UMUM DARI SINI -->
                <li class="nav-header">MENU NAVIGASI</li>
                <li class="has-sub<?php if ($menu == 'users' AND $sub_menu == '' or $menu == 'dashboard') {
                    echo " active";
                } ?>">
                    <a href="dashboard.html">
                        <i class="ion-ios-pulse-strong"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <!-- MENU UMUM SAMPAI SINI -->

                <!-- MENU SUPER ADMIN -->
                <?php if ($level == 'superadmin'): ?>
                    <li <?php if ($menu == 'petugas') {
                        echo " class='active'";
                    } ?>>
                        <a href="petugas/v.html">
                            <div class="icon-img"><i class="fa fa-balance-scale bg-blue"></i></div>
                            <span>Operator</span>
                        </a>
                    </li>
                    <li <?php if ($menu == 'tambahnotaris') {
                        echo " class='active'";
                    } ?>>
                        <a href="tambahnotaris/v.html">
                            <div class="icon-img"><i class="fa fa-user-plus bg-purple"></i></div>
                            <span>Reg. Notaris</span>
                        </a>
                    </li>
                    <li <?php if ($menu == 'users' AND $sub_menu == 'v') {
                        echo " class='active'";
                    } ?>>
                        <a href="users/v.html">
                            <div class="icon-img"><i class="fa fa-users bg-orange"></i></div>
                            <span>Masyarakat Terdaftar</span>
                        </a>
                    </li>
                    <li <?php if ($menu == 'pengaduan' AND $sub_menu == 'v') {
                        echo " class='active'";
                    } ?>>
                        <a href="pengaduan/v.html">
                            <div class="icon-img"><i class="fa fa-comments bg-blue"></i></div>
                            <span>Aduan Masyarakat</span>
                        </a>
                    </li>
                    <li <?php if ($menu == 'notaris') {
                        echo " class='active'";
                    } ?>>
                        <a href="notaris/v.html">
                            <div class="icon-img"><i class="fa fa-user-circle bg-purple"></i></div>
                            <span>Daftar Notaris</span>
                        </a>
                    </li>
                    <li <?php if ($menu == 'laporan' AND $sub_menu == 'v') {
                        echo " class='active'";
                    } ?>>
                        <a href="laporan/v.html">
                            <div class="icon-img"><i class="fa fa-file-text bg-green"></i></div>
                            <span>SLaporan Notaris</span>
                        </a>
                    </li>
                    <li <?php if ($menu == 'cuti' AND $sub_menu == 'v') {
                        echo " class='active'";
                    } ?>>

                        <a style="" href="cuti/v.html">
                            <div class="icon-img"><i class="fa fa-umbrella bg-blue"></i></div>
                            <span>Permohonan Cutis</span>
                        </a>

                    </li>
                    <li  <?php if ($menu == 'persuratan' AND $sub_menu == 'v') {
                        echo " class='active'";
                    } ?>>
                        <a href="persuratan/v.html">
                            <div class="icon-img"><i class="fa fa-envelope bg-green"
                                                     style="background-color: red !important;"></i></div>
                            <span>PPengaduan</span>
                        </a>
                    </li>

                    <li <?php if ($menu == 'laporaduan' AND $sub_menu == 'v') {
                        echo " class='active'";
                    } ?>>
                        <a href="laporaduan/v.html">
                            <div class="icon-img"><i class="fa fa-bullhorn bg-red"
                                                     style="background-color: #e509ff !important;"></i>
                            </div>
                            <span>Laporan Aduanm</span>
                        </a>
                    </li>

                    <li class="nav-header"></li>
                    <li <?php if ($menu == 'slide') {
                        echo " class='active'";
                    } ?>>
                        <a href="slide/v.html">
                            <div class="icon-img"><i class="fa fa-newspaper-o bg-yellow"></i></div>
                            <span>Atur Informasi Publik</span>
                        </a>
                    </li>
                    <li class="has-sub <?php if ($menu == 'kategori') {
                        echo " active";
                    } ?>">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-cogs bg-gray"></i>
                            <span>Atur Kategori Aduan</span>
                        </a>
                        <ul class="sub-menu">
                            <li<?php if ($menu == 'kategori' AND $sub_menu = 'v') {
                                echo " class='active'";
                            } ?>><a href="kategori/v.html">Kategori Aduan</a></li>
                            <li<?php if ($menu == 'kategori' AND $sub_menu = 'sub') {
                                echo " class='active'";
                            } ?>><a href="kategori/sub.html">Sub Kategori</a></li>
                        </ul>
                    </li>
                    <li class="has-sub <?php if ($menu == 'kategori_lap') {
                        echo " active";
                    } ?>">
                        <a href="javascript:;">
                            <b class="caret pull-right"></b>
                            <i class="fa fa-cogs bg-gray"></i>
                            <span>Atur Kategori Laporan</span>
                        </a>
                        <ul class="sub-menu">
                            <li<?php if ($menu == 'kategori_lap' AND $sub_menu = 'v') {
                                echo " class='active'";
                            } ?>><a href="kategori_lap/v.html">Kategori Laporan</a></li>
                            <li<?php if ($menu == 'kategori_lap' AND $sub_menu = 'sub') {
                                echo " class='active'";
                            } ?>><a href="kategori_lap/sub.html">Sub Kategori</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <!-- akhir sesi super admin -->
                <!-- MENU PETUGAS-->
                <?php if ($level == 'petugas'): ?>
                    <li <?php if ($menu == 'users' AND $sub_menu == 'v') {
                        echo " class='active'";
                    } ?>>
                        <a href="users/v.html">
                            <div class="icon-img"><i class="fa fa-users bg-orange"></i></div>
                            <span>Data Masyarakat</span>
                        </a>
                    </li>
                    <li <?php if ($menu == 'pengaduan' AND $sub_menu == 'v') {
                        echo " class='active'";
                    } ?>>
                        <a href="pengaduan/v.html">
                            <div class="icon-img"><i class="fa fa-comments bg-blue"></i></div>
                            <span>Aduan Masyarakat</span>
                        </a>
                    </li>
                    <li <?php if ($menu == 'notaris') {
                        echo " class='active'";
                    } ?>>
                        <a href="notaris/v.html">
                            <div class="icon-img"><i class="fa fa-user-circle bg-purple"></i></div>
                            <span>Data Notaris</span>
                        </a>
                    </li>
                    <li <?php if ($menu == 'laporan' AND $sub_menu == 'v') {
                        echo " class='active'";
                    } ?>>
                        <a href="laporan/v.html">
                            <div class="icon-img"><i class="fa fa-file-text bg-yellow"></i></div>
                            <span>Laporan Notaris</span>
                        </a>
                    </li>
                <?php endif; ?>
                <!-- akhir sesi PETUGAS -->
                <!-- MENU USER -->
                <?php if ($level == 'user'): ?>
                    <li <?php if ($menu == 'pengaduan' AND $sub_menu == 'v') {
                        echo " class='active'";
                    } ?>>
                        <a href="pengaduan/v.html">
                            <div class="icon-img"><i class="fa fa-comments bg-blue"></i></div>
                            <span>Pengaduan</span>
                        </a>
                    </li>
                    <li class="nav-header"></li>
                    <li <?php if ($menu == 'notaris') {
                        echo " class='active'";
                    } ?>>
                        <a href="notaris/v.html">
                            <div class="icon-img"><i class="fa fa-info-circle bg-purple"></i></div>
                            <span>Daftar Notaris-NTB</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li <?php if ($menu == 'laporaduan' AND $sub_menu == 'v') {
                    echo " class='active'";
                } ?>>
                    <a href="laporaduan/v.html">
                        <div class="icon-img"><i class="fa fa-bullhorn bg-red"
                                                 style="background-color: #e509ff !important;"></i>
                        </div>
                        <span>Laporan Aduanm</span>
                    </a>
                </li>

                <li <?php if ($menu == 'cuti' AND $sub_menu == 'v') {
                    echo " class='active'";
                } ?>>
                    <!--menu view for mpd & notaris-->
                    <?php
                    $display = '';
                    if($level=="user"){
                        $display = 'display: none;';
                    }
                    ?>
                    <a style="<?= $display?>" href="cuti/v.html">
                        <div class="icon-img"><i class="fa fa-umbrella bg-blue"></i></div>
                        <span>Permohonan Cuti</span>
                    </a>

                </li>
                <!-- MENU NOTARIS -->
                <?php if ($level == 'notaris'): ?>
                    <li <?php if ($menu == 'laporan' AND $sub_menu == 'v') {
                        echo " class='active'";
                    } ?>>
                        <a href="laporan/v.html">
                            <div class="icon-img"><i class="fa fa-file-text bg-blue"></i></div>
                            <span>LAPORANnn</span>
                        </a>

                    </li>

                    <li>
                        <?php
                        $id_user = $this->session->userdata('id_user');
                        $row = $this->db->get_where('tbl_gdrive', array('user_id' => $id_user))->row();

                        $getLinkGdrive = $row ? $row->link_gdrive : null;

                        if (!empty($getLinkGdrive)) {
                            $href = $getLinkGdrive;
                            $target = 'target="_blank"';
                            $class = '';
                        } else {
                            $href = '#';
                            $target = '';
                            $class = 'disabled';
                        }
                        ?>
                        <style>
                            a.disabled {
                                pointer-events: none;
                                opacity: 0.5;
                                cursor: not-allowed;
                            }
                        </style>
                        <a href="<?= $href; ?>" <?= $target; ?> class="<?= $class; ?>">
                            <div class="icon-img"><i class="fa fa-google bg-orange"></i></div>
                            <span>Penyimpanan</span>
                        </a>

                    </li>
                <?php endif; ?>

                <!-- AKHIR MENU NOTARIS-->
                <li class="nav-header"></li>
                <li>
                    <a href="web/logout.html">
                        <div class="icon-img">
                            <i class="fa fa-sign-out bg-red"></i>
                        </div>
                        <span>Keluar</span>
                    </a>
                </li>
                <!-- begin sidebar minify button -->
                <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i
                                class="ion-ios-arrow-left"></i> <span>Kecilkan</span></a></li>
                <!-- end sidebar minify button -->
            </ul>
            <!-- end sidebar nav -->
        </div>
        <!-- end sidebar scrollbar -->
    </div>
    <div class="sidebar-bg"></div>
    <!-- end #sidebar -->
