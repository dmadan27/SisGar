<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
?>

<div class="modal fade" id="modal_admin">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <!-- button close -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                <!-- header modal -->
                <h4 class="modal-title"></h4>
            </div>
            <form id="form_admin" role=form enctype="multipart/form-data">
            	<div class="modal-body">
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
            	<div class="modal-footer">
	                <button class="btn btn-info btn-flat" type="submit" id="btn_submit_admin" value="Tambah">Tambah</button>
	                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
	            </div>
            </form>
        </div>
    </div>
</div>