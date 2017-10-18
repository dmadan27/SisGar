<?php
	date_default_timezone_set('Asia/Jakarta');

	// Load semua fungsi yang dibutuhkan
	include_once("../function/helper.php");
	include_once("../function/koneksi.php");
	include_once("../function/validasi_form.php");
	include_once("../function/datatable.php");
	include_once("../models/Keuangan_model.php");

	$action = isset($_POST['action']) ? $_POST['action'] : false;

	if(!$action) die("Dilarang Akses Halaman Ini !!");
	else{
		switch (strtolower($action)) {
			case 'list':
				list_keuangan($koneksi);
				break;

			case 'tambah':
				actionAdd($koneksi);
				break;

			case 'edit':
				
				break;

			default:
				die();
				break;
		}
	}
	
	function list_keuangan($koneksi){
		$config_db = array(
			'tabel' => 'v_keuangan',
			'kolomOrder' => array(null, 'tgl', 'jenis', 'nominal', 'ket', null),
			'kolomCari' => array('tgl', 'jenis', 'nominal', 'ket'),
			'orderBy' => false,
			'kondisi' => false,
		);

		$data_keuangan = get_all_keuangan($koneksi, $config_db);

		// siapkan data untuk isi datatable
		$data = array();
		$no_urut = $_POST['start'];
		foreach($data_keuangan as $row){
			$no_urut++;
			$aksi = '<div class="btn-group">';
			$aksi .= '<button type="button" class="btn btn-success btn-flat btn-sm" onclick="edit_keuangan('."'".$row["id"]."'".')">Edit</button>';
			$aksi .= '<button type="button" class="btn btn-danger btn-flat btn-sm" onclick="hapus_keuangan('."'".$row["id"]."'".')">Hapus</button>';		
			$aksi .= '</div>';
			
			$jenis = (strtolower($row['jenis']) == "uang masuk") ? '<span class="label label-success">'.$row['jenis'].'</span>' : '<span class="label label-danger">'.$row['jenis'].'</span>';

			$dataRow = array();
			$dataRow[] = $no_urut;
			$dataRow[] = cetakTgl($row['tgl'], 'full');
			$dataRow[] = $jenis;
			$dataRow[] = rupiah($row['nominal']);
			$dataRow[] = empty($row['ket']) ? "-" : $row['ket'];
			$dataRow[] = $aksi;

			$data[] = $dataRow;
		}

		$output = array(
			'draw' => $_POST['draw'],
			'recordsTotal' => recordTotal($koneksi, $config_db),
			'recordsFiltered' => recordFilter($koneksi, $config_db),
			'data' => $data,
		);

		echo json_encode($output);
	}