<?php
	
	include_once("../../function/helper.php");
	include_once("../../function/validasi_form.php");
	include_once("../../function/koneksi.php");

	//proteksi halaman proses keuangan
	if(isset($_POST["keuanganSubmit"])){
		//inisialisasi var field form
		$tgl = $_POST['tgl'];
		$ket_uang = $_POST["ket_uang"];
		$besaran = $_POST['besaran'];
		$keterangan = $_POST['keterangan'];
		$btn = $_POST["keuanganSubmit"];
		$admin = $_POST["userAdmin"];
		$id_keuangan = isset($_GET['id_keuangan']) ? validInputan($_GET['id_keuangan'],false,true) : false;

		//inisialisasi pesan error
		$tglError = $ket_uangError = $besaranError = $keteranganError = "";

		// var_dump($ket_uang);

		//validasi
			$cek = true;
			//inisialisasi pemanggilan fungsi
			$validTgl = validTgl("Tanggal",$tgl,true); //wajib
			$validKet_uang = validField("Keterangan Uang",$ket_uang,"select",0,0,true); // wajib
			$validBesaran = validTextAngka("Nominal",$besaran,1,10,true); //wajib
			$validKeterangan = validField("Keterangan",$keterangan,"textarea",1,255,true);

			//cek tgl
			if(!$validTgl['cek']){
				$cek = false;
				$tglError = $validTgl['error'];
			}

			//cek ket_uang
			if(!$validKet_uang['cek']){
				$cek = false;
				$ket_uangError = $validKet_uang['error'];
			}

			//cek besaran
			if(!$validBesaran['cek']){
				$cek = false;
				$besaranError = $validBesaran['error'];
			}

			//cek keterangan
			if(!$validKeterangan['cek']){
				$cek = false;
				$keteranganError = $validKeterangan['error'];
			}
		//====================================================//

		if($cek){ //jika semua valid
			//amankan semua inputan dari injeksi
			$tgl = validInputan($tgl,false,false);
			$ket_uang = validInputan($ket_uang,false,false);
			$besaran = validInputan($besaran,false,false);
			$keterangan = validInputan($keterangan,false,false);
			$admin = validInputan($admin,false,true);

			//filter lagi dari injeksi sql
			$tgl = mysqli_real_escape_string($koneksi,$tgl);
			$ket_uang = mysqli_real_escape_string($koneksi,$ket_uang);
			$besaran = mysqli_real_escape_string($koneksi,$besaran);
			$keterangan = mysqli_real_escape_string($koneksi,$keterangan);
			$admin = mysqli_real_escape_string($koneksi,$admin);

			if(strtolower($btn) == "tambah"){ //jika tambah data
				//1. prepare statement
				$query = "CALL insert_t_keuangan(?, ?, ?, ?, ?)";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "ssdss", $tgl, $ket_uang, $besaran, $keterangan, $admin);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_tambah";
			}
			elseif(strtolower($btn) == "ubah"){  //jika ubah data
				//1. prepare statement
				$query = "CALL update_t_keuangan(?, ?, ?, ?, ?, ?)";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "issdss", $id_keuangan, $tgl, $ket_uang, $besaran, $keterangan, $admin);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_ubah";
			}

			//cek query sukses atau tidak
			if($execute){ //jika sukses
				header("Location: ".base_url."index.php?m=keuangan&p=list&notif=$notif");
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
				'tglError' => $tglError,
				'ket_uangError' => $ket_uangError,
				'besaranError' => $besaranError,
				'keteranganError' => $keteranganError,
			);
			$_SESSION['pesanError'] = $pesanError;

			//repopulate field, simpan ke array lalu kirim ke session
			$set_value = array(
				'tgl' => $tgl,
				'ket_uang' => $ket_uang,
				'besaran' => $besaran,
				'keterangan' => $keterangan,
				'btn' => $btn,
			);
			$_SESSION['set_value'] = $set_value;

			if(strtolower($btn) == "ubah")
				header("Location: ".base_url."index.php?m=keuangan&p=form&id_keuangan=$id_keuangan");
			elseif(strtolower($btn) == "tambah")
				header("Location: ".base_url."index.php?m=keuangan&p=form");
		}
	}
	else{
		header("Location: ".base_url."index.php?m=keuangan&p=form");
			die();
	}