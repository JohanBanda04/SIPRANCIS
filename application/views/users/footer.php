

<!-- begin scroll to top btn -->
<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
<!-- end scroll to top btn -->
</div>
<!-- end page container -->

<!-- ================== BEGIN BASE JS ================== -->
<script src="assets/panel/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="assets/panel/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="assets/panel/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="assets/panel/plugins/bootstrap/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
  <script src="assets/crossbrowserjs/html5shiv.js"></script>
  <script src="assets/crossbrowserjs/respond.min.js"></script>
  <script src="assets/crossbrowserjs/excanvas.min.js"></script>
<![endif]-->
<script src="assets/panel/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/panel/plugins/jquery-cookie/jquery.cookie.js"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/panel/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="assets/panel/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/panel/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/panel/js/table-manage-default.demo.min.js"></script>
<!-- <script src="assets/panel/js/dashboard-v2.min.js"></script> -->
<script src="assets/panel/plugins/parsley/dist/parsley.js"></script>

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/panel/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/panel/plugins/ionRangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
<script src="assets/panel/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="assets/panel/plugins/masked-input/masked-input.min.js"></script>
<script src="assets/panel/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="assets/panel/plugins/password-indicator/js/password-indicator.js"></script>
<script src="assets/panel/plugins/bootstrap-combobox/js/bootstrap-combobox.js"></script>
<script src="assets/panel/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="assets/panel/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<script src="assets/panel/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.js"></script>
<script src="assets/panel/plugins/jquery-tag-it/js/tag-it.min.js"></script>
  <script src="assets/panel/plugins/bootstrap-daterangepicker/moment.js"></script>
  <script src="assets/panel/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="assets/panel/plugins/select2/dist/js/select2.min.js"></script>
  <script src="assets/panel/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <script src="assets/panel/plugins/bootstrap-show-password/bootstrap-show-password.js"></script>
  <script src="assets/panel/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js"></script>
  <script src="assets/panel/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.js"></script>
  <script src="assets/panel/plugins/clipboard/clipboard.min.js"></script>
<script src="assets/panel/js/form-plugins.demo.min.js"></script>

<script src="assets/panel/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<?php
// Ambil segment URI
$uri1 = strtolower($this->uri->segment(1)); // contoh: 'notaris'
$uri2 = strtolower($this->uri->segment(2)); // contoh: 'index'

// Gabungkan untuk kemudahan pengecekan
$current_page = $uri1 . '/' . $uri2;
?>

<script>
  $(document).ready(function() {
      let uri1 = "<?php echo $uri1; ?>";
      let uri2 = "<?php echo $uri2; ?>";
    App.init();
    // DashboardV2.init();
    //   if(uri1 !== "notaris" && uri2 === "v"){
    //       TableManageDefault.init();
    //   }
    TableManageDefault.init();
			FormPlugins.init();
  });
</script>
<script type="text/javascript">
function hanyaAngka(evt) {
  var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))

    return false;
  return true;
}

/* Fungsi */
function formatRupiah(angka, prefix)
{
  var number_string = angka.replace(/[^,\d]/g, '').toString(),
    split	= number_string.split(','),
    sisa 	= split[0].length % 3,
    rupiah 	= split[0].substr(0, sisa),
    ribuan 	= split[0].substr(sisa).match(/\d{3}/gi);

  if (ribuan) {
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }

  rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
  return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
}

notif();
function notif()
{
  var audio = new Audio('assets/sound/notif.mp3');
  $.get("<?php echo base_url()?>web/notif_bell/pesan_baru", function(data){
    if (data==1) {
      audio.play();
    }else if (data==11) {
      window.location.href="<?php echo base_url(); ?>web/logout";
    }
  },"json");

  jml_data2 = "<?php echo base_url()?>web/notif_bell/jml";
  $("#jml_notif_bell").load(jml_data2);

  data2 = "<?php echo base_url()?>web/notif_bell";
  $("#notif_bell").load(data2);
}
setInterval(notif, 2000); //2 detik
</script>

</body>

</html>
