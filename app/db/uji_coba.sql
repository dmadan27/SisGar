-- admin
INSERT INTO admin (username, password, nama, email, level, foto, status)
VALUES ('lordRaze', '$2y$10$5VaRlFvfX8IpgsF9B/bhqOcqhlBZfYOnH6SLv7avE/90US0XwZctO', 'RAMADAN SAPUTRA', 'rarasta27@gmail.com', 'SUPERADMIN', 'd532d5e5556cc77ccfedd50e66f6fa1dfd557d7a.jpg', '1');

-- parkir
INSERT INTO parkir(no_parkir, status) 
VALUES 
('A01','1'), ('A02','1'), ('A03','1'), ('A04','1'), ('A05','1'), ('A06','1'), ('A07','1'), ('A08','1'), ('A09','1'),
('B01','1'), ('B02','1'), ('B03','1'), ('B04','1'), ('B05','1'), ('B06','1'), ('B07','1'), ('B08','1'), ('B09','1');

-- harga
INSERT INTO harga(jenis, harga)
VALUES ('B', 250000), ('T', 25000000);

-- member
INSERT INTO member(no_ktp, nama, no_telp, alamat, pekerjaan)
VALUES
('3273245106840001', 'TIA PURNAMASARI', '089508014907', 'JL. CISARANTEN KULON NO. 100 RT 04/03 KEC. ARCAMANIK BANDUNG', 'IBU RUMAH TANGGA'),
('3204250312830012', 'KRIS SYANDI KURNIA', '081394461234', 'JL. CISARANTEN KULON 8E B', 'WIRASWASTA'),
('', 'SAUDARA MBAK TIA', '', '', ''),
('', 'AYI TOHIR', '081909996908', 'JL. CISARANTEN KULON RT 03/03', 'SWASTA'),
('3523172006850002', 'WAWAN FITRI ANTO', '085854004722', '&QUOT;MULTI USAHA GYPSUM&QUOT; CISARANTEN KULON NO. 7AB (DEPAN KANTOR POS/GIRO) ARCAMANIK', 'SWASTA'),
('3273242003760002', 'DIRMAN SUDIRMAN', '085353471597', 'KP. CISARANTEN KULON RT 01/03 ARCAMANIK', 'WIRASWASTA'),
('', 'LIA', '', '', ''),
('', 'HENHEN', '', '', ''),
('', 'YAYAT', '', '', '');

-- nopol
INSERT INTO nopol(id_member, nopol)
VALUES
(1, 'D 1147 OC'), -- tia
(2, 'D 1073 1B'), -- kris
(2, 'D 1091 QR'), -- kris
(3, ''), -- saudara mbak tia
(4, ''), -- ayi
(5, 'W 1549 BV'), -- wawan
(6, 'D 1315 AEQ'), -- dirman
(7, ''), -- lia
(8, ''), -- henhen
(9, ''); -- yayat

