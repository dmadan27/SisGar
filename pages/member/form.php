<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	$id_member = isset($_GET['id_member']) ? validInputan($_GET['id_member'],false,true) : false;
	$pesanError = isset($_SESSION['pesanError']) ? $_SESSION['pesanError'] : false;
	$set_value = isset($_SESSION['set_value']) ? $_SESSION['set_value'] : false;

	//unset session
	unset($_SESSION['pesanError']);
	unset($_SESSION['set_value']);
	
	//inisialisasi pesan error dan value field
	$no_ktpError = $namaError = $pekerjaanError = "";
	$alamatError = $no_telpError = $no_kendaraanError = $fotoError = "";
	$no_ktp = $nama = $pekerjaan = "";
	$alamat = $no_telp = $no_kendaraan = "";

	$btn = "Tambah";

	if($pesanError){ //jika ada pesan error
		$no_ktpError = $pesanError['no_ktpError'];
		$namaError = $pesanError['namaError'];
		$pekerjaanError = $pesanError['pekerjaanError'];
		$alamatError = $pesanError['alamatError'];
		$no_telpError = $pesanError['no_telpError'];
		$no_kendaraanError = $pesanError['no_kendaraanError'];
		$fotoError = $pesanError['fotoError'];
	}

	if($set_value){ //jika repopulate
		$no_ktp = $set_value['no_ktp'];
		$nama = $set_value['nama'];
		$pekerjaan = $set_value['pekerjaan'];
		$alamat = $set_value['alamat'];
		$no_telp = $set_value['no_telp'];
		$no_kendaraan = $set_value['no_kendaraan'];
	}

	if($id_member){ //jika form ubah data
		if(!$pesanError){
			//1. prepare statement
			$query = "SELECT * FROM m_member WHERE id = ?";
			$stmt = mysqli_prepare($koneksi, $query);
			
			//2. bind
			mysqli_stmt_bind_param($stmt, "i", $id_member);

			//3. execute
			$execute = mysqli_stmt_execute($stmt);

			//4. cek execute
			if($execute){
				$hasilQuery = mysqli_stmt_get_result($stmt);
				$data = mysqli_fetch_assoc($hasilQuery);
				$no_ktp = $data['no_ktp'];
				$nama = $data['nama'];
				$pekerjaan = $data['pekerjaan'];
				$alamat = $data['alamat'];
				$no_telp = $data['no_telp'];
				$no_kendaraan = $data['no_kendaraan'];
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
	
	// var_dump($id_member);
?>

	<!-- Content Header -->
	<section class="content-header">
		<h1>Member</h1>
		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li><a href="<?= base_url; ?>"><i class="fa fa-dashboard"></i> Beranda</a></li>
			<li><a href="<?= base_url."index.php?m=member&p=form"; ?>">Member</a></li>
			<li class="active">Form Tambah Member</li>
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
						<form method="POST" action="<?= base_url."pages/member/action.php?id_member=$id_member"; ?>" enctype="multipart/form-data" id="formMember" role="form">

							<div class="box-body">
								<!-- no ktp - wajib -->
								<div class="form-group <?php if(!empty($no_ktpError)) echo 'has-error' ?>">
									<label for="no_ktp">No. KTP*</label>
									<input class="form-control" type="text" name="no_ktp" id="no_ktp" placeholder="Masukkan No. KTP" value="<?= $no_ktp ?>">
									<span class="help-block"><?= $no_ktpError ?></span>
								</div>

								<!-- nama - wajib -->
								<div class="form-group <?php if(!empty($namaError)) echo 'has-error' ?>">
									<label for="nama">Nama Lengkap*</label>
									<input class="form-control" type="text" name="nama" id="nama" placeholder="Masukkan Nama Lengkap" value="<?= $nama ?>">
									<span class="help-block"><?= $namaError ?></span>
								</div>

								<!-- pekerjaan - opsional -->
								<div class="form-group <?php if(!empty($pekerjaanError)) echo 'has-error' ?>">
									<label for="pekerjaan">Pekerjaan</label>
									<input class="form-control" type="text" name="pekerjaan" id="pekerjaan" placeholder="Masukkan Pekerjaan" value="<?= $pekerjaan ?>">
									<span class="help-block"><?= $pekerjaanError ?></span>
								</div>

								<!-- alamat - opsional -->
								<div class="form-group <?php if(!empty($alamatError)) echo 'has-error' ?>">
									<label for="alamat">Alamat</label>
									<textarea class="form-control" name="alamat" id="alamat" placeholder="Masukkan Alamat"><?= $alamat ?></textarea>
									<span class="help-block"><?= $alamatError ?></span>
								</div>

								<!-- no telp - opsional -->
								<div class="form-group <?php if(!empty($no_telpError)) echo 'has-error' ?>">
									<label for="no_telp">No. Telepon</label>
									<input class="form-control" type="text" name="no_telp" id="no_telp" placeholder="Masukkan No. Telepon" value="<?= $no_telp ?>">
									<span class="help-block"><?= $no_telpError ?></span>
								</div>

								<!-- no. kendaraan - opsional -->
								<div class="form-group <?php if(!empty($no_kendaraanError)) echo 'has-error' ?>">
									<label for="no_kendaraan">No. Polisi Kendaraan</label>
									<input class="form-control" type="text" name="no_kendaraan" id="no_kendaraan" placeholder="Masukkan No. Polisi Kendaraan" value="<?= $no_kendaraan ?>">
									<span class="help-block"><?= $no_kendaraanError ?></span>
								</div>
								
								<!-- foto kendaraan - opsional -->
								<div class="form-group <?php if(!empty($fotoError)) echo 'has-error' ?>">
									<label for="foto">Foto Kendaraan</label>
									<input type="file" name="foto" id="foto">
									<span class="help-block"><?= $fotoError ?></span>
								</div>
							</div>
							<div class="box-footer">
								<span class="help-block">* Wajib Diisi</span>
								<!-- tombol submit -->
								<div class="form-group text-right">
									<div class="btn-group">
										<input class="btn btn-success btn-flat margin" type="submit" name="memberSubmit" id="memberSubmit" value="<?= $btn ?>">
										<a href="<?= base_url."index.php?m=member&p=list"; ?>" class="btn btn-default btn-flat margin">Cancel</a>
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