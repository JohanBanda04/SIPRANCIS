<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aph extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!$this->session->userdata('username')) {
            redirect('web/login');
        }
        if ($this->session->userdata('level') != 'aph') {
            redirect('web/login');
        }

        $this->load->model('Mcrud');
        $this->load->model('Mkn_model');
    }

    public function form_pengajuan() {
        $ceks = $this->session->userdata('username');

        $data['judul_web'] = 'Form Pengajuan Perkara - ' . $this->Mcrud->judul_web();
        $data['user']      = $this->Mcrud->get_users_by_un($ceks); // untuk header.php

        // (opsional) tampilkan riwayat pengajuan user APH ini
        // $data['riwayat'] = $this->Mkn_model->getByUser($this->session->userdata('id_user'));

        $this->load->view('users/header', $data);
        $this->load->view('aph/form_pengajuan', $data);
        $this->load->view('users/footer');
    }

    public function simpan_pengajuan()
    {
        // wajib login & role
        if (!$this->session->userdata('username') || $this->session->userdata('level') !== 'aph') {
            redirect('web/login');
        }

        /** =========================
         *  SETUP UPLOAD YANG AMAN
         *  ========================= */
        // Pastikan folder ada
        $baseDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'mkn';
        if (!is_dir($baseDir)) {
            @mkdir($baseDir, 0777, true);
        }

        // Normalisasi path absolut & trailing slash (penting di Windows)
        $absPath = realpath($baseDir);                 // contoh: C:\xampp74\htdocs\SIPRANCIS\uploads\mkn
        if ($absPath === false) {                      // jika realpath gagal (folder baru dibuat)
            $absPath = rtrim($baseDir, "\\/");
        }
        $absPath = str_replace('\\', '/', $absPath);   // gunakan forward slash
        $absPath = rtrim($absPath, '/') . '/';         // wajib akhiri dengan '/'

        // Konfigurasi upload
        $config = [
            'upload_path'   => $absPath,               // path absolut ke folder
            'allowed_types' => 'pdf|jpg|jpeg|png',
            'max_size'      => 4096,                   // KB
            'encrypt_name'  => TRUE,
        ];

        $this->load->library('upload');
        $this->upload->initialize($config);

        // Proses upload (opsional)
        $file_name = null;
        if (!empty($_FILES['lampiran']['name'])) {
            if (!$this->upload->do_upload('lampiran')) {
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-danger"><b>Upload gagal.</b><br>'
                    . $this->upload->display_errors('', '') . '<br>'
                    . 'Path yang dipakai: <code>' . html_escape($absPath) . '</code></div>'
                );
                redirect('aph/pengajuan');
            }
            // simpan path relatif untuk dipakai di view (base_url)
            $file_name = 'uploads/mkn/' . $this->upload->data('file_name');
        }

        // Validasi minimal
        $nama_notaris   = $this->input->post('nama_notaris', true);
        $alamat_notaris = $this->input->post('alamat_notaris', true);
        $kronologi      = $this->input->post('kronologi', true);
        $nomor_akta     = $this->input->post('nomor_akta', true);

        if (!$nama_notaris || !$alamat_notaris || !$kronologi) {
            // hapus file kalau sempat ter-upload
            if ($file_name && file_exists(FCPATH.$file_name)) @unlink(FCPATH.$file_name);
            $this->session->set_flashdata('msg',
                '<div class="alert alert-warning">Form belum lengkap.</div>'
            );
            redirect('aph/pengajuan');
        }

        // Simpan ke DB
        $data_insert = [
            'id_user_pengaju' => $this->session->userdata('id_user'),
            'nama_notaris'    => $nama_notaris,
            'alamat_notaris'  => $alamat_notaris,
            'kronologi'       => $kronologi,
            'nomor_akta'      => $nomor_akta,
            'lampiran_surat'  => $file_name,
            'tahapan'         => 'penyelidikan',
            'status'          => 'pending',
            'tgl_pengajuan'   => date('Y-m-d H:i:s'),
        ];

        $this->Mkn_model->insertPerkara($data_insert);

        $this->session->set_flashdata('msg',
            '<div class="alert alert-success">Pengajuan berhasil disimpan.</div>'
        );
        redirect('aph/pengajuan');
    }
}
