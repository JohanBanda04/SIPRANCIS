<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sekretariat_mkn extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Guard login & role
        if (!$this->session->userdata('username')) { redirect('web/login'); }
        if ($this->session->userdata('level') !== 'sekretariat_mkn') { redirect('web/login'); }

        $this->load->model('Mcrud');     // untuk header & judul_web
        $this->load->model('Mkn_model'); // data perkara
    }

    // Dashboard Sekretariat
    public function index() {
        $ceks = $this->session->userdata('username');

        $data['judul_web'] = 'Sekretariat MKN - ' . $this->Mcrud->judul_web();
        $data['user']      = $this->Mcrud->get_users_by_un($ceks); // header.php butuh $user
        $data['perkara']   = $this->Mkn_model->getAll();

        $this->load->view('users/header', $data);
        $this->load->view('users/sekretariat_mkn', $data); // <- view dashboard utama
        $this->load->view('users/footer');
    }

    // Form buat surat berdasarkan perkara
    public function form_surat($id_perkara = null)
    {
        if (!$id_perkara) redirect('sekretariat_mkn');

        $ceks = $this->session->userdata('username');
        $this->load->model('Surat_model');

        $data['judul_web']  = 'Buat Surat - ' . $this->Mcrud->judul_web();
        $data['user']       = $this->Mcrud->get_users_by_un($ceks);
        $data['perkara']    = $this->Mkn_model->getById((int)$id_perkara);
        $data['list_surat'] = $this->Surat_model->getByPerkara((int)$id_perkara);

        // opsi jenis_surat yang tersedia
        $data['jenis_surat_options'] = [
            'pemanggilan_pemeriksaan'      => 'Pemanggilan Pemeriksaan',
            'undangan_pemeriksaan_anggota' => 'Undangan Pemeriksaan Anggota',
            'putusan_hasil_pemeriksaan'    => 'Putusan Hasil Pemeriksaan',
            'jawaban_ketua_ke_aph'         => 'Jawaban Ketua ke APH',
            'putusan_pengadilan_ke_mpd'    => 'Putusan Pengadilan ke MPD',
            'keterangan_penjelidikan'      => 'Keterangan Penyelidikan',
        ];

        $this->load->view('users/header', $data);
        $this->load->view('sekretariat_mkn/form_surat', $data);
        $this->load->view('users/footer');
    }

    // Simpan surat + auto update tahapan perkara
    public function simpan_surat()
    {
        if ($this->session->userdata('level') !== 'sekretariat_mkn') redirect('web/login');
        $this->load->model('Surat_model');

        $id_perkara  = (int)$this->input->post('id_perkara');
        $jenis_surat = $this->input->post('jenis_surat', true);
        $no_surat    = $this->input->post('no_surat', true);
        $perihal     = $this->input->post('perihal', true);

        if (!$id_perkara || !$jenis_surat || !$no_surat) {
            $this->session->set_flashdata('msg','<div class="alert alert-warning">Form belum lengkap.</div>');
            redirect('sekretariat_mkn/surat/'.$id_perkara);
        }

        // Upload lampiran (opsional)
        $lampiran_path = null;
        if (!empty($_FILES['lampiran']['name'])) {
            // siapkan folder
            $baseDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'mkn_surat';
            if (!is_dir($baseDir)) { @mkdir($baseDir, 0777, true); }
            $absPath = realpath($baseDir);
            if ($absPath === false) $absPath = rtrim($baseDir, "\\/");
            $absPath = str_replace('\\','/',$absPath);
            $absPath = rtrim($absPath,'/').'/';

            // config upload
            $config = [
                'upload_path'   => $absPath,
                'allowed_types' => 'pdf', // tambah: 'pdf|jpg|jpeg|png' jika perlu
                'max_size'      => 4096,
                'encrypt_name'  => TRUE,
            ];
            $this->load->library('upload');
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('lampiran')) {
                $this->session->set_flashdata('msg',
                    '<div class="alert alert-danger"><b>Upload gagal:</b> '.$this->upload->display_errors('','').'</div>');
                redirect('sekretariat_mkn/surat/'.$id_perkara);
            }
            $lampiran_path = 'uploads/mkn_surat/' . $this->upload->data('file_name');
        }

        // simpan surat
        $dataInsert = [
            'id_perkara'        => $id_perkara,
            'jenis_surat'       => $jenis_surat,
            'no_surat'          => $no_surat,
            'perihal'           => $perihal,
            'lampiran_path'     => $lampiran_path,
            'ditujukan_ke_role' => $this->input->post('ditujukan_ke_role', true) ?: 'notaris',
            'ditujukan_ke_id'   => (int)$this->input->post('ditujukan_ke_id'),
            'status_bawa'       => 'belum_dibawa',
            'tgl_surat'         => date('Y-m-d H:i:s'),
        ];
        $this->Surat_model->insertSurat($dataInsert);

        // Auto-update tahapan perkara sesuai jenis_surat
        $mapTahap = [
            'pemanggilan_pemeriksaan'      => 'penyelidikan',
            'keterangan_penjelidikan'      => 'penyelidikan',
            'undangan_pemeriksaan_anggota' => 'penyidikan',
            'putusan_hasil_pemeriksaan'    => 'penuntutan',
            'jawaban_ketua_ke_aph'         => 'penuntutan',
            'putusan_pengadilan_ke_mpd'    => 'sidang',
        ];
        if (isset($mapTahap[$jenis_surat])) {
            $this->Mkn_model->updatePerkara($id_perkara, [
                'tahapan'    => $mapTahap[$jenis_surat],
                'tgl_update' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->session->set_flashdata('msg','<div class="alert alert-success">Surat berhasil dibuat.</div>');
        redirect('sekretariat_mkn/surat/'.$id_perkara);
    }

    // Toggle status_bawa surat (belum_dibawa <-> sudah_dibawa)
    public function toggle_bawa($id_surat = null)
    {
        if (!$id_surat) redirect('sekretariat_mkn');

        $this->load->model('Surat_model');
        $surat = $this->Surat_model->getById((int)$id_surat);
        if (!$surat) redirect('sekretariat_mkn');

        $newStatus = ($surat->status_bawa === 'sudah_dibawa') ? 'belum_dibawa' : 'sudah_dibawa';
        $this->Surat_model->updateSurat((int)$id_surat, [
            'status_bawa' => $newStatus,
            'tgl_update'  => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('msg','<div class="alert alert-success">Status surat diperbarui.</div>');
        redirect('sekretariat_mkn/surat/'.$surat->id_perkara);
    }

    // Detail perkara (lihat ringkasan & daftar surat terkait)
    public function detail($id_perkara = null)
    {
        if (!$this->session->userdata('username') || $this->session->userdata('level') !== 'sekretariat_mkn') {
            redirect('web/login');
        }
        if (!$id_perkara) { redirect('sekretariat_mkn'); }

        $this->load->model('Surat_model');

        $ceks = $this->session->userdata('username');
        $data['judul_web'] = 'Detail Perkara - ' . $this->Mcrud->judul_web();
        $data['user']      = $this->Mcrud->get_users_by_un($ceks);
        $data['perkara']   = $this->Mkn_model->getById((int)$id_perkara);
        $data['surat']     = $this->Surat_model->getByPerkara((int)$id_perkara);

        $this->load->view('users/header', $data);
        $this->load->view('sekretariat_mkn/detail_perkara', $data);
        $this->load->view('users/footer');
    }

    // Kirim perkara ke Anggota (ubah tahapan -> penyidikan)
    public function kirim_ke_anggota($id_perkara = null)
    {
        if (!$this->session->userdata('username') || $this->session->userdata('level') !== 'sekretariat_mkn') {
            redirect('web/login');
        }
        if (!$id_perkara) { redirect('sekretariat_mkn'); }

        $perkara = $this->Mkn_model->getById((int)$id_perkara);
        if (!$perkara) {
            $this->session->set_flashdata('msg','<div class="alert alert-warning">Perkara tidak ditemukan.</div>');
            redirect('sekretariat_mkn');
        }

        $ok = $this->Mkn_model->updatePerkara((int)$id_perkara, [
            'tahapan'    => 'penyidikan',
            'status'     => 'proses', // opsional: tandai sedang diproses
            'tgl_update' => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('msg',
            $ok ? '<div class="alert alert-success">Perkara dikirim ke Anggota MKN (tahap: <b>penyidikan</b>).</div>'
                : '<div class="alert alert-danger">Gagal mengirim perkara ke Anggota.</div>'
        );

        redirect('sekretariat_mkn');
    }
}
