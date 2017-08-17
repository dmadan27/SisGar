-- Database SisGar v2
-- Update : 
-- Normalisasi di setiap tabel, penyederhanaan field2 di tabel2 tertentu,
-- penghapusan tabel m_harga

-- =============================================================

-- tabel admin
CREATE TABLE m_admin(
	username varchar(50) NOT NULL,
	password text NOT NULL,
	nama varchar(150),
	level enum('ADMIN', 'SUPERADMIN'),
	email varchar(100) UNIQUE,
	CONSTRAINT pk_admin_username PRIMARY KEY(username)
);

-- =============================================================

-- tabel log admin
CREATE TABLE t_log_admin(
	id int AUTO_INCREMENT NOT NULL,
	admin varchar(50),
	login datetime, -- waktu login
	ip varchar(50), -- alamat ip
	logout datetime, -- waktu logout
	kode_akses text, -- kode akses
	CONSTRAINT pk_log_admin_id PRIMARY KEY(id),
	CONSTRAINT fk_log_admin_admin FOREIGN KEY(admin) REFERENCES admin(username)
);

-- =============================================================

-- tabel parkir
CREATE TABLE m_parkir(
	id int AUTO_INCREMENT NOT NULL,
	no_parkir char(3) UNIQUE,
	status char(1), -- 0 : tidak tersedia, 1 : tersedia
	CONSTRAINT pk_m_parkir_id PRIMARY KEY(id)
);

-- =============================================================

-- tabel member
CREATE TABLE m_member(
	id varchar(4) NOT NULL,
	no_ktp varchar(20),
	nama varchar(150),
	no_telp varchar(20),
	alamat text,
	pekerjaan varchar(150),
	CONSTRAINT pk_m_member_id PRIMARY KEY(id)
);

-- =============================================================

-- tabel nomor polisi kendaraan
CREATE TABLE m_nopol(
	id int AUTO_INCREMENT NOT NULL,
	id_member varchar(4),
	nopol varchar(10),
	foto varchar(25),
	CONSTRAINT pk_m_nopol_id PRIMARY KEY(id),
	CONSTRAINT fk_m_nopol_id_member FOREIGN KEY(id_member) REFERENCES m_member(id)
);

-- =============================================================

-- tabel saldo total
CREATE TABLE m_saldo(
	id int AUTO_INCREMENT NOT NULL,
	tgl date,
	debit double(12,2) DEFAULT '0.00',
	kredit double(12,2) DEFAULT '0.00',
	saldo double(12,2) DEFAULT '0.00',
	CONSTRAINT pk_m_saldo_id PRIMARY KEY(id)
);

-- =============================================================

-- tabel sewa
CREATE TABLE t_sewa(
	id int AUTO_INCREMENT NOT NULL,
	id_member varchar(4),
	id_parkir int,
	id_nopol int,
	harga double(10,2),
	tgl_sewa date,
	jatuh_tempo date,
	status char(1), -- 0 : kontrak habis, 1 : masih berlaku
	admin varchar(50),
	CONSTRAINT pk_t_sewa_id PRIMARY KEY(id),
	CONSTRAINT fk_t_sewa_id_member FOREIGN KEY(id_member) REFERENCES m_member(id),
	CONSTRAINT fk_t_sewa_id_parkir FOREIGN KEY(id_parkir) REFERENCES m_parkir(id),
	CONSTRAINT fk_t_sewa_admin FOREIGN KEY(admin) REFERENCES admin(username)
);

-- =============================================================

-- tabel keuangan
CREATE TABLE t_keuangan(
	id int AUTO_INCREMENT NOT NULL,
	id_sewa int UNIQUE,
	tgl date,
	jenis char(1), -- k : keluar, m : masuk
	ket text,
	nominal double(10,2),
	-- saldo double(12,2),
	admin varchar(50),
	CONSTRAINT pk_t_keuangan_id PRIMARY KEY(id),
	CONSTRAINT fk_t_keuangan_id_sewa FOREIGN KEY(id_sewa) REFERENCES t_sewa(id),
	CONSTRAINT fk_t_keuangan_admin FOREIGN KEY(admin) REFERENCES m_admin(username)
);

