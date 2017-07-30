<?php
	include_once("helper.php"); //load helper
	include_once("koneksi.php"); //load koneksi

	if(isset($_POST['action']) && !empty($_POST['action'])){
		session_start();
		$kode_akses = isset($_SESSION['sess_kodeAkses']) ? $_SESSION['sess_kodeAkses'] : false;
		$action = $_POST['action'];

		if($action == 'closeModal'){
			$query = "DELETE FROM t_token WHERE kode_akses = '$kode_akses'";
			$hasilQuery = mysqli_query($koneksi, $query);
			if($hasilQuery) reset_session();
			else reset_session();
			$hasil = "success";
			echo $hasil;
		}
		else if($action == 'closeModalLockScreen'){
			$query = "DELETE FROM t_token WHERE kode_akses = '$kode_akses'";
			$hasilQuery = mysqli_query($koneksi, $query);
			if($hasilQuery) $hasil = "success_2";
			else $hasil = "success_2";
			echo $hasil;
		}
		else{
			die();
		}
	}
	else{
		header("Location: ".base_url);
		die();
	}