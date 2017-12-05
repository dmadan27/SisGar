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
			session_start();
			$admin = isset($_SESSION['sess_username']) ? $_SESSION['sess_username'] : false;
			
			$dataForm = array(
				'tgl' => validInputan($dataForm['tgl'], false, false),
				'jenis' => validInputan($dataForm['jenis'], false, false),
				'ket' => validInputan($dataForm['ket'], false, false),
				'nominal' => validInputan($dataForm['nominal'], false, false),
				'admin' => $admin,
			);
			
			// jika query berhasil
			if(insertKeuangan($koneksi, $dataForm)){
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
		// $test = array('test');

		echo json_encode($output);
	}

	function configData($data){
		$configData = array(
			// data tgl
			array(
				'field' => $data['tgl'], 'label' => 'Tanggal', 'error' => 'tglError',
				'value' => 'tgl', 'rule' => 'string | 1 | 150 | required',
			),
			// data jenis
			array(
				'field' => $data['jenis'], 'label' => 'Jenis Keungan', 'error' => 'jenisError',
				'value' => 'jenis', 'rule' => 'huruf | 1 | 1 | required',
			),
			// data keterangan
			array(
				'field' => $data['ket'], 'label' => 'Keterangan', 'error' => 'ketError',
				'value' => 'ket', 'rule' => 'string | 1 | 255 | required',
			),
			// data nominal
			array(
				'field' => $data['nominal'], 'label' => 'Nominal', 'error' => 'nominalError',
				'value' => 'nominal', 'rule' => 'angka | 1 | 99999999 | required',
			),
		);

		return $configData;
	}