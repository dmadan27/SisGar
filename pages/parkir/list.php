<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	$query = "SELECT * FROM m_parkir ORDER BY no_parkir asc";
	$hasilQuery = mysqli_query($koneksi, $query);
	
	if(!$hasilQuery){
		die ("Query Error: ".mysqli_errno($koneksi).
           " - ".mysqli_error($koneksi));
	}

	$notif = isset($_GET['notif']) ? validInputan($_GET['notif'],false,true) : false;
?>

	<!-- css -->
		<!-- DataTables -->
  		<link rel="stylesheet" type="text/css" href="<?= base_url."assets/plugins/DataTables/DataTables-1.10.15/css/dataTables.bootstrap.min.css"; ?>"/>
  		<link rel="stylesheet" type="text/css" href="<?= base_url."assets/plugins/DataTables/Responsive-2.1.1/css/responsive.bootstrap.min.css"; ?>"/>
  	<!-- -->

  	<!-- Content Header -->
	<section class="content-header">
		<h1>Parkir</h1>
		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li><a href="<?= base_url; ?>"><i class="fa fa-dashboard"></i> Beranda</a></li>
			<li><a href="<?= base_url."index.php?m=parkir&p=list"; ?>">Parkir</a></li>
			<li class="active">Data Parkir</li>
		</ol>
	</section>

	<!-- Isi Content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<!-- panel box -->
				<div class="box">
					<!-- judul panel box -->
					<div class="box-header">
						<div class="row">
							<div class="col-sm-6 col-xs-12">
								<h3 class="box-title">Data Parkir</h3>
							</div>
							<div class="col-sm-6 col-xs-12 text-right">
								<a class="btn btn-primary btn-flat margin" href="<?= base_url."index.php?m=parkir&p=form"; ?>">Tambah Parkir</a>
							</div>
						</div>
					</div>
					<!-- isi panel box -->
					<div class="box-body">
						<div class="row">
							<?php
								if($notif){
									if($notif=="succes_tambah"){
										$notif = "Tambah Data Berhasil";
									}
									elseif($notif=="succes_ubah"){
										$notif = "Ubah Data Berhasil";
									}
									?>
									<div class="col-md-12">
										<div class="alert alert-success alert-dismissable fade in" id="success-alert">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
											<h4><i class="icon fa fa-check"></i>Sukses</h4>
											<?= $notif; ?>
										</div>
									</div>
									<?php
								}
									
							?>
						</div>
						<div class="row">
							<div class="col-md-12">
								<table id="tabelParkir" class="table table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th style="width: 15px">No</th>
											<th>No. Parkir</th>
											<th>Status</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$i = 0;
											while($data = mysqli_fetch_assoc($hasilQuery)){
												?>
												<tr>
													<td><?= ++$i ?></td>
													<td><?= $data['no_parkir'] ?></td>
													<td><?php if(strtolower($data['status'])=="1") echo "<span class='label label-success'>TERSEDIA</span>"; elseif(strtolower($data['status'])=="0") echo "<span class='label label-danger'>TIDAK TERSEDIA</span>"; elseif(empty($data['status'])) echo "Belum Didata"; ?></td>
													<td>
														<a href="<?= base_url."index.php?m=parkir&p=form&id_parkir=$data[id]"; ?>" class="btn btn-success btn-xs btn-flat"><i class="fa fa-edit"></i></a>	
													</td>
												</tr>
												<?php
											}
											mysqli_free_result($hasilQuery);
											mysqli_close($koneksi);
										?>
									</tbody>
								</table>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- JavaScript -->
		<!-- DataTables -->
		<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/DataTables-1.10.15/js/jquery.dataTables.min.js"; ?>"></script>
		<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/DataTables-1.10.15/js/dataTables.bootstrap.min.js"; ?>"></script>
		<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/Responsive-2.1.1/js/dataTables.responsive.min.js"; ?>"></script>
		<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/Responsive-2.1.1/js/responsive.bootstrap.min.js"; ?>"></script>
		<script type="text/javascript">
			//setting datatable
			$(function(){
				$("#tabelParkir").DataTable({
					"language" : {
						"lengthMenu": "Tampilkan _MENU_ data/page",
			            "zeroRecords": "Data Tidak Ada",
			            "info": "Page _PAGE_ dari _PAGES_",
			            "infoEmpty": "Data Kosong",
			            "search": "Pencarian:",
			            "paginate": {
					        "first": "Pertama",
					        "last": "Terakhir",
					        "next": "Selanjutnya",
					        "previous": "Sebelumnya"
					    }
					}
				});

				$("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    				$("#success-alert").slideUp(500);
				});
			});
		</script>
	<!-- end javascript -->