<!-- Main content -->
<div class="content-wrapper">
    <!-- Content area -->
    <div class="content">

        <!-- Dashboard content -->
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-9">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                        <h4 class="panel-title"><?php echo $judul_web; ?></h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $this->session->flashdata('msg'); ?>

                        <form class="form-horizontal" action="" data-parsley-validate="true" method="post">

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th style="width:30%">Jenis File</th>
                                        <th style="width:70%">File</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $getData = $this->db->get_where("tbl_user_hasfile", array('user_id' => $id_notaris))->row()??"";
                                    $getKtp = $getData->ktp_path??"Belum Ada";
                                    $getIjazahSH = $getData->ijazah_sh_path??"Belum Ada";
                                    $getIjazahMagister = $getData->ijazah_magister_path??"Belum Ada";
                                    $getNpwp = $getData->npwp_path??"Belum Ada";
                                    $getPengangkatan = $getData->kepmenkum_pengangkatan_path??"Belum Ada";
                                    $getBaSumpah = $getData->berita_acara_sumpah_path??"Belum Ada";
                                    $getPindahWil = $getData->kepmenkum_pindah_wilayah_path??"Belum Ada";
                                    $getBaPindah = $getData->berita_acara_sumpah_pindah_path??"Belum Ada";
                                    ?>
                                    <tr>
                                        <td><b>KTP</b></td>
                                        <td>
                                            <a href="<?php echo $getKtp; ?>" target="_blank">
                                                <?php echo explode('/',$getKtp)[2]??"Belum Ada"; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Ijazah SH</b></td>
                                        <td>
                                            <a href="<?php echo $getIjazahSH; ?>" target="_blank">
                                                <?php echo explode('/',$getIjazahSH)[2]??"Belum Ada"; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Ijazah Magister</b></td>
                                        <td>
                                            <a href="<?php echo $getIjazahMagister; ?>" target="_blank">
                                                <?php echo explode('/',$getIjazahMagister)[2]??"Belum Ada"; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>NPWP</b></td>
                                        <td>
                                            <a href="<?php echo $getNpwp; ?>" target="_blank">
                                                <?php echo explode('/',$getNpwp)[2]??"Belum Ada"; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Keputusan Menteri Hukum tentang Pengangkatan Notaris</b></td>
                                        <td>
                                            <a href="<?php echo $getPengangkatan; ?>" target="_blank">
                                                <?php echo explode('/',$getPengangkatan)[2]??"Belum Ada"; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Berita Acara Sumpah Pengangkatan Notaris</b></td>
                                        <td>
                                            <a href="<?php echo $getBaSumpah; ?>" target="_blank">
                                                <?php echo explode('/',$getBaSumpah)[2]??"Belum Ada"; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Keputusan Menteri Hukum tentang Pindah Wilayah</b></td>
                                        <td>
                                            <a href="<?php echo $getPindahWil; ?>" target="_blank">
                                                <?php echo explode('/',$getPindahWil)[2]??"Belum Ada"; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>BA Sumpah Pindah Wilayah</b></td>
                                        <td>
                                            <a href="<?php echo $getBaPindah; ?>" target="_blank">
                                                <?php echo explode('/',$getBaPindah)[2]??"Belum Ada"; ?>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-right">
                                <a href="<?= strtolower($this->uri->segment(1))?>/<?= strtolower($this->uri->segment(2))?>" class="btn btn-default">Kembali</a>
                                <!--                                <button  type="submit" name="btnsimpan" class="btn btn-primary">Kirim</button>-->
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- /dashboard content -->
