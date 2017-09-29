<?php
	// date_default_timezone_set('Asia/Jakarta');

	include_once("app/function/helper.php");
	include_once("app/function/koneksi.php");

	session_start();
	session_unset(); //unset semua session
	session_destroy(); //hapus semua session
	header("Location: ".base_url);
	die();

