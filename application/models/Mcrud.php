<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mcrud extends CI_Model {

	var $tbl_users				 = 'tbl_user';

	//Sent mail
		public function sent_mail($nama, $email, $aksi)
		{
			$email_saya = "cariprojecthalal@gmail.com";
			$pass_saya  = "L1nk-N3t";

			//konfigurasi email
			$config = array();
			$config['charset'] = 'utf-8';
			$config['useragent'] = 'Codeinteger';
			$config['protocol']= "smtp";
			$config['mailtype']= "html";
			$config['smtp_host']= "ssl://google.com";
			$config['smtp_port']= "587";
			$config['smtp_timeout']= "10";
			$config['smtp_user']= "$email_saya";
			$config['smtp_pass']= "$pass_saya";
			$config['crlf']="\r\n";
			$config['newline']="\r\n";

			$config['wordwrap'] = TRUE;
			//memanggil library email dan set konfigurasi untuk pengiriman email

			$this->email->initialize($config);
			//$ipaddress = get_real_ip(); //untuk mendeteksi alamat IP

			date_default_timezone_set('Asia/Jakarta');
			$waktu 	  = date('Y-m-d H:i:s');
			$tgl 			= date('Y-m-d');

			$id = md5("$email * $tgl");

					$link			= base_url().'web/verify';
					$pesan    = "Hello $nama,
												<br /><br />
												Selamat datang di SIPARIS!<br/>
												Untuk melengkapi registrasi Anda, silahkan klik link berikut<br/>
												<br /><br />
												<b><a href='$link/$id/$email'>Klik Aktivasi disini :)</a></b>
												<br /><br />
												Terimakasih ^_^,
												";
					$subject = "Aktivasi Akun $nama";

			$this->email->from("$email_saya");
			$this->email->to("$email");
			$this->email->subject($subject);
			$this->email->message($pesan);
		}
	//End Sent mail

 public static function tgl_id($date, $bln='')
 {
	 date_default_timezone_set('Asia/Jakarta');
		 $str = explode('-', $date);
		 $bulan = array(
			 '01' => 'Januari',
			 '02' => 'Februari',
			 '03' => 'Maret',
			 '04' => 'April',
			 '05' => 'Mei',
			 '06' => 'Juni',
			 '07' => 'Juli',
			 '08' => 'Agustus',
			 '09' => 'September',
			 '10' => 'Oktober',
			 '11' => 'November',
			 '12' => 'Desember',
		 );
		 if ($bln == '') {
			 $hasil = $str['0'] . "-" . substr($bulan[$str[1]],0,3) . "-" .$str[2];
		 }elseif ($bln == 'full') {
			 $hasil = $str['0'] . " " . $bulan[$str[1]] . " " .$str[2];
		 }else {
			 $hasil = $bulan[$str[1]];
		 }
		 return $hasil;
 }

	public function hari_id($tanggal)
	{
		$day = date('D', strtotime($tanggal));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => "Jum'at",
			'Sat' => 'Sabtu'
		);
		return $dayList[$day];
	}

	public function get_users()
	{
			return $this->db->get_where($this->tbl_users, "dihapus='tidak'");
	}

	public function get_id_user($id)
	{
			return $this->db->get_where($this->tbl_users, array('id_user'=>$id,'dihapus'=>'tidak'));
	}

	public function get_level_users()
	{
			// $this->db->where('tbl_user.level', 'user');
			return $this->db->get_where($this->tbl_users, "dihapus='tidak'");
	}

	public function get_users_by_un($id)
	{
				return $this->db->get_where($this->tbl_users, array('username'=>"$id", "dihapus"=>'tidak'));
	}

	public function get_level_users_by_id($id)
	{
			$this->db->from($this->tbl_users);
			$this->db->where('tbl_user.dihapus', 'tidak');
			$this->db->where('tbl_user.level', 'user');
			$this->db->where('tbl_user.level', 'notaris');
			$this->db->where('tbl_user.id_user', $id);
			$query = $this->db->get();
			return $query->row();
	}

	public function save_user($data)
	{
		$this->db->insert($this->tbl_users, $data);
		return $this->db->insert_id();
	}

	public function update_user($where, $data)
	{
		$this->db->update($this->tbl_users, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_user_by_id($id)
	{
		$this->db->where('id_user', $id);
		$this->db->delete($this->tbl_users);
	}

	public function waktu($data, $aksi='')
	{
		if ($aksi=='full') {
			$tgl_n = date('d-m-Y H:i:s',strtotime($data));
		}else {
			$tgl_n = date('d-m-Y',strtotime($data));
		}
		$hari = $this->Mcrud->hari_id($tgl_n);
		$tgl  = $this->Mcrud->tgl_id($tgl_n,$aksi);
		return $hari.", ".$tgl;
	}

	function judul_web($id='')
	{
		$nama_web = $this->db->get_where('tbl_web',"id_web='1'")->row()->nama_web;
		$ket_web  = $this->db->get_where('tbl_web',"id_web='1'")->row()->ket_web;
		if ($id==1) {
			$data = "$nama_web";
		}elseif ($id==2) {
			$data = "$ket_web";
		}else {
			$data = "$nama_web $ket_web";
		}
		return $data;
	}

	function footer()
	{
			return "Copyright &copy; 2019 | Developer by <a href='http://esotechno.com/' target='_blank'>CV. ESOTECHNO</a>";
	}

	public function cek_filename($file='')
	{
		$data = "assets/favicon.png";
		if ($file != '') {
			if(file_exists("$file")){
				$data = $file;
			}
		}
		return $data;
	}

	public function sosmed($aksi='')
	{
		$data = "javascript:;";
		if ($aksi=='fb') {
			$data = "https://facebook.com/";
		}elseif ($aksi=='twit') {
			$data = "https://twitter.com/";
		}elseif ($aksi=='gplus') {
			$data = "https://plus.google.com/";
		}elseif ($aksi=='ig') {
			$data = "https://instagram.com/";
		}elseif ($aksi=='rss') {
			$data = "https://rss.com/";
		}
		return $data;
	}

	public function kontak($aksi='')
	{
		$data = "";
		if ($aksi=='nama') {
			$data = "CV.Lombok Investama Komputindo";
		}elseif ($aksi=='alamat') {
			$data = "Jl. Raya Anjani, KM.50 Selong-LOTIM ";
		}elseif ($aksi=='email') {
			$data = "cariprojecthalal@gmail.com";
		}elseif ($aksi=='no_hp') {
			$data = "08113401340";
		}elseif ($aksi=='peta') {
			$data = "";
		}
		return $data;
	}

	function d_pelapor($id='',$aksi='')
	{
		if ($aksi=='nama_pelapor') {
			$data = $this->db->get_where('tbl_data_user', array('id_user'=>$id))->row()->nama;
		}elseif ($aksi=='kategori') {
			$data = $this->db->get_where('tbl_kategori', array('id_kategori'=>$id))->row()->nama_kategori;
		}elseif ($aksi=='sub_kategori') {
			$data = $this->db->get_where('tbl_sub_kategori', array('id_sub_kategori'=>$id))->row()->nama_sub_kategori;
		}else {
			$data = '-';
		}
		return $data;
	}
	//d_pelapor notaris
	function d_notaris($id='',$aksi='')
	{
		if ($aksi=='nama_pelapor_notaris') {
			$data = $this->db->get_where('tbl_data_notaris', array('id_user'=>$id))->row()->nama;
		}elseif ($aksi=='kategori_lap') {
			$data = $this->db->get_where('tbl_kategori_lap', array('id_kategori_lap'=>$id))->row()->nama_kategori_lap;
		}elseif ($aksi=='sub_kategori_lap') {
			$data = $this->db->get_where('tbl_sub_kategori_lap', array('id_sub_kategori_lap'=>$id))->row()->nama_sub_kategori_lap;
		}else {
			$data = '-';
		}
		return $data;
	}

	function cek_status($data)
	{
		if ($data=='proses') {
			$v_data = '<label class="label label-danger" style="color: white!important;">ADUAN BARU</label>';
		}elseif ($data=='dispo_mpd') {
			$v_data = '<label class="label label-info" style="color: white!important;">DISPOSISI KE MPD</label>';
		}elseif ($data=='konfirmasi') {
			$v_data = '<label class="label label-warning" style="color: white!important;">SEDANG DITANGANI</label>';
		}elseif ($data=='tolak') {
			$v_data = '<label class="label label-primary" style="color: white!important;">DITOLAK</label>';
		}elseif ($data=='selesai') {
			$v_data = '<label class="label label-success" style="color: white!important;">SELESAI</label>';
		}else {
			$v_data = '';
		}
		return $v_data;
	}

	//cek ststus laporan notaris
		function cek_status_notaris($data)
	{
		if ($data=='proses') {
			$v_data = '<label class="label label-danger">BELUM DITANGGAPI</label>';
		}elseif ($data=='konfirmasi') {
			$v_data = '<label class="label label-primary">SEDANG DIPROSES</label>';
		}elseif ($data=='selesai') {
			$v_data = '<label class="label label-success">SELESAI</label>';
		}else {
			$v_data = '';
		}
		return $v_data;
	}

	function kirim_notif($pengirim,$penerima,$id_pengaduan,$id_laporan='',$pesan='')
	{
		date_default_timezone_set('Asia/Jakarta');
		$tgl = date('Y-m-d H:i:s');
		if ($pengirim=='superadmin') { $pengirim = '1'; }
		if ($penerima=='superadmin') { $penerima = '1'; }

		if ($pesan=='user_kirim_pengaduan') {
			$pesan = "Mengirim Pengaduan baru";
			//notaris kirim laporan
		}elseif ($pesan=='notaris_kirim_laporan') {
			$pesan = "Mengirim Laporan baru";
			// <--- >//	
		}elseif ($pesan=='superadmin_ke_petugas') {
			$pesan = "Mengirim Pekerjaan baru";
		}elseif ($pesan=='superadmin_ke_user') {
			$pesan = "Pengaduan dikonfirmasi";
		}elseif ($pesan=='petugas_ke_user') {
			$pesan = "Pengaduan Selesai";
			//copy lagi
		}elseif ($pesan=='superadmin_ke_notaris') {
			$pesan = "Laporan dikonfirmasi";
		}elseif ($pesan=='petugas_ke_notaris') {
			$pesan = "Laporan diterima";	
		}

		if ($id_pengaduan=='') {
			$link = '';
		}else{
			$link = "pengaduan/v/d/".hashids_encrypt($id_pengaduan);
		}

		// id laporan notaris
		if ($id_laporan=='') {
			$link = '';
		}else{
			$link = "laporan/v/d/".hashids_encrypt($id_laporan);
		}
		//sampai sini

		$data2 = array(
			'pengirim'  => $pengirim,
			'penerima'  => $penerima,
			'pesan'  		=> $pesan,
			'link'			=> $link,
			'id_pengaduan' => $id_pengaduan,
			'id_laporan' => $id_laporan,
			'tgl_notif' => $tgl
		);
		$this->db->insert('tbl_notif',$data2);
		
		
	}

}
