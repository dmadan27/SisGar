<?php
	//setting waktu default
	date_default_timezone_set('Asia/Jakarta');

	//load fungsi
	include_once("../../function/helper.php");
	include_once("../../function/koneksi.php");
	include_once("../../function/validasi_form.php");

	//proteksi halaman
	if(isset($_POST["submitLogin"]) || isset($_POST["submitLockscreen"])){
		session_start();
		
		//inisialisasi var
		$username = $_POST['username'];
		$password = $_POST['password'];

		$pesanError = $usernameError = $passwordError = $hasil = "";

		//validasi
			$cek = true;

			//filter semua inputan
			$username = validInputan($username,false,true);
			$username = mysqli_real_escape_string($koneksi, $username);

			$password = validInputan($password,false,true);
			
			//cek kosong
			if(empty($username) || empty($password)){
				$cek = false;
				$usernameError = $passwordError = "Username atau Password Harap Diisi";
				$hasil = "";
			}
			else{
				//get data admin
				
				//1. prepare statment
				$query = "SELECT * FROM admin WHERE username = ?";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "s", $username);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);

				//4. cek execute
				if($execute){
					// $hasil = var_dump(mysqli_stmt_get_result($stmt));
					$hasilQuery = mysqli_stmt_get_result($stmt);
					//cek username ada atau tidak
					if(mysqli_num_rows($hasilQuery) == 0){
						$cek = false;
						$usernameError = $passwordError = "Username atau Password Anda Salah";
						$hasil = "";
					}
					else{
						//get data admin
						$row = mysqli_fetch_assoc($hasilQuery);
						$nama_temp = $row['nama'];
						$email_temp = $row['email'];
						$pass_temp = $row['password'];
						$lvl_temp = $row['level'];

						//cek password
						if(password_verify($password, $pass_temp)){
				    		$cek = true;
				    		$usernameError = $passwordError = $hasil = "";
						}
						else{
				    		$cek = false;
				    		$usernameError = $passwordError = "Username atau Password Anda Salah";
				    		$hasil = "";
						}
					}
				}
				else{
					die("Error: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
				}

				//tutup statement
				mysqli_stmt_close($stmt);
			}
		//===========================================//


		if($cek){
			//get kode akses
			include_once("../../function/generate_token.php"); //load fungsi generate token
			$lengthRandom = crypto_rand_secure(20,25);
			$kode_akses = getAkses($lengthRandom);
			$config_hash = [
				    'cost' => 11,
				    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
				];
			$kodeAkses_hash = password_hash($kode_akses, PASSWORD_BCRYPT, $config_hash);

			$ip = get_real_ip();
			$login = date('Y-m-d H:i:s');
			
			//get id log user
			$query = "SELECT `AUTO_INCREMENT` id_kode_akses FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'garasi' AND   TABLE_NAME = 'log_admin'";
			$hasilQuery = mysqli_query($koneksi, $query);
			if(!$hasilQuery){
				die ("Query Error: ".mysqli_errno($koneksi).
           		" - ".mysqli_error($koneksi));
			}
			else{
				$dataLog = mysqli_fetch_assoc($hasilQuery);
			}

			//insert data ke log user
			//1. prepare statement
			$query = "INSERT INTO log_admin (user_admin, waktu_login, ip, kode_akses) VALUES (?, ?, ?, ?)";
			$stmt = mysqli_prepare($koneksi, $query);

			//2. bind
			mysqli_stmt_bind_param($stmt, "ssss", $username, $login, $ip, $kodeAkses_hash);

			//3. execute
			$execute = mysqli_stmt_execute($stmt);

			//4. cek execute
			if($execute){
				$usernameError = $passwordError = $hasil = "";

				$_SESSION['sess_auth'] = true;
				$_SESSION['sess_login'] = true;
				$_SESSION['sess_username'] = $row['username']; 
				$_SESSION['sess_nama'] = $row['nama'];
				$_SESSION['sess_email'] = $row['email'];
				$_SESSION['sess_lvl'] = $row['level'];
				$_SESSION['sess_lockscreen'] = false;
				$_SESSION['sess_lastActivity'] = time();
				$_SESSION['sess_kodeAkses'] = $kode_akses;
				$_SESSION['sess_id_kodeAkses'] = $dataLog['id_kode_akses'];	

				//tutup koneksi
				mysqli_close($koneksi);

				header("Location: ".base_url);
				die();	
			}
			else{
				die("Error: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
			}
			
			mysqli_stmt_close($stmt);

			
		}
		else{
			//simpan pesan error ke array lalu kirim ke session
			$pesanError = array(
				'usernameError' => $usernameError,
				'passwordError' => $passwordError,
				'hasil' => $hasil,
			);
			$_SESSION['pesanError'] = $pesanError;

			$set_value = array(
				'username' => $username,
				'password' => $password,
			);
			$_SESSION['set_value'] = $set_value;

			header("Location: ".base_url."login.php");
		}
	}
	else{
		header("Location: ".base_url);
		die();
	}