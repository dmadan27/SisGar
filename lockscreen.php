<?php
	session_start();
	
	include_once("function/helper.php");
	include_once("function/koneksi.php");

	//var session
	$sess_login = isset($_SESSION['sess_login']) ? $_SESSION['sess_login'] : false;
	$sess_lockscreen = isset($_SESSION['sess_lockscreen']) ? $_SESSION['sess_lockscreen'] : false;
	$sess_username = isset($_SESSION['sess_username']) ? $_SESSION['sess_username'] : false;
	$sess_nama = isset($_SESSION['sess_nama']) ? $_SESSION['sess_nama'] : false;

	/*
		## Proteksi lockscreen.php ##
		Bisa masuk jika :
		=> $sess_login - FALSE
		=> $sess_auth - FALSE
		=> $sess_kodeAkses - FALSE
		=> $sess_lockscreen - TRUE
	*/
	if(!$sess_lockscreen){
		header("Location: ".base_url); //arahkan ke index
        die();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Lockscreen | Sistem Informasi Sewa Garasi Cesara</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<script src="<?= base_url."assets/plugins/jQuery/jquery-2.2.3.min.js"; ?>"></script>
		<!-- css -->
			<?php include_once("pages/template/css.php"); ?>
		<!-- -->
	</head>
	<body class="hold-transition lockscreen">
		<div class="lockscreen-wrapper">
			<div class="lockscreen-logo">
				<a href="<?= base_url; ?>"><b>Admin</b>SisGar</a>
			</div>
		</div>
		<!-- username -->
		<div class="lockscreen-name"><?= $sess_username; ?></div>
		
		<!-- START LOCK SCREEN ITEM -->
  		<div class="lockscreen-item">
    		<!-- lockscreen image -->
    		<div class="lockscreen-image">
      			<img src="<?= base_url."assets/dist/img/user2-160x160.jpg"; ?>" alt="User Image">
    		</div>
    		<!-- /.lockscreen-image -->

    		<!-- lockscreen credentials (contains the form) -->
    		<form class="lockscreen-credentials" id="formLockscreen" method="post" action="<?= base_url."pages/login/proses_login.php"; ?>">
    			<input type="hidden" name="username" id="username" value="<?= $sess_username; ?>">
      			<div class="input-group">
       		 		<input type="password" class="form-control" placeholder="password" name="password" id="passwordLockscreen">
        			<div class="input-group-btn">
          				<button type="submit" class="btn" name="submitLockscreen" id="submitLockscreen"><i class="fa fa-arrow-right text-muted"></i></button>
        			</div>
      			</div>
    		</form>
    		<!-- /.lockscreen credentials -->
  		</div>
  		<!-- /.lockscreen-item -->
  		<div class="help-block text-center">
    		Waktu Idle Anda Telah Habis, Silahkan Login Ulang.
  		</div>
	  	<div class="text-center">
	    	<a href="<?= base_url."login.php"; ?>">Login Dengan Akun Yang Lain?</a>
	  	</div>

		<div class="lockscreen-footer text-center">
			<b>Beta Version</b> 0.1
			<br>
    		<strong>SI Sewa Garasi Cesara | Copyright &copy; <?php echo date("Y"); ?> <a href="http://lordraze.com">lordRaze.com</a></strong>
    		<br>All rights reserved.
  		</div>

		<?php include_once("pages/template/javascript.php"); ?>
	</body>
</html>