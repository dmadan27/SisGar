<?php
	include_once("../../function/helper.php");
	include_once("../../function/validasi_form.php");
	include_once("../../function/koneksi.php");

	if(isset($_POST['adminSubmit'])){

		//inisialisasi
		$username = $_POST["username"];
		$password = $_POST["password"];
		$konfPass = $_POST["konfPass"];
		$nama = $_POST["nama"];
		$email = $_POST["email"];
		$level = $_POST["level"];
		$btn = $_POST["adminSubmit"];
		$id_admin = isset($_GET['id_admin']) ? validInputan($_GET['id_admin'],false,true) : false;

		$usernameError = $passwordError = $konfPassError = "";
		$namaError = $emailError = $levelError  = "";

		//validasi
			$cek = true;
			//inisialisasi pemanggilan fungsi
			$validUsername = validField("Username",$username,"text",1,50,true);
			$validPassword = validField("Password",$password,"text",1,50,true);
			$validKonfPass = validField("Konfirmasi Password",$konfPass,"text",1,50,true);
			$validNama = validTextHuruf("Nama",$nama,0,255,true);
			$validEmail = validField("Email",$email,"text",1,50,false);
			$validLevel = validField("Level Admin",$level,"select",1,50,true);

			if(!$validUsername['cek']){
				$cek = false;
				$usernameError = $validUsername['error'];
			}

			if(!$validPassword['cek']){
				$cek = false;
				$passwordError = $validPassword['error'];
			}
			else{
				if($password !== $konfPass){
					$cek = false;
					$passwordError = $konfPassError = "Password dan Konfirmasi Password Tidak Sama";
				}
			}

			if(!$validNama['cek']){
				$cek = false;
				$namaError = $validNama['error'];
			}

			if(!$validEmail['cek']){
				$cek = false;
				$emailError = $validEmail['error'];
			}

			if(!$validLevel['cek']){
				$cek = false;
				$levelError = $validLevel['error'];
			}

			//cek ketersediaan username
			// if(!$validUsername['cek']) $usernameError = $validUsername['error'];
			// else{ //cek username tersedia atau tidak
			// 	$query = "SELECT username FROM admin WHERE username = ?";
			// 	$stmt = mysqli_prepare($koneksi, $query);
			// 	mysqli_stmt_bind_param($stmt, "s", $username);
			// 	$execute = mysqli_stmt_execute($stmt);
			// 	if($execute){
			// 		$hasilQuery = mysqli_stmt_get_result($stmt);
			// 		if(mysqli_num_rows($hasilQuery) == 0){ //jika tidak ada yg sama
			// 			//tersedia
			// 		}
			// 		else{
			// 			//tidak tersedia
			// 		}
			// 	}
			// 	else{
			// 		die("Error: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
			// 	}
			// }


		//================================================//

		if($cek){
			$username = validInputan($username,false,true);
			$password = validInputan($password,false,true);
			$nama = validInputan($nama,false,false);
			$email = validInputan($email,false,true);
			$level = validInputan($level,false,true);

			$config_hash = [
				    'cost' => 11,
				    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
				];
			$pass_hash = password_hash($password, PASSWORD_BCRYPT, $config_hash);

			$username = mysqli_real_escape_string($koneksi,$username);
			$email = mysqli_real_escape_string($koneksi,$email);
			$level = mysqli_real_escape_string($koneksi,$level);

			if(strtolower($btn) == "tambah"){
				$query = "INSERT INTO admin (username, password, nama, level, email) VALUES ";
				$query .= "(?, ?, ?, ?, ?)";
				$stmt = mysqli_prepare($koneksi, $query);

				mysqli_stmt_bind_param($stmt, "sssss", $username, $pass_hash, $nama, $level, $email);

				$execute = mysqli_stmt_execute($stmt);
				$notif = "succes_tambah";
			}
			elseif(strtolower($btn) == "ubah"){

			}

			if($execute){
				header("Location: ".base_url."index.php?m=admin&p=list&notif=$notif");
			}
			else{
				die ("Database Error: ".mysqli_errno($koneksi).
   				" - ".mysqli_error($koneksi));
			}

			mysqli_stmt_free_result($stmt);
			mysqli_stmt_close($stmt);
			mysqli_close($koneksi);
		}
		else{
			session_start();

			//simpan pesan error ke array lalu kirim ke session
			$pesanError = array(
				'usernameError' => $usernameError,
				'passwordError' => $passwordError,
				'konfPassError' => $konfPassError,
				'namaError' => $namaError,
				'emailError' => $emailError,
				'levelError' => $levelError,
			);
			$_SESSION['pesanError'] = $pesanError;
	
			//repopulate field, simpan ke array lalu kirim ke session
			$set_value = array(
				'username' => $username,
				'password' => $password,
				'konfPass' => $konfPass,
				'nama' => $nama,
				'email' => $email,
				'level' => $level,
				'btn' => $btn,
			);
			$_SESSION['set_value'] = $set_value;

			if(strtolower($btn) == "ubah")
				header("Location: ".base_url."index.php?m=admin&p=form&id_admin=$id_admin");
			elseif(strtolower($btn) == "tambah")
				header("Location: ".base_url."index.php?m=admin&p=form");
		}

	}
	else{
		header("Location: ".base_url."index.php?m=admin&p=form");
		die();
	}