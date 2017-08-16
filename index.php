<?php
	Define("BASE_PATH",true);
	//include helper dan fungsi2 yang dibutuhkan
	include_once("function/helper.php");
	include_once("function/koneksi.php");
	include_once("function/validasi_form.php");

	//start seesion
	session_start();
	date_default_timezone_set('Asia/Jakarta');

	$m = isset($_GET['m']) ? validInputan($_GET['m'],false,true) : false; // untuk get menu
	$p = isset($_GET['p']) ? validInputan($_GET['p'],false,true) : false; // untuk get page
	$notif = isset($_GET['notif']) ? validInputan($_GET['notif'],false,true) : false; // untuk get page

	//var session
	$sess_login = isset($_SESSION['sess_login']) ? $_SESSION['sess_login'] : false;
	$sess_auth = isset($_SESSION['sess_auth']) ? $_SESSION['sess_auth'] : false;
	$sess_id_kodeAkses = isset($_SESSION['sess_id_kodeAkses']) ? $_SESSION['sess_id_kodeAkses'] : false; 
	$sess_kodeAkses = isset($_SESSION['sess_kodeAkses']) ? $_SESSION['sess_kodeAkses'] : false;

	$sess_username = isset($_SESSION['sess_username']) ? $_SESSION['sess_username'] : false;
	$sess_nama = isset($_SESSION['sess_nama']) ? $_SESSION['sess_nama'] : false;
	$sess_email = isset($_SESSION['sess_email']) ? $_SESSION['sess_email'] : false;
	$sess_lvl = isset($_SESSION['sess_lvl']) ? $_SESSION['sess_lvl'] : false;

	$sess_lockscreen = isset($_SESSION['sess_lockscreen']) ? $_SESSION['sess_lockscreen'] : false;

	/*
		## Proteksi index.php ##
		Bisa masuk jika :
		=> $sess_login - TRUE
		=> $sess_auth - TRUE
		=> $sess_kodeAkses - TRUE/Ada nilainya.
	*/
	// if(!$sess_login || !$sess_auth || !$sess_kodeAkses){
	// 	reset_session(); //hapus semua session yang ada
	// 	header("Location: ".base_url."login.php"); //arahkan ke login
 //        die();
	// }

	//cek kode akses
	//1. prepare statement
	$query = "SELECT id, user_admin, kode_akses FROM log_admin WHERE id = ?";
	$stmt = mysqli_prepare($koneksi, $query);

	//2. bind
	mysqli_stmt_bind_param($stmt, "i", $sess_id_kodeAkses);

	//3. execute
	$execute = mysqli_stmt_execute($stmt);

	//4. cek execute
	if($execute){
		$hasilQuery = mysqli_stmt_get_result($stmt);
		if(mysqli_num_rows($hasilQuery) == 0){
			reset_session(); //hapus semua session yang ada
			header("Location: ".base_url."login.php"); //arahkan ke login
	        die();
		}
		else{
			$row = mysqli_fetch_assoc($hasilQuery);
			$id_temp = $row['id'];
			$user_temp = $row['user_admin'];
			$kode_akses_temp = $row['kode_akses'];

			//cek user admin sesuai atau tidak
			if(($sess_username != $user_temp) || ($sess_id_kodeAkses != $id_temp)){
				reset_session(); //hapus semua session yang ada
				header("Location: ".base_url."login.php"); //arahkan ke login
		        die();
			}
			else{
				//cek kode akses
				if(!password_verify($sess_kodeAkses, $kode_akses_temp)){
		    		reset_session(); //hapus semua session yang ada
					header("Location: ".base_url."login.php"); //arahkan ke login
			        die();		 
				}
			}	
		}		
	}

	mysqli_stmt_close($stmt);

	//watu idle - 30 menit
	if(isset($_SESSION['sess_lastActivity']) && (time() - $_SESSION['sess_lastActivity'] > (60*60) )){ //1 jam
    	$logout = date('Y-m-d H:i:s');
		//update data log, tambahkan logout
		//1. prepare statement
        $query = "UPDATE log_admin SET waktu_logout = ? WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $query);

        //2. bind
        mysqli_stmt_bind_param($stmt, "si", $logout, $sess_id_kodeAkses);

        //3. execute
        $execute = mysqli_stmt_execute($stmt);

    	if($execute){
        	idle();
        }
        else{
        	idle();
        }

        mysqli_stmt_close($stmt);
		mysqli_close($koneksi);
	}
	$_SESSION['sess_lastActivity'] = time(); // update last activity time stamp

?>

<!DOCTYPE html>
<html>
	<head>
		<!-- jQuery 2.2.3 -->
		<script src="<?= base_url."assets/plugins/jQuery/jquery-2.2.3.min.js"; ?>"></script>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Sistem Informasi Sewa Garasi Cesara</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- CSS -->
			<?php include_once("pages/template/css.php"); ?>
		<!-- end CSS -->
	</head>
	<body class="hold-transition skin-blue sidebar-mini">

		<!-- Site Wrapper -->
		<div class="wrapper">
			<!-- Header -->
			<?php include_once("pages/template/header.php"); ?>

			<!-- Sidebar -->
			<?php include_once("pages/template/sidebar.php"); ?>

			<!-- Content -->
			<?php include_once("pages/template/content.php"); ?>

			<!-- Footer -->
			<?php include_once("pages/template/footer.php"); ?>

			<!-- Control Sidebar -->
			<?php include_once("pages/template/control_sidebar.php"); ?>
		</div>
		<!-- End Site Wrapper -->

		<!-- JavaScript -->
			<?php include_once("pages/template/javascript.php"); ?>
		<!-- end js -->
	</body>
</html>