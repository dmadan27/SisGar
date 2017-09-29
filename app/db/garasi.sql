-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 25 Jun 2017 pada 15.39
-- Versi Server: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `garasi`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_t_keuangan` (IN `tgl_param` DATE, IN `ket_uang_param` CHAR(1), IN `besaran_param` DOUBLE, IN `keterangan_param` TEXT, IN `admin_param` VARCHAR(50))  BEGIN
	DECLARE saldo_terkini DOUBLE;
    
        SELECT m_saldo.saldo INTO saldo_terkini FROM m_saldo;
    
            IF ket_uang_param = "1" THEN     	INSERT INTO t_keuangan (tgl, ket_uang, besaran, keterangan, saldo, user_admin) VALUES (tgl_param, ket_uang_param, besaran_param, keterangan_param, (saldo_terkini+besaran_param), admin_param);
	ELSE
    	INSERT INTO t_keuangan (tgl, ket_uang, besaran, keterangan, saldo, user_admin) VALUES (tgl_param, ket_uang_param, besaran_param, keterangan_param, (saldo_terkini-besaran_param), admin_param);
    END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_t_sewa` (IN `id_member_param` INT, IN `id_parkir_param` INT, IN `id_harga_param` INT, IN `tgl_sewa_param` DATE, IN `jatuh_tempo_param` DATE, IN `status_param` CHAR(1), IN `admin_param` VARCHAR(50))  BEGIN
	DECLARE ket_param VARCHAR(255);
    DECLARE harga_param DOUBLE;
    DECLARE saldo_param DOUBLE;
	DECLARE id_sewa_param INT;
    
        SELECT UPPER(CONCAT('sewa garasi a/n',' ',m_member.nama)) INTO ket_param from m_member where m_member.id = id_member_param;
    
        SELECT m_harga.harga_sewa INTO harga_param FROM m_harga WHERE m_harga.id=id_harga_param;
    
        SELECT m_saldo.saldo INTO saldo_param from m_saldo;
        
        SELECT `AUTO_INCREMENT` INTO id_sewa_param FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'garasi' AND   TABLE_NAME   = 't_sewa';
    
		INSERT INTO t_sewa (id_member, id_parkir, id_harga, tgl_sewa, jatuh_tempo, status, user_admin) VALUES (id_member_param, id_parkir_param, id_harga_param, tgl_sewa_param, jatuh_tempo_param, status_param, admin_param);
    
        UPDATE m_parkir SET m_parkir.status = "0" WHERE m_parkir.id = id_parkir_param;
    
        INSERT INTO t_keuangan (tgl, ket_uang, besaran, keterangan, saldo, kode_transaksi, user_admin) VALUES (tgl_sewa_param, "1", harga_param, ket_param, (saldo_param+harga_param), id_sewa_param, admin_param);
    

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_t_keuangan` (IN `id_param` INT, IN `tgl_param` DATE, IN `ket_uang_param` CHAR(1), IN `besaran_param` DOUBLE, IN `keterangan_param` TEXT, IN `admin_param` VARCHAR(50))  BEGIN
    DECLARE ket_uang_cek CHAR(1);
    DECLARE besaran_cek DOUBLE;
    DECLARE saldo_param DOUBLE;
    
                    SELECT ket_uang INTO ket_uang_cek FROM t_keuangan WHERE id=id_param;
                SELECT besaran INTO besaran_cek FROM t_keuangan WHERE id=id_param;
                SELECT saldo INTO saldo_param FROM t_keuangan WHERE id=id_param;
    
        IF ket_uang_cek != ket_uang_param THEN
    	        IF ket_uang_param = "1" THEN
        	UPDATE t_keuangan SET tgl=tgl_param, ket_uang=ket_uang_param, besaran=besaran_param, keterangan=keterangan_param, saldo = ((saldo_param+besaran_cek)+besaran_param), user_admin = admin_param WHERE id = id_param;
        ELSE            	UPDATE t_keuangan SET tgl=tgl_param, ket_uang=ket_uang_param, besaran=besaran_param, keterangan=keterangan_param, saldo = ((saldo_param-besaran_cek)-besaran_param), user_admin = admin_param WHERE id = id_param;
        END IF;
   	ELSE     	IF besaran_cek != besaran_param THEN
        	        	IF ket_uang_param = "1" THEN
        		UPDATE t_keuangan SET tgl=tgl_param, ket_uang=ket_uang_param, besaran=besaran_param, keterangan=keterangan_param, saldo = ((saldo_param+besaran_cek)+besaran_param), user_admin = admin_param WHERE id = id_param;
        	ELSE            		UPDATE t_keuangan SET tgl=tgl_param, ket_uang=ket_uang_param, besaran=besaran_param, keterangan=keterangan_param, saldo = ((saldo_param-besaran_cek)-besaran_param), user_admin = admin_param WHERE id = id_param;
        	END IF;
        END IF;
    END IF;
            

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `nama` varchar(150) NOT NULL,
  `level` enum('ADMIN','SUPERADMIN') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`username`, `password`, `nama`, `level`, `email`) VALUES
('lordRaze', '$2y$11$YdvC3jZpQzViBpZDv8doUOItw6yPjuUsHO4LK26QlZrQF9Enp6VaW', 'Ramadan Saputra', 'SUPERADMIN', 'rarasta27@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_admin`
--

