<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aph extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // Pastikan user sudah login dan rolenya APH
        if ($this->session->userdata('level') != 'aph') {
            redirect('web/login');
        }

        // Load helper, library, dan database
        $this->load->helper(['form', 'url']);
        $this->load->library(['session', 'upload']);
        $this->load->database();
    }

    /* ---------------------------------------------------------------------
     * DASHBOARD APH
     * -------------------------------------------------------------------*/
    public function dashboard()
    {
        $id_user = $this->session->userdata('id_user');
        // Kirim result object agar kompatibel dengan header.php
        $data['user'] = $this->db->get_where('tbl_user', ['id_user' => $id_user]);
        $data['title'] = 'Dashboard APH';

        // Ambil daftar perkara milik APH login
        $data['perkara'] = $this->db->where('id_aph', $id_user)
                                   ->order_by('created_at', 'DESC')
                                   ->get('tbl_perkara')
                                   ->result();

        $this->load->view('users/header', $data);
        $this->load->view('users/aph', $data);
        $this->load->view('users/footer', $data);
    }

    /* ---------------------------------------------------------------------
     * TAMPIL FORM TAMBAH PERMOHONAN
     * -------------------------------------------------------------------*/
    public function tambah_permohonan()
    {
        $id_user = $this->session->userdata('id_user');
        $data['user']      = $this->db->get_where('tbl_user', ['id_user' => $id_user]);
        $data['judul_web'] = 'Tambah Permohonan Pemeriksaan';

        $this->load->view('users/header', $data);
        $this->load->view('users/aph_tambah_permohonan', $data);
        $this->load->view('users/footer', $data);
    }

    /* ---------------------------------------------------------------------
     * SIMPAN PERMOHONAN (HANDLE SUBMIT FORM)
     * -------------------------------------------------------------------*/
    public function simpan_permohonan()
    {
        // Pastikan request method adalah POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->set_flashdata('error', 'Permintaan tidak valid.');
            redirect('aph/tambah_permohonan');
            return;
        }

        /* ================================================================
         * AUTO BUAT FOLDER UPLOAD (JIKA BELUM ADA)
         * ================================================================ */
        $upload_path = FCPATH . 'uploads/permohonan/';

        if (!is_dir($upload_path)) {
            // Buat folder uploads jika belum ada
            if (!is_dir(FCPATH . 'uploads')) {
                mkdir(FCPATH . 'uploads', 0777, TRUE);
            }

            // Buat folder permohonan di dalam uploads
            if (mkdir($upload_path, 0777, TRUE)) {
                // Tambahkan file index.html agar folder aman (tidak bisa diakses langsung)
                file_put_contents($upload_path . 'index.html', '<!-- Silence is golden -->');
            } else {
                $this->session->set_flashdata('error', 'Gagal membuat folder upload. Periksa izin folder.');
                redirect('aph/tambah_permohonan');
                return;
            }
        }

        /* ================================================================
         * KONFIGURASI UPLOAD
         * ================================================================ */
        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['max_size']      = 3072; // 3MB
        $config['encrypt_name']  = TRUE;

        $this->upload->initialize($config);

        // Proses upload file
        if (!$this->upload->do_upload('lampiran_surat')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('<div class="text-danger">', '</div>'));
            redirect('aph/tambah_permohonan');
            return;
        }

        /* ================================================================
         * UPLOAD SUKSES → SIMPAN DATA KE DATABASE
         * ================================================================ */
        $upload_data = $this->upload->data();

        $data_insert = [
            'id_aph'         => $this->session->userdata('id_user'),
            'nama_notaris'   => $this->input->post('nama_notaris', TRUE),
            'alamat_notaris' => $this->input->post('alamat_notaris', TRUE),
            'kronologi'      => $this->input->post('kronologi', TRUE),
            'nomor_akta'     => $this->input->post('nomor_akta', TRUE),
            'tahapan'        => $this->input->post('tahapan', TRUE),
            'lampiran_surat' => $upload_data['file_name'],
            'status_perkara' => 'aktif',
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s')
        ];

        $insert = $this->db->insert('tbl_perkara', $data_insert);

        if ($insert) {
            $this->session->set_flashdata('success', '✅ Permohonan berhasil dikirim.');
        } else {
            $this->session->set_flashdata('error', '❌ Gagal menyimpan data ke database.');
        }

        redirect('aph/dashboard');
    }
}
