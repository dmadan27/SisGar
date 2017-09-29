<?php
	function get_all_harga($koneksi, $config_db){
		$query = get_dataTable($config_db);
		$statement = $koneksi->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		tutup_koneksi($koneksi);

		return $result;
	}

	function insertHarga($koneksi, $data){
		$query = "INSERT INTO m_harga (jenis, harga) VALUES (:jenis, :harga)";

		$statement = $koneksi->prepare($query);
		$statement->bindParam(':jenis', $data['jenis']);
		$statement->bindParam(':harga', $data['harga']);

		$result = $statement->execute();
		tutup_koneksi($koneksi);

		return $result;
	}