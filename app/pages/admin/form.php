<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	$id_admin = isset($_GET['id_admin']) ? validInputan($_GET['id_admin'],false,true) : false;
	$pesanError = isset($_SESSION['pesanError']) ? $_SESSION['pesanError'] : false;
	$set_value = isset($_SESSION['set_value']) ? $_SESSION['set_value'] : false;

	//unset session
	unset($_SESSION['pesanError']);
	unset($_SESSION['set_value']);

	//inisialisasi pesan error dan value field
	$usernameError = $passwordError = $konfPassError = "";
	$namaError = $emailError = $levelError  = "";
	$username = $password = $konfPass = "";
	$nama = $email = $level = "";

	$btn = "Tambah";

	if($pesanError){
		$usernameError = $pesanError['usernameError'];
		$passwordError = $pesanError['passwordError'];
		$konfPassError = $pesanError['konfPassError'];
		$namaError = $pesanError['namaError'];
		$emailError = $pesanError['emailError'];
		$levelError = $pesanError['levelError'];
	}

	if($set_value){
		$username = $set_value['username'];
		$password = $set_value['password'];
		$konfPass = $set_value['konfPass'];
		$nama = $set_value['nama'];
		$email = $set_value['email'];
		$level = $set_value['level'];
	}

?>

	<!-- Content Header -->
	<section class="content-header">
		<h1>Admin</h1>
		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li><a href="<?= base_url; ?>"><i class="fa fa-dashboard"></i> Beranda</a></li>
			<li><a href="<?= base_url."index.php?m=admin&p=form"; ?>">Admin</a></li>
			<li class="active">Form Tambah Admin</li>
		</ol>
	</section>

	<!-- Isi Content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<!-- panel box -->
				<div class="box box-primary">
					<!-- judul panel box -->
					<div class="box-header with-border">
						<h3 class="box-title">Form Admin</h3>
					</div>
					<!-- isi panel box -->
					<div class="box-body">
						<form method="POST" action="<?= base_url."pages/admin/action.php?id_admin=$id_admin"; ?>" enctype="multipart/form-data" id="formAdmin" role="form">

							<div class="box-body">
								<!-- username - wajib -->
								<div class="form-group <?php if(!empty($usernameError)) echo 'has-error' ?>">
									<label for="username">Username*</label>
									<input class="form-control" type="text" name="username" id="username" placeholder="Masukkan Username" value="<?= $username ?>">
									<span class="help-block"><?= $usernameError ?></span>
								</div>

								<div class="form-group <?php if(!empty($passwordError)) echo 'has-error' ?>">
									<div class="row">
										<!-- password - wajib -->
										<div class="col-sm-6 ">
											<label for="password">Password*</label>
											<input class="form-control" type="password" name="password" id="password" placeholder="Masukkan Password" value="<?= $password ?>">
											<span class="help-block"><?= $passwordError ?></span>
										</div>
										<!-- konf password - wajib -->
										<div class="col-sm-6 ">
											<label for="konfPass">Konfirmasi Password</label>
											<input class="form-control" type="password" name="konfPass" id="konfPass" placeholder="Masukkan Konfirmasi Password" value="<?= $konfPass ?>">
											<span class="help-block"><?= $konfPassError ?></span>
										</div>
									</div>
								</div>
								<!-- nama - opsional -->
								<div class="form-group <?php if(!empty($namaError)) echo 'has-error' ?>">
									<label for="nama">Nama</label>
									<textarea class="form-control" name="nama" id="nama" placeholder="Masukkan Nama Admin"><?= $nama ?></textarea>
									<span class="help-block"><?= $namaError ?></span>
								</div>

								<!-- email - opsional -->
								<div class="form-group <?php if(!empty($emailError)) echo 'has-error' ?>">
									<label for="email">Email</label>
									<input class="form-control" type="text" name="email" id="email" placeholder="Masukkan Email Admin" value="<?= $email ?>">
									<span class="help-block"><?= $emailError ?></span>
								</div>

								<!-- level - wajib -->
								<div class="form-group <?php if(!empty($levelError)) echo 'has-error' ?>">
									<label for="level">Level Admin</label>
									<select class="form-control" name="level" id="level">
										<option value="">-- Pilih Level Admin --</option>
										<option value="ADMIN">Admin</option>
										<option value="SUPERADMIN">Superadmin</option>
									</select>
									<span class="help-block"><?= $levelError ?></span>
								</div>
								
							</div>
							<div class="box-footer">
								<span class="help-block">* Wajib Diisi</span>
								<!-- tombol submit -->
								<div class="form-group text-right">
									<div class="btn-group">
										<input class="btn btn-success btn-flat margin" type="submit" name="adminSubmit" id="adminSubmit" value="<?= $btn ?>">
										<a href="<?= base_url."index.php?m=admin&p=list"; ?>" class="btn btn-default btn-flat margin">Cancel</a>
									</div>
								</div>
							</div>	
						</form>		
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- JavaScript -->

	<!-- end JavaScript -->