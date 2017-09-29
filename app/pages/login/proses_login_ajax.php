<?php
	//setting waktu default
	date_default_timezone_set('Asia/Jakarta');

	//load fungsi
	include_once("../../function/helper.php");
	include_once("../../function/koneksi.php");
	include_once("../../function/validasi_form.php");

	//proteksi halaman
	$cekSubmit = isset($_POST['cekSubmit']) ? $_POST['cekSubmit'] : false;
	if(!$cekSubmit){
		header("Location: ".base_url);
		die();
	}
	else{
		session_start();
		
		//inisialisasi var
		$username = $_POST['username'];
		$password = $_POST['password'];

		//validasi
			$cek = true;

			//filter semua inputan
			$username = validInputan($username,false,true);
			$username = mysqli_real_escape_string($koneksi, $username);

			$password = validInputan($password,false,true);
			
			//cek kosong
			if(empty($username) || empty($password)){
				$cek = false;
				$hasil = "empty";
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
						$hasil = "wrong";
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
				    		$hasil = "sukses";
						}
						else{
				    		$cek = false;
				    		$hasil = "wrong";
						}
					}
				}
				else{
					die("Error: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
				}

				//tutup statement
				mysqli_stmt_close($stmt);

				// //tutup koneksi
				// mysqli_close($koneksi);
			}
		//===========================================//


		if($cek){
			//load fungsi generate token dan email
			include_once("../../function/generate_token.php"); //load fungsi generate token
			include_once("../../function/send_email.php"); //load fungsi send mail

			$kode_akses = getAkses(25);

			//cek level admin
			if(strtolower($lvl_temp) == "superadmin"){
				$cekAuth = true;
				//lakukan pengecekan ulang
				//kirim token ke email admin
				$lengthRandom = crypto_rand_secure(5,10); // angka random untuk token
				$token = getToken($lengthRandom);

				//hash kode token
				$config_hash = [
				    'cost' => 11,
				    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
				];
				$token_hash = password_hash($token, PASSWORD_BCRYPT, $config_hash);

				//kirim ke admin
				$subjek = "Konfirmasi Masuk SUPERADMIN";
				$pesan = "Kode Token : ".$token;
				$send_email = send_mail($email_temp, $nama_temp, $subjek, $pesan);

				if($send_email['cek']){ //jika berhasil
					//buat timeout
					$timeout = date('Y-m-d H:i:s', time()+(60*5));

					//simpan kode token ke db
					//1. prepare statement
					$query = "INSERT INTO t_token (user_admin, kode_token, kode_akses, timeout) VALUES (?, ?, ?, ?)";
					$stmt = mysqli_prepare($koneksi, $query);

					//2. bind
					mysqli_stmt_bind_param($stmt, "ssss", $username, $token_hash, $kode_akses, $timeout);

					//3. execute
					$execute = mysqli_stmt_execute($stmt);

					//4. cek execute
					if($execute){
						//daftarkan session
						$_SESSION['sess_auth'] = true;
						$_SESSION['sess_login'] = false;
					}
					else{
						$hasil = "error_db";
						echo $hasil;
						die();
					}

				}
				else{ //jika kirim email gagal
					$hasil = "fail_send_email";
					echo $hasil;
					die();
				}
			}
			else{
				$cekAuth = false;
				$_SESSION['sess_auth'] = true;
				$_SESSION['sess_login'] = true;
			}

			$_SESSION['sess_kodeAkses'] = $kode_akses;
			$_SESSION['sess_username'] = $row['username']; 
			$_SESSION['sess_nama'] = $row['nama'];
			$_SESSION['sess_email'] = $row['email'];
			$_SESSION['sess_lvl'] = $row['level'];
			$_SESSION['sess_lockscreen'] = false;

			if($cekAuth){
				$hasil = "auth";
				echo $hasil;
			}
			else{ //jika login admin biasa
				$_SESSION['sess_lastActivity'] = time();
				$ip = get_real_ip();
				$login = date('Y-m-d H:i:s');

				//insert data ke log user
				//1. prepare statement
				$query = "INSERT INTO log_admin (user_admin, waktu_login, ip, kode_akses) VALUES (?, ?, ?, ?)";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "ssss", $username, $login, $ip, $kode_akses);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);

				//4. cek execute
				if($execute){
					$hasil = "success";
					echo $hasil;	
				}
				else{
					die("Error: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
				}
				
				mysqli_stmt_close($stmt);
			}

			//tutup koneksi
			mysqli_close($koneksi);
		}
		else{
			echo $hasil;
			die();
		}
	}