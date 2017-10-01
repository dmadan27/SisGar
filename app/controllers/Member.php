<?php
	date_default_timezone_set('Asia/Jakarta');

	// Load semua fungsi yang dibutuhkan
	include_once("../function/helper.php");
	include_once("../function/koneksi.php");
	include_once("../function/validasi_form.php");
	include_once("../function/datatable.php");
	include_once("../models/Member_model.php");

	$action = isset($_POST['action']) ? $_POST['action'] : false;

	if(!$action) die("Dilarang Akses Halaman Ini !!");
	else{
		switch (strtolower($action)) {
			case 'list':
				list_member($koneksi);
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

	function list_member($koneksi){
		$config_db = array(
			'tabel' => 'v_member',
			'kolomOrder' => array(null, 'no_ktp', 'nama', 'no_telp', 'pekerjaan', null, null, null),
			'kolomCari' => array('no_ktp', 'nama', 'no_telp', 'pekerjaan', 'alamat', 'nopol'),
			'orderBy' => false,
			'kondisi' => false,
		);

		$data_member = get_all_member($koneksi, $config_db);

		// siapkan data untuk isi datatable
		$data = array();
		$no_urut = $_POST['start'];
		foreach($data_member as $row){
			$no_urut++;
			$aksi = '<div class="btn-group">';
			$aksi .= '<button type="button" class="btn btn-success btn-flat btn-sm" onclick="edit_member('."'".$row["id_member"]."'".')">Edit</button>';
			$aksi .= '<button type="button" class="btn btn-danger btn-flat btn-sm" onclick="hapus_member('."'".$row["id_member"]."'".')">Hapus</button>';		
			$aksi .= '</div>';
			
			$no_ktp = empty($row['no_ktp']) ? "-" : $row['no_ktp'];
			$no_telp = empty($row['no_telp']) ? "-" : $row['no_telp'];
			$pekerjaan = empty($row['pekerjaan']) ? "-" : $row['pekerjaan'];
			$alamat = empty($row['alamat']) ? "-" : $row['alamat'];
			$nopol = empty($row['nopol']) ? "-" : $row['nopol'];

			$dataRow = array();
			$dataRow[] = $no_urut;
			$dataRow[] = $no_ktp;
			$dataRow[] = $row['nama'];
			$dataRow[] = $no_telp;
			$dataRow[] = $pekerjaan;
			$dataRow[] = $alamat;
			$dataRow[] = $nopol;
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