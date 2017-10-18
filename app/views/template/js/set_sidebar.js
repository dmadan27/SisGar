if(jQuery.isEmptyObject(urlParams)){
	$('.menu-beranda').addClass('active');
}
else{
	switch(urlParams.m.toLowerCase()){
		// menu sewa
		case "sewa":
			$('.menu-sewa').addClass('active');
			break;

		// menu keuangan
		case "keuangan":
			$('.menu-keuangan').addClass('active');
			break;

		// menu member
		case "member":
			$('.menu-data-master').addClass('active');
			$('.menu-member').addClass('active');
			break;

		// menu parkir
		case "parkir":
			$('.menu-data-master').addClass('active');
			$('.menu-parkir').addClass('active');
			break;

		// menu harga
		case "harga":
			$('.menu-data-master').addClass('active');
			$('.menu-harga').addClass('active');
			break;

		// menu admin
		case "admin":
			$('.menu-data-admin').addClass('active');
			$('.menu-admin').addClass('active');
			break;

		// menu log admin
		case "log_admin":
			$('.menu-data-admin').addClass('active');
			$('.menu-log-admin').addClass('active');
			break;

		// default menu beranda
		default:
			$('.menu-beranda').addClass('active');
			break;
	}
}

	