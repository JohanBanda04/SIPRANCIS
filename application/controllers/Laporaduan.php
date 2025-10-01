<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporaduan extends CI_Controller
{

    public function index()
    {

        $data['judul_web'] = "Laporan";
        $this->db->order_by('id_laporan', 'DESC');
        $data['query'] = $this->db->get("tbl_laporan");
        $this->load->view('web/header', $data);
        $this->load->view('web/laporan', $data);
        $this->load->view('web/footer', $data);
    }

    public function cek($no_idn = '')
    {
        $data['judul_web'] = "Laporan";
        if ($no_idn != '') {
            $this->db->join('tbl_data_notaris', 'tbl_data_notaris.id_user=tbl_laporan.user');
            $this->db->order_by('id_laporan', 'DESC');
            $data['query'] = $this->db->get_where("tbl_laporan", array('no_idn' => "$no_idn"));
        }
        $data['no_idn'] = $no_idn;
        $this->load->view('web/header', $data);
        $this->load->view('web/cek', $data);
        $this->load->view('web/footer', $data);
    }

    public function v($aksi = '', $id = '')
    {
        $id = hashids_decrypt($id);
        $ceks = $this->session->userdata('username');
        $id_user = $this->session->userdata('id_user');
        $level = $this->session->userdata('level');
        if (!isset($ceks)) {
            redirect('web/login');
        }

        $data['user'] = $this->Mcrud->get_users_by_un($ceks);

        if ($aksi == 'getNotaris') {
            $mpd_id = $this->input->get('mpd_id'); // dikirim via query string ?mpd_id=...
            $notaris = $this->db->get_where('tbl_data_notaris', ['mpd_area_id' => $mpd_id])->result();

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($notaris));
            return; // hentikan biar tidak render view
        }

        if ($level == 'petugas') {
            $this->db->where('status!=', 'proses');
            $this->db->where('petugas', $id_user);
        }
        if ($level == 'user') {
            $this->db->where('user', $id_user);
        }

        if ($level == 'notaris') {
            $getNamaNotaris = $this->db->get_where('tbl_user', array('id_user' => $this->session->userdata('id_user')))
                ->row()->nama_lengkap;

            /*Normalisasi nama -> hapus spasi & tanda baca, lowercase*/
            /*triggerjo normalisasi nama*/
            $normalizedNama = strtolower(preg_replace('/[^a-z]/i', '', $getNamaNotaris));

            // Cari di sub_kategori dengan LIKE atau manipulasi
            $this->db->like('LOWER(REPLACE(REPLACE(REPLACE(nama_sub_kategori,".",""),",","")," ",""))', $normalizedNama, 'both', false);
            $getIdSubKategori = $this->db->get('tbl_sub_kategori')->row()->id_sub_kategori;

            $this->db->where('id_sub_kategori', $getIdSubKategori);


        }


        if ($aksi == 'proses' or $aksi == 'konfirmasi' or $aksi == 'selesai') {
            $this->db->where('status', $aksi);
        }
        $this->db->order_by('id_pengaduan', 'DESC');
        $data['query'] = $this->db->get("tbl_pengaduan");

        /*ambil data mpd dari tbl_petugas*/
        $this->db->order_by('id_petugas', 'DESC');
        $data['query_mpd'] = $this->db->get("tbl_petugas");

        /*ambil data notaris dari tbl_data_notaris*/
        $this->db->order_by('id_data_notaris', 'ASC');
        $data['query_data_notaris'] = $this->db->get("tbl_data_notaris");
        //echo "<pre>"; print_r($data['query_kedudukan']->result());die;
        //echo "mbokcit"; die;
        //echo "<pre>"; print_r($data['query']->result()); die;

        $cek_notif = $this->db->get_where("tbl_notif", array('penerima' => "$id_user"));
        //echo "<pre>"; print_r($cek_notif->result());die;
        foreach ($cek_notif->result() as $key => $value) {
            $b_notif = $value->baca_notif;
            if (!preg_match("/$id_user/i", $b_notif)) {
                $data_notif = array('baca_notif' => "$id_user, $b_notif");
                $this->db->update('tbl_notif', $data_notif, array('penerima' => $id_user));
            }
        }

        if ($aksi == 't') {
            /*nanti aktifkan untuk ngejaga hak akses*/
            /*if ($level!='user') {
                redirect('404');
            }*/
            $p = "tambah";
            $data['judul_web'] = "BUAT ADUAN BARU";
        } elseif ($aksi == 'd') {
            //echo $id; die;
            $p = "detail";
            $data['judul_web'] = "Detail Pengaduan";
            $data['query'] = $this->db->get_where("tbl_pengaduan", array('id_pengaduan' => "$id"))->row();
            $data['query_tbl_aduan_hasfile'] = $this->db->get_where('tbl_aduan_hasfile', array('pengaduan_id' => $id))->row();
            //echo "<pre>"; print_r($data['query_tbl_aduan_hasfile']); die;
            if ($data['query']->id_pengaduan == '') {
                redirect('404');
            }
        } elseif ($aksi == 'h') {
            $cek_data = $this->db->get_where("tbl_pengaduan", array('id_pengaduan' => "$id"));
            if ($cek_data->num_rows() != 0) {
                if ($cek_data->row()->status != 'proses') {
                    redirect('404');
                }
                if ($cek_data->row()->bukti != '') {
                    unlink($cek_data->row()->bukti);
                }
                $this->db->delete('tbl_notif', array('pengirim' => $id_user, 'id_pengaduan' => $id));
                $this->db->delete('tbl_pengaduan', array('id_pengaduan' => $id));
                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil dihapus.
							</div>
							<br>'
                );
                redirect("laporaduan/v");
            } else {
                redirect('404_content');
            }
        } else {
            $p = "index";
            $data['judul_web'] = "Laporan Aduan";
        }

        $this->load->view('users/header', $data);
        $this->load->view("users/laporaduan/$p", $data);
        $this->load->view('users/footer');

        date_default_timezone_set('Asia/Jakarta');
        $tgl = date('Y-m-d H:i:s');

        $lokasi = 'file/aduan_files';
        $file_size = 1024 * 3; // 3 MB
        $this->upload->initialize(array(
            "upload_path" => "./$lokasi",
            "allowed_types" => "*",
            "max_size" => "$file_size"
        ));


        if (isset($_POST['btnupdate_status'])) {
            $id_pengaduan = $this->input->post('id_pengaduan');
            $status_baru  = $this->input->post('status');

            if (!empty($id_pengaduan) && !empty($status_baru)) {
                // --- Update status di tbl_pengaduan
                $this->db->where('id_pengaduan', $id_pengaduan);
                $this->db->update('tbl_pengaduan', ['status' => $status_baru]);

                // --- Ambil data pelapor
                $rowPengaduan = $this->db->get_where('tbl_pengaduan', ['id_pengaduan' => $id_pengaduan])->row();
                if ($rowPengaduan) {
                    $id_pelapor = $rowPengaduan->user; // id_user pelapor

                    // --- Insert notifikasi
                    $dataNotif = [
                        'pengirim'     => $this->session->userdata('id_user'),
                        'penerima'     => $id_pelapor,
                        'pesan'        => "Status aduan Anda telah diubah menjadi <b>" . strtoupper($status_baru) . "</b>",
                        'link'         => base_url('laporaduan/v/d/' . hashids_encrypt($id_pengaduan)),
                        'id_pengaduan' => $id_pengaduan,
                        'id_laporan'   => null,
                        'tgl_notif'    => date('Y-m-d H:i:s'),
                        'baca_notif'   => 0,
                        'hapus_notif'  => 0
                    ];
                    $this->db->insert('tbl_notif', $dataNotif);
                }

                /* =========================
                   Upload lampiran MODE KONFIRMASI (tidak termasuk MPW)
                   ========================= */
                if ($this->session->userdata('level') == "petugas" && $status_baru == "konfirmasi") {
                    // Pastikan folder tujuan ada
                    $uploadPath = './file/aduan_files/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $config = [
                        'upload_path'   => $uploadPath,
                        'allowed_types' => 'pdf|jpg|jpeg|png|doc|docx',
                        'max_size'      => 5000, // 5 MB
                    ];

                    $this->load->library('upload');
                    $this->upload->initialize($config);

                    // HAPUS MPW dari daftar agar hanya diproses di status 'selesai'
                    $lampiranFields = [
                        'lampiran_pemberitahuan'   => 'surat_pemberitahuan',
                        'lampiran_undangan'        => 'surat_undangan',
                        'lampiran_pemanggilan'     => 'surat_pemanggilan',
                        'lampiran_bap_ttd'         => 'undangan_ttd_bap',
                        'lampiran_bap_pemeriksaan' => 'bap_pemeriksaan_has_ttd',
                        // 'lampiran_laporan_mpw'   => 'surat_laporan_ke_mpw' // <-- DIPINDAH KE BLOK 'SELESAI'
                    ];

                    $dataFile = ['pengaduan_id' => $id_pengaduan];

                    foreach ($lampiranFields as $inputName => $dbField) {
                        if (!empty($_FILES[$inputName]['name'])) {
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload($inputName)) {
                                $fileData = $this->upload->data();
                                $dataFile[$dbField] = 'file/aduan_files/' . $fileData['file_name']; // simpan path relatif
                            } else {
                                log_message('error', 'Upload gagal ' . $inputName . ': ' . $this->upload->display_errors());
                            }
                        }
                    }

                    // Simpan hanya kalau ada file berhasil diupload
                    if (count($dataFile) > 1) {
                        $cek = $this->db->get_where('tbl_aduan_hasfile', ['pengaduan_id' => $id_pengaduan])->row();
                        if ($cek) {
                            $this->db->where('pengaduan_id', $id_pengaduan)->update('tbl_aduan_hasfile', $dataFile);
                        } else {
                            $this->db->insert('tbl_aduan_hasfile', $dataFile);
                        }
                    }
                }

                /* =========================
                   Upload SURAT PENOLAKAN saat status = TOLAK
                   ========================= */
                if ($this->session->userdata('level') == "petugas" && $status_baru == "tolak") {
                    $uploadPath = './file/aduan_files/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    $configTolak = [
                        'upload_path'   => $uploadPath,
                        'allowed_types' => 'pdf|jpg|jpeg|png|doc|docx',
                        'max_size'      => 5000,
                    ];

                    $this->load->library('upload');
                    $this->upload->initialize($configTolak);

                    if (!empty($_FILES['surat_penolakan']['name'])) {
                        if ($this->upload->do_upload('surat_penolakan')) {
                            $fileData = $this->upload->data();
                            $pathRel  = 'file/aduan_files/' . $fileData['file_name'];

                            $cek = $this->db->get_where('tbl_aduan_hasfile', ['pengaduan_id' => $id_pengaduan])->row();

                            // hapus file lama (opsional)
                            if ($cek && !empty($cek->surat_penolakan)) {
                                $old = FCPATH . $cek->surat_penolakan;
                                if (is_file($old)) { @unlink($old); }
                            }

                            $dataFile = [
                                'pengaduan_id'    => $id_pengaduan,
                                'surat_penolakan' => $pathRel
                            ];

                            if ($cek) {
                                $this->db->where('pengaduan_id', $id_pengaduan)->update('tbl_aduan_hasfile', $dataFile);
                            } else {
                                $this->db->insert('tbl_aduan_hasfile', $dataFile);
                            }
                        } else {
                            $err = strip_tags($this->upload->display_errors('', ''));
                            $this->session->set_flashdata('msg',
                                '<div class="alert alert-danger">Gagal mengunggah Surat Penolakan: '.$err.'</div>'
                            );
                            redirect('laporaduan/v');
                            return; // stop eksekusi
                        }
                    }
                }

                /* =========================
                   Upload SURAT LAPORAN KE MPW saat status = SELESAI
                   ========================= */
                if ($this->session->userdata('level') == "petugas" && $status_baru == "selesai") {
                    $uploadPath = './file/aduan_files/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    $configMPW = [
                        'upload_path'   => $uploadPath,
                        'allowed_types' => 'pdf|jpg|jpeg|png|doc|docx',
                        'max_size'      => 5000,
                    ];

                    $this->load->library('upload');
                    $this->upload->initialize($configMPW);

                    if (!empty($_FILES['lampiran_laporan_mpw']['name'])) {
                        if ($this->upload->do_upload('lampiran_laporan_mpw')) {
                            $fileData = $this->upload->data();
                            $pathRel  = 'file/aduan_files/' . $fileData['file_name'];

                            $cek = $this->db->get_where('tbl_aduan_hasfile', ['pengaduan_id' => $id_pengaduan])->row();

                            // hapus file MPW lama (opsional)
                            if ($cek && !empty($cek->surat_laporan_ke_mpw)) {
                                $old = FCPATH . $cek->surat_laporan_ke_mpw;
                                if (is_file($old)) { @unlink($old); }
                            }

                            $dataFile = [
                                'pengaduan_id'           => $id_pengaduan,
                                'surat_laporan_ke_mpw'   => $pathRel
                            ];

                            if ($cek) {
                                $this->db->where('pengaduan_id', $id_pengaduan)->update('tbl_aduan_hasfile', $dataFile);
                            } else {
                                $this->db->insert('tbl_aduan_hasfile', $dataFile);
                            }
                        } else {
                            $err = strip_tags($this->upload->display_errors('', ''));
                            $this->session->set_flashdata('msg',
                                '<div class="alert alert-danger">Gagal mengunggah Surat Laporan ke MPW: '.$err.'</div>'
                            );
                            redirect('laporaduan/v');
                            return; // stop eksekusi
                        }
                    }
                }

                /* =========================
                   HAPUS surat_penolakan jika status bergeser dari 'tolak' → selain 'tolak'
                   dan user memang minta dihapus (flag dari form/JS)
                   ========================= */
                $remove = $this->input->post('remove_surat_penolakan');
                if ($this->session->userdata('level') == "petugas" && $status_baru !== 'tolak' && $remove == '1') {
                    $cek = $this->db->get_where('tbl_aduan_hasfile', ['pengaduan_id' => $id_pengaduan])->row();
                    if ($cek && !empty($cek->surat_penolakan)) {
                        $old = FCPATH . $cek->surat_penolakan;
                        if (is_file($old)) { @unlink($old); }
                        $this->db->where('pengaduan_id', $id_pengaduan)
                            ->update('tbl_aduan_hasfile', ['surat_penolakan' => NULL]);
                    }
                }

                // --- Feedback ke user
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Status berhasil diperbarui dan notifikasi terkirim.</div>');
                redirect('laporaduan/v');

            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Data tidak lengkap.</div>');
                redirect('laporaduan/v');
            }
        }


        if (isset($_POST['btnsimpan'])) {

            $mpd_area_id = htmlentities(strip_tags($this->input->post('mpd_area_id')));
            //echo "<pre>"; print_r($this->db->get_where('tbl_petugas',array('id_petugas'=>$mpd_area_id))->row()->id_user);die;
            $petugas = $this->db->get_where('tbl_petugas', array('id_petugas' => $mpd_area_id))->row()->id_user ?? "";
            $id_data_notaris = htmlentities(strip_tags($this->input->post('id_data_notaris')));
            $isi_pengaduan = htmlentities(strip_tags($this->input->post('isi_pengaduan')));
            $ket_pengaduan = htmlentities(strip_tags($this->input->post('ket_pengaduan')));

            //echo $id_data_notaris; die;
            //$getNamaNotaris = $this->db->get_where("tbl_data_notaris",array(''=>$id_data_notaris))->row();
            $data['getNamaNotaris'] = $this->db->get_where("tbl_data_notaris", array('id_data_notaris' => "$id_data_notaris"))->row()->nama;
            //echo $data['getNamaNotaris']; die;
            $getNamaNotaris = trim($data['getNamaNotaris']);

// hilangkan spasi & titik dari string pencarian
            $searchNama = str_replace([' ', '.'], '', strtoupper($getNamaNotaris));

            $this->db->select('*');
            $this->db->from('tbl_sub_kategori');
// hilangkan spasi & titik dari kolom juga
            $this->db->where("REPLACE(REPLACE(UPPER(nama_sub_kategori),' ',''),'.','') LIKE '%$searchNama%'");

            $result = $this->db->get();
            $data['cekSubKategori'] = $result->row();
            $data['cekSubKategoriRows'] = $result->num_rows();

// debug
            $id_sub_kategori = null;
            if ($data['cekSubKategoriRows'] <= 0) {
                //insert to tbl_sub_kategori
                //$id_sub_kategori=
                $data_sub_kategori = array(
                    'id_kategori' => 2,
                    'nama_sub_kategori' => $getNamaNotaris,
                );
                $this->db->insert('tbl_sub_kategori', $data_sub_kategori);
                $id_sub_kategori = $this->db->insert_id();

            } else if ($data['cekSubKategoriRows'] > 0) {
                //$id_sub_kategori
                //echo "<pre>"; print_r($data['cekSubKategori']); die;
                $id_sub_kategori = $data['cekSubKategori']->id_sub_kategori ?? "";
            }

            // default
            $simpan = 'n';
            $pesan = '';
            $bukti = null;

            // lokasi penyimpanan
            $lokasi = 'file/aduan_files';

            //echo "<pre>"; print_r($this->input->post()); die;
            // cek & buat folder jika belum ada
            if (!is_dir($lokasi)) {
                mkdir($lokasi, 0777, true);
            }

            // config upload
            $new_name = time() . '_' . $_FILES['daduk_aduan']['name'];
            $new_name = preg_replace('/\s+/', '_', $new_name); // ganti spasi jadi _
            $config['upload_path'] = $lokasi;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['max_size'] = 5120; // 5 MB
            $config['file_name'] = $new_name;
            $this->upload->initialize($config);

            // upload file
            if (!$this->upload->do_upload('daduk_aduan')) {
                $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
            } else {
                $gbr = $this->upload->data();
                $bukti = $lokasi . "/" . $gbr['file_name'];
                $simpan = 'y';
            }


            if ($simpan == 'y') {
                $data = array(
                    'isi_pengaduan' => $isi_pengaduan,
                    'ket_pengaduan' => $ket_pengaduan,
                    'bukti' => $bukti,
                    'user' => $id_user,
                    'notaris_id' => $id_data_notaris,
                    'petugas' => $petugas,
                    'status' => 'proses',
                    'tgl_pengaduan' => $tgl,
                    // kolom lain biarkan null/otomatis
                    'id_kategori' => '2',
                    'id_sub_kategori' => $id_sub_kategori,
                    'pesan_petugas' => NULL,
                    'file_petugas' => NULL,
                    'tgl_konfirmasi' => NULL,
                    'tgl_selesai' => NULL,
                );

                $this->db->insert('tbl_pengaduan', $data);
                $id_pengaduan = $this->db->insert_id();

                $data['getMpdUserId'] = $this->db->get_where('tbl_petugas', array('id_petugas' => $mpd_area_id))->row()->id_user;
                $data['getMpwUserId'] = '1';
                //echo $data['getMpdUserId']; die;
                //echo $id_user; die;

                $notif_to_mpd = array();
                $notif_to_mpw = array();
                $notif_to_user = array();

                if ($this->session->userdata('level') == 'user') {
                    //kirim notif ke mpw dan mpd terkait
                    $notif_to_mpd = array(
                        'pengirim' => $id_user,
                        'penerima' => $data['getMpdUserId'], // arahkan ke petugas (MPD) yang dipilih dan MPW tp user_id nya,miss
                        'pesan' => 'Pengaduan baru telah dikirim.',
                        'link' => 'laporaduan/v/d/' . hashids_encrypt($id_pengaduan),
                        'id_pengaduan' => $id_pengaduan,
                        'id_laporan' => NULL,
                        'tgl_notif' => date('Y-m-d H:i:s'),
                        'baca_notif' => 0,
                        'hapus_notif' => 0,
                        'is_read' => 0,
                    );
                    $this->db->insert('tbl_notif', $notif_to_mpd);

                    $notif_to_mpw = array(
                        'pengirim' => $id_user,
                        'penerima' => $data['getMpwUserId'], // arahkan ke petugas (MPD) yang dipilih dan MPW tp user_id nya,miss
                        'pesan' => 'Pengaduan baru telah dikirim.',
                        'link' => 'laporaduan/v/d/' . hashids_encrypt($id_pengaduan),
                        'id_pengaduan' => $id_pengaduan,
                        'id_laporan' => NULL,
                        'tgl_notif' => date('Y-m-d H:i:s'),
                        'baca_notif' => 0,
                        'hapus_notif' => 0,
                        'is_read' => 0
                    );
                    $this->db->insert('tbl_notif', $notif_to_mpw);

                } else if ($this->session->userdata('level') == 'superadmin' || $this->session->userdata('level') == 'petugas') {
                    //echo $this->session->userdata('level');
                    $notif_to_user = array();
                }


                $this->session->set_flashdata('msg',
                    '
        <div class="alert alert-success alert-dismissible" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
             <strong>Sukses!</strong> Berhasil disimpan.
        </div>
        <br>'
                );

            } else {
                $this->session->set_flashdata('msg',
                    '
        <div class="alert alert-warning alert-dismissible" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
             <strong>Gagal!</strong> ' . $pesan . '.
        </div>
        <br>'
                );

                if (!empty($id)) {
                    redirect("laporaduan/v/$aksi/" . hashids_encrypt($id));
                } else {
                    redirect("laporaduan/v/$aksi");
                }
            }
            //redirect("laporaduan/v");
            redirect("laporaduan/v");
        }


        if (isset($_POST['btnkirim'])) {
            $id_pengaduan = htmlentities(strip_tags($this->input->post('id_pengaduan')));
            $data_lama = $this->db->get_where('tbl_pengaduan', array('id_pengaduan' => $id_pengaduan))->row();
            $simpan = 'y';
            $pesan = '';
            if ($level == 'superadmin') {
                $id_petugas = htmlentities(strip_tags($this->input->post('id_petugas')));
                $data = array(
                    'petugas' => $id_petugas,
                    'status' => 'konfirmasi',
                    'tgl_konfirmasi' => $tgl
                );
                $pesan = 'Berhasil dikirim ke petugas';
                $this->Mcrud->kirim_notif('superadmin', $id_petugas, $id_pengaduan, 'superadmin_ke_petugas');
                $this->Mcrud->kirim_notif('superadmin', $data_lama->user, $id_pengaduan, 'superadmin_ke_user');
            } else {
                $pesan_petugas = htmlentities(strip_tags($this->input->post('pesan_petugas')));
                $status = htmlentities(strip_tags($this->input->post('status')));
                $file = $data_lama->file_petugas;
                $pesan = 'Berhasil disimpan';
                if ($_FILES['file']['error'] <> 4) {
                    if (!$this->upload->do_upload('file')) {
                        $simpan = 'n';
                        $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                    } else {
                        if ($file != '') {
                            unlink("$file");
                        }
                        $gbr = $this->upload->data();
                        $filename = "$lokasi/" . $gbr['file_name'];
                        $file = preg_replace('/ /', '_', $filename);
                    }
                }

                $data = array(
                    'pesan_petugas' => $pesan_petugas,
                    'status' => $status,
                    'file_petugas' => $file,
                    'tgl_selesai' => $tgl
                );
                $this->Mcrud->kirim_notif($data_lama->petugas, $data_lama->user, $id_pengaduan, 'petugas_ke_user');
            }

            if ($simpan == 'y') {
                $this->db->update('tbl_pengaduan', $data, array('id_pengaduan' => $id_pengaduan));
                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> ' . $pesan . '.
							</div>
						 <br>'
                );
            } else {
                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-warning alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Gagal!</strong> ' . $pesan . '.
							</div>
						 <br>'
                );
            }
            redirect('pengaduan/v');
        }

    }

    public function v_old($aksi = '', $id = '')
    {
        //echo "pret"; die;
        $id = hashids_decrypt($id);
        $ceks = $this->session->userdata('username');
        $id_user = $this->session->userdata('id_user');
        $level = $this->session->userdata('level');
        if (!isset($ceks)) {
            redirect('web/login');
        }

        $data['user'] = $this->Mcrud->get_users_by_un($ceks);

        if ($level == 'petugas') {
            $this->db->where('status!=', 'proses');
            $this->db->where('petugas', $id_user);
        }
        if ($level == 'notaris') {
            $this->db->where('notaris', $id_user);
        }
        if ($aksi == 'proses' or $aksi == 'konfirmasi' or $aksi == 'selesai') {
            $this->db->where('status', $aksi);
        }
        $this->db->order_by('id_laporan', 'DESC');
        $data['query'] = $this->db->get("tbl_laporan");

        $cek_notif = $this->db->get_where("tbl_notif", array('penerima' => "$id_user"));
        foreach ($cek_notif->result() as $key => $value) {
            $b_notif = $value->baca_notif;
            if (!preg_match("/$id_user/i", $b_notif)) {
                $data_notif = array('baca_notif' => "$id_user, $b_notif");
                $this->db->update('tbl_notif', $data_notif, array('penerima' => $id_user));
            }
        }

        if ($aksi == 't') {
            if ($level != 'notaris') {
                redirect('404');
            }
            $p = "tambah";
            $data['judul_web'] = "BUAT LAPORAN BARU";
        } elseif ($aksi == 'd') {
            $p = "detail";
            $data['judul_web'] = "RINCIAN LAPORAN";
            $data['query'] = $this->db->get_where("tbl_laporan", array('id_laporan' => "$id"))->row();
            if ($data['query']->id_laporan == '') {
                redirect('404');
            }
        } elseif ($aksi == 'h') {
            $cek_data = $this->db->get_where("tbl_laporan", array('id_laporan' => "$id"));
            if ($cek_data->num_rows() != 0) {
                if ($cek_data->row()->status != 'proses') {
                    redirect('404');
                }
                if ($cek_data->row()->lampiran != '') {
                    unlink($cek_data->row()->lampiran);
                }
                $this->db->delete('tbl_notif', array('pengirim' => $id_user, 'id_laporan' => $id));
                $this->db->delete('tbl_laporan', array('id_laporan' => $id));
                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> Berhasil dihapus.
							</div>
							<br>'
                );
                redirect("laporan/v");
            } else {
                redirect('404_content');
            }
        } else {
            $p = "index";
            $data['judul_web'] = "LAPORAN";
        }

        $this->load->view('users/header', $data);
        $this->load->view("users/laporan/$p", $data);
        $this->load->view('users/footer');

        date_default_timezone_set('Asia/Jakarta');
        $tgl = date('Y-m-d H:i:s');

        $lokasi = 'file';
        $file_size = 1024 * 3; // 3 MB
        $this->upload->initialize(array(
            "upload_path" => "./$lokasi",
            "allowed_types" => "*",
            "max_size" => "$file_size"
        ));

        if (isset($_POST['btnsimpan'])) {
            $id_kategori_lap = htmlentities(strip_tags($this->input->post('id_kategori_lap')));
            $id_sub_kategori_lap = htmlentities(strip_tags($this->input->post('id_sub_kategori_lap')));
            $isi_laporan = htmlentities(strip_tags($this->input->post('isi_laporan')));
            $ket_laporan = htmlentities(strip_tags($this->input->post('ket_laporan')));

            if (!$this->upload->do_upload('lampiran')) {
                $simpan = 'n';
                $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
            } else {
                $gbr = $this->upload->data();
                $filename = "$lokasi/" . $gbr['file_name'];
                $lampiran = preg_replace('/ /', '_', $filename);
                $simpan = 'y';
            }

            if ($simpan == 'y') {
                $data = array(
                    'id_kategori_lap' => $id_kategori_lap,
                    'id_sub_kategori_lap' => $id_sub_kategori_lap,
                    'isi_laporan' => $isi_laporan,
                    'ket_laporan' => $ket_laporan,
                    'lampiran' => $lampiran,
                    'notaris' => $id_user,
                    'status' => 'proses',
                    'tgl_laporan' => $tgl
                );
                $this->db->insert('tbl_laporan', $data);

                $id_laporan = $this->db->insert_id();
                $this->Mcrud->kirim_notif($id_user, 'superadmin', $id_laporan, 'notaris_kirim_laporan');

                $this->session->set_flashdata('msg',
                    '
										<div class="alert alert-success alert-dismissible" role="alert">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												 <span aria-hidden="true">&times;</span>
											 </button>
											 <strong>Sukses!</strong> Berhasil disimpan.
										</div>
									 <br>'
                );
            } else {
                $this->session->set_flashdata('msg',
                    '
	 							<div class="alert alert-warning alert-dismissible" role="alert">
	 								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	 									 <span aria-hidden="true">&times;</span>
	 								 </button>
	 								 <strong>Gagal!</strong> ' . $pesan . '.
	 							</div>
	 						 <br>'
                );
                redirect("laporan/v/$aksi/" . hashids_decrypt($id));
            }
            redirect("laporan/v");
        }


        if (isset($_POST['btnkirim'])) {
            $id_laporan = htmlentities(strip_tags($this->input->post('id_laporan')));
            $data_lama = $this->db->get_where('tbl_laporan', array('id_laporan' => $id_laporan))->row();
            $simpan = 'y';
            $pesan = '';
            if ($level == 'superadmin') {
                $id_petugas = htmlentities(strip_tags($this->input->post('id_petugas')));
                $data = array(
                    'petugas' => $id_petugas,
                    'status' => 'konfirmasi',
                    'tgl_konfirmasi' => $tgl
                );
                $pesan = 'Berhasil dikirim ke petugas';
                $this->Mcrud->kirim_notif('superadmin', $id_petugas, $id_laporan, 'superadmin_ke_petugas');
                $this->Mcrud->kirim_notif('superadmin', $data_lama->user, $id_laporan, 'superadmin_ke_notaris');
            } else {
                $pesan_petugas = htmlentities(strip_tags($this->input->post('pesan_petugas')));
                $status = htmlentities(strip_tags($this->input->post('status')));
                $file = $data_lama->file_petugas;
                $pesan = 'Berhasil disimpan';
                if ($_FILES['file']['error'] <> 4) {
                    if (!$this->upload->do_upload('file')) {
                        $simpan = 'n';
                        $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                    } else {
                        if ($file != '') {
                            unlink("$file");
                        }
                        $gbr = $this->upload->data();
                        $filename = "$lokasi/" . $gbr['file_name'];
                        $file = preg_replace('/ /', '_', $filename);
                    }
                }

                $data = array(
                    'pesan_petugas' => $pesan_petugas,
                    'status' => $status,
                    'file_petugas' => $file,
                    'tgl_selesai' => $tgl
                );
                $this->Mcrud->kirim_notif($data_lama->petugas, $data_lama->notaris, $id_laporan, 'petugas_ke_notaris');
            }

            if ($simpan == 'y') {
                $this->db->update('tbl_laporan', $data, array('id_laporan' => $id_laporan));
                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-success alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Sukses!</strong> ' . $pesan . '.
							</div>
						 <br>'
                );
            } else {
                $this->session->set_flashdata('msg',
                    '
							<div class="alert alert-warning alert-dismissible" role="alert">
								 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
									 <span aria-hidden="true">&times;</span>
								 </button>
								 <strong>Gagal!</strong> ' . $pesan . '.
							</div>
						 <br>'
                );
            }
            redirect('laporan/v');
        }

    }


    public function ajax()
    {
        if (isset($_POST['btnkirim'])) {
            $id = $this->input->post('id');
            $data = $this->db->get_where('tbl_laporan', array('id_laporan' => $id))->row();
            $pesan_petugas = $data->pesan_petugas;
            $status = $data->status;
            echo json_encode(array('pesan_petugas' => $pesan_petugas, 'status' => $status));
        } else {
            /*tes  push*/
            redirect('404');
        }
    }

}
