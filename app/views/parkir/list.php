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
    <h1>Parkir</h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url ?>"><i class="fa fa-dashboard"></i> SiSGar</a></li>
        <li class="active"><a href="<?= base_url."index.php?m=parkir&p=list"; ?>">Parkir</a></li>
        <li>Data Parkir</li>
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
                            <h3 class="box-title">Data Parkir</h3>
                        </div>
                    </div>
                    <!-- panel button -->
                    <div class="row" style="padding-top: 25px;">
                        <div class="col-md-12 col-xs-12">
                            <div class="btn-group">
                                <!-- tambah -->
                                <button type="button" id="btn_tambahParkir" class="btn btn-default btn-flat"><i class="fa fa-plus"></i> Tambah</button>
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
                            <table id="tabel_parkir" class="table table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 15px">No</th>
                                        <th>No Parkir</th>
                                        <th>Status</th>
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

<!-- modals -->
<div class="modal fade" id="modal_parkir">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <!-- button close -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                <!-- header modal -->
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="form_modal_parkir" role=form>
                    <input type="hidden" name="id_parkir" id="id_parkir">
                    <!-- field no parkir -->
                    <div class="form-group field-parkir">
                        <label for="no_parkir">No Parkir</label>
                        <input type="text" name="no_parkir" id="no_parkir" class="form-control">
                        <span class="help-block small"></span>
                    </div>
            </div>
            <div class="box-footer">
                <input class="btn btn-primary pull-right btn-flat" type="submit" id="btn_submit_parkir" name="action" value="Tambah">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/DataTables-1.10.15/js/jquery.dataTables.min.js"; ?>"></script>
<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/DataTables-1.10.15/js/dataTables.bootstrap.min.js"; ?>"></script>
<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/Responsive-2.1.1/js/dataTables.responsive.min.js"; ?>"></script>
<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/Responsive-2.1.1/js/responsive.bootstrap.min.js"; ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		// setting datatable
		var tabel_parkir = $("#tabel_parkir").DataTable({
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
	            url: base_url+"app/controllers/Parkir.php",
	            type: 'POST',
	            data: {
	                "action" : "list",
	            }
	        },
	        "columnDefs": [
	            {
	                "targets":[0, 3], // disable order di kolom 1 dan 3
	                "orderable":false,
	            }
	        ],
            "createdRow": function(row, data, dataIndex){
                if($(data[2]).text().toLowerCase() == "tidak tersedia") $(row).addClass('danger');
            },
	    });

	    // onclick btn tambah
	    $("#btn_tambahParkir").click(function(){
	    	reset_form();
	    	$("#modal_parkir .modal-title").text("Form Tambah Data Parkir");
	    	$("#btn_submit_parkir").prop("value", "Tambah");
	    	$("#modal_parkir").modal();
	    });

	    // submit form tambah harga
	    $("#form_modal_parkir").submit(function(e){
	    	e.preventDefault();
	    	submitParkir();

	    	return false;
	    });

	    // onchange field form
	    	// field jenis
	    	$("#no_parkir").change(function(){
	    		if(this.value !== ""){
	    			$(".field-parkir").removeClass('has-error');
					$(".field-parkir").find('.help-block').text("");
	    		}
	    	});
	    // =====================================
	});

	function submitParkir(){
		// get data form
		var id = $("#id_parkir").val().trim();
        var no_parkir = $("#no_parkir").val().trim();
        var submit = $("#btn_submit_parkir").val();

        $.ajax({
        	url: base_url+"app/controllers/Parkir.php",
            type: "post",
            dataType: "json",
            data: {
                "id" : id,
                "no_parkir" : no_parkir,
                "action" : submit,
            },
            success: function(hasil){
                // cek hasil dari ajax
                // cek statusnya
                if(hasil.status){ // jika status true
                    reset_form(); 
                    $("#modal_parkir").modal('hide');
                    // cek jenis actionya
                    if(submit.toLowerCase()==="edit") alertify.success('Data Berhasil Diedit'); // jika edit
                    else alertify.success('Data Berhasil Ditambah'); // jika tambah
                    $("#tabel_parkir").DataTable().ajax.reload(); // reload tabel
                }
                else{ // jika status false
                    // cek jenis error
                    if(hasil.errorDb){ // jika ada error database
                        swal("Pesan Error", "Koneksi Database Error, Silahkan Coba Lagi", "error")
                        reset_form();
                        $("#modal_parkir").modal('hide');
                    }
                    else{
                    	if(hasil.duplikat){
                    		$(".field-parkir").addClass('has-error');
							$(".field-parkir").find('.help-block').text("No Parkir Sudah Ada, Harap Ganti Dengan Yang Lainnya !");
                    	}
                    	else{
                    		reset_form();
	                        // set error
	                        setError(hasil.pesanError);	
                    	}
                        setValue(hasil.set_value);
                    }   
                }
                console.log(hasil);
            },
            error: function (jqXHR, textStatus, errorThrown){ // error handling
                swal("Pesan Error", "Operasi Gagal, Silahkan Coba Lagi", "error");
                $("#modal_parkir").modal('hide');
                reset_form();
                console.log(jqXHR, textStatus, errorThrown);
            }
        })
	}

	function edit_parkir(id){

	}

	function hapus_parkir(id){

	}

	function setError(error){
		if(!jQuery.isEmptyObject(error.no_parkirError)){
			$(".field-parkir").addClass('has-error');
			$(".field-parkir").find('.help-block').text(error.no_parkirError);
		}
		else{
			$(".field-parkir").removeClass('has-error');
			$(".field-parkir").find('.help-block').text("");
		}
	}

	function setValue(value){
		$("#no_parkir").val(value.no_parkir);
	}

	function reset_form(){
		$(".field-parkir").removeClass('has-error');
		$(".field-parkir").find('.help-block').text("");
		$('#form_modal_parkir').trigger('reset');
	}
</script>