<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sekretariat_model extends CI_Model {

    // Ambil semua permohonan dari APH
    public function get_permohonan_aph() {
        $this->db->select('p.*, u.nama_lengkap AS nama_aph');
        $this->db->from('tbl_perkara p');
        $this->db->join('tbl_user u', 'p.id_aph = u.id_user', 'left');
        $this->db->order_by('p.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // Ambil satu perkara berdasarkan ID
    public function get_perkara_by_id($id_perkara) {
        return $this->db->get_where('tbl_perkara', ['id_perkara' => $id_perkara])->row();
    }

    // Tambahkan surat baru
    public function insert_surat($data) {
        return $this->db->insert('tbl_surat', $data);
    }

    // Ubah status surat
    public function update_status_surat($id_surat, $status) {
        $this->db->where('id_surat', $id_surat);
        return $this->db->update('tbl_surat', ['status_dibawa' => $status]);
    }

    // Ambil daftar surat yang sudah dibuat
    public function get_daftar_surat() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('tbl_surat')->result();
    }
}
