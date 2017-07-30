<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	$id_harga = isset($_GET['id_harga']) ? validInputan($_GET['id_harga'],false,true) : false;
	$pesanError = isset($_SESSION['pesanError']) ? $_SESSION['pesanError'] : false;
	$set_value = isset($_SESSION['set_value']) ? $_SESSION['set_value'] : false;

	//unset session
	unset($_SESSION['pesanError']);
	unset($_SESSION['set_value']);

	//inisialisasi pesan error dan value field
	$jenis_sewaError = $harga_sewaError = "";
	$jenis_sewa = $harga_sewa = "";

	$btn = "Tambah";

	if($pesanError){ //jika ada pesan error
		$jenis_sewaError = $pesanError['jenis_sewaError'];
		$harga_sewaError = $pesanError['harga_sewaError'];
	}

	if($set_value){ //jika repopulate
		$jenis_sewa = $set_value['jenis_sewa'];
		$harga_sewa = $set_value['harga_sewa'];
	}

	if($id_harga){ //jika form ubah data
		if(!$pesanError){
			//1. prepare statement
			$query = "SELECT * FROM m_harga WHERE id = ?";
			$stmt = mysqli_prepare($koneksi, $query);

			//2. bind
			mysqli_stmt_bind_param($stmt, "i", $id_harga);

			//3. execute
			$execute = mysqli_stmt_execute($stmt);

			//4. cek execute
			if($execute){
				$hasilQuery = mysqli_stmt_get_result($stmt);
				$data = mysqli_fetch_assoc($hasilQuery);
				$jenis_sewa = $data['jenis_sewa'];
				$harga_sewa = $data['harga_sewa'];
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
		<h1>Harga Sewa</h1>
		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li><a href="<?= base_url; ?>"><i class="fa fa-dashboard"></i> Beranda</a></li>
			<li><a href="<?= base_url."index.php?m=parkir&p=list"; ?>">Harga</a></li>
			<li class="active">Data Harga Sewa</li>
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
						<h3 class="box-title">Form Harga Sewa</h3>
					</div>
					<!-- isi panel box -->
					<div class="box-body">
						<form method="POST" action="<?= base_url."pages/harga/action.php?id_harga=$id_harga"; ?>" enctype="multipart/form-data" id="formHarga" role="form">

							<div class="box-body">
								<!-- jenis sewa - wajib -->
								<div class="form-group <?php if(!empty($jenis_sewaError)) echo 'has-error' ?>">
									<label for="no_parkir">Jenis Sewa*</label>
									<select class="form-control" id="jenis_sewa" name="jenis_sewa">
										<option value="">-- Pilih Jenis Sewa --</option>
										<option value="B" <?php if(strtolower($jenis_sewa)=="b") echo 'selected'; ?>>Bulanan</option>
										<option value="T" <?php if(strtolower($jenis_sewa)=="t") echo 'selected'; ?>>Tahunan</option>
									</select>
									<span class="help-block"><?= $jenis_sewaError; ?></span>
								</div>

								<!-- harga sewa - wajib -->
								<div class="form-group <?php if(!empty($harga_sewaError)) echo 'has-error' ?>">
									<label for="status">Harga Sewa*</label>
									<input class="form-control" type="text" name="harga_sewa" id="harga_sewa" placeholder="Masukkan Harga Sewa" value="<?= $harga_sewa ?>">
									<span class="help-block"><?= $harga_sewaError ?></span>
								</div>
							</div>
							<div class="box-footer">
								<span class="help-block">* Wajib Diisi</span>
								<!-- tombol submit -->
								<div class="form-group text-right">
									<div class="btn-group">
										<input class="btn btn-success btn-flat margin" type="submit" name="hargaSubmit" id="hargaSubmit" value="<?= $btn ?>">
										<a href="<?= base_url."index.php?m=harga&p=list"; ?>" class="btn btn-default btn-flat margin">Cancel</a>
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