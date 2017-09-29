<?php
	date_default_timezone_set('Asia/Jakarta');

	// Load semua fungsi yang dibutuhkan
	include_once("../function/helper.php");
	include_once("../function/koneksi.php");
	include_once("../function/validasi_form.php");
	include_once("../function/datatable.php");
	include_once("../models/Admin_model.php");

	$action = isset($_POST['action']) ? $_POST['action'] : false;

	if(!$action) die("Dilarang Akses Halaman Ini !!");
	else{
		switch (strtolower($action)) {
			case 'list':
				list_admin($koneksi);
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

	function list_admin($koneksi){
		$config_db = array(
			'tabel' => 'v_admin',
			'kolomOrder' => array(null, 'username', 'nama', 'email', 'hak_akses', 'level', 'status', null),
			'kolomCari' => array('username', 'nama', 'email', 'level', 'status'),
			'orderBy' => false,
			'kondisi' => false,
		);

		$data_admin = get_all_admin($koneksi, $config_db);

		// siapkan data untuk isi datatable
		$data = array();
		$no_urut = $_POST['start'];
		foreach($data_admin as $row){
			$no_urut++;
			$aksi = '<div class="btn-group">';
			$aksi .= '<a role="button" class="btn btn-info btn-flat btn-sm" href="'.base_url.'index.php?m=admin&p=view&id='.$row["username"].'">Detail</a>';
			
			if(strtolower($row['level']) != 'superadmin'){
				$aksi .= '<div class="btn-group">';
				$aksi .= '<button type="button" class="btn btn-success btn-sm btn-flat dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-down"></i> Edit</button>';
				$aksi .= '<ul class="dropdown-menu"><li><a role="button" href="'.base_url.'index.php?m=admin&p=form&id='.$row["username"].'"><i class="fa fa-plus"></i> Edit Profil</a></li>';
				$aksi .= '<li><a role="button" onclick="edit_status('."'".$row["username"]."'".')"><i class="fa fa-plus"></i> Edit Status</a></li>';
				$aksi .= '</ul></div>';
			}
		
			$aksi .= '</div>';
			
			$dataRow = array();
			$dataRow[] = $no_urut;
			$dataRow[] = $row['username'];
			$dataRow[] = $row['nama'];
			$dataRow[] = $row['email'];
			$dataRow[] = $row['hak_akses'];
			$dataRow[] = $row['level'];
			$dataRow[] = $row['status'];
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
		$foto = isset($_FILES['foto']) ? $_FILES['foto'] : false;

		// validasi
			// inisialisasi
			$cekFoto = true;
			$status = $errorDb = $duplikat = false;

			$configData = configData($dataForm);
			$validasi = set_validasi($configData);
			$cek = $validasi['cek'];
			$pesanError = $validasi['pesanError'];
			$set_value = $validasi['set_value'];

			if($foto){
				$configFoto = array(
					'error' => $foto['error'],
					'size' => $foto['size'],
					'name' => $foto['name'],
					'tmp_name' => $foto['tmp_name'],
					'max' => 2*1048576,
				);
				$valid_foto = validFoto($configFoto);
				if(!$valid_foto['cek']){
					$cek = false;
					$pesanError['fotoError'] = $valid_foto['error'];
				}
				else $valueFoto = $valid_foto['namaFile'];
			}
			else $valueFoto = "";

			// cek password
			$validPassword = validPassword("Password", $dataForm['password'], $dataForm['confirm'], 6, 50);
			if(!$validPassword['cek']){
				$cek = false;	
			}
			$pesanError['passwordError'] = $validPassword['error']['password'];
			$pesanError['confirmError'] = $validPassword['error']['confirm'];
			$set_value['password'] = $validPassword['value']['password'];
			$set_value['confirm'] = $validPassword['value']['confirm'];

		// ======================================= //
		if($cek){
			$dataForm = array(
				'username' => validInputan($dataForm['username'], false, true),
				'password' => password_hash($dataForm['password'], PASSWORD_BCRYPT),
				'confirm' => validInputan($dataForm['confirm'], false, true),
				'nama' => validInputan($dataForm['nama'], false, false),
				'email' => validInputan($dataForm['email'], false, true),
				'level' => validInputan($dataForm['level'], false, false),
				'foto' => validInputan($valueFoto, false, true),
			);

			// cek duplikat username
			$config_duplikat = array(
				'tabel' => 'v_admin',
				'field' => 'username',
				'value' => $dataForm['username'],
			);

			if(cekDuplikat($koneksi, $config_duplikat)){ // jika ada yg sama
				$status = $errorDb = false;
				$duplikat = true;
			}
			else{
				$duplikat = false;

				// upload foto
				if($foto){
					$path = "../../assets/gambar/admin/$valueFoto";
					if(!move_uploaded_file($foto['tmp_name'], $path)){
						$pesanError['fotoError'] = "Upload Foto Gagal";
						$status = $cekFoto = false;
					}
				}

				if($cekFoto){					
					// jika query berhasil
					if(insertAdmin($koneksi, $dataForm)){
						$status = true;
						$errorDb = false;
						session_start();
						$_SESSION['notif'] = "Tambah Data Berhasil";
					} 
					else{
						$status = false;
						$errorDb = true;
					}
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
			// data nama
			array(
				'field' => $data['nama'], 'label' => 'Nama', 'error' => 'namaError',
				'value' => 'nama', 'rule' => 'string | 1 | 150 | required',
			),
			// data username
			array(
				'field' => $data['username'], 'label' => 'Username', 'error' => 'usernameError',
				'value' => 'username', 'rule' => 'string | 1 | 50 | required',
			),
			// data email
			array(
				'field' => $data['email'], 'label' => 'Email', 'error' => 'emailError',
				'value' => 'email', 'rule' => 'email | 1 | 100 | not_required',
			),
			// data level
			array(
				'field' => $data['level'], 'label' => 'Level Admin', 'error' => 'levelError',
				'value' => 'level', 'rule' => 'huruf | 5 | 10 | required',
			),
		);

		return $configData;
	}