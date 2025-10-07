<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuti extends CI_Controller
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
        //echo "pret"; die;
        $id = hashids_decrypt($id);
        $ceks = $this->session->userdata('username');
        $id_user = $this->session->userdata('id_user');
        $level = $this->session->userdata('level');
        if (!isset($ceks)) {
            redirect('web/login');
        }

        $data['user'] = $this->Mcrud->get_users_by_un($ceks);

        if ($level == 'superadmin') {
            $this->db->where_in('status', ['dispo_mpd', 'decline', 'approve', 'pengajuan']);
        }
        if ($level == 'petugas') {
            //$this->db->where('status', 'dispo_mpd');
            $getIdMpdPetugas = $this->db->get_where('tbl_petugas', array('id_user' => $this->session->userdata('id_user')))
                ->row()->id_petugas;
            //echo $getIdMpdPetugas; die;
            //echo $this->session->userdata('id_user'); die;
            $this->db->where_in('status', ['dispo_mpd', 'decline', 'approve','pengajuan']);
            $this->db->where('mpd_id', $getIdMpdPetugas);
            //$this->db->where('petugas', $id_user);
        }
        if ($level == 'notaris') {
            $this->db->where('user_id', $id_user);
        }
        if ($aksi == 'proses' or $aksi == 'konfirmasi' or $aksi == 'selesai') {
            //$this->db->where('status', $aksi);
        }
        $this->db->order_by('id_cuti', 'DESC');
        $data['query'] = $this->db->get("tbl_cuti");

        $cek_notif = $this->db->get_where("tbl_notif", array('penerima' => "$id_user"));
        foreach ($cek_notif->result() as $key => $value) {
            $b_notif = $value->baca_notif;
            if (!preg_match("/$id_user/i", $b_notif)) {
                $data_notif = array('baca_notif' => "$id_user, $b_notif");
                $this->db->update('tbl_notif', $data_notif, array('penerima' => $id_user));
            }
        }

        $data['getNotaris'] = $this->db->get_where('tbl_data_notaris', array('id_user' => $id_user))->result();
        if($level=='petugas'){
            $data['getNotaris'] = $this->db->get('tbl_data_notaris')->result();
            //echo "<pre>"; print_r($data['getNotaris']); die;
        }

        if ($aksi == 't') {
            if ($level != 'notaris') {
                redirect('404');
            }
            $p = "tambah";
            $data['judul_web'] = "PERMOHONAN CUTI BARU";

            if ($level == "notaris") {
            } else if ($level == "superadmin") {
                echo "laman superadmin cuti gimana";
                die;
            } else if ($level == "petugas") {
                echo "laman petugas mpdn cuti gimana";
                die;
            }
        } elseif ($aksi == 'd') {
            $p = "detail";
            $data['judul_web'] = "RINCIAN CUTI";
            $data['query'] = $this->db->get_where("tbl_cuti", array('id_cuti' => "$id"))->row();
            //echo "<pre>";print_r($data['query']); die;
            if ($data['query']->id_cuti == '') {
                redirect('404');
            }
        } else if ($aksi == 'e') {
            $p = "edit";
            $data['judul_web'] = "EDIT CUTI";
            $data['query'] = $this->db->get_where("tbl_cuti", array('id_cuti' => "$id"))->row();
        } elseif ($aksi == 'h') {
            $cek_data = $this->db->get_where("tbl_cuti", array('id_cuti' => "$id"));
            if ($cek_data->num_rows() != 0) {
                $row = $cek_data->row();

                // hanya boleh hapus jika status = pengajuan
                $allowed_status_todelete = ['pengajuan', 'approve', 'decline', 'dispo_mpd'];
                if (!in_array($row->status, $allowed_status_todelete)) {
                    redirect('404');
                }

                // daftar kolom file yang akan dihapus
                $fileFields = [
                    'surat_permohonan_cuti',
                    'sk_pengangkatan_notaris',
                    'berita_acara_sumpah',
                    'sertifikat_cuti_asli',
                    'surat_penunjukan_notaris_pengganti',
                    'lamp_syarat_cuti'
                ];

                // base path penyimpanan
                //$lokasi = FCPATH . 'file/cuti_files/';

                // hapus masing-masing file jika ada
                foreach ($fileFields as $field) {
                    if (!empty($row->$field)) {
                        $filePath = FCPATH . $row->$field; // langsung pakai value dari DB
                        if (file_exists($filePath)) {
                            @unlink($filePath); // hapus file
                        }
                    }
                }

                // hapus data cuti di DB
                $this->db->delete('tbl_cuti', array('id_cuti' => $id));

                // set flashdata sukses
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-success alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
                 <strong>Sukses!</strong> Berhasil dihapus.
             </div><br>'
                );

                redirect("cuti/v");
            } else {
                redirect('404_content');
            }
        } else {
            $p = "index";
            $data['judul_web'] = "CUTI";
            $data['tableName'] = "TABEL DAFTAR CUTI";

        }

        $data['level'] = $this->session->userdata('level');
        $this->load->view('users/header', $data);
        $this->load->view("users/cuti/$p", $data);
        $this->load->view('users/footer');

        date_default_timezone_set('Asia/Jakarta');
        $tgl = date('Y-m-d H:i:s');

        $lokasi = 'file/cuti_files';

        // cek apakah folder sudah ada, kalau belum maka dibuat
        if (!is_dir($lokasi)) {
            mkdir($lokasi, 0777, true); // 0777 = full access, true = recursive
        }

