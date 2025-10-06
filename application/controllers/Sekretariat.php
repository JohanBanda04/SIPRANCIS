<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sekretariat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Sekretariat_model');
        $this->load->helper(['url','form']);
        $this->load->library('session');
    
        // Pastikan user sudah login
        if (!$this->session->userdata('username')) {
            redirect('web/login');
        }
    
        // Pastikan hanya sekretariat MKN yang bisa akses
        if ($this->session->userdata('level') !== 'sekretariat_mkn') {
            redirect('dashboard');
        }
    }    

    /* ---------------------------------------------------------------------
     * DASHBOARD SEKRETARIAT
     * -------------------------------------------------------------------*/
    public function dashboard()
{
    $id_user = $this->session->userdata('id_user');
    $data['user'] = $this->db->get_where('tbl_user', ['id_user' => $id_user]);
    $data['perkara'] = $this->Sekretariat_model->get_permohonan_aph();
    $data['title'] = 'Dashboard Sekretariat MKN';

    $this->load->view('users/header', $data);
    $this->load->view('users/sekretariat_mkn', $data);
    $this->load->view('users/footer');
}

    /* ---------------------------------------------------------------------
     * FORM BUAT SURAT
     * -------------------------------------------------------------------*/
    public function buat_surat($id_perkara = null)
    {
        $id_user = $this->session->userdata('id_user');
        $data['user'] = $this->db->get_where('tbl_user', ['id_user' => $id_user]);
        $data['perkara'] = $this->Sekretariat_model->get_perkara_by_id($id_perkara);

        if ($this->input->post()) {
            $config['upload_path']   = './uploads/surat/';
            $config['allowed_types'] = 'pdf|doc|docx|jpg|png';
            $config['max_size']      = 3072;
            $config['encrypt_name']  = TRUE;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file_surat')) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">'.$this->upload->display_errors().'</div>');
                redirect('sekretariat/buat_surat/'.$id_perkara);
                return;
            }

            $file = $this->upload->data('file_name');

            $data_surat = [
                'id_perkara'     => $this->input->post('id_perkara'),
                'jenis_surat'    => $this->input->post('jenis_surat'),
                'lampiran_file'  => $file,
                'status_dibawa'  => 'Belum',
                'created_at'     => date('Y-m-d H:i:s')
            ];

            $this->Sekretariat_model->insert_surat($data_surat);

            $this->session->set_flashdata('msg', '<div class="alert alert-success">Surat berhasil dibuat!</div>');
            redirect('sekretariat/dashboard');
            return;
        }

        $this->load->view('users/header', $data);
        $this->load->view('users/sekretariat_form_surat', $data);
        $this->load->view('users/footer');
    }

    /* ---------------------------------------------------------------------
     * UBAH STATUS SURAT
     * -------------------------------------------------------------------*/
    public function ubah_status($id_surat) {
        $status = $this->input->post('status_dibawa');
        $this->Sekretariat_model->update_status_surat($id_surat, $status);
        redirect('sekretariat/dashboard');
    }
}
