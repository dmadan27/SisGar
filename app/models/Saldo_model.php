<?php
	function get_ketSaldo($koneksi){
		$query = "SELECT * FROM v_saldo";

		$statement = $koneksi->prepare($query);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		tutup_koneksi($koneksi);

		return $result;
	}