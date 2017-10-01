<?php
	function get_all_parkir($koneksi, $config_db){
		$query = get_dataTable($config_db);
		$statement = $koneksi->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		tutup_koneksi($koneksi);

		return $result;
	}

	function insertParkir($koneksi, $data){
		$status = "1";
		$query = "INSERT INTO m_parkir (no_parkir, status) VALUES (:no_parkir, :status)";

		$statement = $koneksi->prepare($query);
		$statement->bindParam(':no_parkir', $data['no_parkir']);
		$statement->bindParam(':status', $status);

		$result = $statement->execute();
		tutup_koneksi($koneksi);

		return $result;
	}