-- =============================================================

-- ============================ PROCEDURE ============================

-- =============================================================

-- Procedure tambah transasksi sewa
-- mengubah status parkir, menambah ke keuangan, dan update saldo
CREATE PROCEDURE tambah_sewa(
	in id_member_param varchar(4),
	in id_parkir_param int,
	in id_nopol_param int,
	in harga_param double(10,2),
	in tgl_sewa_param date,
	in jatuh_tempo_param date,
	in status_param char(1),
	in admin_param varchar(50)
)
BEGIN
	
	DECLARE nama_member_param varchar(255);
	DECLARE saldo_param double(12,2);
	DECLARE debit_param double(12,2);
	DECLARE id_sewa_param int;
	DECLARE tgl_saldo date;

	-- tgl skrng
	-- SELECT CURRENT_DATE INTO tgl_skrng;

	-- get ket member
	SELECT UPPER(CONCAT('sewa garasi a/n' ,m.nama,' - ',n.nopol)) INTO ket_param 
		FROM m_member m
		JOIN m_nopol n ON n.id_member = m.id 
	WHERE n.id = id_nopol_param;

	-- get saldo terakhir
	SELECT saldo INTO saldo_param FROM m_saldo ORDER BY id DESC LIMIT 1;
	SELECT tgl INTO tgl_saldo FROM m_saldo ORDER BY id DESC LIMIT 1;
	-- SELECT saldo INTO saldo_param FROM m_saldo WHERE id IN (SELECT MAX(id) FROM m_saldo);

	-- get auto increment t_sewa
	SELECT `AUTO_INCREMENT` INTO id_sewa_param 
		FROM INFORMATION_SCHEMA.TABLES 
	WHERE TABLE_SCHEMA = 'garasi' AND TABLE_NAME = 't_sewa';

	-- insert t_sewa
	INSERT INTO t_sewa (
		id_member, id_parkir, id_nopol, harga, tgl_sewa, jatuh_tempo, status, admin) 
	VALUES (
		id_member_param, id_parkir_param, id_nopol_param, harga_param, tgl_sewa_param, 
		jatuh_tempo_param, status_param, admin_param);

	-- update status parkir
	UPDATE m_parkir SET status = "0" WHERE id = id_parkir_param;

	-- insert t_keuangan
	INSERT INTO t_keuangan (
		id_sewa, tgl, jenis, ket, nominal, admin) 
	VALUES (
		id_sewa_param, tgl_sewa_param, "M", ket_param, harga_param, admin_param);

	-- insert / update saldo
	IF tgl_saldo = CURRENT_DATE THEN -- lakukan update
		SELECT debit INTO debit_param FROM m_saldo WHERE tgl = CURRENT_DATE;
		UPDATE m_saldo SET debit = (debit_param+harga_param), saldo = (saldo_param+harga_param) WHERE tgl = CURRENT_DATE;
	ELSE -- lakukan insert
		INSERT INTO m_saldo (tgl, debit, kredit, saldo) VALUES (CURRENT_DATE, harga_param, 0, (saldo_param+harga_param));
	END IF;
	

END;

-- =============================================================

