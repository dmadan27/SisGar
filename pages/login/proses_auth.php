<?php
	//setting waktu default
	date_default_timezone_set('Asia/Jakarta');

	//load fungsi
	include_once("../../function/helper.php");
	include_once("../../function/koneksi.php");
	include_once("../../function/validasi_form.php");

	$cekAuth = isset($_POST['cekAuth']) ? $_POST['cekAuth'] : false;
	if(!$cekAuth){
		header("Location: ".base_url);
		die();
	}
	else{
		session_start();

		$kodeToken = $_POST['kodeToken'];
		$kode_akses = isset($_SESSION['sess_kodeAkses']) ? $_SESSION['sess_kodeAkses'] : false;
		$username = isset($_SESSION['sess_username']) ? $_SESSION['sess_username'] : false; 
		$cek = true;

		//validasi
			//filter semua inputan
			$kodeToken = validInputan($kodeToken,false,true);
			$kodeToken = mysqli_real_escape_string($koneksi, $kodeToken);

			if(empty($kodeToken)){ //jika kosong
				$cek = false;
				$hasilAuth = "empty";
				echo $hasilAuth;
				die();
			}
			else{
				//get kode token, kode akses di db
				//1. prepare statement
				$query = "SELECT * FROM t_token WHERE user_admin = ? AND kode_akses = ?";
				$stmt = mysqli_prepare($koneksi, $query);

				//2. bind
				mysqli_stmt_bind_param($stmt, "ss", $username, $kode_akses);

				//3. execute
				$execute = mysqli_stmt_execute($stmt);

				//4. cek execute
				if($execute){
					$hasilQuery = mysqli_stmt_get_result($stmt);
					//cek username ada atau tidak
					if(mysqli_num_rows($hasilQuery) == 0){ //jika tidak ada
						//hapus semua session
						reset_session();
						$cek = false;
						$hasilAuth = "fail";
						echo $hasilAuth;
						die();
					}
					else{ //jika ada
						$row = mysqli_fetch_assoc($hasilQuery);
						$id_token = $row['id'];
						// $_SESSION['sess_idToken'] = $id_token;
						$token_temp = $row['kode_token'];
						$timeout = $row['timeout']; //waktu timeout
						$now = time(); //waktu sekarang

						$timeout = strtotime($timeout);

						//cek kode token
						if((password_verify($kodeToken, $token_temp)) && ($now <= $timeout)){
							$cek = true;
						}
						else{ //jika kode token salah / waktu habis 
							$cek = false;
							$hasilAuth = "false_token";
						}
					}
				}
				else{
					die("Error: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
				}

				mysqli_stmt_close($stmt);
			}
		//=======================================//

		if($cek){
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
			if(!$execute){
				die("Error: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
			}

			//hapus data token di db
			//1. prepare statement
			$query = "DELETE FROM t_token WHERE id = ?";
			$stmt = mysqli_prepare($koneksi, $query);

			//2. bind
			mysqli_stmt_bind_param($stmt, "i", $id_token);

			//3. execute
			$execute = mysqli_stmt_execute($stmt);

			//4. cek execute
			if($execute){
				$_SESSION['sess_auth'] = true;
				$_SESSION['sess_login'] = true;
				// unset($_SESSION['auth']);
				// unset($_SESSION['sess_idToken']);
				$hasilAuth = "success";
				echo $hasilAuth;
			}
			else{
				$_SESSION['sess_auth'] = true;
				$_SESSION['sess_login'] = true;
				// unset($_SESSION['auth']);
				// unset($_SESSION['sess_idToken']);
				$hasilAuth = "success";
				echo $hasilAuth;
				die ("Database Error: ".mysqli_errno($koneksi).
   				" - ".mysqli_error($koneksi));
			}

			mysqli_stmt_close($stmt);
		}
		else{
			//hapus data token di db
			//1. prepare statement
			$query = "DELETE FROM t_token WHERE id = ?";
			$stmt = mysqli_prepare($koneksi, $query);

			//2. bind
			mysqli_stmt_bind_param($stmt, "i", $id_token);

			//3. execute
			$execute = mysqli_stmt_execute($stmt);

			//4. cek execute
			if($execute){
				reset_session();
			}
			else{
				reset_session();
				die("Error: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
			}
			echo $hasilAuth;
			mysqli_stmt_close($stmt);
			// die();
		}

		mysqli_close($koneksi);
	}