-- sewa
-- CALL tambah_sewa(id_member, id_parkir, id_nopol, jenis, harga, tgl, jatuh, status, admin);

	-- bulan april
	CALL tambah_sewa(1, 1, 1, 'B', 250000, '2017-04-06', '2017-05-06', '0', 'lordRaze'); -- tia
	CALL tambah_sewa(2, 2, 2, 'B', 250000, '2017-04-08', '2017-05-08', '0', 'lordRaze'); -- kris
	CALL tambah_sewa(3, 3, 4, 'B', 250000, '2017-04-20', '2017-05-20', '0', 'lordRaze'); -- saudara mbak lia
	CALL tambah_sewa(4, 4, 5, 'B', 250000, '2017-04-22', '2017-05-22', '0', 'lordRaze'); -- ayi tohir
	CALL tambah_sewa(5, 5, 6, 'B', 250000, '2017-04-23', '2017-05-23', '0', 'lordRaze'); -- wawan
	CALL tambah_sewa(6, 6, 7, 'B', 250000, '2017-04-27', '2017-05-27', '0', 'lordRaze'); -- dirman

	-- bulan mei
	CALL tambah_sewa(1, 1, 1, 'B', 250000, '2017-05-06', '2017-06-06', '0', 'lordRaze'); -- tia
	CALL tambah_sewa(2, 2, 2, 'B', 250000, '2017-05-08', '2017-06-08', '0', 'lordRaze'); -- kris
	CALL tambah_sewa(7, 3, 8, 'B', 250000, '2017-05-10', '2017-06-10', '0', 'lordRaze'); -- lia
	CALL tambah_sewa(4, 4, 5, 'B', 250000, '2017-05-22', '2017-06-22', '0', 'lordRaze'); -- ayi
	CALL tambah_sewa(5, 5, 6, 'B', 250000, '2017-05-23', '2017-06-23', '0', 'lordRaze'); -- wawan
	CALL tambah_sewa(6, 6, 7, 'T', 2500000, '2017-05-30', '2018-05-30', '1', 'lordRaze'); -- dirman
	CALL tambah_sewa(2, 7, 3, 'B', 250000, '2017-05-31', '2017-07-01', '0', 'lordRaze'); -- kris

	-- bulan juni
	CALL tambah_sewa(1, 1, 1, 'B', 250000, '2017-06-06', '2017-07-06', '0', 'lordRaze'); -- tia
	CALL tambah_sewa(2, 2, 2, 'B', 250000, '2017-06-08', '2017-07-08', '0', 'lordRaze'); -- kris

	-- bulan juli
	CALL tambah_sewa(4, 4, 5, 'B', 250000, '2017-07-04', '2017-08-04', '0', 'lordRaze'); -- ayi
	CALL tambah_sewa(1, 1, 1, 'B', 250000, '2017-07-06', '2017-08-06', '0', 'lordRaze'); -- tia
	CALL tambah_sewa(2, 2, 2, 'B', 250000, '2017-07-08', '2017-08-08', '0', 'lordRaze'); -- kris
	CALL tambah_sewa(5, 5, 6, 'B', 250000, '2017-07-12', '2017-08-12', '0', 'lordRaze'); -- wawan
	CALL tambah_sewa(8, 3, 9, 'B', 250000, '2017-07-14', '2017-08-14', '0', 'lordRaze'); -- henhen
	CALL tambah_sewa(2, 7, 3, 'B', 250000, '2017-07-27', '2017-08-27', '0', 'lordRaze'); -- kris

	-- bulan agustus
	CALL tambah_sewa(4, 4, 5, 'B', 250000, '2017-08-04', '2017-09-04', '0', 'lordRaze'); -- ayi
	CALL tambah_sewa(1, 1, 1, 'B', 250000, '2017-08-06', '2017-09-06', '0', 'lordRaze'); -- tia
	CALL tambah_sewa(2, 2, 2, 'B', 250000, '2017-08-08', '2017-09-08', '0', 'lordRaze'); -- kris
	CALL tambah_sewa(5, 5, 6, 'B', 250000, '2017-08-12', '2017-09-12', '0', 'lordRaze'); -- wawan
	CALL tambah_sewa(8, 3, 9, 'B', 250000, '2017-08-14', '2017-09-14', '0', 'lordRaze'); -- henhen
	CALL tambah_sewa(2, 7, 3, 'B', 250000, '2017-08-27', '2017-09-27', '0', 'lordRaze'); -- kris

	-- bulan september
	CALL tambah_sewa(9, 8, 10, 'B', 250000, '2017-09-01', '2017-10-01', '0', 'lordRaze'); -- yayat
	CALL tambah_sewa(4, 4, 5, 'B', 250000, '2017-09-04', '2017-10-04', '0', 'lordRaze'); -- ayi
	CALL tambah_sewa(1, 1, 1, 'B', 250000, '2017-09-06', '2017-10-06', '0', 'lordRaze'); -- tia
	CALL tambah_sewa(2, 2, 2, 'B', 250000, '2017-09-08', '2017-10-08', '0', 'lordRaze'); -- kris
	CALL tambah_sewa(5, 5, 6, 'B', 250000, '2017-09-12', '2017-10-12', '0', 'lordRaze'); -- wawan
	CALL tambah_sewa(8, 3, 9, 'B', 250000, '2017-09-14', '2017-10-14', '0', 'lordRaze'); -- henhen
	CALL tambah_sewa(2, 7, 3, 'B', 250000, '2017-09-27', '2017-10-27', '0', 'lordRaze'); -- kris

	-- bulan oktober
	CALL tambah_sewa(9, 8, 10, 'B', 250000, '2017-10-01', '2017-11-01', '1', 'lordRaze'); -- yayat
	CALL tambah_sewa(4, 4, 5, 'B', 250000, '2017-10-04', '2017-11-04', '1', 'lordRaze'); -- ayi
	CALL tambah_sewa(2, 2, 2, 'B', 250000, '2017-10-08', '2017-11-08', '1', 'lordRaze'); -- kris
	CALL tambah_sewa(2, 7, 3, 'B', 250000, '2017-10-27', '2017-11-27', '1', 'lordRaze'); -- kris

	CALL tambah_sewa(1, 1, 1, 'B', 250000, '2017-10-04', '2017-11-04', '1', 'lordRaze'); -- tia
	CALL tambah_sewa(5, 5, 6, 'B', 250000, '2017-10-12', '2017-11-12', '1', 'lordRaze'); -- wawan
	CALL tambah_sewa(8, 3, 9, 'B', 250000, '2017-10-14', '2017-11-14', '1', 'lordRaze'); -- henhen