CREATE TABLE `log_admin` (
  `id` int(11) NOT NULL,
  `user_admin` varchar(50) DEFAULT NULL,
  `waktu_login` datetime DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `waktu_logout` datetime DEFAULT NULL,
  `kegiatan` text,
  `kode_akses` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_harga`
--

CREATE TABLE `m_harga` (
  `id` int(11) NOT NULL,
  `jenis_sewa` char(1) DEFAULT NULL,
  `harga_sewa` double(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `m_harga`
--

INSERT INTO `m_harga` (`id`, `jenis_sewa`, `harga_sewa`) VALUES
(1, 'B', 250000.00),
(2, 'T', 2500000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_member`
--

CREATE TABLE `m_member` (
  `id` int(11) NOT NULL,
  `no_ktp` varchar(20) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `pekerjaan` varchar(255) DEFAULT NULL,
  `alamat` text,
  `no_telp` varchar(20) DEFAULT NULL,
  `no_kendaraan` varchar(25) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `m_member`
--

INSERT INTO `m_member` (`id`, `no_ktp`, `nama`, `pekerjaan`, `alamat`, `no_telp`, `no_kendaraan`, `foto`) VALUES
(1, '3273245106840001', 'TIA PURNAMASARI', 'IBU RUMAH TANGGA', 'JL. CISARANTEN KULON NO. 100 RT 04/03 KEC. ARCAMANIK BANDUNG', '089508014907', 'D 1147 OC', ''),
(2, '3204250312830012', 'KRIS SYANDI KURNIA', 'WIRASWASTA', 'JL. CISARANTEN KULON 8E B', '081394461234', 'D 1073 1B / D 1091 QR', ''),
(3, '3523172006850002', 'WAWAN FITRI ANTO', 'SWASTA', '&QUOT;MULTI USAHA GYPSUM&QUOT; CISARANTEN KULON NO. 7AB (DEPAN KANTOR POS/GIRO) ARCAMANIK', '085854004722', 'W 1549 BV', ''),
(4, '1000000000000001', 'AYI TOHIR', 'SWASTA', 'JL. CISARANTEN KULON RT 03/03', '081909996908', '', ''),
(5, '1000000000000002', 'LIA', '', '', '', '', ''),
(6, '3273242003760002', 'DIRMAN SUDIRMAN', 'WIRASWASTA', 'KP. CISARANTEN KULON RT 01/03 ARCAMANIK', '085353471597', 'D 1315 AEQ', ''),
(7, '101343215151542351', 'KARTIKA SARI', 'ASFASFAS', 'ASFASFASFA', '087822678679', '', ''),
(8, '101343215151542351', 'KARTIKA SARI', 'ASFASFAS', 'ASFASFASFA', '087822678679', '', ''),
(9, '32151325235213513261', 'KARTIKA AJA UNYUL', 'IBU RUMAH TANGGA GAJE', 'GARUDA', '087822678679', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_parkir`
--

CREATE TABLE `m_parkir` (
  `id` int(11) NOT NULL,
  `no_parkir` char(3) DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `m_parkir`
--

INSERT INTO `m_parkir` (`id`, `no_parkir`, `status`) VALUES
(1, 'A01', '0'),
(2, 'A02', '0'),
(3, 'A03', '0'),
(4, 'A04', '0'),
(5, 'A05', '0'),
(6, 'A06', '1'),
(7, 'A07', '1'),
(8, 'A08', '1'),
(9, 'B01', '1'),
(10, 'B02', '1'),
(11, 'B03', '1'),
(12, 'B04', '1'),
(13, 'B05', '1'),
(14, 'B06', '1'),
(15, 'B07', '1'),
(16, 'B08', '1'),
(17, 'A09', '1'),
(18, 'B09', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `m_saldo`
--

CREATE TABLE `m_saldo` (
  `id` int(11) NOT NULL,
  `saldo` double(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `m_saldo`
--

INSERT INTO `m_saldo` (`id`, `saldo`) VALUES
(1, 3800500.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_keuangan`
--

CREATE TABLE `t_keuangan` (
  `id` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `ket_uang` char(1) DEFAULT NULL,
  `besaran` double(10,2) DEFAULT NULL,
  `keterangan` text,
  `saldo` double(10,2) DEFAULT NULL,
  `kode_transaksi` int(11) DEFAULT NULL,
  `user_admin` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `t_keuangan`
--

INSERT INTO `t_keuangan` (`id`, `tgl`, `ket_uang`, `besaran`, `keterangan`, `saldo`, `kode_transaksi`, `user_admin`) VALUES
(1, '2017-05-06', '1', 250000.00, 'SEWA GARASI A/N TIA PURNAMASARI', 250000.00, 1, NULL),
(2, '2017-05-08', '1', 250000.00, 'SEWA GARASI A/N KRIS SYANDI KURNIA', 500000.00, 2, NULL),
(3, '2017-04-23', '1', 250000.00, 'SEWA GARASI A/N WAWAN FITRI ANTO', 750000.00, 3, NULL),
(4, '2017-04-22', '1', 250000.00, 'SEWA GARASI A/N AYI TOHIR', 1000000.00, 4, NULL),
(5, '2017-04-20', '1', 250000.00, 'SEWA GARASI A/N SAUDARANYA MBAK TIA', 1250000.00, 5, NULL),
(6, '2017-05-10', '1', 250000.00, 'SEWA GARASI A/N LIA', 1500000.00, 6, NULL),
(7, '2017-05-06', '1', 250000.00, 'SEWA GARASI A/N TIA PURNAMASARI', 1750000.00, 7, NULL),
(8, '2017-05-08', '1', 250000.00, 'SEWA GARASI A/N KRIS SYANDI KURNIA', 2000000.00, 8, NULL),
(12, '2017-04-25', '0', 84000.00, 'DUPLIKAT KUNCI 7 BUAH @12.000/KUNCI', 1916000.00, NULL, NULL),
(13, '2017-04-25', '0', 61500.00, 'PILOK WARNA MERAH UNTUK LABEL PARKIR @20.500/BUAH', 1854500.00, NULL, NULL),
(14, '2017-05-10', '0', 229000.00, 'PERLENGKAPAN LAMPU UNTUK GARASI: KABEL 30M @150.000, 1 SET LAMPU NEON @45.000, 1 SET LAMPU LED BESERTA PERLENGKAPAN LAINNYA @34.000', 1625500.00, NULL, NULL),
(15, '2017-05-31', '1', 250000.00, 'SEWA GARASI A/N KRIS SYANDI KURNIA', 1875500.00, 9, NULL),
(16, '2017-05-30', '1', 250000.00, 'SEWA GARASI A/N AYI TOHIR', 2125500.00, 10, NULL),
(17, '2017-05-30', '1', 2500000.00, 'SEWA GARASI A/N DIRMAN SUDIRMAN', 4625500.00, 11, NULL),
(18, '2017-05-23', '1', 250000.00, 'SEWA GARASI A/N WAWAN FITRI ANTO', 4875500.00, 12, NULL),
(19, '2017-06-06', '1', 250000.00, 'SEWA GARASI A/N TIA PURNAMASARI', 5125500.00, 13, NULL),
(20, '2017-06-08', '1', 250000.00, 'SEWA GARASI A/N KRIS SYANDI KURNIA', 5375500.00, 14, NULL),
(21, '2017-06-08', '0', 75000.00, 'BAYAR LISTRIK', 5300500.00, NULL, NULL),
(22, '2017-06-18', '0', 1500000.00, '-', 3800500.00, NULL, NULL);

--
-- Trigger `t_keuangan`
--
DELIMITER $$
CREATE TRIGGER `trigger_after_delete_t_keuangan` AFTER DELETE ON `t_keuangan` FOR EACH ROW BEGIN
	DECLARE saldo_param DOUBLE;
    
        SELECT m_saldo.saldo INTO saldo_param FROM m_saldo;
        
        IF OLD.ket_uang = "1" THEN
        	UPDATE m_saldo SET m_saldo.saldo = (saldo_param-OLD.besaran);
            ELSE
            	UPDATE m_saldo SET m_saldo.saldo = (saldo_param+OLD.besaran);
      	END IF;
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_after_insert_t_keuangan` BEFORE INSERT ON `t_keuangan` FOR EACH ROW BEGIN
	DECLARE saldo_param DOUBLE;
    SELECT m_saldo.saldo INTO saldo_param FROM m_saldo;
    
    IF NEW.ket_uang = "1" THEN     	UPDATE m_saldo SET m_saldo.saldo = (saldo_param+NEW.besaran);
        ELSE
        	UPDATE m_saldo SET m_saldo.saldo = (saldo_param-NEW.besaran);
        END IF;
        
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_after_update_t_penjualan` AFTER UPDATE ON `t_keuangan` FOR EACH ROW BEGIN
	DECLARE saldo_param DOUBLE;
    
        SELECT m_saldo.saldo INTO saldo_param FROM m_saldo;
    
    	IF OLD.ket_uang != NEW.ket_uang THEN 				IF NEW.ket_uang = "1" THEN 			UPDATE m_saldo SET m_saldo.saldo = ((saldo_param+OLD.besaran)+NEW.besaran);
			ELSE 				UPDATE m_saldo SET m_saldo.saldo = ((saldo_param-OLD.besaran)-NEW.besaran);
		END IF;
		ELSE 						IF OLD.besaran != NEW.besaran THEN 								IF NEW.ket_uang = "1" THEN 					UPDATE m_saldo SET m_saldo.saldo = ((saldo_param-OLD.besaran)+NEW.besaran);
					ELSE 						UPDATE m_saldo SET m_saldo.saldo = ((saldo_param+OLD.besaran)-NEW.besaran);
				END IF;
			END IF;
		
       	END IF;
    
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_sewa`
--

CREATE TABLE `t_sewa` (
  `id` int(11) NOT NULL,
  `id_member` int(11) NOT NULL,
  `id_parkir` int(11) NOT NULL,
  `id_harga` int(11) DEFAULT NULL,
  `tgl_sewa` date DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `user_admin` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `t_sewa`
--

INSERT INTO `t_sewa` (`id`, `id_member`, `id_parkir`, `id_harga`, `tgl_sewa`, `jatuh_tempo`, `status`, `user_admin`) VALUES
(1, 1, 1, 1, '2017-04-06', '2017-05-06', '0', 'lordRaze'),
(2, 2, 2, 1, '2017-04-08', '2017-05-08', '0', 'lordRaze'),
(3, 3, 3, 1, '2017-04-23', '2017-05-23', '0', 'lordRaze'),
(4, 4, 4, 1, '2017-04-22', '2017-05-22', '0', 'lordRaze'),
(5, 6, 5, 1, '2017-04-20', '2017-05-20', '0', 'lordRaze'),
(6, 5, 6, 1, '2017-05-10', '2017-06-10', '0', 'lordRaze'),
(7, 1, 1, 1, '2017-05-06', '2017-06-06', '0', 'lordRaze'),
(8, 2, 2, 1, '2017-05-08', '2017-06-08', '0', 'lordRaze'),
(9, 2, 3, 1, '2017-05-31', '2017-07-01', '1', 'lordRaze'),
(10, 4, 4, 1, '2017-05-30', '2017-06-30', '1', 'lordRaze'),
(11, 6, 5, 2, '2017-05-30', '2018-05-30', '1', 'lordRaze'),
(12, 3, 7, 1, '2017-05-23', '2017-06-23', '0', 'lordRaze'),
(13, 1, 1, 1, '2017-06-06', '2017-07-06', '1', 'lordRaze'),
(14, 2, 2, 1, '2017-06-08', '2017-07-08', '1', 'lordRaze');

--
-- Trigger `t_sewa`
--
DELIMITER $$
CREATE TRIGGER `trigger_after_delete_t_sewa` BEFORE DELETE ON `t_sewa` FOR EACH ROW BEGIN
	    IF OLD.status = "1" THEN     	        UPDATE m_parkir SET m_parkir.status = "1" WHERE m_parkir.id = OLD.id_parkir;
    END IF;
    
        DELETE FROM t_keuangan WHERE t_keuangan.kode_transaksi = OLD.id;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trigger_after_update_t_sewa` AFTER UPDATE ON `t_sewa` FOR EACH ROW BEGIN
	DECLARE harga_baru DOUBLE;
	DECLARE besaran_awal DOUBLE;
	DECLARE saldo_awal DOUBLE;

	SELECT t_keuangan.besaran INTO besaran_awal FROM t_keuangan 
    WHERE t_keuangan.kode_transaksi = OLD.id;
    
    SELECT t_keuangan.saldo INTO saldo_awal FROM t_keuangan 
    WHERE t_keuangan.kode_transaksi = OLD.id;

	SELECT m_harga.harga_sewa INTO harga_baru FROM m_harga WHERE m_harga.id = NEW.id_harga;

	IF OLD.status=NEW.status THEN
		IF OLD.id_parkir != NEW.id_parkir THEN
			UPDATE m_parkir SET status="1" WHERE id=OLD.id_parkir;
			UPDATE m_parkir SET status="0" WHERE id=NEW.id_parkir;
		END IF;
    ELSE
    	UPDATE m_parkir SET status="1" WHERE id=NEW.id_parkir;
    END IF;

    IF OLD.id_harga != NEW.id_harga THEN
    	UPDATE t_keuangan SET besaran = harga_baru, saldo = ((saldo_awal-besaran_awal)+harga_baru) WHERE kode_transaksi = OLD.id;
    END IF;
    
    UPDATE t_keuangan SET tgl = NEW.tgl_sewa WHERE kode_transaksi = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_token`
--

CREATE TABLE `t_token` (
  `id` int(11) NOT NULL,
  `user_admin` varchar(50) DEFAULT NULL,
  `kode_token` text,
  `kode_akses` text,
  `timeout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_log_admin`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_log_admin` (
`id` int(11)
,`username` varchar(50)
,`nama` varchar(150)
,`login` datetime
,`ip` varchar(50)
,`logout` datetime
,`kegiatan` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_t_sewa`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_t_sewa` (
`id` int(11)
,`nama` varchar(255)
,`no_parkir` char(3)
,`jenis_sewa` varchar(7)
,`harga_sewa` double(10,2)
,`tgl_sewa` date
,`jatuh_tempo` date
,`status` varchar(13)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_log_admin`
--
DROP TABLE IF EXISTS `v_log_admin`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_log_admin`  AS  select `log`.`id` AS `id`,`log`.`user_admin` AS `username`,`a`.`nama` AS `nama`,`log`.`waktu_login` AS `login`,`log`.`ip` AS `ip`,`log`.`waktu_logout` AS `logout`,`log`.`kegiatan` AS `kegiatan` from (`log_admin` `log` join `admin` `a` on((`a`.`username` = `log`.`user_admin`))) order by `log`.`waktu_login` desc ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_t_sewa`
--
DROP TABLE IF EXISTS `v_t_sewa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_t_sewa`  AS  select `sewa`.`id` AS `id`,`member`.`nama` AS `nama`,`parkir`.`no_parkir` AS `no_parkir`,(case when (`harga`.`jenis_sewa` = 'B') then 'Bulanan' else 'Tahunan' end) AS `jenis_sewa`,`harga`.`harga_sewa` AS `harga_sewa`,`sewa`.`tgl_sewa` AS `tgl_sewa`,`sewa`.`jatuh_tempo` AS `jatuh_tempo`,(case when (`sewa`.`status` = '1') then 'Masih Berlaku' else 'Kontrak Habis' end) AS `status` from (((`t_sewa` `sewa` join `m_member` `member` on((`member`.`id` = `sewa`.`id_member`))) join `m_parkir` `parkir` on((`parkir`.`id` = `sewa`.`id_parkir`))) join `m_harga` `harga` on((`harga`.`id` = `sewa`.`id_harga`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `log_admin`
--
ALTER TABLE `log_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_admin_user_admin` (`user_admin`);

--
-- Indexes for table `m_harga`
--
ALTER TABLE `m_harga`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_member`
--
ALTER TABLE `m_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_parkir`
--
ALTER TABLE `m_parkir`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_saldo`
--
ALTER TABLE `m_saldo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_keuangan`
--
ALTER TABLE `t_keuangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_t_keuangan_kode_transaksi` (`kode_transaksi`),
  ADD KEY `fk_t_keuangan_user_admin` (`user_admin`);

--
-- Indexes for table `t_sewa`
--
ALTER TABLE `t_sewa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_t_sewa_id_member` (`id_member`),
  ADD KEY `fk_t_sewa_id_parkir` (`id_parkir`),
  ADD KEY `fk_t_sewa_id_harga` (`id_harga`),
  ADD KEY `fk_t_sewa_user_admin` (`user_admin`);

--
-- Indexes for table `t_token`
--
ALTER TABLE `t_token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_t_token_user_admin` (`user_admin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_admin`
--
ALTER TABLE `log_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m_harga`
--
ALTER TABLE `m_harga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `m_member`
--
ALTER TABLE `m_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `m_parkir`
--
ALTER TABLE `m_parkir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `m_saldo`
--
ALTER TABLE `m_saldo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `t_keuangan`
--
ALTER TABLE `t_keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `t_sewa`
--
ALTER TABLE `t_sewa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `t_token`
--
ALTER TABLE `t_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `log_admin`
--
ALTER TABLE `log_admin`
  ADD CONSTRAINT `fk_log_admin_user_admin` FOREIGN KEY (`user_admin`) REFERENCES `admin` (`username`);

--
-- Ketidakleluasaan untuk tabel `t_keuangan`
--
ALTER TABLE `t_keuangan`
  ADD CONSTRAINT `fk_t_keuangan_kode_transaksi` FOREIGN KEY (`kode_transaksi`) REFERENCES `t_sewa` (`id`),
  ADD CONSTRAINT `fk_t_keuangan_user_admin` FOREIGN KEY (`user_admin`) REFERENCES `admin` (`username`);

--
-- Ketidakleluasaan untuk tabel `t_sewa`
--
ALTER TABLE `t_sewa`
  ADD CONSTRAINT `fk_t_sewa_id_harga` FOREIGN KEY (`id_harga`) REFERENCES `m_harga` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_sewa_id_member` FOREIGN KEY (`id_member`) REFERENCES `m_member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_sewa_id_parkir` FOREIGN KEY (`id_parkir`) REFERENCES `m_parkir` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_t_sewa_user_admin` FOREIGN KEY (`user_admin`) REFERENCES `admin` (`username`);

--
-- Ketidakleluasaan untuk tabel `t_token`
--
ALTER TABLE `t_token`
  ADD CONSTRAINT `fk_t_token_user_admin` FOREIGN KEY (`user_admin`) REFERENCES `admin` (`username`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
