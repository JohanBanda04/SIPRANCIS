<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mkn extends CI_Controller {

    /**
     * Mkn constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper(array('url','form'));

        // DEBUG SESSION
        echo "<pre>";
        print_r($this->session->userdata());
        echo "</pre>";

        if ($this->session->userdata('level') != 'sekretariat_mkn') {
            redirect('web/login');
        }
    }

    // Halaman daftar akun APH
    public function aph()
    {
        $data['title'] = 'Kelola Akun APH';
        $data['aph_list'] = $this->User_model->get_users_by_level('aph');
        $this->load->view('mkn/aph_list', $data);
    }

    // Form tambah akun APH
    public function add_aph()
    {
        if ($this->input->post()) {
            $this->User_model->insert_user(array(
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'username'     => $this->input->post('username'),
                'password'     => md5($this->input->post('password')),
                'level'        => 'aph',
                'instansi'     => $this->input->post('instansi'),
                'tgl_daftar'   => date('Y-m-d H:i:s'),
                'aktif'        => 1,
                'dihapus'      => 0
            ));
            redirect('mkn/aph');
        } else {
            $data['title'] = 'Tambah Akun APH';
            $this->load->view('mkn/add_aph', $data);
        }
    }
}
