<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aph extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Cek login & role
        if($this->session->userdata('level') != 'aph'){
            redirect('web/login');
        }
    }

    public function dashboard()
    {
        $id_user = $this->session->userdata('id_user');
        $data['user'] = $this->db->get_where('tbl_user', ['id_user'=>$id_user]);
        $data['title'] = 'Dashboard APH';

        $this->load->view('users/header', $data);
        $this->load->view('users/aph', $data);
        $this->load->view('users/footer', $data);
    }
}
