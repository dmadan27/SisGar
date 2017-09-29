<?php
	date_default_timezone_set('Asia/Jakarta');

	include_once("../function/helper.php");
	include_once("../function/koneksi.php");
	include_once("../function/validasi_form.php");
	include_once("../models/Admin_model.php");

	$action = isset($_POST['action']) ? $_POST['action'] : false;

	if(!$action) die("Dilarang Akses Halaman Ini !!");
	else{
		switch (strtolower($action)) {
			case 'login':
				session_start();
				login($koneksi);
				break;
			
			default:
				die();
				break;
		}
	}

	function login($koneksi){
		$cek = true;
		$status = false;
		$usernameError = $passwordError = $pesanError = $set_value = "";

		$username = validInputan($_POST['username'], false, true);
		$password = validInputan($_POST['password'], false, true);

		if(empty($username) || empty($password)){
			$cek = false;
			$usernameError = $passwordError = "Username dan Password Harap Diisi";
		}
		else{
			$data_login = get_login($koneksi, $username);
			if(!$data_login){
				$cek = false;
				$usernameError = $passwordError = "Username atau Password Anda Salah !";
			}
			else{
				if(password_verify($password, $data_login['password'])) $cek = true;
				else{
					$usernameError = $passwordError = "Username atau Password Anda Salah !";
					$cek = false;
				} 		
			}
		}

		if($cek){
			// cek status aktif
			if($data_login['status'] === "1"){
				// cek level dan hak akses
				$hak_akses = set_hak_akses($data_login['level']);
				$status = true;
				// set session
				$_SESSION['sess_login'] = $status;
				$_SESSION['sess_username'] = $data_login['username'];
				$_SESSION['sess_nama'] = $data_login['nama'];
				$_SESSION['sess_email'] = $data_login['email'];
				$_SESSION['sess_foto'] = $data_login['foto'];
				$_SESSION['sess_status'] = $data_login['status'];
				$_SESSION['sess_level'] = $data_login['level'];
				$_SESSION['sess_akses'] = $hak_akses;
				$_SESSION['sess_lockscreen'] = false;
				// $_SESSION['sess_time'] = false;
			}
			else{
				$status = false;
				$usernameError = $passwordError = "Username atau Password Anda Salah !";
			}
		}
		else $status = false;

		$_SESSION['sess_login'] = $status;

		$pesanError = array(
			'username' => $usernameError,
			'password' => $passwordError,
		);

		$set_value = array(
			'username' => $username,
			'password' => $password,
		);

		$output = array(
			'status' => $status,
			'pesanError' => $pesanError,
			'set_value' => $set_value,
		);

		echo json_encode($output);
	}

	function set_hak_akses($level){
		$akses = array(
			'beranda' => '<li class="menu-beranda"><a href="'.base_url.'"><i class="fa fa-link"></i><span>Beranda</span></a></li>', 
			'sewa' => '<li class="menu-sewa"><a href="'.base_url.'index.php?m=sewa&p=list"><i class="fa fa-link"></i><span>Data Sewa</span></a></li>', 
			'keuangan' => '<li class="menu-keuangan"><a href="'.base_url.'index.php?m=keuangan&p=list"><i class="fa fa-link"></i><span>Data Keuangan</span></a></li>', 
			'data_master' => array(
					'member' => '<li class="menu-member"><a href="'.base_url.'index.php?m=member&p=list">Data Member</a></li>', 
					'parkir' => '<li class="menu-parkir"><a href="'.base_url.'index.php?m=parkir&p=list">Data Parkir</a></li>', 
					'harga' => '<li class="menu-harga"><a href="'.base_url.'index.php?m=harga&p=list">Data Harga</a></li>',
				),  
			'data_admin' => array(
					'admin' => '<li class="menu-admin"><a href="'.base_url.'index.php?m=admin&p=list"><i class="fa fa-link"></i><span>Data Admin</span></a></li>',
					'log_admin' => '<li class="menu-log-admin"><a href="'.base_url.'index.php?m=log_admin&p=list"><i class="fa fa-link"></i><span>Data Log Admin</span></a></li>',
				),
		);

		switch (strtolower($level)) {
			case 'superadmin':
				$hak_akses = $akses;	
				break;
			
			default:
				$hak_akses = array(
					'beranda' => $akses['beranda'], 
					'sewa' => $akses['sewa'], 
					'keuangan' => $akses['keuangan'], 
					'data_master' => $akses['data_master'],
				);
				break;
		}

		return $hak_akses;
	}

	

	
	