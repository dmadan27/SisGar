<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	$id_sewa = isset($_GET['id_sewa']) ? validInputan($_GET['id_sewa'],false,true) : false;
	$pesanError = isset($_SESSION['pesanError']) ? $_SESSION['pesanError'] : false;
	$set_value = isset($_SESSION['set_value']) ? $_SESSION['set_value'] : false;

	//unset session
	unset($_SESSION['pesanError']);
	unset($_SESSION['set_value']);

	//inisialisasi pesan error dan value field
	$memberError = $no_parkirError = $jenis_sewaError = $statusError = "";
	$harga_sewaError =  $tgl_sewaError = $jatuh_tempoError = "";
	$member = $no_parkir = $jenis_sewa = $harga_sewa = "";
	$tgl_sewa = $jatuh_tempo = $status = "";

	$btn = "Tambah";

	//query get member
	$queryMember = "SELECT id id_member, nama nama_member FROM m_member";
	$hasilQueryMember = mysqli_query($koneksi, $queryMember);

	//query get parkir default
		//1. prepare statement
		$statusParkir = "1";
		$queryParkir = "SELECT id id_parkir, no_parkir FROM m_parkir WHERE status = ? ORDER BY no_parkir ASC";
		$stmt = mysqli_prepare($koneksi, $queryParkir);

		//2. bind
		mysqli_stmt_bind_param($stmt, "s", $statusParkir);

		//3. execute
		mysqli_stmt_execute($stmt);
		$hasilQueryParkir = mysqli_stmt_get_result($stmt);

	//query get harga
	$queryHarga = "SELECT id id_harga, (CASE WHEN jenis_sewa='B' THEN 'Bulanan' ELSE 'Tahunan' END) AS jenis_sewa, harga_sewa FROM m_harga";
	$hasilQueryHarga = mysqli_query($koneksi, $queryHarga);

	if($pesanError){ //jika ada pesan error
		$memberError = $pesanError['memberError'];
		$no_parkirError = $pesanError['no_parkirError'];
		$jenis_sewaError = $pesanError['jenis_sewaError'];
		$harga_sewaError = $pesanError['harga_sewaError'];
		$tgl_sewaError = $pesanError['tgl_sewaError'];
		$jatuh_tempoError = $pesanError['jatuh_tempoError'];
		$statusError = $pesanError['statusError'];
	}

	if($set_value){ //jika repopulate
		$member = $set_value['member'];
		$no_parkir = $set_value['no_parkir'];
		$jenis_sewa = $set_value['jenis_sewa'];
		$harga_sewa = $set_value['harga_sewa'];
		$tgl_sewa = $set_value['tgl_sewa'];
		$jatuh_tempo = $set_value['jatuh_tempo'];
		$status = $set_value['status'];
	}

	if($id_sewa){ //jika form ubah data
		//query get parkir
		//1. prepare statement
		$queryParkir = "SELECT * FROM(SELECT id id_parkir, no_parkir FROM m_parkir WHERE status = ? UNION ";
		$queryParkir .= "SELECT sewa.id_parkir id_parkir, parkir.no_parkir no_parkir FROM t_sewa sewa ";
		$queryParkir .= "JOIN m_parkir parkir ON parkir.id=sewa.id_parkir WHERE sewa.id = ?)a ORDER BY no_parkir asc";
		$stmt = mysqli_prepare($koneksi, $queryParkir);

		//2. bind
		mysqli_stmt_bind_param($stmt, "si", $statusParkir, $id_parkir);

		//3. execute
		mysqli_stmt_execute($stmt);
		$hasilQueryParkir = mysqli_stmt_get_result($stmt);
		
		if(!$pesanError){
			//1. prepare statement
			$query = "SELECT * FROM t_sewa WHERE id = ?";
			$stmt = mysqli_prepare($koneksi, $query);

			//2. bind
			mysqli_stmt_bind_param($stmt, "i", $id_sewa);

			//3. execute
			$execute = mysqli_stmt_execute($stmt);

			//4. cek execute
			if($execute){
				$hasilQuery = mysqli_stmt_get_result($stmt);
				$data = mysqli_fetch_assoc($hasilQuery);
				$member = $data['id_member'];
				$no_parkir = $data['id_parkir'];
				$jenis_sewa = $data['id_harga'];
				// $harga_sewa = $data['harga_sewa'];
				$tgl_sewa = $data['tgl_sewa'];
				$jatuh_tempo = $data['jatuh_tempo'];
				$status = $data['status'];
			}
			else{
				die("Error: ".mysqli_errno($koneksi)." - ".mysqli_error($koneksi));
			}	
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
		<h1>Sewa</h1>
		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li><a href="<?= base_url; ?>"><i class="fa fa-dashboard"></i> Beranda</a></li>
			<li><a href="<?= base_url."index.php?m=sewa&p=form"; ?>">Sewa</a></li>
			<li class="active">Form Tambah Transaksi Sewa</li>
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
						<h3 class="box-title">Form Transaksi Sewa</h3>
					</div>
					<!-- isi panel box -->
					<div class="box-body">
						<form method="POST" action="<?= base_url."pages/sewa/action.php?id_sewa=$id_sewa"; ?>" enctype="multipart/form-data" id="formSewa" role="form">
							<input type="hidden" name="userAdmin" value="<?= $sess_username ?>">
							<div class="box-body">
								<!-- daftar member -->
								<div class="form-group <?php if(!empty($memberError)) echo 'has-error' ?>">
									<label for="member">Member</label>
									<div class="input-group">
										<!-- select member -->
										<select class="form-control select2" style="width: 100%;" id="member" name="member">
											<option value="">-- Pilih Member --</option>
											<?php
												while($dataMember = mysqli_fetch_assoc($hasilQueryMember)){
													?>
													<option value="<?= $dataMember['id_member']; ?>" <?php if($dataMember['id_member']==$member) echo 'selected' ?> >
														<?= $dataMember['nama_member']; ?>
													</option>
													<?php
												}
											?>
										</select>
										<!-- btn member baru -->
										<div class="input-group-btn">
											<button class="btn btn-info btn-flat" type="button" id="btnTambahMember" data-toggle="tooltip" data-placement="bottom" title="Member Tidak Tersedia? Tambah Member Baru">Member Baru</button>
										</div>
										
									</div>
									<span class="help-block"><?= $memberError ?></span>
								</div>
											
								<!-- daftar no parkir -->
								<div class="form-group <?php if(!empty($no_parkirError)) echo 'has-error' ?>">
									<label for="parkir">No. Parkir</label>
									<select class="form-control select2" style="width: 100%;" id="no_parkir" name="no_parkir">
										<option value="">-- Pilih No. Parkir --</option>
										<?php
											while($dataParkir = mysqli_fetch_assoc($hasilQueryParkir)){
												?>
												<option value="<?= $dataParkir['id_parkir']; ?>" <?php if($dataParkir['id_parkir']==$no_parkir) echo 'selected' ?> >
													<?= $dataParkir['no_parkir']; ?>
												</option>
												<?php
											}
										?>
									</select>
									<span class="help-block"><?= $no_parkirError ?></span>
								</div>

								<!-- jenis sewa - harga sewa -->
								<div class="form-group <?php if(!empty($jenis_sewaError)) echo 'has-error' ?>">
									<div class="row">
										<div class="col-xs-6">
											<label for="jenis_sewa">Jenis Sewa</label>
											<select class="form-control" id="jenis_sewa" name="jenis_sewa">
												<option value="">-- Pilih Jenis Sewa --</option>
												<?php
													while($dataHarga = mysqli_fetch_assoc($hasilQueryHarga)){
														?>
														<option value="<?= $dataHarga['id_harga']; ?>" <?php if($dataHarga['id_harga']==$jenis_sewa){echo 'selected'; $harga_sewa=$dataHarga['harga_sewa'];}  ?> >
															<?= $dataHarga['jenis_sewa']; ?>
														</option>
														<?php
													}
												?>
											</select>
											<span class="help-block"><?= $jenis_sewaError ?></span>
										</div>
										<div class="col-xs-6">
											<label for="harga_sewa">Harga Sewa</label>
											<input class="form-control" type="text" name="harga_sewa" id="harga_sewa" value="<?php if(!empty($harga_sewa)) echo round($harga_sewa,2); else echo $harga_sewa; ?>" readonly>
											<span class="help-block"><?= $harga_sewaError ?></span>
										</div>
									</div>
									
								</div>

								<!-- tgl sewa - jatuh tempo -->
								<div class="form-group <?php if(!empty($tgl_sewaError)) echo 'has-error' ?>">
									<div class="row">
										<div class="col-xs-6">
											<label for="tgl_sewa">Tanggal Sewa</label>
											<input class="form-control datepicker" type="text" name="tgl_sewa" id="tgl_sewa" placeholder="Masukkan Tanggal Sewa (yyyy-mm-dd)" value="<?= $tgl_sewa ?>">
											<span class="help-block"><?= $tgl_sewaError ?></span>
										</div>
										<div class="col-xs-6">
											<label for="jatuh_tempo">Jatuh Tempo</label>
											<input class="form-control datepicker" type="text" name="jatuh_tempo" id="jatuh_tempo" value="<?= $jatuh_tempo ?>" readonly>
											<span class="help-block"><?= $jatuh_tempoError ?></span>
										</div>
									</div>
								</div>
							</div>
							<div class="box-footer">
								<span class="help-block">* Wajib Diisi</span>
								<!-- tombol submit -->
								<div class="form-group text-right">
									<div class="btn-group">
										<input class="btn btn-success btn-flat margin" type="submit" name="sewaSubmit" id="sewaSubmit" value="<?= $btn ?>">
										<a href="<?= base_url."index.php?m=sewa&p=list"; ?>" class="btn btn-default btn-flat margin">Cancel</a>
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
	    		//Initialize Select2 Elements
	    		$(".select2").select2();

	    		//tooltip
				$('[data-toggle="tooltip"]').tooltip();

				//setting datepicker
				$(".datepicker").datepicker({
					autoclose: true,
			        format: "yyyy-mm-dd",
			        todayHighlight: true,
			        orientation: "bottom auto",
			        todayBtn: true,
			        todayHighlight: true,
				});

				//modals tambah member baru
				$("#btnTambahMember").click(function(){
					//$("#myModal").modal();
				});
			});

			/* js Native */
			//==========variabel global==========//
				var member = document.getElementById("member");
				var no_parkir = document.getElementById("no_parkir");
				var jenis_sewa = document.getElementById("jenis_sewa");
				var harga_sewa = document.getElementById("harga_sewa");
				var tgl_sewa = document.getElementById("tgl_sewa");
				var jatuh_tempo = document.getElementById("jatuh_tempo");
				var status_kontrak = document.getElementById("status");

				var sewaSubmit = document.getElementById("sewaSubmit"); //btn submit

				var cekBulanan, cekTahunan; //cek untuk penambahan bulan/tahun
			//==================================//

			//onchange jenis sewa --> harga sewa
			jenis_sewa.onchange = function(){
				//cek nilai
				if(jenis_sewa.value.toLowerCase() == "1"){ //jika bulanan -> 250rb
					harga_sewa.value = 250000;
					//jatuh tempo +1 bulan
					cekBulanan = true;
					cekTahunan = false;
					tgl_sewa.focus();
				}
				else if(jenis_sewa.value.toLowerCase() == "2"){ //jika tahunan -> 2,5jt
					harga_sewa.value = 2500000;
					//jatuh tempo +1 tahun
					cekBulanan = false;
					cekTahunan = true;
					tgl_sewa.focus();
				}
				else{
					harga_sewa.value = "";
					cekBulanan = false;
					cekTahunan = false;
					tgl_sewa.value = "";
					jatuh_tempo.value = "";
				}
				console.log(cekBulanan, cekTahunan);
			};

			//onchange tgl_sewa
			tgl_sewa.onchange = function(){
				var now = new Date(tgl_sewa.value);
				var current;
				var tahun = now.getFullYear(); 
				var bulan = now.getMonth();
				var hari = now.getDate();

				if(cekBulanan){
					bulan = bulan+1;
					if(now.getMonth() == 11){ //jika bulan desember, +1 tahun
						current = new Date(tahun+1, 0, hari);
						jatuh_tempo.value = formatTgl(current);
					}
					else{ //jika selain desember
						current = new Date(tahun, bulan, hari);
						jatuh_tempo.value = formatTgl(current);
					}
				}
				else if(cekTahunan){
					tahun = tahun+1;
					current = new Date(tahun, bulan, hari);
					jatuh_tempo.value = formatTgl(current);
				}
			}

			//format tgl yyyy-mm-dd
			function formatTgl(date){
    			var d = new Date(date),
        			month = '' + (d.getMonth() + 1),
        			day = '' + d.getDate(),
        			year = d.getFullYear();

    			if (month.length < 2)
    				month = '0' + month;
    			if (day.length < 2) 
    				day = '0' + day;
    			
    			return [year, month, day].join('-');
			}
		</script>
	<!-- end JavaScript -->