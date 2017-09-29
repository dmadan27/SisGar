<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	$id_keuangan = isset($_GET['id_keuangan']) ? validInputan($_GET['id_keuangan'],false,true) : false;
	$pesanError = isset($_SESSION['pesanError']) ? $_SESSION['pesanError'] : false;
	$set_value = isset($_SESSION['set_value']) ? $_SESSION['set_value'] : false;

	//unset session
	unset($_SESSION['pesanError']);
	unset($_SESSION['set_value']);

	//inisialisasi pesan error dan value field
	$tglError = $ket_uangError = $besaranError = $keteranganError = "";
	$tgl = $ket_uang = $besaran = $keterangan = "";

	$btn = "Tambah";

	if($pesanError){ //jika ada pesan error
		$tglError = $pesanError['tglError'];
		$ket_uangError = $pesanError['ket_uangError'];
		$besaranError = $pesanError['besaranError'];
		$keteranganError = $pesanError['keteranganError'];
	}

	if($set_value){ //jika repopulate
		$tgl = $set_value['tgl'];
		$ket_uang = $set_value['ket_uang'];
		$besaran = $set_value['besaran'];
		$keterangan = $set_value['keterangan'];
	}

	if($id_keuangan){ //jika form ubah data
		if(!$pesanError){
			//get data keuangan
			//1. prepare statement
			$query = "SELECT id, tgl, ket_uang, besaran, keterangan FROM t_keuangan WHERE id = ?";
			$stmt = mysqli_prepare($koneksi, $query);

			//2. bind
			mysqli_stmt_bind_param($stmt, "i", $id_keuangan);

			//3. execute
			$execute = mysqli_stmt_execute($stmt);

			//4. cek execute
			if($execute){
				$hasilQuery = mysqli_stmt_get_result($stmt);
				$data = mysqli_fetch_assoc($hasilQuery);
				$tgl = $data['tgl'];
				$ket_uang = $data['ket_uang'];
				$keterangan = $data['keterangan'];
				$besaran = $data['besaran'];
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

	<!-- css -->
		<!-- Datepicker -->
		<link rel="stylesheet" type="text/css" href="<?= base_url."assets/plugins/datepicker/bootstrap-datepicker3.min.css"; ?>"/>
	<!-- -->

	<!-- Content Header -->
	<section class="content-header">
		<h1>Keuangan</h1>
		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li><a href="<?= base_url; ?>"><i class="fa fa-dashboard"></i> Beranda</a></li>
			<li><a href="<?= base_url."index.php?m=keuangan&p=form"; ?>">Keuangan</a></li>
			<li class="active">Form Tambah Keuangan</li>
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
						<h3 class="box-title">Form Keuangan</h3>
					</div>
					<!-- isi panel box -->
					<div class="box-body">
						<form method="POST" action="<?= base_url."pages/keuangan/action.php?id_keuangan=$id_keuangan"; ?>" enctype="multipart/form-data" id="formKeuangan" role="form">
							<input type="hidden" name="userAdmin" value="<?= $sess_username ?>">
							<div class="box-body">
								<!-- tanggal -->
								<div class="form-group <?php if(!empty($tglError)) echo 'has-error' ?>">
									<label for="tgl">Tanggal*</label>
									<input class="form-control datepicker" type="text" name="tgl" id="tgl" value="<?= $tgl ?>">
									<span class="help-block"><?= $tglError ?></span>
								</div>

								<!-- Keterengan Uang dan nominal/besaran -->
								<div class="form-group">
									<!-- isi form -->
									<div class="row">
										<!-- keterangan uang -->
										<div class="col-xs-4 <?php if(!empty($ket_uangError)) echo 'has-error' ?>">
											<label for="ket_uang">Keterangan Uang*</label>
											<select class="form-control" name="ket_uang" id="ket_uang">
												<option value="">-- Pilih Keterangan Uang --</option>
												<option value="1" <?php if(strtolower($ket_uang)=="1") echo 'selected'; ?> >Uang Masuk</option>
												<option value="0" <?php if(strtolower($ket_uang)=="0") echo 'selected'; ?> >Uang Keluar</option>
											</select>
											<span class="help-block"><?= $ket_uangError ?></span>	
										</div>
										<!-- nominal/besaran -->
										<div class="col-xs-8 <?php if(!empty($besaranError)) echo 'has-error' ?>">
											<label for="besaran">Nominal*</label>
											<input class="form-control" type="text" name="besaran" id="besaran" value="<?php if(!empty($besaran)) echo round($besaran,2); else echo $besaran; ?>">
										</div>
									</div>
								</div>
								
								<!-- Keterangan -->
								<div class="form-group <?php if(!empty($keteranganError)) echo 'has-error' ?>">
									<label for="keterangan">Keterangan*</label>
									<textarea class="form-control" name="keterangan" id="keterangan" rows="5"><?= $keterangan ?></textarea>
								</div>

							</div>
							<div class="box-footer">
								<span class="help-block">* Wajib Diisi</span>
								<!-- tombol submit -->
								<div class="form-group text-right">
									<div class="btn-group">
										<input class="btn btn-success btn-flat margin" type="submit" name="keuanganSubmit" id="keuanganSubmit" value="<?= $btn ?>">
										<a href="<?= base_url."index.php?m=keuangan&p=list"; ?>" class="btn btn-default btn-flat margin">Cancel</a>
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
		<!-- js datepicker -->
		<script type="text/javascript" src="<?= base_url."assets/plugins/datepicker/bootstrap-datepicker.min.js"; ?>"></script>
		<!-- Select2 -->
		<script src="<?= base_url."assets/plugins/select2/select2.full.min.js"; ?>"></script>
		<script type="text/javascript">
			/* jQuery */
			$(function(){
				//setting datepicker
				$(".datepicker").datepicker({
					autoclose: true,
			        format: "yyyy-mm-dd",
			        todayHighlight: true,
			        orientation: "bottom auto",
			        todayBtn: true,
			        todayHighlight: true,
				});
			});			
		</script>
	<!-- end JavaScript -->