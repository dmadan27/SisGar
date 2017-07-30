<?php
	define("base_url", "http://localhost/garasi/");

	//fungsi format rupiah
	function rupiah($harga){
		$string = "Rp. ".number_format($harga,2,",",".");
		return $string;
	}

	//fungsi format tgl indo
	function cetakTgl($tgl, $full = false){
		//array hari
		$arrHari = array(
					1 => "Senin",
					2 => "Selasa",
					3 => "Rabu",
					4 => "Kamis",
					5 => "Jumat",
					6 => "Sabtu",
					7 => "Minggu",
				);
		//array bulan
		$arrBulan = array(
					1 => "Januari",
					2 => "Februari",
					3 => "Maret",
					4 => "April",
					5 => "Mei",
					6 => "Juni",
					7 => "Juli",
					8 => "Agustus",
					9 => "September",
					10 => "Oktober",
					11 => "November",
					12 => "Desember",
				);

		if($full){
			//explode $tgl
			$split = explode("-", $tgl);
			$getTahun = $split[0]; //get tgl
			$getBulan = $split[1]; //get bulan
			$getTgl = explode(" ", $split[2]); //explode tahun, karna menyatu dgn jam
			$getJam = $getTgl[1]; //get jam
			$getTgl = $getTgl[0]; //get tahun

			$tgl_indo = $getTgl." ".$arrBulan[(int)$getBulan]." ".$getTahun; //format dd bulan tahun
			$num = date('N', strtotime($tgl)); //get tgl untuk disesuaikan dgn hari
			$cetak_tgl = $arrHari[$num].", ".$tgl_indo." Jam ".$getJam;
		}
		else{
			//explode $tgl
			$split = explode("-", $tgl);
			$getTgl = $split[2]; //get tgl
			$getBulan = $split[1]; //get bulan
			$getTahun = $split[0]; //get tahun

			$tgl_indo = $getTgl." ".$arrBulan[(int)$getBulan]." ".$getTahun; //format dd bulan tahun
			$num = date('N', strtotime($tgl)); //get tgl untuk disesuaikan dgn hari
			$cetak_tgl = $arrHari[$num].", ".$tgl_indo;
		}

		return $cetak_tgl; 
	}


	//fungsi unset dan destroy semua session
	function reset_session(){
		session_unset(); //unset semua session
		session_destroy(); //hapus semua session
	}

	//fungsi idle - logout otomatis
	function idle(){
        $_SESSION['sess_lockscreen'] = true;
        //unset beberapa session
        unset($_SESSION['sess_login']);
        unset($_SESSION['sess_auth']);
        unset($_SESSION['sess_kodeAkses']);
        unset($_SESSION['sess_id_kodeAkses']);
        unset($_SESSION['sess_lastActivity']);

        //arahkan ke lockscreen
        header("Location: ".base_url."lockscreen.php");
        die(); 
	}
	
	//fungsi mendapatkan ip
    function get_real_ip() 
    {
        $clientip = isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] ? $_SERVER['HTTP_CLIENT_IP'] : false;
        $xforwarderfor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : false;
        $xforwarded = isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'] ? $_SERVER['HTTP_X_FORWARDED'] : false;
        $forwardedfor = isset( $_SERVER['HTTP_FORWARDED_FOR'] ) && $_SERVER['HTTP_FORWARDED_FOR'] ? $_SERVER['HTTP_FORWARDED_FOR'] : false;
        $forwarded = isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'] ? $_SERVER['HTTP_FORWARDED'] : false;
        $remoteadd = isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : false;
    
        // Function to get the client ip address
        if($clientip !== false){
            $ipaddress = $clientip;
        }
        elseif($xforwarderfor !== false){
            $ipaddress = $xforwarderfor;
        }
        elseif($xforwarded !== false){
            $ipaddress = $xforwarded;
        }
        elseif($forwardedfor !== false){
            $ipaddress = $forwardedfor;
        }
        elseif($forwarded !== false){
            $ipaddress = $forwarded;
        }
        elseif($remoteadd !== false){
            $ipaddress = $remoteadd;
        }
        else{
            $ipaddress = false; # unknown
        }
        
        return $ipaddress;
    }