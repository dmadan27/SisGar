<?php
	// prototype fungsi set validasi
	function set_validasi($configData){
		$cek = true;

		foreach($configData as $config){
			foreach ($config as $key => $value) {
				// get nilai tiap array
				if($key == "field") $field = $value;
				if($key == "label") $label = $value;
				if($key == "error") $error = $value;
				if($key == "value") $setValue = $value;
				if($key == "rule"){
					// explode rule
					// dapatkan masing2 rule
					$rule = explode("|", $value);
					$rule = array_map('trim', $rule);

					$jenis = $rule[0];
					$min = $rule[1];
					$max = $rule[2];
					$required = strtolower($rule[3]) == "required" ? true : false;
					
					$pesanError[$error] = "";
					$set_value[$setValue] = $field;
					
					// get fungsi validasi
					switch ($jenis) {
						case 'string':
							$valid = validString($label, $field, $min, $max, $required);
							if(!$valid['cek']){
								$cek = false;
								$pesanError[$error] = $valid['error'];
							}
							break;

						case 'huruf':
							$valid = validHuruf($label, $field, $min, $max, $required);
							if(!$valid['cek']){
								$cek = false;
								$pesanError[$error] = $valid['error'];
							}
							break;

						case 'angka':
							$valid = validAngka($label, $field, $min, $max, $required);
							if(!$valid['cek']){
								$cek = false;
								$pesanError[$error] = $valid['error'];
							}
							break;

						case 'email':
							$valid = validEmail($label, $field, $min, $max, $required);
							if(!$valid['cek']){
								$cek = false;
								$pesanError[$error] = $valid['error'];
							}							
							break;
						
						default:
							die();
							break;
					}

					$output = array(
						'cek' => $cek,
						'pesanError' => $pesanError,
						'set_value' => $set_value,
					);
				}
			}
		}

		return $output;
	}

	/*
		template fungsi validasi
		# format tempalte:
			=> cek require --> cek kosong --> cek pattern --> cek min-max
		# agar penulisan fungsi yg lainnnya lebih mudah
	*/
	function validTemplate($cekValid){
		$required = $cekValid['required'];
		$value = $cekValid['value'];
		$min = $cekValid['min'];
		$max = $cekValid['max'];
		$label = $cekValid['label'];
		$cek = $cekValid['cek'];
		$error = $cekValid['error'];
		$pattern = $cekValid['pattern'];
		$jenis = $cekValid['jenis'];

		// cek jenis
		switch (strtolower($jenis)) {
			case 'string':
			case 'huruf':
				// cek required
				if($required){ // jika wajib
					// cek kosong
					if(empty($value)){ // jika kosong
						$setError = $error['empty'];
						$cek = false;
					}
					else{
						// cek pattern
						if(!preg_match($pattern, $value)){ // jika tidak sesuai
							$setError = $error['pattern'];
							$cek = false;
						}
						else{
							// cek min-max karakter
							if(strlen($value) >= $min && strlen($value) <= $max){ // jika sesuai
								$setError = "";
								$cek = true;
							}
							else{
								$setError = $error['length'];
								$cek = false;
							}
						}
					}
				}
				else{ // jika opsional
					// jika diisi cek pattern --> cek max
					if(!empty($value)){ // jika diisi
						// cek pattern
						if(!preg_match($pattern, $value)){ // jika tidak sesuai
							$setError = $error['pattern'];
							$cek = false;
						}
						else{
							// cek min-max karakter
							if(strlen($value) <= $max){ // jika sesuai
								$setError = "";
								$cek = true;
							}
							else{
								$setError = $error['length'];
								$cek = false;
							}
						}
					}
					else{ // jika kosong
						$setError = "";
						$cek = true;
					}
				}
			break;
			case 'email':
				if($required){
					if(empty($value)){
						$setError = $error['empty'];
						$cek = false;
					}
					else{
						if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
							$setError = $error['pattern'];
							$cek = false;
						}
						else{
							if(strlen($value) >= $min && strlen($value) <= $max){ // jika sesuai
								$setError = "";
								$cek = true;
							}
							else{
								$setError = $error['length'];
								$cek = false;
							}
						}
					}
				}
				else{ // jika opsional
					// jika diisi cek pattern --> cek max
					if(!empty($value)){ // jika diisi
						// cek pattern
						if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
							$setError = $error['pattern'];
							$cek = false;
						}
						else{
							// cek min-max karakter
							if(strlen($value) <= $max){ // jika sesuai
								$setError = "";
								$cek = true;
							}
							else{
								$setError = $error['length'];
								$cek = false;
							}
						}
					}
					else{ // jika kosong
						$setError = "";
						$cek = true;
					}
				}
			break;

			case 'angka':
				// cek required
				if($required){ // jika wajib
					// cek kosong
					if(empty($value)){ // jika kosong
						$setError = $error['empty'];
						$cek = false;
					}
					else{
						// cek pattern
						if(!preg_match($pattern, $value)){ // jika tidak sesuai
							$setError = $error['pattern'];
							$cek = false;
						}
						else{
							// cek min-max karakter
							if($value >= $min && $value <= $max){ // jika sesuai
								$setError = "";
								$cek = true;
							}
							else{
								$setError = $error['length'];
								$cek = false;
							}
						}
					}
				}
				else{ // jika opsional
					// jika diisi cek pattern --> cek max
					if(!empty($value)){ // jika diisi
						// cek pattern
						if(!preg_match($pattern, $value)){ // jika tidak sesuai
							$setError = $error['pattern'];
							$cek = false;
						}
						else{
							// cek min-max karakter
							if($value <= $max){ // jika sesuai
								$setError = "";
								$cek = true;
							}
							else{
								$setError = $error['length'];
								$cek = false;
							}
						}
					}
					else{ // jika kosong
						$setError = "";
						$cek = true;
					}
				}
				break;

			default:
				die();
				break;
		}
			
		$output = array(
			'cek' => $cek,
			'error' => $setError,
		);
		
		return $output;
	}
	/*
		fungsi validasi untuk tipe data string/alphanumeric (hanya huruf, angka, dan spasi)
		# $label --> string yang ingin ditampilkan sebagai pesan
		# $value --> nilai dari $_POST
		# $min --> min karakter
		# $max --> max karakter
		# $required --> wajib diisi atau tidak (true - false)
	*/
	function validString($label, $value, $min, $max, $required){
		// inisialisasi
		$cekValid['cek'] = true;
		$cekValid['error'] = array(
			'empty' => $label." Harus Diisi",
			'pattern' => $label." Harus Berupa Huruf atau Angka",
			'length' => "Panjang $label Min. $min dan Maks. $max Karakter",
		);
		$cekValid['value'] = trim($value);
		$cekValid['label'] = trim($label);
		$cekValid['min'] = $min;
		$cekValid['max'] = $max;
		$cekValid['pattern'] = "/^[a-zA-Z0-9-_,.!?' \/]*$/";
		$cekValid['required'] = $required;
		$cekValid['jenis'] = "string";

		$output = validTemplate($cekValid);

		return $output;
	}

	/*
		fungsi validasi untuk tipe data angka saja
		# $label --> string yang ingin ditampilkan sebagai pesan
		# $value --> nilai dari $_POST
		# $min --> min karakter
		# $max --> max karakter
		# $required --> wajib diisi atau tidak (true - false)
	*/
	function validAngka($label, $value, $min, $max, $required){
		// inisialisasi
		$cekValid['cek'] = true;
		$cekValid['error'] = array(
			'empty' => $label." Harus Diisi",
			'pattern' => $label." Harus Berupa Angka",
			'length' => "Nilai $label Min. $min dan Maks. $max",
		);
		$cekValid['value'] = trim($value);
		$cekValid['label'] = trim($label);
		$cekValid['min'] = $min;
		$cekValid['max'] = $max;
		$cekValid['pattern'] = "/^[0-9.]*$/";
		$cekValid['required'] = $required;
		$cekValid['jenis'] = "angka";

		$output = validTemplate($cekValid);

		return $output;
	}

	/*
		fungsi validasi untuk tipe data huruf saja dan spasi
		# $label --> string yang ingin ditampilkan sebagai pesan
		# $value --> nilai dari $_POST
		# $min --> min karakter
		# $max --> max karakter
		# $required --> wajib diisi atau tidak (true - false)
	*/
	function validHuruf($label, $value, $min, $max, $required){
		// inisialisasi
		$cekValid['cek'] = true;
		$cekValid['error'] = array(
			'empty' => $label." Harus Diisi",
			'pattern' => $label." Harus Berupa Huruf",
			'length' => "Panjang $label Min. $min dan Maks. $max Karakter",
		);
		$cekValid['value'] = trim($value);
		$cekValid['label'] = trim($label);
		$cekValid['min'] = $min;
		$cekValid['max'] = $max;
		$cekValid['required'] = $required;
		$cekValid['jenis'] = "huruf";
		$cekValid['pattern'] = "/^[a-zA-Z\s]*$/";

		$output = validTemplate($cekValid);

		return $output;
	}

	/*
		fungsi validasi email
	*/
	function validEmail($label, $value, $min, $max, $required){
		$cekValid['cek'] = true;
		$cekValid['error'] = array(
			'empty' => $label." Harus Diisi",
			'pattern' => "Format ".$label." Tidak Sesuai",
			'length' => "Panjang $label Min. $min dan Maks. $max Karakter",
		);
		$cekValid['value'] = trim($value);
		$cekValid['label'] = trim($label);
		$cekValid['min'] = $min;
		$cekValid['max'] = $max;
		$cekValid['required'] = $required;
		$cekValid['jenis'] = "email";
		$cekValid['pattern'] = "";

		$output = validTemplate($cekValid);

		return $output;
	}

	/*
		fungsi validasi foto, mengecek yg di upload adalah benar2 foto
	*/
	function validFoto($configFoto){
		$errorFile = $configFoto['error'];
		$sizeFile = $configFoto['size'];
		$tmp_nameFile = $configFoto['tmp_name'];
		$max = $configFoto['max'];
		// $path = $configFoto['path'];

		// cek error value
		switch ($errorFile) {
			case UPLOAD_ERR_OK:
				$cekValid['error'] = "";
            	break;
	        case UPLOAD_ERR_NO_FILE:
	            $output = array(
					'cek' => false,
					'error' => "Upload Gagal, Tidak File Yang Terupload",
				);
				return $output;
				break;

	        case UPLOAD_ERR_INI_SIZE:
	        case UPLOAD_ERR_FORM_SIZE:
	            $output = array(
					'cek' => false,
					'error' => "Upload Gagal, Ukuran File Tidak Sesuai",
				);
				return $output;
				break;

	        default:
	            $output = array(
					'cek' => false,
					'error' => "Upload Gagal, Error Tidak Diketahui",
				);
				return $output;
				break;
		}

		// cek ukuran foto
		// 2*1048576 (2 mb)
		if($sizeFile > $max){
			/*
				lakukan resize ukuran foto (pengembangan)
			*/
			$output = array(
				'cek' => false,
				'error' => "Upload Gagal, Ukuran File Tidak Sesuai",
			);
			return $output;
		}

		// cek mime type file
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		// jika format file ngaco
		if(false === $ext = array_search(
			$finfo->file($tmp_nameFile), 
			array(
	            'jpg' => 'image/jpeg',
	            'png' => 'image/png',
	            'gif' => 'image/gif',
	        ), true))
		{
			$output = array(
				'cek' => false,
				'error' => "Upload Gagal, Format File Tidak Sesuai",
			);
			return $output;
		}

		// ganti nama file
		$namaFileBaru = sprintf('%s.%s', sha1_file($tmp_nameFile), $ext);

		$output = array(
			'cek' => true,
			'error' => "",
			'namaFile' => $namaFileBaru,
		);

		return $output;
	}

	/*
		fungsi validasi untuk password dan konfirm password
	*/
	function validPassword($label, $value, $confirm, $min, $max){
		$cek = true;
		$label = trim($label);
		$value = trim($value);
		$pattern = "/^[a-zA-Z0-9-_ ]*$/";
		$error = array(
			'empty' => $label." Harus Diisi",
			'pattern' => "Format ".$label." Tidak Sesuai atau Mengandung Karakter Ilegal",
			'length' => "Panjang $label Min. $min dan Maks. $max Karakter",
			'confirm' => $label." Tidak Sesuai Dengan Konfirm Password",
		);

		// if($required){ // jika wajib
			// cek kosong
			if(empty($value)){ // jika kosong
				$errorPassword = $errorConfirm = $error['empty'];
				$cek = false;
			}
			else{
				// cek pattern
				if(!preg_match($pattern, $value)){ // jika tidak sesuai
					$errorPassword = $errorConfirm = $error['pattern'];
					$cek = false;
				}
				else{
					// cek min-max karakter
					if(strlen($value) >= $min && strlen($value) <= $max){ // jika sesuai
						// cek konfirm password
						if($value !== $confirm){
							$cek = false;
							$errorPassword = $errorConfirm = $error['confirm'];
						}
						else{
							$errorPassword = $errorConfirm = "";
							$cek = true;
						}
					}
					else{
						$errorPassword = $errorConfirm = $error['length'];
						$cek = false;
					}
				}
			}
		// }
		// else{ // jika opsional
		// 	// jika diisi cek pattern --> cek max
		// 	if(!empty($value)){ // jika diisi
		// 		// cek pattern
		// 		if(!preg_match($pattern, $value)){ // jika tidak sesuai
		// 			$setError = $error['pattern'];
		// 			$cek = false;
		// 		}
		// 		else{
		// 			// cek min-max karakter
		// 			if(strlen($value) <= $max){ // jika sesuai
		// 				$setError = "";
		// 				$cek = true;
		// 			}
		// 			else{
		// 				$setError = $error['length'];
		// 				$cek = false;
		// 			}
		// 		}
		// 	}
		// 	else{ // jika kosong
		// 		$setError = "";
		// 		$cek = true;
		// 	}
		// }
		
		$output = array(
			'cek' => $cek,
			'value' => array(
				'password' => $value,
				'confirm' => $confirm,
			),
			'error' => array(
				'password' => $errorPassword,
				'confirm' => $errorConfirm,
			),
		);

		return $output;
	}

	/*
		validasi inputan - untuk menghilangkan injeksi
		# $data --> data/inputan yang ingin di amankan
		# $cekArray --> jenis data apakah array atau bukan - true/false
		# $cekPassEmail --> jenis data email/pass - true/false
	*/
	function validInputan($data, $cekArray, $cekPassEmail){
		if(!$cekArray){ // jika false maka bukan array
			$data = trim($data);
			// hilangkan tag-tag dan jgn render jika mengandung tag-tag
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			// jika bukan email/pass maka uppercase
			if(!$cekPassEmail) $data = strtoupper($data);
		}
		else{
			foreach($data as $valueData){
				$valueData = trim($valueData);
				$valueData = stripslashes($valueData);
				$valueData = htmlspecialchars(strtoupper($valueData));
			}
		}

		return $data;
	}
