<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	$id_parkir = isset($_GET['id_parkir']) ? validInputan($_GET['id_parkir'],false,true) : false;
	$pesanError = isset($_SESSION['pesanError']) ? $_SESSION['pesanError'] : false;
	$set_value = isset($_SESSION['set_value']) ? $_SESSION['set_value'] : false;

	//unset session
	unset($_SESSION['pesanError']);
	unset($_SESSION['set_value']);

	//inisialisasi pesan error dan value field
	$no_parkirError = $statusError = "";
	$no_parkir = $status = "";

	$btn = "Tambah";

	if($pesanError){ //jika ada pesan error
		$no_parkirError = $pesanError['no_parkirError'];
		$statusError = $pesanError['statusError'];
	}

	if($set_value){ //jika repopulate
		$no_parkir = $set_value['no_parkir'];
		$status = $set_value['status'];
	}

	if($id_parkir){ //jika form ubah data
		if(!$pesanError){
			//1. prepare statement
			$query = "SELECT * FROM m_parkir WHERE id = ?";
			$stmt = mysqli_prepare($koneksi, $query);

			//2. bind
			mysqli_stmt_bind_param($stmt, "i", $id_parkir);

			//3. execute
			$execute = mysqli_stmt_execute($stmt);

			//4. cek execute
			if($execute){
				$hasilQuery = mysqli_stmt_get_result($stmt);
				$data = mysqli_fetch_assoc($hasilQuery);
				$no_parkir = $data['no_parkir'];
				$status = $data['status'];
			}
			else{
				die("Error: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
			}

			mysqli_stmt_free_result($stmt);
			mysqli_stmt_close($stmt);
			mysqli_close($koneksi);
		}
		$btn = "Ubah";
	}
?>
	
	<!-- Content Header -->
	<section class="content-header">
		<h1>Parkir</h1>
		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li><a href="<?= base_url; ?>"><i class="fa fa-dashboard"></i> Beranda</a></li>
			<li><a href="<?= base_url."index.php?m=parkir&p=form"; ?>">Parkir</a></li>
			<li class="active">Form Tambah Parkir</li>
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
						<h3 class="box-title">Form Member</h3>
					</div>
					<!-- isi panel box -->
					<div class="box-body">
						<form method="POST" action="<?= base_url."pages/parkir/action.php?id_parkir=$id_parkir"; ?>" enctype="multipart/form-data" id="formParkir" role="form">

							<div class="box-body">
								<!-- no parkir - wajib -->
								<div class="form-group <?php if(!empty($no_parkirError)) echo 'has-error' ?>">
									<label for="no_parkir">No. Parkir*</label>
									<input class="form-control" type="text" name="no_parkir" id="no_parkir" placeholder="Masukkan No. Parkir" value="<?= $no_parkir ?>">
									<span class="help-block"><?= $no_parkirError ?></span>
								</div>

								<!-- status - wajib -->
								<div class="form-group <?php if(!empty($statusError)) echo 'has-error' ?>">
									<label for="status">Status Parkir*</label>
									<select class="form-control" id="status" name="status">
										<option value="">-- Pilih Status Parkir --</option>
										<option value="1" <?php if($status=="1") echo 'selected'; ?>>Tersedia</option>
										<option value="0" <?php if($status=="0") echo 'selected'; ?>>Tidak Tersedia</option>
									</select>
									<span class="help-block"><?= $statusError ?></span>
								</div>
							</div>
							<div class="box-footer">
								<span class="help-block">* Wajib Diisi</span>
								<!-- tombol submit -->
								<div class="form-group text-right">
									<div class="btn-group">
										<input class="btn btn-success btn-flat margin" type="submit" name="parkirSubmit" id="parkirSubmit" value="<?= $btn ?>">
										<a href="<?= base_url."index.php?m=parkir&p=list"; ?>" class="btn btn-default btn-flat margin">Cancel</a>
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