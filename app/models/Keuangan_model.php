<?php
	function get_all_keuangan($koneksi, $config_db){
		$query = get_dataTable($config_db);
		$statement = $koneksi->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		tutup_koneksi($koneksi);

		return $result;
	}

	function insertKeuangan($koneksi, $data){
		$query = "INSERT INTO keuangan (tgl, jenis, ket, nominal, admin) VALUES (:tgl, :jenis, :ket, :nominal, :admin)";

		$statement = $koneksi->prepare($query);
		$statement->bindParam(':tgl', $data['tgl']);
		$statement->bindParam(':jenis', $data['jenis']);
		$statement->bindParam(':ket', $data['ket']);
		$statement->bindParam(':nominal', $data['nominal']);
		$statement->bindParam(':admin', $data['admin']);
		$result = $statement->execute();
		tutup_koneksi($koneksi);

		return $result;
	}