<?php

	include_once("../../function/helper.php");
	include_once("../../function/validasi_form.php");
	include_once("../../function/koneksi.php");

	//proteksi halaman proses sewa
	if(isset($_POST["sewaSubmit"])){

		//inisialisasi var field form
		$member = $_POST["member"]; //id member
		$no_parkir = $_POST["no_parkir"]; //id parkir
		$jenis_sewa = $_POST["jenis_sewa"]; //id harga
		$harga_sewa = $_POST["harga_sewa"];
		$tgl_sewa = $_POST["tgl_sewa"];
		$jatuh_tempo = $_POST["jatuh_tempo"];
		$status = "1"; //masih berlaku -- untuk tambah
		$btn = $_POST["sewaSubmit"];
		$admin = $_POST["userAdmin"];
		$id_sewa = isset($_GET['id_sewa']) ? validInputan($_GET['id_sewa'],false,true) : false;

		//inisialisasi pesan error
		$memberError = $no_parkirError = $jenis_sewaError = $statusError = "";
		$harga_sewaError =  $tgl_sewaError = $jatuh_tempoError = $pesanError = "";

		//validasi
			$cek = true;
			//inisialisasi pemanggilan fungsi
			$validMember = validField("Member",$member,"select",0,0,true); // wajib
			$validParkir = validField("No. Parkir",$no_parkir,"select",0,0,true); // wajib
			$validJenisSewa = validField("Jenis Sewa",$jenis_sewa,"select",0,0,true); // wajib - id harga
			//$validHargaSewa =
			$validTglSewa = validTgl("Tanggal Sewa",$tgl_sewa,true); //wajib
			$validJatuhTempo = validTgl("Jatuh Tempo",$jatuh_tempo,true); //wajib 

			//cek member
			if(!$validMember['cek']){
				$cek = false;
				$memberError = $validMember['error'];
			}

			//cek parkir
			if(!$validParkir['cek']){
				$cek = false;
				$no_parkirError = $validParkir['error'];
			}

			//cek jenis sewa
			if(!$validJenisSewa['cek']){
				$cek = false;
				$jenis_sewaError = $validJenisSewa['error'];
			}

			//cek tgl sewa
			if(!$validTglSewa['cek']){
				$cek = false;
				$tgl_sewaError = $validTglSewa['error'];
			}

			//cek jatuh tempo
			if(!$validJatuhTempo['cek']){
				$cek = false;
				$jatuh_tempoError = $validJatuhTempo['error'];
			}
		//=========================//

		if($cek){ //jika semua valid
			//amankan semua inputan dari injeksi
			$member = validInputan($member,false,false);
			$no_parkir = validInputan($no_parkir,false,false);
			$jenis_sewa = validInputan($jenis_sewa,false,false);
			//$harga_sewa = validInputan($harga_sewa,false,false);
			$tgl_sewa = validInputan($tgl_sewa,false,false);
			$jatuh_tempo = validInputan($jatuh_tempo,false,false);
			$status = validInputan($status,false,false);
			$admin = validInputan($admin,false,true);

			//filter lg dari injeksi sql
			$member = mysqli_real_escape_string($koneksi,$member);
			$no_parkir = mysqli_real_escape_string($koneksi,$no_parkir);
			$jenis_sewa = mysqli_real_escape_string($koneksi,$jenis_sewa);
			//$harga_sewa = mysqli_real_escape_string($koneksi,$harga_sewa);
			$tgl_sewa = mysqli_real_escape_string($koneksi,$tgl_sewa);
			$jatuh_tempo = mysqli_real_escape_string($koneksi,$jatuh_tempo);
			$status = mysqli_real_escape_string($koneksi,$status);
			$admin = mysqli_real_escape_string($koneksi,$admin);

			if(strtolower($btn) == "tambah"){ //jika tambah data
				//1. prepare statement
				$query = "CALL insert_t_sewa(?, ?, ?, ?, ?, ?, ?)";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "iiissss", $member, $no_parkir, $jenis_sewa, $tgl_sewa, $jatuh_tempo, $status, $admin);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_tambah";
			}
			elseif(strtolower($btn) == "ubah"){  //jika ubah data
				//1. prepare statement
				$query = "UPDATE t_sewa SET ";
				$query .= "id_member = ?, id_parkir = ?, id_harga = ?, tgl_sewa = ?, jatuh_tempo = ? WHERE id = ?";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "iiissi", $member, $no_parkir, $jenis_sewa, $tgl_sewa, $jatuh_tempo, $id_sewa);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_ubah";
			}

			//cek query sukses atau tidak
			if($execute){ //jika sukses
				header("Location: ".base_url."index.php?m=sewa&p=list&notif=$notif");
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
				'memberError' => $memberError,
				'no_parkirError' => $no_parkirError,
				'jenis_sewaError' => $jenis_sewaError,
				'harga_sewaError' => $harga_sewaError,
				'tgl_sewaError' => $tgl_sewaError,
				'jatuh_tempoError' => $jatuh_tempoError,
				'statusError' => $statusError,
			);
			$_SESSION['pesanError'] = $pesanError;
	
			//repopulate field, simpan ke array lalu kirim ke session
			$set_value = array(
				'member' => $member,
				'no_parkir' => $no_parkir,
				'jenis_sewa' => $jenis_sewa,
				'harga_sewa' => $harga_sewa,
				'tgl_sewa' => $tgl_sewa,
				'jatuh_tempo' => $jatuh_tempo,
				'status' => $status,
				'btn' => $btn,
			);
			$_SESSION['set_value'] = $set_value;
			
			if(strtolower($btn) == "ubah")
				header("Location: ".base_url."index.php?m=sewa&p=form&id_sewa=$id_sewa");
			elseif(strtolower($btn) == "tambah")
				header("Location: ".base_url."index.php?m=sewa&p=form");
		}
	}
	else{
		$action = isset($_GET['action']) ? $_GET['action'] : false;
		$id_sewa = isset($_GET['id_sewa']) ? $_GET['id_sewa'] : false;
		
		if($action){
			if(strtolower($action)=="expired"){ //ubah status menjadi expired
				$statusAction = "0";
				//1. prepare statement
				$query = "UPDATE t_sewa SET ";
				$query .= "status = ? WHERE id = ?";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "si", $statusAction, $id_sewa);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_ubah";
			}
			elseif(strtolower($action)=="hapus"){ //jika hapus

			}

			//cek query sukses atau tidak
			if($execute){ //jika sukses
				header("Location: ".base_url."index.php?m=sewa&p=list&notif=$notif");
			}
			else{ //jika gagal
				die ("Database Error: ".mysqli_errno($koneksi).
   				" - ".mysqli_error($koneksi));
			}
		}
		else{
			header("Location: ".base_url."index.php?m=sewa&p=form");
			die();
		}
	}	