-- procedure edit t_sewa
-- edit ket. selain harga dapat kapan saja
CREATE PROCEDURE edit_sewa(
	in id_param int,
	in id_member_param varchar(4),
	in id_parkir_param int,
	in id_nopol_param int,
	-- in harga_param double(10,2),
	in tgl_sewa_param date,
	in jatuh_tempo_param date
)
BEGIN

	-- get data parkir sebelumnya
	SELECT id_parkir INTO id_parkir_lama FROM t_sewa WHERE id = id_param;

	-- get data tgl sebelumnya
	SELECT tgl_sewa INTO tgl_sewa_lama FROM t_sewa WHERE id = id_param;

	-- cek parkir berubah
	IF id_parkir_param != id_parkir_lama THEN -- jika ada perubahan
		-- update parkir lama jd tersedia
		UPDATE m_parkir SET status = "1" WHERE id = id_parkir_lama;
		-- update parkir baru jd tidak tersedia
		UPDATE m_parkir SET status = "0" WHERE id = id_parkir_param;
	END IF;

	-- get ket member
	SELECT UPPER(CONCAT('sewa garasi a/n' ,m.nama,' - ',n.nopol)) INTO ket_param 
		FROM m_member m
		JOIN m_nopol n ON n.id_member = m.id 
	WHERE n.id = id_nopol_param;

	-- update t_keuangan
	UPDATE t_keuangan SET tgl=tgl_sewa_param, ket=ket_param WHERE id_sewa=id_param;

	-- update t_sewa
	UPDATE t_sewa SET 
		id_member=id_member_param, id_parkir=id_parkir_param, id_nopol=id_nopol_param, 
		tgl_sewa=tgl_sewa_param, jatuh_tempo=jatuh_tempo_param 
	WHERE id=id_param;

END;

-- =============================================================

-- procedure kontrak sewa habis
CREATE PROCEDURE edit_sewa_kontrak_habis(
	in id_param int
)
BEGIN
	DECLARE id_parkir_param int;

	-- get id parkir
	SELECT id_parkir INTO id_parkir_param FROM t_sewa where id = id_param;

	-- update status sewa
	UPDATE t_sewa SET status = 0 WHERE id = id_param;

	-- update parkir
	UPDATE m_parkir SET status = "1" WHERE id = id_parkir_param;
END;

-- =============================================================

-- Procedure tambah keuangan
-- update saldo
CREATE PROCEDURE tambah_keuangan(
	in tgl_param date,
	in jenis_param char(1),
	in ket_param text,
	in nominal_param double(10,2),
	in admin_param varchar(50)
)
BEGIN
	DECLARE saldo_param double(12,2);
	DECLARE debit_param double(12,2);
	DECLARE kredit_param double(12,2);
	DECLARE tgl_saldo date;

	-- get saldo terakhir
	SELECT saldo INTO saldo_param FROM m_saldo ORDER BY id DESC LIMIT 1;
	SELECT tgl INTO tgl_saldo FROM m_saldo ORDER BY id DESC LIMIT 1;

	-- insert t_keuangan
	INSERT INTO t_keuangan (
		tgl, jenis, ket, nominal, admin) 
	VALUES (
		tgl_param, jenis_param, ket_param, nominal_param, admin_param);

	-- cek jenis keuangan
	IF tgl_saldo = CURRENT_DATE THEN -- lakukan update
		
		IF LCASE(jenis_param) = "m" THEN -- jika uang masuk
			SELECT debit INTO debit_param FROM m_saldo WHERE tgl = CURRENT_DATE;
			UPDATE m_saldo SET debit = (debit_param+nominal_param), saldo = (saldo_param+nominal_param) WHERE tgl = CURRENT_DATE;
		ELSE -- jika uang keluar
			SELECT kredit INTO kredit_param FROM m_saldo WHERE tgl = CURRENT_DATE;
			UPDATE m_saldo SET kredit = (kredit_param+nominal_param), saldo = (saldo_param-nominal_param) WHERE tgl = CURRENT_DATE;
		END IF;

	ELSE

		IF LCASE(jenis_param) = "m" THEN -- jika uang masuk
			INSERT INTO m_saldo (tgl, debit, kredit, saldo) VALUES (CURRENT_DATE, nominal_param, 0, (saldo_param+nominal_param));
		ELSE -- jika uang keluar
			INSERT INTO m_saldo (tgl, debit, kredit, saldo) VALUES (CURRENT_DATE, 0, nominal_param, (saldo_param-nominal_param));
		END IF;

	END IF;
	
END;

-- =============================================================
