<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	// var_dump($_SESSION['pesanError']);
	//$notif = isset($_GET['notif']) ? $_GET['notif'] : false;

	// if($notif){
	// 	$pesan = "Halaman Tidak Tersedia";
	// 	//tampilkan pesan
	// 	echo '<div class="alert alert-danger alert-dismissable"><a href="#"" class="close" data-dismiss="alert" aria-label="close">×</a><h4><strong>Pesan Error!</strong></h4>'.$pesan.'</div>';
	// }

	//query get admin
	$query = "SELECT count(*) admin FROM admin";
	$hasilQuery = mysqli_query($koneksi, $query);
	$dataAdmin = mysqli_fetch_assoc($hasilQuery);

	//query get parkir
	$statusParkir = "1";
	$query = "SELECT count(*) jumlah FROM m_parkir WHERE status = ?";
	$stmt = mysqli_prepare($koneksi, $query);
	mysqli_stmt_bind_param($stmt, "s", $statusParkir);
	$execute = mysqli_stmt_execute($stmt);
	$hasilQuery = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
	$dataParkir = $hasilQuery['jumlah'];

	//query get total penyewa
	$statusSewa = "1";
	$query = "SELECT count(*) jumlah FROM t_sewa WHERE status = ?";
	$stmt = mysqli_prepare($koneksi, $query);
	mysqli_stmt_bind_param($stmt, "s", $statusSewa);
	$execute = mysqli_stmt_execute($stmt);
	$hasilQuery = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
	$dataSewa = $hasilQuery['jumlah'];

	//query get saldo
	$query = "SELECT saldo FROM m_saldo";
	$hasilQuery = mysqli_query($koneksi, $query);
	$dataSaldo = mysqli_fetch_assoc($hasilQuery);

	mysqli_stmt_free_result($stmt);
	mysqli_stmt_close($stmt);
	mysqli_close($koneksi);
?>

	<!-- Content Header -->
	<section class="content-header">
		<h1>Beranda</h1>
		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li><a href="<?= base_url; ?>"><i class="fa fa-dashboard"></i> Beranda</a></li>
			<li class="active">Dashbord Admin</li>
		</ol>
	</section>

	<!-- Isi Content -->
	<section class="content">
		<!-- Box info -->
		<div class="row">
			<!-- Total Admin -->
			<div class="col-md-6">
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3><?= $dataAdmin['admin'] ?></h3>
						<p>Jumlah Admin</p>
					</div>
					<div class="icon">
						<i class="ion ion-ios-contact-outline"></i>
					</div>
					<?php
					?>
					<a href="<?php if(strtolower($sess_lvl)=='superadmin') echo base_url."index.php?m=admin&p=list"; else echo '#'; ?>" class="small-box-footer">Info Lebih Lanjut <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- Tempat Parkir Tersedia -->
			<div class="col-md-6">
				<div class="small-box bg-red">
					<div class="inner">
						<h3><?= $dataParkir ?></h3>
						<p>Jumlah Tempat Parkir Tersedia</p>
					</div>
					<div class="icon">
						<i class="ion ion-model-s"></i>
					</div>
					<a href="<?= base_url."index.php?m=parkir&p=list" ?>" class="small-box-footer">Info Lebih Lanjut <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- Total yang nyewa -->
			<div class="col-md-6">
				<div class="small-box bg-aqua">
					<div class="inner">
						<h3><?= $dataSewa ?></h3>
						<p>Jumlah Penyewa Garasi Saat ini</p>
					</div>
					<div class="icon">
						<i class="ion ion-person-stalker"></i>
					</div>
					<a href="<?= base_url."index.php?m=sewa&p=list" ?>" class="small-box-footer">Info Lebih Lanjut <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- Saldo Terakhir -->
			<div class="col-md-6">
				<div class="small-box bg-green">
					<div class="inner">
						<h3><?= rupiah($dataSaldo['saldo']) ?></h3>
						<p>Jumlah Saldo Terakhir</p>
					</div>
					<div class="icon">
						<i class="ion ion-cash"></i>
					</div>
					<a href="<?= base_url."index.php?m=keuangan&p=list" ?>" class="small-box-footer">Info Lebih Lanjut <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
		</div>

	</section>
