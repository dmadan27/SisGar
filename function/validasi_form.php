<?php
	/*validasi hanya huruf
		-- $namaField --> string yang ingin ditampilkan sebagai pesan
		-- $text --> nilai dari $_POST
		-- $min --> min karakter
		-- $max --> max karakter
		-- $required --> wajib diisi atau tidak (true - false)
	*/
	function validTextHuruf($namaField,$text,$min,$max,$required){
		$cekValid['cek'] = true;
		$cekValid['error'] = "";
		$cekValid['required'] = $required;
		$cekValid['nilai'] = trim($text);
		$patternHuruf = "/^[a-zA-Z ]*$/";

		//cek required
		if($cekValid['required']){ //jika wajib diisi
			if(empty($cekValid['nilai'])){ //jika kosong
				$cekValid['error'] = $namaField." Harus Diisi";
				$cekValid['cek'] = false;
			}
			else{ //jika ada isinya
				//cek tipe datanya
				if(!preg_match($patternHuruf, $cekValid['nilai'])){ //jika tidak sesuai
					$cekValid['error'] = $namaField." Harus Berupa Huruf";
					$cekValid['cek'] = false;
				}
				else{ //jika sesuai
					//cek panjang karakter
					if(strlen($cekValid['nilai']) >= $min && strlen($cekValid['nilai']) <= $max){ //jika sesuai
						$cekValid['cek'] = true;
						$cekValid['error'] = "";
					}
					else{
						$cekValid['error'] = "Panjang karakter min $min karakter dan maks $max karakter";
						$cekValid['cek'] = false;
					}
				}
			}
		}
		else{ //jika opsional
			//cek jenis inputan dan maksimal karakter saja
			if(!preg_match($patternHuruf, $cekValid['nilai'])){ //jika tidak sesuai
				$cekValid['error'] = $namaField." Harus Berupa Huruf";
				$cekValid['cek'] = false;
			}
			else{ //jika sesuai
				//cek maksimal karakter inputan
				if(strlen($cekValid['nilai']) <= $max){ //jika sesuai
					$cekValid['cek'] = true;
					$cekValid['error'] = "";
				}
				else{
					$cekValid['error'] = "Panjang karakter maks $max karakter";
					$cekValid['cek'] = false;
				}
			}
		}

		return $cekValid;
	}

	/*validasi hanya angka
		-- $namaField --> string yang ingin ditampilkan sebagai pesan
		-- $text --> nilai dari $_POST
		-- $min --> min karakter
		-- $max --> max karakter
		-- $required --> wajib diisi atau tidak (true - false)
	*/
	function validTextAngka($namaField,$text,$min,$max,$required){
		$cekValid['cek'] = true;
		$cekValid['error'] = "";
		$cekValid['required'] = $required;
		$cekValid['nilai'] = trim($text);
		$patternAngka = "/^[0-9]*$/";

		if($cekValid['required']){ //jika wajib diisi
			if(empty($cekValid['nilai'])){ //jika kosong
				$cekValid['error'] = $namaField." Harus Diisi";
				$cekValid['cek'] = false;
			}
			else{ //jika ada isinya
				//cek isi data hanya angka
				if(!preg_match($patternAngka,$cekValid['nilai'])){ //jika selain angka
					$cekValid['error'] = $namaField." Harus Berupa Angka";
					$cekValid['cek'] = false;
				}
				else{
					//cek min-max panjang karakter
					if(strlen($cekValid['nilai']) >= $min && strlen($cekValid['nilai']) <= $max){ //jika sesuai
						$cekValid['cek'] = true;
						$cekValid['error'] = "";
					}
					else{ //jika sesuai
						$cekValid['error'] = "Panjang karakter min $min karakter dan maks $max karakter";
						$cekValid['cek'] = false;
					}
				}
			}
		}
		else{ //jika opsional
			//cek jenis inputan dan maksimal karakter saja
			if(!preg_match($patternAngka, $cekValid['nilai'])){ //jika tidak sesuai
				$cekValid['error'] = $namaField." Harus Berupa Angka";
				$cekValid['cek'] = false;
			}
			else{ //jika sesuai
				//cek maksimal karakter inputan
				if(strlen($cekValid['nilai']) <= $max){ //jika sesuai
					$cekValid['cek'] = true;
					$cekValid['error'] = "";
				}
				else{
					$cekValid['error'] = "Panjang Karakter Maks $max Karakter";
					$cekValid['cek'] = false;
				}
			}
		}

		return $cekValid;
	}

	/*validasi field (textarea, select, radio) -- tidak mengecek pattern
		-- $namaField --> string yang ingin ditampilkan sebagai pesan
		-- $field --> nilai dari $_POST
		-- $jenisField --> jenis field apakah textarea, text, select, radio
		-- $min --> min karakter
		-- $max --> max karakter
		-- $required --> wajib diisi atau tidak (true - false)
	*/
	function validField($namaField,$field,$jenisField,$min,$max,$required){
		$cekValid['cek'] = true;
		$cekValid['error'] = "";
		$cekValid['required'] = $required;
		$cekValid['nilai'] = trim($field);

		//cek dahulu jenis field
		if(strtolower($jenisField) == "text" || strtolower($jenisField) == "textarea"){ //jika text - textarea
			if($cekValid['required']){ //jika wajib diisi
				//cek tidak boleh kosong, minimal dan maksimal
				if(empty($cekValid['nilai'])){ //jika kosong
					$cekValid['error'] = $namaField." Harus Diisi";
					$cekValid['cek'] = false;
				}
				else{ //jika diisi
					//cek min dan maks
					if(strlen($cekValid['nilai']) >= $min && strlen($cekValid['nilai']) <= $max){ //jika sesuai
						$cekValid['cek'] = true;
						$cekValid['error'] = "";
					}
					else{ //jika sesuai
						$cekValid['error'] = "Panjang karakter min $min karakter dan maks $max karakter";
						$cekValid['cek'] = false;
					}
				}
			}
			else{ //jika opsional
				//cek maksimal karakter saja
				if(strlen($cekValid['nilai']) <= $max){ //jika sesuai
					$cekValid['cek'] = true;
					$cekValid['error'] = "";
				}
				else{
					$cekValid['error'] = "Panjang Karakter Maks $max Karakter";
					$cekValid['cek'] = false;
				}
			}
		}
		elseif(strtolower($jenisField) == "select"){ //jika select
			if($cekValid['required']){ //jika wajib diisi
				if($cekValid['nilai'] === '0'){
					$cekValid['error'] = "";
					$cekValid['cek'] = true;
				}
				elseif(empty($cekValid['nilai'])){ //jika kosong
					$cekValid['error'] = $namaField." Harus Diisi";
					$cekValid['cek'] = false;
				}
				else{
					$cekValid['error'] = "";
					$cekValid['cek'] = true;
				}
			}
			else{ //jika opsional
				$cekValid['error'] = "";
				$cekValid['cek'] = true;
			}
		}
		elseif(strtolower($jenisField) == "radio"){ //jika radio
			if($cekValid['required']){ //jika wajib diisi

			}
			else{ //jika opsional

			}
		}
		elseif(strtolower($jenisField) == "checkbox"){ //jika checkbox
			if($cekValid['required']){ //jika wajib diisi

			}
			else{ //jika opsional

			}
		}

		return $cekValid;
	}

	/*validasi upload foto/gambar
		-- $config --> array $_FILES yg sudah di inisiaalisasi
		-- $ukuranFile --> ukuran maks file yg akan di upload, satuan kb
		-- $required --> wajib diisi atau tidak (true - false)
	*/
	function validUploadGambar($config,$ukuranFile,$required){
		$cekValid['cek'] = true;
		$cekValid['error'] = "";
		$cekValid['required'] = $required;
		//array pesan error
		$arrUploadError = array(
	        0 => "File Sukses Di Upload",
	        1 => "Upload Gagal, Ukuran File Melebihi Batas Maksimal (2 MB)",
	        2 => "Upload Gagal, Ukuran File Melebihi Batas Maksimal",
	        3 => "Upload Gagal, File hanya ter-upload sebagian",
	        4 => "Upload Gagal, Tidak Ada File Yang ter-upload",
	        6 => "Upload Gagal, Server Error",
	        7 => "Upload Gagal, Server Error",
	        8 => "Upload Gagal, Server Error",
	    );
	    //array tipe file
		$arrTipeFile = array("image/jpeg", "image/gif","image/png");
		$errorUpload = $config['error'];
		$pesanError = $arrUploadError[$errorUpload];

		if($cekValid['required']){ //jika wajib diisi
			//cek harus diisi, ukuran, tipe file
			if(empty($config['name'])){ //jika kosong
				$cekValid['error'] = "Tidak Ada File Yang Diupload";
				$cekValid['cek'] = false;
			}
			else{ //jika diisi
				//cek ukuran file
				if($config['size'] > $ukuranFile){ //jika melebihi ukuran yg ditentukan
					$cekValid['error'] = "Ukuran File Melebihi Batas Maksimal ($ukuranFile)";
					$cekValid['cek'] = false;
				}
				else{ //jika ukuran aman
					if($errorUpload == 0){ //jika file tidak ada error
						//cek ekstensi file yang diijinkan
						if(!in_array($config['type'], $arrTipeFile)){ //jika tidak sesuai
							$cekValid['error'] = "Mohon Upload File Gambar (.jpg, .png, .gif)";
							$cekValid['cek'] = false;
						}
						else{ //jika sesuai
							//cek lagi apakah memang benar gambar
							$mime = mime_content_type($config['tmp_name']);
							if(!in_array($mime, $arrTipeFile)){ //jika bukan gambar
								$cekValid['error'] = "Mohon Upload File Gambar (.jpg, .png, .gif)";
								$cekValid['cek'] = false;
							}
							else{ //jika gambar asli
								$cekValid['error'] = "";
								$cekValid['cek'] = true;
							}
						}
					}
					else{ //jika file bermasalah
						$cekValid['error'] = $pesanError;
						$cekValid['cek'] = false;
					}
				}
			}
		}
		else{ //jika opsional
			//cek ukuran dan tipe file
			if(empty($config['name'])){ //jika kosong
				$cekValid['error'] = "";
				$cekValid['cek'] = true;
			}
			else{
				//cek ukuran file
				if($config['size'] > $ukuranFile){ //jika melebihi ukuran yg ditentukan
					$cekValid['error'] = "Ukuran File Melebihi Batas Maksimal ($ukuranFile)";
					$cekValid['cek'] = false;
				}
				else{ //jika ukuran aman
					if($errorUpload == 0){ //jika file tidak ada error
						//cek ekstensi file yang diijinkan
						if(!in_array($config['type'], $arrTipeFile)){ //jika tidak sesuai
							$cekValid['error'] = "Mohon Upload File Gambar (.jpg, .png, .gif)";
							$cekValid['cek'] = false;
						}
						else{ //jika sesuai
							//cek lagi apakah memang benar gambar
							$mime = mime_content_type($config['tmp_name']);
							if(!in_array($mime, $arrTipeFile)){ //jika bukan gambar
								$cekValid['error'] = "Mohon Upload File Gambar (.jpg, .png, .gif)";
								$cekValid['cek'] = false;
							}
							else{ //jika gambar asli
								$cekValid['error'] = "";
								$cekValid['cek'] = true;
							}
						}
					}
					else{ //jika file bermasalah
						$cekValid['error'] = $pesanError;
						$cekValid['cek'] = false;
					}
				}
			}
		}

		return $cekValid;
	}

	/*fungsi ganti nama file - dibutuhkan untuk upload foto
		-- $fileUpload --> $_FILES['name']
		-- $stringBaru --> Nama file baru yg diinginkan
	*/
	function gantiNamaFile($fileUpload,$stringBaru){
		//hilangkan spasi ganda didpn ataupun belakang
		$fileUpload = trim($fileUpload);
		
		//cari info file
		$fileInfo = pathinfo($fileUpload);
		$extensi = $fileInfo["extension"];
		$namaFileLama = $fileInfo["filename"];
		
		$namaFileBaru = str_replace($namaFileLama,$stringBaru,$fileUpload);

		return $namaFileBaru;
	}

	//validasi tgl
	function validTgl($namaField,$tgl,$required){
		//format tgl yyyy-mm-dd
		$cekValid['cek'] = true;
		$cekValid['error'] = "";
		$cekValid['required'] = $required;
		$cekValid['nilai'] = trim($tgl);
		$formatTgl = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";

		if($cekValid['required']){ //jika wajib
			//cek kosong/tidak
			if(empty($cekValid['nilai'])){ //jika kosong
				$cekValid['error'] = $namaField." Harus Diisi";
				$cekValid['cek'] = false;	
			}
			else{ //jika diisi
				//cek format tgl
				if(!preg_match($formatTgl, $cekValid['nilai'])){ //jika tidak sesuai
					$cekValid['cek'] = false;
					$cekValid['error'] = "Format tanggal tidak sesuai (yyyy-mm-dd)";
				}
				else{
					//ubah string ke array
					$arrTgl = explode("-", $cekValid['nilai']);

					//buat variabel dd-mm-yyyy
					$dd = $arrTgl[2]; //tgl
					$mm = $arrTgl[1];; //bulan
					$yyyy = $arrTgl[0];; //tahun

					//cek dd-mm-yyyy sesuai dengan kalender atau tidak
					$cekValidDate = checkdate($mm, $dd, $yyyy);
					if($cekValidDate){ //jika sesuai
						$cekValid['cek'] = true;
						$cekValid['error'] = "";
					}
					else{
						$cekValid['cek'] = false;
						$cekValid['error'] = "Tanggal salah tidak sesuai kalender";
					}
				}
			}
		}
		else{ //jika opsional
			//cek format tgl
			if(!preg_match($formatTgl, $cekValid['nilai'])){ //jika tidak sesuai
				$cekValid['cek'] = false;
				$cekValid['error'] = "Format tanggal tidak sesuai (yyyy-mm-dd)";
			}
			else{
				//ubah string ke array
				$arrTgl = explode("-", $cekValid['nilai']);

				//buat variabel dd-mm-yyyy
				$dd = $arrTgl[2]; //tgl
				$mm = $arrTgl[1];; //bulan
				$yyyy = $arrTgl[0];; //tahun

				//cek dd-mm-yyyy sesuai dengan kalender atau tidak
				$cekValidDate = checkdate($mm, $dd, $yyyy);
				if($cekValidDate){ //jika sesuai
					$cekValid['cek'] = true;
					$cekValid['error'] = "";
				}
				else{
					$cekValid['cek'] = false;
					$cekValid['error'] = "Tanggal salah tidak sesuai kalender";
				}
			}
		}

		return $cekValid;
	}

	/*validasi inputan - untuk menghilangkan injeksi
		-- $data --> data/inputan yang ingin di amankan
		-- $cekArray --> jenis data apakah array atau bukan - true/false
		-- $cekPassEmail --> jenis data email/pass - true/false
	*/
	function validInputan($data,$cekArray,$cekPassEmail){
		if(!$cekArray){ //jika false maka bukan array
			$data = trim($data);
			//hilangkan tag-tag dan jgn render jika mengandung tag-tag
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			if(!$cekPassEmail) //jika bukan email/pass maka uppercase
				$data = strtoupper($data);
		}
		else{
			$jmlhArr = count($data);
			for($i=0; $i<$jmlhArr; $i++){
				$data[$i] = trim($data[$i]);
				$data[$i] = stripslashes($data[$i]);
				$data[$i] = htmlspecialchars(strtoupper($data[$i]));
			}
		}

		return $data;
	}

