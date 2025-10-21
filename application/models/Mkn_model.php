<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mkn_model extends CI_Model
{
    protected $table = 'tbl_mkn_perkara';

    // ✅ Semua perkara (terbaru dulu)
    public function getAll()
    {
        return $this->db->order_by('id_perkara', 'DESC')
            ->get($this->table)
            ->result();
    }

    // ✅ Per perkara by id
    public function getById($id)
    {
        return $this->db->get_where($this->table, ['id_perkara' => (int)$id])->row();
    }

    // ✅ Per tahapan (untuk Anggota MKN) — toleran spasi/kapital
    public function getByTahap($tahapan)
    {
        $needle = strtolower(trim($tahapan));
        $this->db->where('LOWER(TRIM(tahapan)) =', $needle);
        return $this->db->order_by('id_perkara', 'DESC')
            ->get($this->table)
            ->result();
    }

    // ✅ Riwayat pengajuan oleh user APH tertentu (opsional buat halaman APH)
    public function getByUser($id_user_pengaju)
    {
        return $this->db->where('id_user_pengaju', (int)$id_user_pengaju)
            ->order_by('id_perkara','DESC')
            ->get($this->table)
            ->result();
    }

    // ✅ Insert perkara (dari APH)
    public function insertPerkara($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // ✅ Update perkara
    public function updatePerkara($id, $data)
    {
        return $this->db->update($this->table, $data, ['id_perkara' => (int)$id]);
    }

    // ⛳ Guard cepat (opsional)
    public function exists($id)
    {
        return $this->db->select('id_perkara')
                ->from($this->table)
                ->where('id_perkara', (int)$id)
                ->limit(1)
                ->get()
                ->num_rows() > 0;
    }

    // ⛳ Helper update tahapan berdasar jenis surat (opsional)
    public function advanceStageByJenisSurat($id_perkara, $jenis_surat)
    {
        $map = [
            'pemanggilan_pemeriksaan'      => 'penyelidikan',
            'keterangan_penjelidikan'      => 'penyelidikan',
            'undangan_pemeriksaan_anggota' => 'penyidikan',
            'putusan_hasil_pemeriksaan'    => 'penuntutan',
            'jawaban_ketua_ke_aph'         => 'penuntutan',
            'putusan_pengadilan_ke_mpd'    => 'sidang',
        ];
        if (!isset($map[$jenis_surat])) return false;

        return $this->updatePerkara((int)$id_perkara, [
            'tahapan'    => $map[$jenis_surat],
            'tgl_update' => date('Y-m-d H:i:s'),
        ]);
    }
}
