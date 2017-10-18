<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
?>

<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="<?= base_url."assets/plugins/DataTables/DataTables-1.10.15/css/dataTables.bootstrap.min.css"; ?>"/>
<link rel="stylesheet" type="text/css" href="<?= base_url."assets/plugins/DataTables/Responsive-2.1.1/css/responsive.bootstrap.min.css"; ?>"/>
<!-- Datepicker -->
<link rel="stylesheet" type="text/css" href="<?= base_url."assets/plugins/datepicker/bootstrap-datepicker3.min.css"; ?>"/>

<!-- header dan breadcrumb -->
<section class="content-header">
    <h1>Sewa</h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url ?>"><i class="fa fa-dashboard"></i> SiSGar</a></li>
        <li><a href="<?= base_url."index.php?m=sewa&p=list"; ?>">Sewa</a></li>
        <li class="active">Data Sewa</li>
    </ol>
</section>

<!-- isi konten -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <!-- panel box -->
            <div class="box">
                <!-- judul panel box -->
                <div class="box-header">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <h3 class="box-title">Data Sewa</h3>
                        </div>
                    </div>
                    <!-- panel button -->
                    <div class="row" style="padding-top: 25px;">
                        <div class="col-md-12 col-xs-12">
                            <div class="btn-group">
                                <!-- tambah -->
                                <button type="button" id="btn_tambahSewa" class="btn btn-default btn-flat"><i class="fa fa-plus"></i> Tambah</button>
                                <!-- export excel -->
                                <button type="button" class="btn btn-success btn-flat" id="exportExcel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                                <!-- export pdf -->
                                <button type="button" class="btn btn-danger btn-flat" id="exportPdf"><i class="fa fa-file-pdf-o"></i> Export Pdf</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- isi panel box -->
                <div class="box-body">
                    <!-- tabel -->
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <table id="tabel_sewa" class="table table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 15px">No</th>
                                        <th>Nama</th>
                                        <th>No. Polis</th>
                                        <th>No. Parkir</th>
                                        <th>Jenis Sewa</th>
                                        <th>Harga</th>
                                        <th>Tanggal Sewa</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Status Kontrak</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="overlay" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/DataTables-1.10.15/js/jquery.dataTables.min.js"; ?>"></script>
<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/DataTables-1.10.15/js/dataTables.bootstrap.min.js"; ?>"></script>
<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/Responsive-2.1.1/js/dataTables.responsive.min.js"; ?>"></script>
<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/Responsive-2.1.1/js/responsive.bootstrap.min.js"; ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var tabel_sewa = $("#tabel_sewa").DataTable({
	        "language" : {
	            "lengthMenu": "Tampilkan _MENU_ data/page",
	            "zeroRecords": "Data Tidak Ada",
	            "info": "Menampilkan _START_ s.d _END_ dari _TOTAL_ data",
	            "infoEmpty": "Menampilkan 0 s.d 0 dari 0 data",
	            "search": "Pencarian:",
	            "loadingRecords": "Loading...",
	            "processing": "Processing...",
	            "paginate": {
	                "first": "Pertama",
	                "last": "Terakhir",
	                "next": "Selanjutnya",
	                "previous": "Sebelumnya"
	            }
	        },
	        "lengthMenu": [ 25, 50, 75, 100 ],
	        "pageLength": 25,
	        order: [],
	        processing: true,
	        serverSide: true,
	        ajax: {
	            url: base_url+"app/controllers/Sewa.php",
	            type: 'POST',
	            data: {
	                "action" : "list",
	            }
	        },
	        "columnDefs": [
	            {
	                "targets":[0, 9], // disable order di kolom 1 dan 3
	                "orderable":false,
	            }
	        ],
            "createdRow": function(row, data, dataIndex){
                if($(data[8]).text().toLowerCase() == "kontrak habis") $(row).addClass('danger');
            },
	    });
	});
</script>