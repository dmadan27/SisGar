<?php
	// setting date
	date_default_timezone_set('Asia/Jakarta');

	//load plugin PHPMailer
	require 'PHPMailer/PHPMailerAutoload.php';
	/*
		$toEmail => email penerima, disesuaikan dgn email admin
		$toName => nama penerima, disesuaikan dgn nama email
		$subjek => judul pesan
		$isiPesan => isi email
	*/
	function send_mail($toEmail, $toName, $subjek, $isiPesan){
		$cekValid['cek'] = true;
		$cekValid['error'] = "";

		// $username = "admin_sisgar@lordraze.com";
		// $password = "IniAdalahAdmin SISGAR";

		$username = "rarastasaputra@gmail.com";
		$password = "RamadanSaputra2702951234567890";

		$mail = new PHPMailer;

		$mail->CharSet = 'utf-8';
		ini_set('default_charset', 'UTF-8');
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = 'smtp.gmail.com'; //sesuaikan lagi
		$mail->Port = 587; //sesuaikan lagi
		$mail->SMTPSecure = 'tls'; //sesuaikan lagi
		$mail->SMTPAuth = true;
		$mail->Username = $username; //sesuaikan lagi
		$mail->Password = $password; //sesuaikan lagi
		$mail->setFrom($username, "ADMIN SISGAR");
		$mail->addAddress($toEmail, $toName);
		$mail->Subject = $subjek;
		$mail->Body = $isiPesan;
		// $mail->AltBody = 'Latihan Kirim Email dengan PHPMailer';
		if (!$mail->send()) { //jika gagal
		    $cekValid['error'] = "Mailer Error: " . $mail->ErrorInfo;
		    $cekValid['cek'] = false;
		}
		else {
		    $cekValid['error'] = "";
		    $cekValid['cek'] = true;
		}

		return $cekValid;
	}