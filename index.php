<?php
	Define("BASE_PATH", true);
	date_default_timezone_set('Asia/Jakarta');

	// include semua fungsi
	include_once("app/function/helper.php");
	include_once("app/function/koneksi.php");
	include_once("app/function/validasi_form.php");

	// start session
	session_start();
	$sess_login = isset($_SESSION['sess_login']) ? $_SESSION['sess_login'] : false;
	$sess_username = isset($_SESSION['sess_username']) ? $_SESSION['sess_username'] : false;
	$sess_nama = isset($_SESSION['sess_nama']) ? $_SESSION['sess_nama'] : false;
	$sess_email = isset($_SESSION['sess_email']) ? $_SESSION['sess_email'] : false;
	$sess_foto = isset($_SESSION['sess_foto']) ? $_SESSION['sess_foto'] : false;
	$sess_level = isset($_SESSION['sess_level']) ? $_SESSION['sess_level'] : false;
	$sess_akses = isset($_SESSION['sess_akses']) ? $_SESSION['sess_akses'] : false;
	$sess_lockscreen = isset($_SESSION['sess_lockscreen']) ? $_SESSION['sess_lockscreen'] : false;
	// $sess_time = isset($_SESSION['sess_time']) ? $_SESSION['sess_time'] : false;

	// cek status login
	if(!$sess_login){
		header("Location: ".base_url."login.php");
		die();
	}

	// cek waktu idle

	$m = isset($_GET['m']) ? strtolower(validInputan($_GET['m'], false, false)) : false; // untuk get menu
	$p = isset($_GET['p']) ? strtolower(validInputan($_GET['p'], false, false)) : false; // untuk get page
	
	// cek hak akses
	if(!get_hak_akses($m, $sess_akses)){
		header("Location: ".base_url);
		die();
	}

	// //watu idle - 30 menit
	// if(isset($_SESSION['sess_lastActivity']) && (time() - $_SESSION['sess_lastActivity'] > (60*60) )){ //1 jam
 //    	$logout = date('Y-m-d H:i:s');
	// 	//update data log, tambahkan logout
	// 	//1. prepare statement
 //        $query = "UPDATE log_admin SET waktu_logout = ? WHERE id = ?";
 //        $stmt = mysqli_prepare($koneksi, $query);

 //        //2. bind
 //        mysqli_stmt_bind_param($stmt, "si", $logout, $sess_id_kodeAkses);

 //        //3. execute
 //        $execute = mysqli_stmt_execute($stmt);

 //    	if($execute){
 //        	idle();
 //        }
 //        else{
 //        	idle();
 //        }

 //        mysqli_stmt_close($stmt);
	// 	mysqli_close($koneksi);
	// }
	// $_SESSION['sess_lastActivity'] = time(); // update last activity time stamp

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		
		<title>Sistem Informasi Sewa Garasi Cesara</title>
		<!-- jQuery -->
		<script src="<?= base_url."assets/plugins/jQuery/jquery-2.2.3.min.js"; ?>"></script>
		<script type="text/javascript">
		    var base_url = "<?php print base_url; ?>";
		    var urlParams = <?php echo json_encode($_GET, JSON_HEX_TAG);?>;
		</script>
		<!-- CSS -->
		<?php include_once("app/views/template/css.php") ?>
	</head>
	<body class="hold-transition skin-blue sidebar-mini">

		<!-- Site Wrapper -->
		<div class="wrapper">
			<!-- Header -->
			<?php include_once("app/views/template/header.php") ?>

			<!-- Sidebar -->
			<?php include_once("app/views/template/sidebar.php") ?>

			<!-- Content -->
			<?php include_once("app/views/template/content.php") ?>

			<!-- Footer -->
			<?php include_once("app/views/template/footer.php") ?>

			<!-- Control Sidebar -->
			<?php include_once("app/views/template/control_sidebar.php") ?>

		</div>
		<!-- ./wrapper -->
		
		<!-- JavaScript -->
		<?php include_once("app/views/template/javascript.php") ?>
	</body>
</html>