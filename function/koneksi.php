<?php
	//buat koneksi dengan db
	$dbHost = "localhost"; //host/server yg dipakai
	$dbUser = "root"; //username host/server 
	$dbPass = ""; //password host
	$dbName = "garasi"; //nama database
	$koneksi = mysqli_connect($dbHost,$dbUser,$dbPass,$dbName); //menampung koneksi

	//periksa koneksi, tampilkan pesan error jika gagal
	if(!$koneksi) //jika gagal
		die("Koneksi Ke Database Gagal: ".mysqli_connect_errno()." - ".mysqli_connect_error());