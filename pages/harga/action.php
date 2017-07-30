<?php
	include_once("../../function/helper.php");
	include_once("../../function/validasi_form.php");
	include_once("../../function/koneksi.php");

	//proteksi halaman proses member
	if(isset($_POST["hargaSubmit"])){

		//inisialisasi var field form
		$jenis_sewa = $_POST["jenis_sewa"];
		$harga_sewa = $_POST["harga_sewa"];
		$btn = $_POST["hargaSubmit"];
		$id_harga = isset($_GET['id_harga']) ? validInputan($_GET['id_harga'],false,true) : false;

		//inisialisasi pesan error
		$pesanError = $jenis_sewaError = $harga_sewaError = "";

		//validasi
			$cek = true;
			//inisialisasi pemanggilan fungsi
			$validJenisSewa = validField("Jenis Sewa",$jenis_sewa,"select",2,3,true); //wajib
			$validHargaSewa = validTextAngka("Harga Sewa",$harga_sewa,1,10,true); //wajib

			//cek Jenis Sewa
			if(!$validJenisSewa['cek']){
				$cek = false;
				$jenis_sewaError = $validJenisSewa['error'];
			}

			//cek harga sewa
			if(!$validHargaSewa['cek']){
				$cek = false;
				$harga_sewaError = $validHargaSewa['error'];
			}
		//=========================//

		if($cek){ //jika semua valid
			//amankan semua inputan dari injeksi
			$jenis_sewa = validInputan($jenis_sewa,false,false);
			$harga_sewa = validInputan($harga_sewa,false,false);

			//filter lg dari injeksi sql
			$jenis_sewa = mysqli_real_escape_string($koneksi,$jenis_sewa);
			$harga_sewa = mysqli_real_escape_string($koneksi,$harga_sewa);

			if(strtolower($btn) == "tambah"){ //jika tambah data
				//1. prepare statement
				$query = "INSERT INTO m_harga (jenis_sewa, harga_sewa) VALUES ";
				$query .= "(?, ?)";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "sd", $jenis_sewa, $harga_sewa);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_tambah";
			}
			elseif(strtolower($btn) == "ubah"){  //jika ubah data
				//1. prepare statement
				$query = "UPDATE m_harga SET ";
				$query .= "jenis_sewa = ?, harga_sewa = ? WHERE id = ?";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "sdi", $jenis_sewa, $harga_sewa, $id_harga);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_ubah";
			}

			//cek query sukses atau tidak
			if($execute){ //jika sukses
				header("Location: ".base_url."index.php?m=harga&p=list&notif=$notif");
			}
			else{ //jika gagal
				die ("Database Error: ".mysqli_errno($koneksi).
   				" - ".mysqli_error($koneksi));
			}

			mysqli_stmt_free_result($stmt);
			mysqli_stmt_close($stmt);
		}
		else{ //jika ada yang tidak valid
			session_start();

			//simpan pesan error ke array lalu kirim ke session
			$pesanError = array(
				'jenis_sewaError' => $jenis_sewaError,
				'harga_sewaError' => $harga_sewaError,
			);
			$_SESSION['pesanError'] = $pesanError;
	
			//repopulate field, simpan ke array lalu kirim ke session
			$set_value = array(
				'jenis_sewa' => $jenis_sewa,
				'harga_sewa' => $harga_sewa,
				'btn' => $btn,
			);
			$_SESSION['set_value'] = $set_value;

			if(strtolower($btn) == "ubah")
				header("Location: ".base_url."index.php?m=harga&p=form&id_harga=$id_harga");
			elseif(strtolower($btn) == "tambah")
				header("Location: ".base_url."index.php?m=harga&p=form");
		}


	}
	else{
		header("Location: ".base_url."index.php?m=harga&p=form");
		die();
	}