// limit ukuran file
        $file_size = 1024 * 3; // 3 MB
        $this->upload->initialize(array(
            "upload_path" => "./$lokasi",
            "allowed_types" => "pdf|doc|docx",
            "max_size" => "$file_size"
        ));

        if (isset($_POST['btnsimpan'])) {
            $simpan = 'y';
            $pesan = '';
            //echo $this->input->post('id_pemohon'); die;

            $alasan = htmlentities(strip_tags($this->input->post('alasan_cuti')));
            $keterangan = htmlentities(strip_tags($this->input->post('ket_laporan')));
            $tgl_awal = htmlentities(strip_tags($this->input->post('tgl_awal_cuti')));
            $tgl_akhir = htmlentities(strip_tags($this->input->post('tgl_akhir_cuti')));
            $jml_hari = htmlentities(strip_tags($this->input->post('jumlah_hari')));
            //$jml_hari = explode(' ',$jml_hari)[0];
            $id_pemohon = htmlentities(strip_tags($this->input->post('id_pemohon')));
            $getMpdId = $this->db->get_where('tbl_data_notaris', array('id_user' => $id_pemohon))->row()->mpd_area_id;
            //echo "<pre>"; print_r($getMpdId->mpd_area_id); die;

            // Pastikan jumlah hari murni angka
            $jml_hari = preg_replace('/[^0-9]/', '', $jml_hari);

            // --- Upload dokumen ---
            $fields = [
                'surat_permohonan_cuti' => 'Surat Permohonan Cuti',
                'sk_pengangkatan_notaris' => 'SK Pengangkatan Notaris',
                'berita_acara_sumpah' => 'Berita Acara Sumpah',
                'sertifikat_cuti_asli' => 'Sertifikat Cuti Asli',
                'surat_penunjukan_notaris_pengganti' => 'Surat Penunjukan Notaris Pengganti',
                'lamp_syarat_cuti' => 'Dokumen Pendukung Lainnya'
            ];

            $uploadData = [];

            foreach ($fields as $field => $label) {
                if ($_FILES[$field]['error'] <> 4) { // kalau ada file diupload
                    if (!$this->upload->do_upload($field)) {
                        $simpan = 'n';
                        $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        break; // stop kalau ada yang gagal
                    } else {
                        $gbr = $this->upload->data();
                        $filename = "$lokasi/" . $gbr['file_name'];
                        $filename = preg_replace('/\s+/', '_', $filename); // ganti spasi dengan underscore
                        $uploadData[$field] = $filename;
                    }
                }
            }

            if ($simpan == 'y') {
                $dataInsert = [
                    'alasan' => $alasan ?? "",
                    'keterangan' => $keterangan ?? "",
                    'user_id' => $id_user,
                    'mpd_id' => $id_pemohon,
                    'tgl_awal' => $tgl_awal,
                    'tgl_akhir' => $tgl_akhir,
                    'jml_hari_cuti' => $jml_hari,
                    'surat_permohonan_cuti' => $uploadData['surat_permohonan_cuti'] ?? '',
                    'sk_pengangkatan_notaris' => $uploadData['sk_pengangkatan_notaris'] ?? '',
                    'berita_acara_sumpah' => $uploadData['berita_acara_sumpah'] ?? '',
                    'sertifikat_cuti_asli' => $uploadData['sertifikat_cuti_asli'] ?? '',
                    'surat_penunjukan_notaris_pengganti' => $uploadData['surat_penunjukan_notaris_pengganti'] ?? '',
                    'lamp_syarat_cuti' => $uploadData['lamp_syarat_cuti'] ?? '',
                    'status' => 'pengajuan' ?? '',
                    'mpd_id' => $getMpdId ?? '',
                    'created_at' => $tgl
                ];

                $this->db->insert('tbl_cuti', $dataInsert);

                $this->session->set_flashdata('msg',
                    '<div class="alert alert-success alert-dismissible" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
             <strong>Sukses!</strong> Permohonan cuti berhasil disimpan.
         </div><br>'
                );
                redirect('cuti/v');
            } else {
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-warning alert-dismissible" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
             <strong>Gagal!</strong> ' . $pesan . '
         </div><br>'
                );
                redirect('cuti/v/t'); // balik ke form tambah
            }
        }

        if (isset($_POST['btnupdate'])) {
            //echo "brekele"; die;
            $simpan = 'y';
            $pesan = '';

            $id_cuti = $this->input->post('id_cuti'); // pastikan ada hidden input di form
            $alasan = htmlentities(strip_tags($this->input->post('alasan_cuti')));
            $keterangan = htmlentities(strip_tags($this->input->post('ket_laporan')));
            $tgl_awal = htmlentities(strip_tags($this->input->post('tgl_awal_cuti')));
            $tgl_akhir = htmlentities(strip_tags($this->input->post('tgl_akhir_cuti')));
            $jml_hari = preg_replace('/[^0-9]/', '', $this->input->post('jumlah_hari'));
            $id_pemohon = htmlentities(strip_tags($this->input->post('id_pemohon')));

            $getMpdId = $this->db->get_where('tbl_data_notaris', ['id_user' => $id_pemohon])->row()->mpd_area_id;

            // ambil data lama
            $rowLama = $this->db->get_where("tbl_cuti", ["id_cuti" => $id_cuti])->row();

            // daftar kolom file
            $fields = [
                'surat_permohonan_cuti' => 'Surat Permohonan Cuti',
                'sk_pengangkatan_notaris' => 'SK Pengangkatan Notaris',
                'berita_acara_sumpah' => 'Berita Acara Sumpah',
                'sertifikat_cuti_asli' => 'Sertifikat Cuti Asli',
                'surat_penunjukan_notaris_pengganti' => 'Surat Penunjukan Notaris Pengganti',
                'lamp_syarat_cuti' => 'Dokumen Pendukung Lainnya',
                'sk_cuti_bympd' => 'SK Cuti oleh MPD'
            ];

            $uploadData = [];
            $lokasi = 'file/cuti_files/';

            foreach ($fields as $field => $label) {
                if ($_FILES[$field]['error'] <> 4) { // ada file baru
                    if (!$this->upload->do_upload($field)) {
                        $simpan = 'n';
                        $pesan = htmlentities(strip_tags($this->upload->display_errors('<p>', '</p>')));
                        break;
                    } else {
                        // hapus file lama kalau ada
                        if (!empty($rowLama->$field) && file_exists(FCPATH . $rowLama->$field)) {
                            @unlink(FCPATH . $rowLama->$field);
                        }

                        // simpan file baru
                        $gbr = $this->upload->data();
                        $filename = $lokasi . preg_replace('/\s+/', '_', $gbr['file_name']);
                        $uploadData[$field] = $filename;
                    }
                }
            }

            if ($simpan == 'y') {
                $dataUpdate = [
                    'alasan' => $alasan ?? "",
                    'keterangan' => $keterangan ?? "",
                    'user_id' => $id_pemohon,
                    'mpd_id' => $getMpdId ?? '',
                    'tgl_awal' => $tgl_awal,
                    'tgl_akhir' => $tgl_akhir,
                    'jml_hari_cuti' => $jml_hari,
                    'status' => 'pengajuan',
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // merge upload data (replace hanya yg diupload baru)
                $dataUpdate = array_merge($dataUpdate, $uploadData);

                $this->db->where('id_cuti', $id_cuti);
                $this->db->update('tbl_cuti', $dataUpdate);

                $this->session->set_flashdata('msg',
                    '<div class="alert alert-success alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
                 <strong>Sukses!</strong> Permohonan cuti berhasil diupdate.
             </div><br>'
                );
                redirect('cuti/v');
            } else {
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-warning alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
                 <strong>Gagal!</strong> ' . $pesan . '
             </div><br>'
                );
                redirect('cuti/v/e/' . $id_cuti); // balik ke form edit
            }
        }

        // ---- Update Status Cuti dari Modal ----
        if (isset($_POST['btnupdate_status'])) {
            //echo "prity"; die;
            $id_cuti = $this->input->post('id_cuti');
            $aksi = $this->input->post('aksi');
            //echo $id_cuti; die;
            //echo $aksi; die;
            if (!empty($id_cuti) && !empty($aksi)) {
                $this->db->where('id_cuti', $id_cuti);
                $this->db->update('tbl_cuti', [
                    'status' => $aksi,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $this->session->set_flashdata('msg',
                    '<div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Sukses!</strong> Status cuti berhasil diperbarui.
                </div><br>'
                );
            } else {
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Gagal!</strong> Data tidak lengkap.
                </div><br>'
                );
            }

            redirect('cuti/v');
        }

        // ---- Update Status Cuti dari Modal (Petugas) ----
        if (isset($_POST['btnupdate_status_bympd'])) {
            $id_cuti = $this->input->post('id_cuti');
            $aksi = $this->input->post('aksi');
            $catatan = $this->input->post('catatan_petugas');

            if (!empty($id_cuti) && !empty($aksi)) {

                // Cek & buat folder jika belum ada
                $lokasi = 'file/cuti_files/';
                if (!is_dir(FCPATH . $lokasi)) {
                    mkdir(FCPATH . $lokasi, 0777, true);
                }

                // Upload file SK Cuti (opsional)
                $uploadData = [];

                if (isset($_FILES['sk_cuti_bympd']) && $_FILES['sk_cuti_bympd']['error'] <> 4) {
                    $file_size = 1024 * 5; // 5 MB
                    $this->upload->initialize([
                        "upload_path" => "./$lokasi",
                        "allowed_types" => "pdf|doc|docx",
                        "max_size" => "$file_size"
                    ]);

                    /*ambil file lama*/
                    $rowLama = $this->db->get_where("tbl_cuti", array('id_cuti' => $id_cuti))->row();
                    $fileLama = $rowLama->sk_cuti_bympd ?? '';
                    //echo "<pre>"; print_r($rowLama); die;
                    //echo $fileLama; die;

                    if ($this->upload->do_upload('sk_cuti_bympd')) {
                        $gbr = $this->upload->data();
                        $fileName = preg_replace('/\s+/', '_', $gbr['file_name']);
                        /*record yamg tersimpan di kolom sk_cuti_bympd di tbl_cuti database*/
                        $uploadData['sk_cuti_bympd'] = $lokasi . $fileName;

                        /*hapus file lama jika ada dan file-nya eksis*/
                        if(!empty($fileLama) && file_exists(FCPATH . $fileLama)){
                            @unlink(FCPATH.$fileLama);
                        }
                    } else {
                        // kalau upload gagal, simpan pesan tapi lanjut update status
                        $this->session->set_flashdata('msg',
                            '<div class="alert alert-warning alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Perhatian!</strong> File SK Cuti gagal diupload: ' .
                            htmlentities(strip_tags($this->upload->display_errors('', ''))) . '
                    </div><br>'
                        );
                    }
                }
                // 🔹 Data update dasar
                $dataUpdate = [
                    'status' => $aksi,
                    'catatan' => $catatan,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                // Gabungkan data upload (jika ada)
                if (!empty($uploadData)) {
                    $dataUpdate = array_merge($dataUpdate, $uploadData);
                }

                // Simpan ke database
                $this->db->where('id_cuti', $id_cuti);
                $this->db->update('tbl_cuti', $dataUpdate);

                $this->session->set_flashdata('msg',
                    '<div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Sukses!</strong> Status cuti + catatan' .
                    (!empty($uploadData) ? ' + SK Cuti' : '') . ' berhasil diperbarui.
            </div><br>'
                );

            } else {
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Gagal!</strong> Data tidak lengkap.
            </div><br>'
                );
            }
            redirect('cuti/v');
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
            redirect('404');
        }
    }

}
