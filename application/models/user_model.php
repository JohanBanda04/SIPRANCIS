<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function get_users_by_level($level)
    {
        return $this->db->get_where('tbl_user', ['level' => $level, 'dihapus' => 0])->result();
    }

    public function insert_user($data)
    {
        return $this->db->insert('tbl_user', $data);
    }
}
