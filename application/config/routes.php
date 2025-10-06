<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| DEFAULT ROUTE
|--------------------------------------------------------------------------
*/
$route['default_controller'] = 'web';
$route['404_override'] = 'web/error_not_found';
$route['translate_uri_dashes'] = FALSE;

/*
|--------------------------------------------------------------------------
| DASHBOARD UMUM (Role lama)
|--------------------------------------------------------------------------
*/
$route['dashboard'] = 'users/index';

/*
|--------------------------------------------------------------------------
| HALAMAN WEB (Publik)
|--------------------------------------------------------------------------
*/
$route['page']                    = 'web/index';
$route['page/(:any)']             = 'web/index/$1';
$route['page/pengurus/(:any)']    = 'web/pengurus/$1';
$route['index']                   = 'web/index';
$route['profile']                 = 'users/profile';
$route['ubah_pass']               = 'users/ubah_pass';
$route['dossier_pribadi']         = 'users/dossier_pribadi';
$route['tambahnotarisvl']         = 'tambahnotaris/v/l';
$route['unggah_file']             = 'users/unggah_file';

/*
|--------------------------------------------------------------------------
| MODUL KAS
|--------------------------------------------------------------------------
*/
$route['kas/masuk']               = 'data/kas/masuk';
$route['kas/keluar']              = 'data/kas/keluar';
$route['kas/rekap']               = 'data/kas/rekap';

/*
|--------------------------------------------------------------------------
| WEB UMUM
|--------------------------------------------------------------------------
*/
$route['download']                = 'web/download';
$route['kontak']                  = 'web/kontak';

/*
|--------------------------------------------------------------------------
| ROLE BARU MKN (Penting)
|--------------------------------------------------------------------------
*/
$route['sekretariat/dashboard']   = 'sekretariat/dashboard';
$route['anggota_mkn/dashboard']   = 'anggota_mkn/dashboard';
$route['aph/dashboard']           = 'aph/dashboard';

// Form permohonan APH
$route['aph/tambah_permohonan']      = 'aph/tambah_permohonan';
$route['aph/tambah_permohonan.html'] = 'aph/tambah_permohonan';
$route['aph/simpan_permohonan']      = 'aph/simpan_permohonan';

/*
|--------------------------------------------------------------------------
| ROUTE KOMPATIBILITAS / BACKWARD COMPATIBILITY
|--------------------------------------------------------------------------
*/
$route['sekretariat/dashboard.html'] = 'sekretariat/dashboard';
$route['anggota/dashboard']          = 'anggota_mkn/dashboard';
$route['anggota/dashboard.html']     = 'anggota_mkn/dashboard';
$route['aph/dashboard.html']         = 'aph/dashboard';

/*
|--------------------------------------------------------------------------
| ROUTE LOGIN DAN REGISTER (Fix login.html issue)
|--------------------------------------------------------------------------
| Ini memastikan semua URL lama yang mengarah ke .html tetap diarahkan ke controller.
|--------------------------------------------------------------------------
*/
$route['web/login']                 = 'web/login';
$route['web/login.html']            = 'web/login';

$route['web/user_register']         = 'web/user_register';
$route['web/user_register.html']    = 'web/user_register';

$route['web/index.html']            = 'web/index';
$route['web/beranda.html']          = 'web/index';

// Tambahan untuk root akses langsung
$route['login']                     = 'web/login';
$route['login.html']                = 'web/login';
$route['user_register']             = 'web/user_register';
$route['user_register.html']        = 'web/user_register';
/*
|--------------------------------------------------------------------------
| ROUTE LOGOUT (Fix logout.html issue)
|--------------------------------------------------------------------------
*/
$route['web/logout'] = 'web/logout';
$route['web/logout.html'] = 'web/logout';

// Untuk jaga-jaga kalau ada link langsung tanpa /web/
$route['logout'] = 'web/logout';
$route['logout.html'] = 'web/logout';
