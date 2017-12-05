<?php
	function get_all_sewa($koneksi, $config_db){
		$query = get_dataTable($config_db);
		$statement = $koneksi->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		tutup_koneksi($koneksi);

		return $result;
	}

	function get_memberAktif($koneksi){
		$query = "SELECT COUNT(*) member_aktif FROM sewa WHERE status = '1'";
		$statement = $koneksi->prepare($query);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		tutup_koneksi($koneksi);

		return $result;
	}