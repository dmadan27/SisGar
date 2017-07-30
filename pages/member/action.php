<?php
	include_once("../../function/helper.php");
	include_once("../../function/validasi_form.php");
	include_once("../../function/koneksi.php");

	//proteksi halaman proses member
	if(isset($_POST["memberSubmit"])){

		//inisialisasi var field form
		$no_ktp = $_POST["no_ktp"];
		$nama = $_POST["nama"];
		$pekerjaan = $_POST["pekerjaan"];
		$alamat = $_POST["alamat"];
		$no_telp = $_POST["no_telp"];
		$no_kendaraan = $_POST["no_kendaraan"];
		$btn = $_POST["memberSubmit"];
		$id_member = isset($_GET['id_member']) ? validInputan($_GET['id_member'],false,true) : false;

		//inisialisasi pesan error
		$pesanError = $no_ktpError = $namaError = "";
		$pekerjaanError = $alamatError = $no_telpError ="";
		$no_kendaraanError = $fotoError = "";

		//config foto
		$configFoto = array(
			'name' => $_FILES['foto']['name'],
			'type' => $_FILES['foto']['type'],
			'tmp_name' => $_FILES['foto']['tmp_name'],
			'error' => $_FILES['foto']['error'],
			'size' => $_FILES['foto']['size'],
		);

		//validasi
			$cek = true;
			//inisialisasi pemanggilan fungsi
			$validKtp = validTextAngka("No. KTP",$no_ktp,16,20,true); //wajib
			$validNama = validTextHuruf("Nama",$nama,0,255,true); //wajib
			$validPekerjaan = validField("Pekerjaan",$pekerjaan,"text",1,255,false); //opsional
			$validAlamat = validField("Alamat",$alamat,"textarea",1,255,false); //opsional
			$validNoTelp = validTextAngka("No. Telepon",$no_telp,1,20,false); //opsional
			$validNopol = validField("No. Polisi Kendaraan",$pekerjaan,"text",1,25,false); //opsional
			$validFoto = validUploadGambar($configFoto,716800,false); //opsional - 700kb

			//cek no_ktp
			if(!$validKtp['cek']){
				$cek = false;
				$no_ktpError = $validKtp['error'];
			}

			//cek nama
			if(!$validNama['cek']){
				$cek = false;
				$namaError = $validNama['error'];
			}

			//cek pekerjaan
			if(!$validPekerjaan['cek']){
				$cek = false;
				$pekerjaanError = $validPekerjaan['error'];
			}

			//cek alamat
			if(!$validAlamat['cek']){
				$cek = false;
				$alamatError = $validAlamat['error'];
			}

			//cek no_telepon
			if(!$validNoTelp['cek']){
				$cek = false;
				$no_telpError = $validNoTelp['error'];
			}

			//cek nopol
			if(!$validNopol['cek']){
				$cek = false;
				$no_kendaraanError = $validNopol['error'];
			}

			//cek foto
			if(!$validFoto['cek']){
				$cek = false;
				$fotoError = $validFoto['error'];
			}
		//=========================//
		
		if($cek){ //jika semua valid
			//amankan semua inputan dari injeksi
			$no_ktp = validInputan($no_ktp,false,false);
			$nama = validInputan($nama,false,false);
			$pekerjaan = validInputan($pekerjaan,false,false);
			$alamat = validInputan($alamat,false,false);
			$no_telp = validInputan($no_telp,false,false);
			$no_kendaraan = validInputan($no_kendaraan,false,false);
			$foto = validInputan($configFoto['name'],false,true);

			//filter lg dari injeksi sql
			$no_ktp = mysqli_real_escape_string($koneksi,$no_ktp);
			$nama = mysqli_real_escape_string($koneksi,$nama);
			$pekerjaan = mysqli_real_escape_string($koneksi,$pekerjaan);
			$alamat = mysqli_real_escape_string($koneksi,$alamat);
			$no_telp = mysqli_real_escape_string($koneksi,$no_telp);
			$no_kendaraan = mysqli_real_escape_string($koneksi,$no_kendaraan);
			$foto = mysqli_real_escape_string($koneksi,$foto);
			
			//cek apakah upload foto atau tidak
			if(!empty($configFoto['name'])){ //jika upload foto
				//upload file dan ubah nama sesuai nim
				$namaFileBaru = gantiNamaFile($foto,$no_ktp);
				$namaFolder = "../../gambar";
				$pathFile = "$namaFolder/$namaFileBaru";
				$foto = mysqli_real_escape_string($koneksi,$namaFileBaru);
				
				if(!move_uploaded_file($configFoto['tmp_name'], $pathFile)){ //jika gagal upload foto
					$notif = "error_upload";
					header("Location: ".base_url."index.php?m=member&p=form&notif=$notif");
					die();
				}
			}

			if(strtolower($btn) == "tambah"){ //jika tambah data
				//1. prepare statement
				$query = "INSERT INTO m_member (no_ktp, nama, pekerjaan, alamat, no_telp, no_kendaraan, foto) VALUES ";
				$query .= "(?, ?, ?, ?, ?, ?, ?)";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "sssssss", $no_ktp, $nama, $pekerjaan, $alamat, $no_telp, $no_kendaraan, $foto);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_tambah";
			}
			elseif(strtolower($btn) == "ubah"){  //jika ubah data
				//1. prepare statement
				$query = "UPDATE m_member SET ";
				$query .= "no_ktp = ?, nama = ?, pekerjaan = ?, alamat = ?, no_telp = ?, no_kendaraan = ?, foto = ? WHERE id = ?";
				$stmt = mysqli_prepare($koneksi, $query);
				
				//2. bind
				mysqli_stmt_bind_param($stmt, "sssssssi", $no_ktp, $nama, $pekerjaan, $alamat, $no_telp, $no_kendaraan, $foto, $id_member);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_ubah";
			}

			//cek query sukses atau tidak
			if($execute){ //jika sukses
				header("Location: ".base_url."index.php?m=member&p=list&notif=$notif");
			}
			else{ //jika gagal
				die ("Database Error: ".mysqli_errno($koneksi).
   				" - ".mysqli_error($koneksi));
			}

			mysqli_stmt_free_result($stmt);
			mysqli_stmt_close($stmt);
			mysqli_close($koneksi);

		}
		else{ //jika ada yang tidak valid
			session_start();

			//simpan pesan error ke array lalu kirim ke session
			$pesanError = array(
				'no_ktpError' => $no_ktpError,
				'namaError' => $namaError,
				'pekerjaanError' => $pekerjaanError,
				'alamatError' => $alamatError,
				'no_telpError' => $no_telpError,
				'no_kendaraanError' => $no_kendaraanError,
				'fotoError' => $fotoError,
			);
			$_SESSION['pesanError'] = $pesanError;
	
			//repopulate field, simpan ke array lalu kirim ke session
			$set_value = array(
				'no_ktp' => $no_ktp,
				'nama' => $nama,
				'pekerjaan' => $pekerjaan,
				'alamat' => $alamat,
				'no_telp' => $no_telp,
				'no_kendaraan' => $no_kendaraan,
				'btn' => $btn,
			);
			$_SESSION['set_value'] = $set_value;
			
			if(strtolower($btn) == "ubah")
				header("Location: ".base_url."index.php?m=member&p=form&id_member=$id_member");
			elseif(strtolower($btn) == "tambah")
				header("Location: ".base_url."index.php?m=member&p=form");
		}
		
	}
	else{
		header("Location: ".base_url."index.php?m=member&p=form");
		die();
	}	