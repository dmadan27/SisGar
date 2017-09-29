<?php
    include_once("app/function/helper.php");
	include_once("app/function/koneksi.php");
	session_start();
	// var_dump($_SESSION);
	$sess_login = isset($_SESSION['sess_login']) ? $_SESSION['sess_login'] : false;
	// $sess_username = isset($_SESSION['sess_username']) ? $_SESSION['sess_username'] : false;
	// $sess_nama = isset($_SESSION['sess_nama']) ? $_SESSION['sess_nama'] : false;
	// $sess_email = isset($_SESSION['sess_email']) ? $_SESSION['sess_email'] : false;
	// $sess_foto = isset($_SESSION['sess_foto']) ? $_SESSION['sess_foto'] : false;
	// $sess_level = isset($_SESSION['sess_level']) ? $_SESSION['sess_level'] : false;
	// $sess_akses = isset($_SESSION['sess_akses']) ? $_SESSION['sess_akses'] : false;
	// $sess_lockscreen = isset($_SESSION['sess_lockscreen']) ? $_SESSION['sess_lockscreen'] : false;

	if($sess_login){
		header("Location: ".base_url);
		die();
	}

?>

<!DOCTYPE html>
<html>
	<head>
	  	<meta charset="utf-8">
	  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	  	<title>Login | Sistem Informasi Sewa Garasi Cesara</title>
	  	<!-- Tell the browser to be responsive to screen width -->
	  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	 	<!-- jQuery -->
		<script src="<?= base_url."assets/plugins/jQuery/jquery-2.2.3.min.js"; ?>"></script>
		<!-- CSS -->
		<?php include_once("app/views/template/css.php") ?>
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
		  	<div class="login-logo">
		    	<a href="<?= base_url; ?>"><b>Login</b>SISGAR</a>
		  	</div>
		  	<!-- /.login-logo -->
		  	<div class="login-box-body">
			  	<p class="login-box-msg">Silahkan Login untuk Masuk ke Sistem</p>
			    <!-- form -->
			    <form id="form_login" role="form">
			      	<div class="form-group has-feedback field-username">
			        	<input type="text" class="form-control" placeholder="Masukkan Username" id="username" name="username">
			        	<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			        	<span class="help-block small"></span>
			      	</div>
			      	<div class="form-group has-feedback field-password">
			       	 	<input type="password" class="form-control" placeholder="Masukkan Password" id="password" name="password">
			        	<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			        	<span class="help-block small"></span>
			      	</div>
			      	<hr>
			      	<div class="row">
			        	<!-- /.col -->
			        	<div class="col-xs-offset-8 col-xs-4">
			          		<button id="btn_login" value="login" type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
			        	</div>
			        	<!-- /.col -->
			      	</div>
			    </form>
			    <a href="#">Lupa Password?</a>
			</div>
		  	<!-- /.login-box-body -->
		</div>
		<!-- JavaScript -->
		<?php include_once("app/views/template/javascript.php") ?>
		<!-- js custom -->
		<script type="text/javascript">
            var base_url = "<?php print base_url; ?>";
        </script>
        <script type="text/javascript">
        	$(document).ready(function(){
        		$("#username").change(function(){
        			$('.field-username').removeClass('has-error');
        			$('.field-username').find('.help-block').text('');
        		});

        		$("#password").change(function(){
        			$('.field-password').removeClass('has-error');
        			$('.field-password').find('.help-block').text('');
        		});

        		$("#form_login").submit(function(e){
        			e.preventDefault();
					login();

					return false;
        		});
        	});

        	function login(){
        		$.ajax({
        			url: base_url+"app/controllers/Login.php",
        			type: "post",
        			dataType: "json",
        			data:{
        				"username": $("#username").val().trim(),
        				"password": $("#password").val().trim(),
        				"action": $("#btn_login").val().trim(),
        			},
        			success: function(hasil){
        				console.log(hasil);
        				if(hasil.status) document.location=base_url;
        				else{
        					// set error
        					set_error(hasil.pesanError);
        					// set value
        					set_value(hasil.set_value);
        				}
        			},
        			error: function (jqXHR, textStatus, errorThrown) { // error handling
			            swal("Pesan Error", "Operasi Gagal, Silahkan Coba Lagi", "error");
			            console.log(jqXHR, textStatus, errorThrown);
			        }
        		})
        	}

        	function set_value(data){
        		$("#username").val(data.username);
        		$("#password").val(data.password);
        	}

        	function set_error(data){
        		if(!jQuery.isEmptyObject(data.username)){
        			$('.field-username').addClass('has-error');
        			$('.field-username').find('.help-block').text(data.username);
        		}
        		else{
        			$('.field-username').removeClass('has-error');
        			$('.field-username').find('.help-block').text('');
        		}

        		if(!jQuery.isEmptyObject(data.password)){
        			$('.field-password').addClass('has-error');
        			$('.field-password').find('.help-block').text(data.password);
        		}
        		else{
        			$('.field-password').removeClass('has-error');
        			$('.field-password').find('.help-block').text('');
        		}
        	}
        </script>
	</body>
</html>