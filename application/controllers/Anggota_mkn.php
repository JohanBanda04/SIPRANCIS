<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggota_mkn extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Guard login & role
        if (!$this->session->userdata('username')) { redirect('web/login'); }
        if ($this->session->userdata('level') !== 'anggota_mkn') { redirect('web/login'); }

        $this->load->model('Mcrud');
        $this->load->model('Mkn_model');
    }

    public function index()
    {
        $ceks = $this->session->userdata('username');

        $data['judul_web']    = 'Dashboard Anggota MKN - ' . $this->Mcrud->judul_web();
        $data['user']         = $this->Mcrud->get_users_by_un($ceks);
        $data['mode']         = 'index'; // <-- view serbaguna
        $data['perkara_list'] = $this->Mkn_model->getByTahap('penyidikan');

        $this->load->view('users/header', $data);
        $this->load->view('users/anggota_mkn', $data); // <-- PASTIKAN INI
        $this->load->view('users/footer');
    }

    public function periksa($id_perkara = null)
    {
        if (!$id_perkara) { redirect('anggota_mkn'); }

        $ceks = $this->session->userdata('username');

        $data['judul_web'] = 'Periksa Perkara - ' . $this->Mcrud->judul_web();
        $data['user']      = $this->Mcrud->get_users_by_un($ceks);
        $data['mode']      = 'periksa'; // <-- view serbaguna
        $data['perkara']   = $this->Mkn_model->getById((int)$id_perkara);

        if (!$data['perkara']) {
            $this->session->set_flashdata('msg','<div class="alert alert-warning">Perkara tidak ditemukan.</div>');
            redirect('anggota_mkn');
        }

        $this->load->view('users/header', $data);
        $this->load->view('users/anggota_mkn', $data); // <-- PASTIKAN INI
        $this->load->view('users/footer');
    }

    public function simpan_pemeriksaan()
    {
        if ($this->session->userdata('level') !== 'anggota_mkn') { redirect('web/login'); }

        $id_perkara = (int)$this->input->post('id_perkara');
        $hasil      = $this->input->post('hasil_pemeriksaan', true);
        $catatan    = $this->input->post('catatan', true);

        if (!$id_perkara || !$hasil) {
            $this->session->set_flashdata('msg','<div class="alert alert-warning">Lengkapi hasil pemeriksaan.</div>');
            redirect('anggota_mkn/periksa/'.$id_perkara);
        }

        $gabungan = "Hasil Pemeriksaan:\n".$hasil;
        if (!empty($catatan)) { $gabungan .= "\n\nCatatan:\n".$catatan; }

        $ok = $this->Mkn_model->updatePerkara($id_perkara, [
            'catatan'    => $gabungan,
            'tgl_update' => date('Y-m-d H:i:s'),
        ]);

        $this->session->set_flashdata('msg',
            $ok ? '<div class="alert alert-success">Hasil pemeriksaan disimpan.</div>'
                : '<div class="alert alert-danger">Gagal menyimpan hasil pemeriksaan.</div>'
        );

        redirect('anggota_mkn');
    }
}
