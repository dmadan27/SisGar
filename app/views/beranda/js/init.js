$(document).ready(function(){
	load_panelInfo();
});

// fungsi get data untuk box info
function load_panelInfo(){
	$.ajax({
		url: base_url+"app/controllers/Beranda.php",
			type: "post",
			dataType: "json",
			data: {
				"action": "get_panelInfo"
			},
			success: function(data){
				console.log(data);
				setValue_panelInfo(data);
			},
			error: function (jqXHR, textStatus, errorThrown){ // error handling
	            swal("Pesan Error", "Operasi Gagal, Silahkan Coba Lagi", "error");
	            // clearBarang();
	            console.log(jqXHR, textStatus, errorThrown);
	        }
	})
}

// fungsi set value panel info
function setValue_panelInfo(data){
	$('.panel-sewa h3').text(data.dataSaldo.uang_sewa);
	$('.panel-pengeluaran h3').text(data.dataSaldo.uang_keluar);
	$('.panel-saldo h3').text(data.dataSaldo.saldo);

	$('.panel-admin h3').text(data.dataAdmin.admin_aktif);
	$('.panel-member h3').text(data.dataMember.member_aktif);
	$('.panel-parkir h3').text(data.dataParkir.parkir_tersedia);
}