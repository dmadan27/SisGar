<?php
	date_default_timezone_set('Asia/Jakarta');

	// Load semua fungsi yang dibutuhkan
	include_once("../function/helper.php");
	include_once("../function/koneksi.php");
	include_once("../function/validasi_form.php");
	include_once("../function/datatable.php");
	include_once("../models/Parkir_model.php");

	$action = isset($_POST['action']) ? $_POST['action'] : false;

	if(!$action) die("Dilarang Akses Halaman Ini !!");
	else{
		switch (strtolower($action)) {
			case 'list':
				list_parkir($koneksi);
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
	
	function list_parkir($koneksi){
		$config_db = array(
			'tabel' => 'm_parkir',
			'kolomOrder' => array(null, 'no_parkir', 'status', null),
			'kolomCari' => array('no_parkir', 'status'),
			'orderBy' => array("no_parkir" => "asc"),
			'kondisi' => false,
		);

		$data_parkir = get_all_parkir($koneksi, $config_db);

		// siapkan data untuk isi datatable
		$data = array();
		$no_urut = $_POST['start'];
		foreach($data_parkir as $row){
			$no_urut++;
			$aksi = '<div class="btn-group">';
			$aksi .= '<button type="button" class="btn btn-success btn-flat btn-sm" onclick="edit_parkir('."'".$row["id"]."'".')">Edit</button>';
			$aksi .= '<button type="button" class="btn btn-danger btn-flat btn-sm" onclick="hapus_parkir('."'".$row["id"]."'".')">Hapus</button>';		
			$aksi .= '</div>';
			
			$status = (strtolower($row['status']) == "1") ? '<span class="label label-success">TERSEDIA</span>' : '<span class="label label-danger">TIDAK TERSEDIA</span>';

			$dataRow = array();
			$dataRow[] = $no_urut;
			$dataRow[] = $row['no_parkir'];
			$dataRow[] = $status;
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
			$status = $errorDb = $duplikat = false;

			$configData = configData($dataForm);
			$validasi = set_validasi($configData);
			$cek = $validasi['cek'];
			$pesanError = $validasi['pesanError'];
			$set_value = $validasi['set_value'];

		// ======================================= //
		if($cek){
			$dataForm = array(
				'no_parkir' => validInputan($dataForm['no_parkir'], false, false),
			);

			$config_duplikat = array(
				'tabel' => 'm_parkir',
				'field' => 'no_parkir',
				'value' => $dataForm['no_parkir'],
			);

			if(cekDuplikat($koneksi, $config_duplikat)){ // jika ada yg sama
				$status = $errorDb = false;
				$duplikat = true;
			}
			else{
				$duplikat = false;
				// jika query berhasil
				if(insertParkir($koneksi, $dataForm)){
					$status = true;
					$errorDb = false;
				} 
				else{
					$status = false;
					$errorDb = true;
				}
			}	
		}
		else $status = false;

		$output = array(
			'status' => $status,
			'errorDb' => $errorDb,
			'duplikat' => $duplikat,
			'pesanError' => $pesanError,
			'set_value' => $set_value,
		);

		echo json_encode($output);
	}

	function configData($data){
		$configData = array(
			// data no parkir
			array(
				'field' => $data['no_parkir'], 'label' => 'No Parkir', 'error' => 'no_parkirError',
				'value' => 'no_parkir', 'rule' => 'string | 3 | 3 | required',
			),
		);

		return $configData;
	}