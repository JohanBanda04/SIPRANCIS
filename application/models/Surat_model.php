<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_model extends CI_Model {
    private $table = 'tbl_mkn_surat';

    public function getByPerkara($id_perkara){
        return $this->db->where('id_perkara', $id_perkara)
            ->order_by('id_surat','DESC')->get($this->table)->result();
    }

    public function insertSurat($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function getById($id_surat){
        return $this->db->get_where($this->table, ['id_surat'=>$id_surat])->row();
    }

    public function updateSurat($id_surat, $data){
        return $this->db->update($this->table, $data, ['id_surat'=>$id_surat]);
    }
}
