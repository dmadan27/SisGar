$(document).ready(function(){
	setSelect_status();
	setSelect_level();

	$("#tambah_admin").click(function(){
		setLoading(false);
    	resetForm();
    	$("#modal_admin .modal-title").html("Form Tambah Data Admin"); // ganti heade form
        $("#btn_submit_admin").prop("value", "Tambah");
        $("#modal_admin").modal();
	});

	$("#form_admin").submit(function(e){
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

	// onchange foto
		$(document).on('click', '#close-preview', function(){ 
		    $('.image-preview').popover('hide');
		    // Hover befor close the preview
		    $('.image-preview').hover(
		        function () {
		           $('.image-preview').popover('show');
		        }, 
		         function () {
		           $('.image-preview').popover('hide');
		        }
		    );    
		});

		// Create the close button
	    var closebtn = $('<button/>', {
	        type:"button",
	        text: 'x',
	        id: 'close-preview',
	        style: 'font-size: initial;',
	    });
	    closebtn.attr("class","close pull-right");
	    // Set the popover default content
	    $('.image-preview').popover({
	        trigger:'manual',
	        html:true,
	        title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
	        content: "Tidak Ada Foto",
	        placement:'bottom'
	    });
	    // Clear event
	    $('.image-preview-clear').click(function(){
	        $('.image-preview').attr("data-content","").popover('hide');
	        $('.image-preview-filename').val("");
	        $('.image-preview-clear').hide();
	        $('.image-preview-input input:file').val("");
	        $(".image-preview-input-title").text("Pilih Foto"); 
	    }); 
	    // Create the preview image
	    $("#foto").change(function (){
	        var img = $('<img/>', {
	            id: 'dynamic',
	            width:250,
	            height:200
	        });      
	        var file = this.files[0];
	        var reader = new FileReader();
	        // Set preview image into the popover data-content
	        reader.onload = function (e) {
	            $(".image-preview-input-title").text("Ganti");
	            $(".image-preview-clear").show();
	            $("#foto_text").val(file.name);            
	            img.attr('src', e.target.result);
	            $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
	        }        
	        reader.readAsDataURL(file);
	    });
	// =================================================
});

function getDataForm(){
	var data = new FormData();

	data.append('username', $("#username").val().trim()); // data id barang
	data.append('password', $("#password").val().trim()); // data id warna
	data.append('confirm', $("#confirm_pass").val().trim()); // data kd barang
	data.append('email', $("#email").val().trim()); // data nama
	data.append('level', $("#level").val().trim());
	data.append('nama', $("#nama").val().trim());
	data.append('foto', $("#foto")[0].files[0]); // data foto
	data.append('action', $("#btn_submit_admin").val().trim());
	
	return data;
}

function submit(){
	var data = getDataForm();

	$.ajax({
		url : base_url+"app/controllers/Admin.php",
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
				$("#modal_admin").modal('hide');
				$("#tabel_admin").DataTable().ajax.reload();
			}
			else{
				if(hasil.errorDb){ // jika db error
					swal("Pesan Error", "Koneksi Database Error, Silahkan Coba Lagi", "error");
				}
				else{
					if(hasil.duplikat){
						$(".field-username").addClass('has-error');
						$(".field-username").find('.help-block').text("Username Tidak Tersedia");
					}
					else{
						// set error
						setError(hasil.pesanError);
					}
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
	if(!jQuery.isEmptyObject(error.namaError)){
		$(".field-nama").addClass('has-error');
		$(".field-nama").find('.help-block').text(error.namaError);
	}
	else{
		$(".field-nama").removeClass('has-error');
		$(".field-nama").find('.help-block').text("");
	}

	// set error field username
	if(!jQuery.isEmptyObject(error.usernameError)){
		$(".field-username").addClass('has-error');
		$(".field-username").find('.help-block').text(error.usernameError);
	}
	else{
		$(".field-username").removeClass('has-error');
		$(".field-username").find('.help-block').text("");
	}

	// set error field password
	if(!jQuery.isEmptyObject(error.passwordError)){
		$(".field-password").addClass('has-error');
		$(".field-password").find('.help-block').text(error.passwordError);
	}
	else{
		$(".field-password").removeClass('has-error');
		$(".field-password").find('.help-block').text("");
	}

	// set error field confirm password
	if(!jQuery.isEmptyObject(error.confirmError)){
		$(".field-confirm").addClass('has-error');
		$(".field-confirm").find('.help-block').text(error.confirmError);
	}
	else{
		$(".field-confirm").removeClass('has-error');
		$(".field-confirm").find('.help-block').text("");
	}

	// set error field email
	if(!jQuery.isEmptyObject(error.emailError)){
		$(".field-email").addClass('has-error');
		$(".field-email").find('.help-block').text(error.emailError);
	}
	else{
		$(".field-email").removeClass('has-error');
		$(".field-email").find('.help-block').text("");
	}

	// set error field foto
	if(!jQuery.isEmptyObject(error.fotoError)){
		$(".field-foto").addClass('has-error');
		$(".field-foto").find('.help-block').text(error.fotoError);
	}
	else{
		$(".field-foto").removeClass('has-error');
		$(".field-foto").find('.help-block').text("");
	}

	// set error field level
	if(!jQuery.isEmptyObject(error.levelError)){
		$(".field-level").addClass('has-error');
		$(".field-level").find('.help-block').text(error.levelError);
	}
	else{
		$(".field-level").removeClass('has-error');
		$(".field-level").find('.help-block').text("");
	}
}

function setValue(value){
	$("#nama").val(value.nama);
	$("#username").val(value.username);
	$("#password").val(value.password);
	$("#confirm_pass").val(value.confirm);
	$("#email").val(value.email);
	$("#level").val(value.level);
}

function resetForm(){

}

function setSelect_level(){
	var arrayJenis = [
		{value: "", text: "-- Pilih Level Admin --"},
		{value: "ADMIN",text: "ADMIN"},
		{value: "SUPERADMIN",text: "SUPERADMIN"},
	];
	
	$.each(arrayJenis, function(index, item){
		var option = new Option(item.text, item.value);
		$("#level").append(option);
	});
}

function setSelect_status(){
	var arrayJenis = [
		{value: "", text: "-- Pilih Status Admin --"},
		{value: "1",text: "AKTIF"},
		{value: "0",text: "NON-AKTIF"},
	];
	
	$.each(arrayJenis, function(index, item){
		var option = new Option(item.text, item.value);
		$("#status").append(option);
	});

	$("#status").prop('disabled', true);
	$('#status option[value=1]').attr('selected','selected');
}

function setLoading(block=true){

}