-- keuangan
INSERT INTO keuangan(tgl, jenis, ket, nominal, admin) 
VALUES
('2017-04-25', 'K', 'DUPLIKAT KUNCI 7 BUAH @12.000/KUNCI', 85000,'lordRaze'),
('2017-04-25', 'K', 'PILOK WARNA MERAH UNTUK LABEL PARKIR @20.500/BUAH', 65000,'lordRaze'),
('2017-05-10', 'K', 'PERLENGKAPAN LAMPU UNTUK GARASI: KABEL 30M @150.000, 1 SET LAMPU NEON @45.000, 1 SET LAMPU LED BESERTA PERLENGKAPAN LAINNYA @34.000', 250000,'lordRaze'),
('2017-06-08', 'K', 'LISTRIK GARASI', 75000,'lordRaze'),
('2017-06-18', 'K', 'KEPERLUAN LEBARAN', 2000000,'lordRaze'),
('2017-06-19', 'K', 'TAKSI KE KOTABUMI', 350000,'lordRaze'),
('2017-07-05', 'K', 'BELI LAMPU NEON SATU SET, KABEL 10M, BESERTA UANG JASA PASANG, DLL.', 200000,'lordRaze'),
('2017-07-10', 'K', 'LISTRIK GARASI', 75000,'lordRaze'),
('2017-07-13', 'K', 'UANG JASA PANGGIL ORANG CEK POMPA AIR', 100000,'lordRaze'),
('2017-07-14', 'K', 'UANG BENSIN MOBIL YARIS ANUN', 75000,'lordRaze'),
('2017-07-17', 'K', 'INTERNET BANDUNG', 540000,'lordRaze'),
('2017-08-10', 'K', 'LISTRIK GARASI', 75000,'lordRaze'),
('2017-08-11', 'K', 'GANTI ACCU MOTOR MIO', 250000,'lordRaze'),
('2017-08-11', 'K', 'GANTI LAMPU RUMAH', 50000,'lordRaze'),
('2017-08-16', 'K', 'INTERNET BANDUNG', 550000,'lordRaze'),
('2017-09-08', 'K', 'INTERNET BANDUNG', 550000,'lordRaze'),
('2017-09-10', 'K', 'LISTRIK GARASI', 75000,'lordRaze'),
('2017-10-09', 'K', 'LISTRIK GARASI', 75000,'lordRaze'),
('2017-10-09', 'K', 'IURAN RW 2 BULAN', 75000,'lordRaze'),
('2017-10-09', 'K', 'INTERNET BANDUNG', 540000,'lordRaze'),
('2017-10-14', 'K', 'TAKSI KE KOTABUMI', 350000,'lordRaze'),
('2017-10-16', 'K', 'GANTI LAMPU TAMAN DAN BENERIN KABEL YANG KONSLET', 50000,'lordRaze');