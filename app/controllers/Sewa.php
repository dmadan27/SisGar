<?php
	date_default_timezone_set('Asia/Jakarta');

	// Load semua fungsi yang dibutuhkan
	include_once("../function/helper.php");
	include_once("../function/koneksi.php");
	include_once("../function/validasi_form.php");
	include_once("../function/datatable.php");
	include_once("../models/Sewa_model.php");

	$action = isset($_POST['action']) ? $_POST['action'] : false;

	if(!$action) die("Dilarang Akses Halaman Ini !!");
	else{
		switch (strtolower($action)) {
			case 'list':
				list_sewa($koneksi);
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
	
	function list_sewa($koneksi){
		$config_db = array(
			'tabel' => 'v_sewa',
			'kolomOrder' => array(null, 'nama', 'nopol', 'no_parkir', 'jenis', 'harga', 'tgl_sewa', 'jatuh_tempo', 'status', null),
			'kolomCari' => array('nama', 'nopol', 'no_parkir', 'jenis', 'harga', 'tgl_sewa', 'jatuh_tempo', 'status'),
			'orderBy' => false,
			'kondisi' => false,
		);

		$data_sewa = get_all_sewa($koneksi, $config_db);

		// siapkan data untuk isi datatable
		$data = array();
		$no_urut = $_POST['start'];
		foreach($data_sewa as $row){
			$no_urut++;
			$aksi = '<div class="btn-group">';
			$aksi .= '<button type="button" class="btn btn-success btn-flat btn-sm" onclick="edit_sewa('."'".$row["id_sewa"]."'".')">Edit</button>';
			$aksi .= '<button type="button" class="btn btn-danger btn-flat btn-sm" onclick="hapus_sewa('."'".$row["id_sewa"]."'".')">Hapus</button>';		
			$aksi .= '</div>';
			
			$status = (strtolower($row['status']) == "masih berlaku") ? '<span class="label label-success">'.$row['status'].'</span>' : '<span class="label label-danger">'.$row['status'].'</span>';

			$dataRow = array();
			$dataRow[] = $no_urut;
			$dataRow[] = $row['nama'];
			$dataRow[] = empty($row['nopol']) ? "-" : $row['nopol'];
			$dataRow[] = $row['no_parkir'];
			$dataRow[] = $row['jenis'];
			$dataRow[] = rupiah($row['harga']);
			$dataRow[] = cetakTgl($row['tgl_sewa'], 'full');
			$dataRow[] = cetakTgl($row['jatuh_tempo'], 'full');
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