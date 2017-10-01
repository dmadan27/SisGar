<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	$id = isset($_GET['id']) ? $_GET['id'] : false;

	if($id) $btn = "edit";
	else $btn = "tambah";
?>

<!-- isi konten -->
<section class="content-header">
	<h1>Admin</h1>
	<ol class="breadcrumb">
		<li><a href="<?= base_url ?>"><i class="fa fa-dashboard"></i>SisGar</a></li>
		<li>Admin</li>
		<li><a href="<?= base_url."index.php?m=admin&p=list" ?>">Data Admin</a></li>
		<li><i class="active"></i>Form Data Admin</a></li>
	</ol>
</section>

<!-- isi -->
<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<!-- panel box -->
			<div class="box">
				<!-- judul panel box -->
				<div class="box-header">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <h3 class="box-title">Form Data Admin</h3>
                        </div>
                    </div>
				</div>
				<form id="form_admin" role="form" enctype="multipart/form-data">
					<input type="hidden" name="id" id="id">
    				<!-- isi panel box -->
    				<div class="box-body">
    					<!-- fieldset data admin -->
    					<div class="row">
    						<!-- data admin -->
    						<div class="col-md-6">
    							<fieldset>
          							<legend>Data Admin</legend>
          							<!-- nama -->
          							<div class="form-group field-nama">
          								<label for="nama">Nama</label>
          								<div class="input-group">
							                <span class="input-group-addon">
							                	<i class="fa fa-user"></i>
							                </span>
							                <input type="text" class="form-control" placeholder="Masukkan Nama" id="nama" name="nama">
          								</div>
          								<span class="help-block small"></span>
          							</div>

          							<!-- username -->
          							<div class="form-group field-username">
          								<label for="username">Username</label>
          								<div class="input-group">
							                <span class="input-group-addon">
							                	<i class="fa fa-user"></i>
							                </span>
							                <input type="text" class="form-control" placeholder="Masukkan Username" id="username" name="username">
          								</div>
          								<span class="help-block small"></span>
          							</div>
          							
          							<!-- password -->
          							<div class="form-group field-password">
          								<label for="password">Password</label>
          								<div class="input-group">
							                <span class="input-group-addon">
							                	<i class="fa fa-key"></i>
							                </span>
							                <input type="password" class="form-control" placeholder="Masukkan Password (Minimal 6 Karakter)" id="password" name="password">
          								</div>
          								<span class="help-block small"></span>
          							</div>

          							<!-- confirm password -->
          							<div class="form-group field-confirm">
          								<label for="confirm_pass">Confirm Password</label>
          								<div class="input-group">
							                <span class="input-group-addon">
							                	<i class="fa fa-lock"></i>
							                </span>
							                <input type="password" class="form-control" placeholder="Confirm Password" id="confirm_pass" name="confirm_pass">
          								</div>
          								<span class="help-block small"></span>
          							</div>

          							<!-- email -->
          							<div class="form-group field-email">
          								<label for="email">Email</label>
          								<div class="input-group">
							                <span class="input-group-addon">
							                	<i class="fa fa-envelope"></i>
							                </span>
							                <input type="text" class="form-control" placeholder="Masukkan Email" id="email" name="email">
          								</div>
          								<span class="help-block small"></span>
          							</div>

          							<!-- foto -->
					          		<div class="form-group field-foto">
					          			<!-- preview foto -->
					          			<label for="foto">Foto</label>
					          			<div class="input-group image-preview">
							                <input type="text" id="foto_text" class="form-control image-preview-filename" disabled="disabled">
							                <span class="input-group-btn">
							                    <!-- image-preview-clear button -->
							                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
							                        <span class="glyphicon glyphicon-remove"></span> Hapus
							                    </button>
							                    <!-- image-preview-input -->
							                    <div class="btn btn-danger image-preview-input">
							                        <span class="glyphicon glyphicon-folder-open"></span>
							                        <span class="image-preview-input-title">Pilih Foto</span>
							                        <input type="file" accept="image/png, image/jpeg, image/gif" name="foto" id="foto" />
							                    </div>
							                </span>
							            </div>
							            <span class="help-block small"></span>
					                </div>
              					</fieldset>
    						</div>
    						<!-- level admin -->
    						<div class="col-md-6">
    							<fieldset>
    								<legend>Hak Akses Admin</legend>
    								<!-- level -->
                        			<div class="form-group field-level">
                        				<label for="level">Level</label>
                        				<select id="level" name="level" class="form-control">
                        				</select>
                        				<span class="help-block small"></span>
                        			</div>

                        			<!-- hak akses -->
          							<!-- <div class="form-group field-email">
          								<label for="hak_akses">Hak Akses Sistem</label>
          								<div class="input-group">
							                <span class="input-group-addon">
							                	<i class="fa fa-envelope"></i>
							                </span>
							                <input type="text" class="form-control" placeholder="Masukkan Email" id="email" name="email">
          								</div>
          								<span class="help-block small"></span>
          							</div> -->
    							</fieldset>
    						</div>				
    					</div>	
    				</div>

    				<!-- footer -->
	    			<div class="box-footer text-right">
	    				<div class="form-group">
	    					<button type="submit" class="btn btn-primary btn-lg" id="btn_submit_admin" name="action" value="<?= $btn ?>"><i class="fa fa-plus"></i> <?= ucfirst($btn); ?></button>
							<a href="<?=base_url."index.php?m=admin&p=list" ?>" class="btn btn-default btn-lg"><i class="fa fa-reply"></i>  Batal</a>
	    				</div>
					</div>
				</form>		
			</div>
		</div>
	</div>	
</section>

<!-- <script type="text/javascript">
    var base_url = "<?php print base_url; ?>";
    var urlParams = <?php echo json_encode($_GET, JSON_HEX_TAG);?>;
</script> -->
<script type="text/javascript">
	$(document).ready(function(){
		var arr_hakAkses = getHakAkses();
		setLevel();

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

		// submit barang
		$("#form_admin").submit(function(e){
			e.preventDefault();
			submitAdmin();

			return false;
		});

		// onchange field form
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

	function submitAdmin(){
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
				if(hasil.status) document.location=base_url+"index.php?m=admin&p=list";
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
					}	
				}

				console.log(hasil);
			},
			error: function (jqXHR, textStatus, errorThrown){ // error handling
	            swal("Pesan Error", "Operasi Gagal, Silahkan Coba Lagi", "error");
	            // reset_form("#form_barang");
	            console.log(jqXHR, textStatus, errorThrown);
	        }
		})
	}

	function getEdit(){

	}

	function setLevel(){
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

	function getHakAkses(){

	}

	function setHakAkses(level, hak_akses){
		if(level === ""){

		}
		else{

		}
	}

	function setError(error){
		// set error field nama
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
</script>