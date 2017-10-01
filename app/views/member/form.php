<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");
	
	$id = isset($_GET['id']) ? $_GET['id'] : false;

	if($id) $btn = "edit";
	else $btn = "tambah";
?>

<section class="content-header">
    <h1>Member</h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url ?>"><i class="fa fa-dashboard"></i> SiSGar</a></li>
        <li class="active"><a href="<?= base_url."index.php?m=member&p=list"; ?>">Member</a></li>
        <li>Form Data Member</li>
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
                            <h3 class="box-title">Form Data Member</h3>
                        </div>
                    </div>
				</div>
				<form id="form_member" role="form" enctype="multipart/form-data">
					<input type="hidden" name="id_member" id="id_member">
    				<!-- isi panel box -->
    				<div class="box-body">
    					<!-- fieldset data barang -->
    					<div class="row">
    						<!-- data barang  -->
    						<div class="col-md-6">
    							<fieldset>
          							<legend>Data Member</legend>
          							<div class="row">
          								<div class="col-md-6 col-xs-12">
          									<!-- no ktp -->
		          							<div class="form-group field-ktp">
		          								<label for="no_ktp">No. KTP</label>
		          								<input type="text" class="form-control" placeholder="Masukkan No. KTP" id="no_ktp" name="no_ktp">
		          								<span class="help-block small"></span>
		          							</div>
          								</div>
          								<div class="col-md-6 col-xs-12">
          									<!-- nama -->
		          							<div class="form-group field-nama">
		  										<label for="nama">Nama</label>
		          								<input type="text" class="form-control" placeholder="Masukkan Nama" id="nama" name="nama">
		          								<span class="help-block small"></span>	
		          							</div>
          								</div>
          							</div>
          							<div class="row">
          								<div class="col-md-6 col-xs-12">
          									<!-- no telp -->
		          							<div class="form-group field-telp">
		          								<label for="no_telp">No. Telepon</label>
		          								<input type="text" class="form-control" placeholder="Masukkan No. Telepon" id="no_telp" name="no_telp">
		          								<span class="help-block small"></span>
		          							</div>
          								</div>
          								<div class="col-md-6 col-xs-12">
          									<!-- pekerjaan -->
		          							<div class="form-group field-pekerjaan">
		          								<label for="pekerjaan">Pekerjaan</label>
		          								<input type="text" class="form-control" placeholder="Masukkan Pekerjaan" id="pekerjaan" name="pekerjaan">
		          								<span class="help-block small"></span>
		          							</div>
          								</div>
          							</div>
          							<div class="form-group field-alamat">
          								<label for="alamat">Alamat</label>
          								<textarea class="form-control" rows="2" placeholder="Masukkan Alamat" id="alamat" name="alamat"></textarea>
          								<span class="help-block small"></span>
          							</div>       
              					</fieldset>
    						</div>
    						<div class="col-md-6">
    							<div class="row">
    								<div class="col-sm-12">
    									<fieldset>
		          							<legend>Data Kendraan</legend>
		          							<!-- nopol -->
		          							<div class="form-group field-nopol">
			                    				<div class="row">
			                    					<div class="col-md-12">
			                    						<label for="nopol">No. Polisi Kendraan</label>
			                    						<div class="input-group">
					                        				<input type="text" class="form-control" placeholder="Masukkan No. Polisi Kendaraan" id="nopol" name="nopol">
					                        				<span class="input-group-btn">
					                        					<button type="button" class="btn bg-maroon btn-flat" id="btn_tambahNopol"><i class="fa fa-plus"></i></button>
					                        				</span>
				                        				</div>
			                    					</div>
			                    				</div>
			                    			</div>
		          						</fieldset>
    								</div>
    							</div>
    							<div class="row">
    								<div class="col-md-12">
    									<div class="table-responsive">
	   										<table id="tabel_nopol" class="table table-bordered table-hover">
		                        				<thead>
		                        					<tr>
		                        						<th style="width: 15px">No</th>
		                        						<th>No. Polisi Kendaraan</th>
		                        						<th>Aksi</th>
		                        					</tr>
		                        				</thead>
		                        				<tbody></tbody>
		                        			</table>
	   									</div>
    								</div>
    							</div>	
    						</div>
    					</div>	
    				</div>
	    			<div class="box-footer text-right">
	    				<div class="form-group">
	    					<button type="submit" class="btn btn-default btn-lg btn-flat" id="btn_submit_member" name="action" value="<?= $btn ?>"><i class="fa fa-plus"></i> <?= ucfirst($btn); ?></button>
							<a href="<?=base_url."index.php?m=member&p=list" ?>" class="btn btn-default btn-lg btn-flat"><i class="fa fa-reply"></i>  Batal</a>
	    				</div>
					</div>
				</form>		
			</div>
		</div>
	</div>	
</section>

<script type="text/javascript">
    var listNopol = [];
    var indexNopol = 0;
</script>
<script type="text/javascript">
	var cekEdit = false;

	if(!jQuery.isEmptyObject(urlParams.id)){ // jika ada parameter get
		cekEdit = true;
	}

	if(cekEdit) edit_member(urlParams.id);
	
</script>