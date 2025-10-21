<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sekretariat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // pastikan user sudah login & role sesuai
        if ($this->session->userdata('level') != 'sekretariat') {
            redirect('web/login');
        }
    }

    public function dashboard() {
        $data['judul_web'] = "Dashboard Sekretariat";
        $this->load->view('sekretariat/dashboard', $data);
    }
}
