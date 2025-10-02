<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'web';
$route['404_override'] = 'web/error_not_found';
$route['translate_uri_dashes'] = FALSE;

// Dashboard umum (role lama)
$route['dashboard']    = 'users/index';

// Halaman Web
$route['page']         = 'web/index';
$route['page/(:any)']  = 'web/index/$1';
$route['page/pengurus/(:any)']  = 'web/pengurus/$1';
$route['index']        = 'web/index';
$route['profile']      = 'users/profile';
$route['ubah_pass']    = 'users/ubah_pass';
$route['dossier_pribadi'] = 'users/dossier_pribadi';
$route['tambahnotarisvl'] = 'tambahnotaris/v/l';
$route['unggah_file']  = 'users/unggah_file';

// Modul Kas
$route['kas/masuk']    = 'data/kas/masuk';
$route['kas/keluar']   = 'data/kas/keluar';
$route['kas/rekap']    = 'data/kas/rekap';

// Web umum
$route['download']     = 'web/download';
$route['kontak']       = 'web/kontak';

// Tambahan untuk role baru
$route['sekretariat/dashboard'] = 'sekretariat/dashboard';
$route['anggota_mkn/dashboard'] = 'anggota_mkn/dashboard';
$route['aph/dashboard']         = 'aph/dashboard';

// Redirect URL lama agar tetap jalan
$route['anggota/dashboard']      = 'anggota_mkn/dashboard';
$route['anggota/dashboard.html'] = 'anggota_mkn/dashboard';
$route['aph/dashboard.html']     = 'aph/dashboard';

// Jika ada halaman lain yang ingin di-mapping manual, bisa ditambahkan di sini
