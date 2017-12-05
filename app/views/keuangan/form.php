<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
?>

<div class="modal fade" id="modal_keuangan">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- button close -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                <!-- header modal -->
                <h4 class="modal-title"></h4>
            </div>
            <form id="form_keuangan" role=form enctype="multipart/form-data">
            	<input type="hidden" name="id_keuangan" id="id_keuangan">
            	<div class="modal-body">
            		<!-- tgl -->
					<div class="form-group field-tgl">
						<label for="tgl">Tanggal</label>
						<div class="input-group">
			                <span class="input-group-addon">
			                	<i class="fa fa-calendar-plus-o"></i>
			                </span>
			                <input type="text" class="form-control datepicker" placeholder="Masukkan Tanggal" id="tgl" name="tgl">
						</div>
						<span class="help-block small"></span>
					</div>

					<!-- jenis -->
					<div class="form-group field-jenis">
						<label for="jenis">Jenis</label>
						<select id="jenis" class="form-control"></select>
						<span class="help-block small"></span>
					</div>
					
					<!-- ket -->
					<div class="form-group field-ket">
						<label for="password">Keterangan</label>
						<textarea id="ket" class="form-control" placeholder="Masukkan Keterangan"></textarea>
						<span class="help-block small"></span>
					</div>

					<!-- nominal -->
					<div class="form-group field-nominal">
						<label for="nominal">Nominal</label>
						<div class="input-group">
			                <span class="input-group-addon">
			                	Rp.
			                </span>
			                <input type="number" min="0" class="form-control" placeholder="Masukkan Nominal" id="nominal" name="nominal">
						</div>
						<span class="help-block small"></span>
					</div>
            	</div>
            	<div class="modal-footer">
	                <button class="btn btn-info btn-flat" type="submit" id="btn_submit_keuangan" value="Tambah">Tambah</button>
	                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Batal</button>
	            </div>
            </form>
        </div>
    </div>
</div>