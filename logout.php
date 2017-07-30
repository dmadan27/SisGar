<?php

	session_start();

	date_default_timezone_set('Asia/Jakarta');

	include_once("function/helper.php");
	include_once("function/koneksi.php");

	//var session
	$sess_login = isset($_SESSION['sess_login']) ? $_SESSION['sess_login'] : false;
	
	/*
		## Proteksi logout.php ##
		Bisa masuk jika :
		=> $sess_login - TRUE
	*/
	if($sess_login){ //jika terdeteksi login = true
		$logout = date('Y-m-d H:i:s');
        $kode_akses = $_SESSION['sess_kodeAkses'];
        $id_temp = $_SESSION['sess_id_kodeAkses'];
        $username = $_SESSION['sess_username'];
        //update data log, tambahkan logout
        //1. prepare statement
        $query = "UPDATE log_admin SET waktu_logout = ? WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $query);

        //2. bind
        mysqli_stmt_bind_param($stmt, "si", $logout, $id_temp);

        //3. execute
        $execute = mysqli_stmt_execute($stmt);

        //4. cek execute
        if($execute){
        	reset_session();
			header("Location: ".base_url); //arahkan ke index
	        die();
        }
        else{
        	reset_session();
			header("Location: ".base_url); //arahkan ke index
	        die();
        }

        mysqli_stmt_close($stmt);
		mysqli_close($koneksi);
	}
	else{ //jika mengakses langsung tanpa adanya session
		reset_session();
		header("Location: ".base_url); //arahkan ke index
        die();
        // echo "Anda mencoba mengakses logout";
	}