<?php
	function get_all_admin($koneksi, $config_db){
		$query = get_dataTable($config_db);
		$statement = $koneksi->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		tutup_koneksi($koneksi);

		return $result;
	}

	function get_login($koneksi, $username){
		$query = "SELECT * FROM m_admin WHERE BINARY username = :username";

		$statement = $koneksi->prepare($query);
		$statement->bindParam(':username', $username);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		tutup_koneksi($koneksi);

		return $result;
	}

	function insertAdmin($koneksi, $data){
		$status = "1";
		$query = "INSERT INTO m_admin (username, password, nama, email, level, foto, status) VALUES (:username, :password, :nama, :email, :level, :foto, :status)";

		$statement = $koneksi->prepare($query);
		$statement->bindParam(':username', $data['username']);
		$statement->bindParam(':password', $data['password']);
		$statement->bindParam(':nama', $data['nama']);
		$statement->bindParam(':email', $data['email']);
		// $statement->bindParam(':email', $data['hak_akses']);
		$statement->bindParam(':level', $data['level']);
		$statement->bindParam(':foto', $data['foto']);
		$statement->bindParam(':status', $status);
		$result = $statement->execute();
		tutup_koneksi($koneksi);

		return $result;
	}