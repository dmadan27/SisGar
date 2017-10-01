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
    <h1>Harga Sewa</h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url ?>"><i class="fa fa-dashboard"></i> SiSGar</a></li>
        <li class="active"><a href="<?= base_url."index.php?m=harga&p=list"; ?>">Harga Sewa</a></li>
        <li>Data Harga Sewa</li>
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
                            <h3 class="box-title">Data Harga</h3>
                        </div>
                    </div>
                    <!-- panel button -->
                    <div class="row" style="padding-top: 25px;">
                        <div class="col-md-12 col-xs-12">
                            <div class="btn-group">
                                <!-- tambah -->
                                <button type="button" id="btn_tambahHarga" class="btn btn-default btn-flat"><i class="fa fa-plus"></i> Tambah</button>
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
                            <table id="tabel_harga" class="table table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 15px">No</th>
                                        <th>Jenis Sewa</th>
                                        <th>Harga Sewa</th>
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
<div class="modal fade" id="modal_harga">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <!-- button close -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                <!-- header modal -->
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <form id="form_modal_harga" role=form>
                    <input type="hidden" name="id_harga" id="id_harga">
                    <!-- field jenis sewa -->
                    <div class="form-group field-jenis">
                        <label for="jenis">Jenis Sewa</label>
                        <select class="form-control" id="jenis" name="jenis"></select>
                        <span class="help-block small"></span>
                    </div>
                    <!-- field harga sewa -->
                    <div class="form-group field-harga">
						<label for="harga">Harga Sewa</label>
						<div class="input-group">
							<span class="input-group-addon" style="background-color: #dd4b39; color: white;">Rp. </span>
							<input type="number" min="0" class="form-control" placeholder="Masukkan Harga Sewa" id="harga" name="harga">
							<span class="input-group-addon">,00</span>
						</div>
						<span class="help-block small"></span>		
					</div>
            </div>
            <div class="box-footer">
                <input class="btn btn-primary pull-right btn-flat" type="submit" id="btn_submit_harga" name="action" value="Tambah">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- js -->
<!-- DataTables -->
<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/DataTables-1.10.15/js/jquery.dataTables.min.js"; ?>"></script>
<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/DataTables-1.10.15/js/dataTables.bootstrap.min.js"; ?>"></script>
<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/Responsive-2.1.1/js/dataTables.responsive.min.js"; ?>"></script>
<script type="text/javascript" src="<?= base_url."assets/plugins/DataTables/Responsive-2.1.1/js/responsive.bootstrap.min.js"; ?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		// setting datatable
		var tabel_harga = $("#tabel_harga").DataTable({
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
	            url: base_url+"app/controllers/Harga.php",
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
	    });

		set_jenis();

	    // onclick btn tambah
	    $("#btn_tambahHarga").click(function(){
	    	reset_form();
	    	$("#modal_harga .modal-title").text("Form Tambah Data Harga Sewa");
	    	$("#btn_submit_harga").prop("value", "Tambah");
	    	$("#modal_harga").modal();
	    });

	    // submit form tambah harga
	    $("#form_modal_harga").submit(function(e){
	    	e.preventDefault();
	    	submitHarga();

	    	return false;
	    });

	    // onchange field form
	    	// field jenis
	    	$("#jenis").change(function(){
	    		if(this.value !== ""){
	    			$(".field-jenis").removeClass('has-error');
					$(".field-jenis").find('.help-block').text("");
	    		}
	    	});

	    	// field harga
	    	$("#harga").change(function(){
	    		$(".field-harga").removeClass('has-error');
				$(".field-harga").find('.help-block').text("");
	    	});
	    // =====================================
	});

	function submitHarga(){
		// get data form
		var id = $("#id_harga").val().trim();
        var jenis = $("#jenis").val().trim();
        var harga = $("#harga").val().trim();
        var submit = $("#btn_submit_harga").val();

        $.ajax({
        	url: base_url+"app/controllers/Harga.php",
            type: "post",
            dataType: "json",
            data: {
                "id" : id,
                "jenis" : jenis,
                "harga" : harga,
                "action" : submit,
            },
            success: function(hasil){
                // cek hasil dari ajax
                // cek statusnya
                if(hasil.status){ // jika status true
                    reset_form(); 
                    $("#modal_harga").modal('hide');
                    // cek jenis actionya
                    if(submit.toLowerCase()==="edit") alertify.success('Data Berhasil Diedit'); // jika edit
                    else alertify.success('Data Berhasil Ditambah'); // jika tambah
                    $("#tabel_harga").DataTable().ajax.reload(); // reload tabel
                }
                else{ // jika status false
                    // cek jenis error
                    if(hasil.errorDb){ // jika ada error database
                        swal("Pesan Error", "Koneksi Database Error, Silahkan Coba Lagi", "error")
                        reset_form();
                        $("#modal_harga").modal('hide');
                    }
                    else{
                        reset_form();
                        // set error
                        setError(hasil.pesanError);
                        setValue(hasil.set_value);
                    }   
                }
                console.log(hasil);
            },
            error: function (jqXHR, textStatus, errorThrown){ // error handling
                swal("Pesan Error", "Operasi Gagal, Silahkan Coba Lagi", "error");
                $("#modal_harga").modal('hide');
                reset_form();
                console.log(jqXHR, textStatus, errorThrown);
            }
        })
	}

	function edit_harga(id){

	}

	function hapus_harga(id){

	}

	function setError(error){
		// set error field jenis
		if(!jQuery.isEmptyObject(error.jenisError)){
			$(".field-jenis").addClass('has-error');
			$(".field-jenis").find('.help-block').text(error.jenisError);
		}
		else{
			$(".field-jenis").removeClass('has-error');
			$(".field-jenis").find('.help-block').text("");
		}

		// set error field harga
		if(!jQuery.isEmptyObject(error.hargaError)){
			$(".field-harga").addClass('has-error');
			$(".field-harga").find('.help-block').text(error.hargaError);
		}
		else{
			$(".field-harga").removeClass('has-error');
			$(".field-harga").find('.help-block').text("");
		}
	}

	function setValue(value){
		var harga = parseFloat(value.harga) ? parseFloat(value.harga) : value.harga;

		$("#jenis").val(value.jenis);
		$("#harga").val(harga);
	}

	function set_jenis(){
		var arrayJenis = [
			{value: "", text: "-- Pilih Jenis Harga Sewa --"},
			{value: "B",text: "Bulanan"},
			{value: "T",text: "Tahunan"},
		];
		
		$.each(arrayJenis, function(index, item){
			var option = new Option(item.text, item.value);
			$("#jenis").append(option);
		});
	}

	function reset_form(){
		// reset pesan error
		$(".field-jenis").removeClass('has-error');
		$(".field-jenis").find('.help-block').text("");
		$(".field-harga").removeClass('has-error');
		$(".field-harga").find('.help-block').text("");
		// bersihkan form
		$('#form_modal_harga').trigger('reset');
	}
</script>