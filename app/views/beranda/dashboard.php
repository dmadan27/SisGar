<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
?>

<!-- header dan breadcrumb -->
<section class="content-header">
    <h1>Beranda</h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url ?>"><i class="fa fa-dashboard"></i> SiSGar</a></li>
        <li class="active">Beranda</li>
    </ol>
</section>

<!-- isi konten -->
<section class="content">
    <div class="row">
        <!-- total uang sewa -->
        <div class="col-md-6">
        	<div class="small-box bg-aqua box-sewa">
				<div class="inner">
					<h3></h3>
					<p>Total Uang Sewa</p>
				</div>
				<div class="icon">
					<i class="ion ion-person-stalker"></i>
				</div>
				<a href="#" class="small-box-footer">Info Lebih Lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
        </div>
        <!-- total pengeluaran -->
        <div class="col-md-6">
        	<div class="small-box bg-aqua box-pengeluaran">
				<div class="inner">
					<h3></h3>
					<p>Total Pengeluaran</p>
				</div>
				<div class="icon">
					<i class="ion ion-person-stalker"></i>
				</div>
				<a href="#" class="small-box-footer">Info Lebih Lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
        </div>
    </div>
    <div class="row">
    	<!-- saldo -->
        <div class="col-md-6">
        	<div class="small-box bg-aqua box-saldo">
				<div class="inner">
					<h3></h3>
					<p>Total Saldo</p>
				</div>
				<div class="icon">
					<i class="ion ion-person-stalker"></i>
				</div>
				<a href="#" class="small-box-footer">Info Lebih Lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
        </div>

        <!-- total admin -->
        <div class="col-md-6">
        	<div class="small-box bg-aqua box-admin">
				<div class="inner">
					<h3></h3>
					<p>Total Admin</p>
				</div>
				<div class="icon">
					<i class="ion ion-person-stalker"></i>
				</div>
				<a href="#" class="small-box-footer">Info Lebih Lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
        </div>
    </div>
    <div class="row">
    	<!-- total parkir tersedia -->
        <div class="col-md-6">
        	<div class="small-box bg-aqua box-parkir">
				<div class="inner">
					<h3></h3>
					<p>Total Parkir Tersedia</p>
				</div>
				<div class="icon">
					<i class="ion ion-person-stalker"></i>
				</div>
				<a href="#" class="small-box-footer">Info Lebih Lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
        </div>
        <!-- total member yang sudah bayar -->
        <div class="col-md-6">
        	<div class="small-box bg-aqua box-member">
				<div class="inner">
					<h3></h3>
					<p>Total Member Yang Sudah Bayar</p>
				</div>
				<div class="icon">
					<i class="ion ion-person-stalker"></i>
				</div>
				<a href="#" class="small-box-footer">Info Lebih Lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
        </div>
    </div>
</section>