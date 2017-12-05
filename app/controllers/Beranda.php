<?php
	date_default_timezone_set('Asia/Jakarta');

	// Load semua fungsi yang dibutuhkan
	include_once("../function/helper.php");
	include_once("../function/koneksi.php");
	include_once("../function/validasi_form.php");
	include_once("../function/datatable.php");

	include_once("../models/Saldo_model.php");
	include_once("../models/Sewa_model.php");
	include_once("../models/Admin_model.php");
	include_once("../models/Parkir_model.php");

	$action = isset($_POST['action']) ? $_POST['action'] : false;

	if(!$action) die("Dilarang Akses Halaman Ini !!");
	else{
		switch (strtolower($action)) {
			case 'get_panelinfo':
				get_panelInfo($koneksi);
				break;

			default:
				die();
				break;
		}
	}

	function get_panelInfo($koneksi){
		$get_dataSaldo = get_ketSaldo($koneksi);
		$dataSaldo = array(
			'uang_sewa' => rupiah($get_dataSaldo['uang_sewa']),
			'uang_keluar' => rupiah($get_dataSaldo['uang_keluar']),
			'uang_masuk' => rupiah($get_dataSaldo['uang_masuk']),
			'saldo' => rupiah(($get_dataSaldo['uang_sewa']-$get_dataSaldo['uang_keluar'])),
		);

		$dataMember = get_memberAktif($koneksi);
		$dataAdmin = get_adminAktif($koneksi);
		$dataParkir = get_parkirTersedia($koneksi);

		$output = array(
			'dataSaldo' => $dataSaldo,
			'dataMember' => $dataMember,
			'dataAdmin' => $dataAdmin,
			'dataParkir' => $dataParkir,
		);

		echo json_encode($output);
	}