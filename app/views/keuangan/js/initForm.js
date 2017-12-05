$(document).ready(function(){
	setSelect_jenis();

	$(".datepicker").datepicker({
		autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "bottom auto",
        todayBtn: true,
        todayHighlight: true,
	});

	$("#btn_tambahKeuangan").click(function(){
		setLoading(false);
    	resetForm();
    	$("#modal_keuangan .modal-title").html("Form Tambah Data Keuangan"); // ganti heade form
        $("#btn_submit_keuangan").prop("value", "Tambah");
        $("#modal_keuangan").modal();
	});

	$("#form_keuangan").submit(function(e){
		e.preventDefault();
    	submit();
    	return false;
	});

	// onchange field
		// field nama
		$("#nama").change(function(){
			$(".field-nama").removeClass('has-error');
			$(".field-nama").find('.help-block').text("");
		});

		// field username
		$("#username").change(function(){
			$(".field-username").removeClass('has-error');
			$(".field-username").find('.help-block').text("");
		});

		// field password
		$("#password").change(function(){
			$(".field-password").removeClass('has-error');
			$(".field-password").find('.help-block').text("");
		});

		// field confirm
		$("#confirm_pass").change(function(){
			$(".field-confirm").removeClass('has-error');
			$(".field-confirm").find('.help-block').text("");
		});

		// field email
		$("#email").change(function(){
			$(".field-email").removeClass('has-error');
			$(".field-email").find('.help-block').text("");
		});

		// field foto
		$("#foto").change(function(){
			$(".field-foto").removeClass('has-error');
			$(".field-foto").find('.help-block').text("");
		});

		// field level
		$("#level").change(function(){
			if(this.value !== ""){
				$(".field-level").removeClass('has-error');
				$(".field-level").find('.help-block').text("");

				// set hak akses sesuai level
				setHakAkses(this.value, arr_hakAkses);
			}
		});
	// =================================================
});

function getDataForm(){
	var data = new FormData();

	data.append('id_keuangan', $("#id_keuangan").val().trim());
	data.append('tgl', $("#tgl").val().trim()); 
	data.append('ket', $("#ket").val().trim()); 
	data.append('jenis', $("#jenis").val().trim()); 
	data.append('nominal', $("#nominal").val().trim()); 
	data.append('action', $("#btn_submit_keuangan").val().trim());
	
	return data;
}

function submit(){
	var data = getDataForm();

	$.ajax({
		url : base_url+"app/controllers/Keuangan.php",
		type : "post",
		dataType : "json",
		data: data,
		contentType: false,
	    cache: false,
		processData: false,
		success: function(hasil){
			if(hasil.status){
				alertify.success("Data Berhasil Ditambah");
				resetForm();
				$("#modal_keuangan").modal('hide');
				$("#tabel_keuangan").DataTable().ajax.reload();
			}
			else{
				if(hasil.errorDb){ // jika db error
					swal("Pesan Error", "Koneksi Database Error, Silahkan Coba Lagi", "error");
				}
				else{					
					// set error
					setError(hasil.pesanError);

					// set value
					setValue(hasil.set_value);
					alertify.error("Harap Cek Isian Form Kembali");
				}	
			}

			console.log(hasil);
		},
		error: function (jqXHR, textStatus, errorThrown){ // error handling
            swal("Pesan Error", "Operasi Gagal, Silahkan Coba Lagi", "error");
            console.log(jqXHR, textStatus, errorThrown);
        }
	})
}

function getEdit(id){

}

function setError(error){
	if(!jQuery.isEmptyObject(error.tglError)){
		$(".field-tgl").addClass('has-error');
		$(".field-tgl").find('.help-block').text(error.tglError);
	}
	else{
		$(".field-tgl").removeClass('has-error');
		$(".field-tgl").find('.help-block').text("");
	}

	if(!jQuery.isEmptyObject(error.jenisError)){
		$(".field-jenis").addClass('has-error');
		$(".field-jenis").find('.help-block').text(error.jenisError);
	}
	else{
		$(".field-jenis").removeClass('has-error');
		$(".field-jenis").find('.help-block').text("");
	}

	if(!jQuery.isEmptyObject(error.ketError)){
		$(".field-ket").addClass('has-error');
		$(".field-ket").find('.help-block').text(error.ketError);
	}
	else{
		$(".field-ket").removeClass('has-error');
		$(".field-ket").find('.help-block').text("");
	}

	if(!jQuery.isEmptyObject(error.nominalError)){
		$(".field-nominal").addClass('has-error');
		$(".field-nominal").find('.help-block').text(error.nominalError);
	}
	else{
		$(".field-nominal").removeClass('has-error');
		$(".field-nominal").find('.help-block').text("");
	}
}

function setValue(value){
	$('#tgl').datepicker('update',value.tgl);
	$("#jenis").val(value.jenis);
	$("#ket").val(value.ket);
	$("#nominal").val(value.nominal);
	$("#id_keuangan").val(value.id);
}

function resetForm(){

}

function setSelect_jenis(){
	var arrayJenis = [
		{value: "", text: "-- Pilih Jenis Keuangan --"},
		{value: "M",text: "UANG MASUK"},
		{value: "K",text: "UANG KELUAR"},
	];
	
	$.each(arrayJenis, function(index, item){
		var option = new Option(item.text, item.value);
		$("#jenis").append(option);
	});
}

function setLoading(block=true){

}

