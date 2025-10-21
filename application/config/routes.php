<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|---------------------------------------------------------------------------
| DEFAULT ROUTE
|---------------------------------------------------------------------------
*/
$route['default_controller']   = 'web';
$route['404_override']         = 'web/error_not_found';
$route['translate_uri_dashes'] = FALSE;

/*
|---------------------------------------------------------------------------
| DASHBOARD UMUM (Role lama non-MKN: admin / user / notaris dkk.)
|---------------------------------------------------------------------------
*/
$route['dashboard'] = 'users/index';

/*
|---------------------------------------------------------------------------
| HALAMAN WEB (Publik)
|---------------------------------------------------------------------------
*/
$route['page']                 = 'web/index';
$route['page/(:any)']          = 'web/index/$1';
$route['page/pengurus/(:any)'] = 'web/pengurus/$1';
$route['index']                = 'web/index';
$route['profile']              = 'users/profile';
$route['ubah_pass']            = 'users/ubah_pass';
$route['dossier_pribadi']      = 'users/dossier_pribadi';
$route['tambahnotarisvl']      = 'tambahnotaris/v/l';
$route['unggah_file']          = 'users/unggah_file';

/*
|---------------------------------------------------------------------------
| MODUL KAS
|---------------------------------------------------------------------------
*/
$route['kas/masuk']  = 'data/kas/masuk';
$route['kas/keluar'] = 'data/kas/keluar';
$route['kas/rekap']  = 'data/kas/rekap';

/*
|---------------------------------------------------------------------------
| WEB UMUM
|---------------------------------------------------------------------------
*/
$route['download'] = 'web/download';
$route['kontak']   = 'web/kontak';

/*
|---------------------------------------------------------------------------
| ROLE BARU MKN (FINAL ONLY) — TANPA ALIAS LAMA
|---------------------------------------------------------------------------
*/
/* Sekretariat MKN */
$route['sekretariat_mkn']                  = 'Sekretariat_mkn/index';
$route['sekretariat_mkn/index']            = 'Sekretariat_mkn/index';
$route['sekretariat_mkn/detail/(:num)']    = 'Sekretariat_mkn/detail/$1';
$route['sekretariat_mkn/surat/(:num)']     = 'Sekretariat_mkn/form_surat/$1';
$route['sekretariat_mkn/surat/simpan']     = 'Sekretariat_mkn/simpan_surat';
$route['sekretariat_mkn/surat/bawa/(:num)']= 'Sekretariat_mkn/toggle_bawa/$1';
$route['sekretariat_mkn/kirim_ke_anggota/(:num)'] = 'Sekretariat_mkn/kirim_ke_anggota/$1';

/* Anggota MKN */
$route['anggota_mkn']                      = 'Anggota_mkn/index';
$route['anggota_mkn/index']                = 'Anggota_mkn/index';
$route['anggota_mkn/periksa/(:num)']       = 'Anggota_mkn/periksa/$1';
$route['anggota_mkn/simpan']               = 'Anggota_mkn/simpan_pemeriksaan';

/* APH */
$route['aph/pengajuan']                    = 'Aph/form_pengajuan';
$route['aph/pengajuan.html']               = 'Aph/form_pengajuan';
$route['aph/simpan_pengajuan']             = 'Aph/simpan_pengajuan';

/*
|---------------------------------------------------------------------------
| LOGIN & REGISTER (termasuk variasi .html)
|---------------------------------------------------------------------------
*/
$route['web/login']               = 'web/login';
$route['web/login.html']          = 'web/login';
$route['web/user_register']       = 'web/user_register';
$route['web/user_register.html']  = 'web/user_register';
$route['web/index.html']          = 'web/index';
$route['web/beranda.html']        = 'web/index';

/* Root akses langsung */
$route['login']                   = 'web/login';
$route['login.html']              = 'web/login';
$route['user_register']           = 'web/user_register';
$route['user_register.html']      = 'web/user_register';

/*
|---------------------------------------------------------------------------
| LOGOUT (termasuk variasi .html)
|---------------------------------------------------------------------------
*/
$route['web/logout']      = 'web/logout';
$route['web/logout.html'] = 'web/logout';
$route['logout']          = 'web/logout';
$route['logout.html']     = 'web/logout';
