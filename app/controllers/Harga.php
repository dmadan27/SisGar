<?php
	date_default_timezone_set('Asia/Jakarta');

	// Load semua fungsi yang dibutuhkan
	include_once("../function/helper.php");
	include_once("../function/koneksi.php");
	include_once("../function/validasi_form.php");
	include_once("../function/datatable.php");
	include_once("../models/Harga_model.php");

	$action = isset($_POST['action']) ? $_POST['action'] : false;

	if(!$action) die("Dilarang Akses Halaman Ini !!");
	else{
		switch (strtolower($action)) {
			case 'list':
				list_harga($koneksi);
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

	function list_harga($koneksi){
		$config_db = array(
			'tabel' => 'harga',
			'kolomOrder' => array(null, 'jenis', 'harga', null),
			'kolomCari' => array('jenis', 'harga'),
			'orderBy' => false,
			'kondisi' => false,
		);

		$data_harga = get_all_harga($koneksi, $config_db);

		// siapkan data untuk isi datatable
		$data = array();
		$no_urut = $_POST['start'];
		foreach($data_harga as $row){
			$no_urut++;
			$aksi = '<div class="btn-group">';
			$aksi .= '<button type="button" class="btn btn-success btn-flat btn-sm" onclick="edit_harga('."'".$row["id"]."'".')">Edit</button>';
			$aksi .= '<button type="button" class="btn btn-danger btn-flat btn-sm" onclick="hapus_harga('."'".$row["id"]."'".')">Hapus</button>';		
			$aksi .= '</div>';
			
			$jenis = (strtolower($row['jenis']) == "b") ? "BULANAN" : "TAHUNAN";

			$dataRow = array();
			$dataRow[] = $no_urut;
			$dataRow[] = $jenis;
			$dataRow[] = rupiah($row['harga']);
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

	function actionAdd($koneksi){
		$dataForm = isset($_POST) ? $_POST : false;

		// validasi
			// inisialisasi
			$status = $errorDb = false;

			$configData = configData($dataForm);
			$validasi = set_validasi($configData);
			$cek = $validasi['cek'];
			$pesanError = $validasi['pesanError'];
			$set_value = $validasi['set_value'];

		// ======================================= //
		if($cek){
			$dataForm = array(
				'jenis' => validInputan($dataForm['jenis'], false, false),
				'harga' => validInputan($dataForm['harga'], false, false),
			);

			// jika query berhasil
			if(insertHarga($koneksi, $dataForm)){
				$status = true;
				$errorDb = false;
			} 
			else{
				$status = false;
				$errorDb = true;
			}
		}
		else $status = false;

		$output = array(
			'status' => $status,
			'errorDb' => $errorDb,
			'pesanError' => $pesanError,
			'set_value' => $set_value,
		);

		echo json_encode($output);
	}

	function configData($data){
		$configData = array(
			// data jenis
			array(
				'field' => $data['jenis'], 'label' => 'Jenis Sewa', 'error' => 'jenisError',
				'value' => 'jenis', 'rule' => 'huruf | 1 | 1 | required',
			),
			// data harga
			array(
				'field' => $data['harga'], 'label' => 'Harga Sewa', 'error' => 'hargaError',
				'value' => 'harga', 'rule' => 'angka | 1 | 3000000 | required',
			),
		);

		return $configData;
	}