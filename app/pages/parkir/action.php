<?php
	include_once("../../function/helper.php");
	include_once("../../function/validasi_form.php");
	include_once("../../function/koneksi.php");

	//proteksi halaman proses member
	if(isset($_POST["parkirSubmit"])){

		//inisialisasi var field form
		$no_parkir = $_POST["no_parkir"];
		$status = $_POST["status"];
		$btn = $_POST["parkirSubmit"];
		$id_parkir = isset($_GET['id_parkir']) ? validInputan($_GET['id_parkir'],false,true) : false;

		//inisialisasi pesan error
		$pesanError = $no_parkirError = $statusError = "";

		//validasi
			$cek = true;
			//inisialisasi pemanggilan fungsi
			$validNoParkir = validField("No. Parkir",$no_parkir,"text",2,3,true); //wajib
			$validStatus = validField("Status Parkir",$status,"select",1,1,true); //wajib

			//cek no. parkir
			if(!$validNoParkir['cek']){
				$cek = false;
				$no_parkirError = $validNoParkir['error'];
			}

			//cek status parkir
			if(!$validStatus['cek']){
				$cek = false;
				$statusError = $validStatus['error'];
			}
		//=========================//

		if($cek){ //jika semua valid
			//amankan semua inputan dari injeksi
			$no_parkir = validInputan($no_parkir,false,false);
			$status = validInputan($status,false,false);

			//filter lg dari injeksi sql
			$no_parkir = mysqli_real_escape_string($koneksi,$no_parkir);
			$status = mysqli_real_escape_string($koneksi,$status);

			if(strtolower($btn) == "tambah"){ //jika tambah data
				//1. prepare statement
				$query = "INSERT INTO m_parkir (no_parkir, status) VALUES ";
				$query .= "(?, ?)";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "ss", $no_parkir, $status);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_tambah";
			}
			elseif(strtolower($btn) == "ubah"){  //jika ubah data
				//1. prepare statement
				$query = "UPDATE m_parkir SET ";
				$query .= "no_parkir = ?, status = ? WHERE id = ?";
				$stmt = mysqli_prepare($koneksi, $query);
				
				//2. bind
				mysqli_stmt_bind_param($stmt, "ssi", $no_parkir, $status, $id_parkir);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_ubah";
			}

			//cek query sukses atau tidak
			if($execute){ //jika sukses
				header("Location: ".base_url."index.php?m=parkir&p=list&notif=$notif");
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
				'no_parkirError' => $no_parkirError,
				'statusError' => $statusError,
			);
			$_SESSION['pesanError'] = $pesanError;
	
			//repopulate field, simpan ke array lalu kirim ke session
			$set_value = array(
				'no_parkir' => $no_parkir,
				'status' => $status,
				'btn' => $btn,
			);
			$_SESSION['set_value'] = $set_value;

			if(strtolower($btn) == "ubah")
				header("Location: ".base_url."index.php?m=parkir&p=form&id_parkir=$id_parkir");
			elseif(strtolower($btn) == "tambah")
				header("Location: ".base_url."index.php?m=parkir&p=form");
		}
	}
	else{
		header("Location: ".base_url."index.php?m=parkir&p=form");
		die();
	}	