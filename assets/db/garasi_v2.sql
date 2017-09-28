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
	email varchar(100) UNIQUE,
	hak_akses text,
	level enum('ADMIN', 'SUPERADMIN'),
	status char(1), -- 1 : aktif, 0 : tidak aktif
	CONSTRAINT pk_admin_username PRIMARY KEY(username)
);

-- =============================================================

-- tabel log admin
CREATE TABLE t_log_admin(
	id int AUTO_INCREMENT NOT NULL,
	admin varchar(50),
	login datetime, -- waktu login
	ip varchar(50), -- alamat ip
	-- logout datetime, -- waktu logout
	-- kode_akses text, -- kode akses
	CONSTRAINT pk_log_admin_id PRIMARY KEY(id),
	CONSTRAINT fk_log_admin_admin FOREIGN KEY(admin) REFERENCES m_admin(username)
);

-- =============================================================

-- tabel parkir
CREATE TABLE m_parkir(
	id int AUTO_INCREMENT NOT NULL,
	no_parkir char(3) UNIQUE,
	status char(1), -- not_available (n) : tidak tersedia, available (a) : tersedia
	CONSTRAINT pk_m_parkir_id PRIMARY KEY(id)
);

-- =============================================================

-- tabel harga
CREATE TABLE m_harga(
	id int AUTO_INCREMENT NOT NULL,
	jenis char(1), -- b : bulanan, t : tahunan
	harga double(10,2) DEFAULT '0.00',
	CONSTRAINT pk_m_harga_id PRIMARY KEY(id)
);

-- =============================================================

-- tabel member
CREATE TABLE m_member(
	id int AUTO_INCREMENT NOT NULL,
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
	id_member int,
	nopol varchar(10),
	foto varchar(25),
	CONSTRAINT pk_m_nopol_id PRIMARY KEY(id),
	CONSTRAINT fk_m_nopol_id_member FOREIGN KEY(id_member) REFERENCES m_member(id)
);

-- =============================================================

-- tabel saldo total
CREATE TABLE m_saldo(
	id int AUTO_INCREMENT NOT NULL,
	saldo double(12,2) DEFAULT '0.00',
	CONSTRAINT pk_m_saldo_id PRIMARY KEY(id)
);

-- =============================================================

-- tabel sewa
CREATE TABLE t_sewa(
	id int AUTO_INCREMENT NOT NULL,
	id_member int,
	id_parkir int,
	id_nopol int,
	jenis char(1), -- b : bulanan, t : tahunan
	harga double(10,2),
	tgl_sewa date,
	jatuh_tempo date,
	status char(1), -- 0 : kontrak habis, 1 : masih berlaku
	admin varchar(50),
	CONSTRAINT pk_t_sewa_id PRIMARY KEY(id),
	CONSTRAINT fk_t_sewa_id_member FOREIGN KEY(id_member) REFERENCES m_member(id),
	CONSTRAINT fk_t_sewa_id_nopol FOREIGN KEY(id_nopol) REFERENCES m_nopol(id),
	CONSTRAINT fk_t_sewa_id_parkir FOREIGN KEY(id_parkir) REFERENCES m_parkir(id),
	CONSTRAINT fk_t_sewa_admin FOREIGN KEY(admin) REFERENCES m_admin(username)
);

-- =============================================================

-- tabel keuangan
-- transaksi selain sewa
CREATE TABLE t_keuangan(
	id int AUTO_INCREMENT NOT NULL,
	-- id_sewa int UNIQUE,
	tgl date,
	jenis char(1), -- k : keluar, m : masuk
	ket text,
	nominal double(10,2),
	admin varchar(50),
	CONSTRAINT pk_t_keuangan_id PRIMARY KEY(id),
	-- CONSTRAINT fk_t_keuangan_id_sewa FOREIGN KEY(id_sewa) REFERENCES t_sewa(id),
	CONSTRAINT fk_t_keuangan_admin FOREIGN KEY(admin) REFERENCES m_admin(username)
);

-- =============================================================

-- ============================ PROCEDURE ============================

-- =============================================================

-- Procedure tambah transasksi sewa
-- mengubah status parkir, menambah ke keuangan, dan update saldo
CREATE PROCEDURE tambah_sewa(
	in id_member_param int,
	in id_parkir_param int,
	in id_nopol_param int,
	in jenis_param char(1),
	in harga_param double(10,2),
	in tgl_sewa_param date,
	in jatuh_tempo_param date,
	in status_param char(1),
	in admin_param varchar(50)
)
BEGIN
	
	DECLARE saldo_param double(12,2);

	-- get saldo terakhir
	SELECT saldo INTO saldo_param FROM m_saldo;

	-- insert t_sewa
	INSERT INTO t_sewa (
		id_member, id_parkir, id_nopol, jenis, harga, tgl_sewa, jatuh_tempo, status, admin) 
	VALUES (
		id_member_param, id_parkir_param, id_nopol_param, jenis_param, harga_param, tgl_sewa_param, 
		jatuh_tempo_param, status_param, admin_param);

	-- update status parkir
	UPDATE m_parkir SET status = "N" WHERE id = id_parkir_param;

	-- update saldo
	UPDATE m_saldo SET saldo = (saldo_param+harga_param);
END;

-- =============================================================

-- procedure edit t_sewa
-- edit data sewa, jika ada perubahan di harga maka update saldo, 
-- jika tidak do nothing
CREATE PROCEDURE edit_sewa(
	in id_param int,
	in id_member_param int,
	in id_parkir_param int,
	in id_nopol_param int,
	in jenis_param char(1),
	in harga_param double(10,2),
	in tgl_sewa_param date,
	in jatuh_tempo_param date
)
BEGIN
	DECLARE id_parkir_lama int;
	DECLARE tgl_sewa_lama date;
	DECLARE harga_lama double(10,2);
	DECLARE saldo_param double(10,2);

	-- get data parkir sebelumnya
	SELECT id_parkir INTO id_parkir_lama FROM t_sewa WHERE id = id_param;

	-- get data tgl sebelumnya
	SELECT tgl_sewa INTO tgl_sewa_lama FROM t_sewa WHERE id = id_param;

	-- get data harga sebelumnya
	SELECT harga INTO harga_lama FROM t_sewa WHERE id = id_param;

	-- get data saldo terakhir
	SELECT saldo INTO saldo_param FROM m_saldo;

	-- cek parkir berubah
	IF id_parkir_param != id_parkir_lama THEN -- jika ada perubahan
		-- update parkir lama jd tersedia
		UPDATE m_parkir SET status = "A" WHERE id = id_parkir_lama;
		-- update parkir baru jd tidak tersedia
		UPDATE m_parkir SET status = "N" WHERE id = id_parkir_param;
	END IF;

	-- update t_sewa
	UPDATE t_sewa SET 
		id_member=id_member_param, id_parkir=id_parkir_param, id_nopol=id_nopol_param, 
		jenis=jenis_param, harga=harga_param, tgl_sewa=tgl_sewa_param, jatuh_tempo=jatuh_tempo_param 
	WHERE id=id_param;

	-- cek perubahan harga
	IF harga_lama != harga_param THEN -- jika ada perubahan
		-- update saldo
		-- saldo = saldo terakhir - harga lama + harga baru
		UPDATE m_saldo SET saldo = ((saldo_param-harga_lama)+harga_param);
	END IF;
END;

-- =============================================================

-- procedure hapus t_sewa
-- hapus data jika memang data tidak pernah ada
-- update parkir dan saldo
CREATE PROCEDURE hapus_sewa(
	in id_param int
)
BEGIN
	DECLARE id_parkir_param int;
	DECLARE harga_param double(10,2);
	DECLARE saldo_param int;

	-- get data id parkir
	SELECT id_parkir INTO id_parkir_param FROM t_sewa WHERE id = id_param;

	-- get data harga
	SELECT harga INTO harga_param FROM t_sewa WHERE id = id_param;

	-- get data saldo
	SELECT saldo INTO saldo_param FROM m_saldo;

	-- update parkir
	UPDATE m_parkir SET status = "A" WHERE id = id_parkir_param;

	-- update saldo
	UPDATE m_saldo SET saldo = (saldo_param-harga_param);

	-- hapus t_sewa
	DELETE FROM t_sewa WHERE id = id_param;
END;

-- =============================================================

-- procedure kontrak sewa habis
CREATE PROCEDURE edit_status_kontrak_sewa(
	in id_param int,
	in status_param char(1)
)
BEGIN
	DECLARE id_parkir_param int;

	-- get id parkir
	SELECT id_parkir INTO id_parkir_param FROM t_sewa where id = id_param;

	-- cek status
	IF status_param = "0" THEN -- jika req. status 0 / kontrak habis
		-- update status sewa
		UPDATE t_sewa SET status = "0" WHERE id = id_param;

		-- update parkir
		UPDATE m_parkir SET status = "A" WHERE id = id_parkir_param;
	ELSE
		-- update status sewa
		UPDATE t_sewa SET status = "1" WHERE id = id_param;

		-- update parkir
		UPDATE m_parkir SET status = "N" WHERE id = id_parkir_param;
	END IF;
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

	-- get saldo terakhir
	SELECT saldo INTO saldo_param FROM m_saldo;

	-- insert t_keuangan
	INSERT INTO t_keuangan (
		tgl, jenis, ket, nominal, admin) 
	VALUES (
		tgl_param, jenis_param, ket_param, nominal_param, admin_param);

	-- cek jenis keuangan
	IF jenis_param = "M" THEN -- jika uang masuk
		-- update saldo, saldo = saldo + nominal
		UPDATE m_saldo SET saldo = (saldo_param+nominal_param);
	ELSE -- jika uang keluar
		-- update saldo, saldo = saldo - nominal
		UPDATE m_saldo SET saldo = (saldo_param-nominal_param);
	END IF;
END;

-- =============================================================

-- procedure edit keuangan
-- update saldo
CREATE PROCEDURE edit_keuangan(
	in id_param int,
	in tgl_param date,
	in jenis_param char(1),
	in ket_param text,
	in nominal_param double(10,2)
)
BEGIN
	DECLARE saldo_param double(12,2);
	DECLARE jenis_lama char(1);
	DECLARE nominal_lama double(10,2);

	-- get saldo terakhir
	SELECT saldo INTO saldo_param FROM m_saldo;

	-- get data jenis
	SELECT jenis INTO jenis_lama FROM t_keuangan WHERE id = id_param;

	-- get data nominal
	SELECT nominal INTO nominal_lama FROM t_keuangan WHERE id = id_param;

	-- update t_keuangan
	UPDATE t_keuangan SET 
		tgl=tgl_param, jenis=jenis_param, ket=ket_param, nominal=nominal_param
	WHERE id = id_param;

	-- cek perubahan jenis
	IF jenis_param != jenis_lama THEN -- jika ada perubahan
		-- cek masuk / keluar
		IF jenis_param = "K" THEN -- jika berubah jadi keluar
			-- update saldo, saldo = (saldo-nominal lama)+nominal baru
			UPDATE m_saldo SET saldo = ((saldo_param-nominal_lama)+nominal_param);
		ELSE -- jika berubah jadi masuk
			-- update saldo, saldo = (saldo+nominal lama)-nominal baru
			UPDATE m_saldo SET saldo = ((saldo_param+nominal_lama)-nominal_param);
		END IF;
	-- cek perubahan nominal
	ELSE 
		IF nominal_param != nominal_lama THEN -- jika ada perubahan
			-- cek masuk / keluar
			IF jenis_param = "K" THEN -- jika keluar
				-- update saldo, saldo = (saldo-nominal lama)+nominal baru
				UPDATE m_saldo SET saldo = ((saldo_param-nominal_lama)+nominal_param);
			ELSE -- jika masuk
				-- update saldo, saldo = (saldo+nominal lama)-nominal baru
				UPDATE m_saldo SET saldo = ((saldo_param+nominal_lama)-nominal_param);
			END IF;
		END IF;
	END IF;
END;

-- =============================================================

-- procedure hapus keuangan
-- hapus data jika memang data tidak pernah ada
-- update saldo
CREATE PROCEDURE hapus_keuangan(
	in id_param int
)
BEGIN
	DECLARE saldo_param double(12,2);
	DECLARE nominal_lama double(10,2);

	-- get saldo terakhir
	SELECT saldo INTO saldo_param FROM m_saldo;

	-- get data nominal
	SELECT nominal INTO nominal_lama FROM t_keuangan WHERE id = id_param;

	-- update saldo
	UPDATE m_saldo SET saldo = (saldo_param-nominal_lama);

	-- hapus t_keuangan
	DELETE FROM t_keuangan WHERE id = id_param;
END;

-- =============================================================

-- ============================ VIEW ============================

-- =============================================================

-- view member
CREATE OR REPLACE VIEW v_member AS
	SELECT m.id id_member, m.no_ktp, m.nama, m.no_telp, m.alamat, 
		m.pekerjaan, n.id id_nopol, n.nopol, n.foto
	FROM m_member m
	JOIN m_nopol n
		ON n.id_member = m.id
	ORDER BY m.nama ASC

-- =============================================================

-- view admin
CREATE OR REPLACE VIEW v_admin AS
	SELECT username, nama, email, hak_akses, level,
		(CASE WHEN (status = "1") THEN "AKTIF" ELSE "NON AKTIF" END) AS status
	FROM m_admin
	ORDER BY level DESC, status ASC

-- =============================================================

-- view log_admin
CREATE OR REPLACE VIEW v_log_admin AS
	SELECT log.id, log.admin, a.nama, log.login, log.ip
	FROM t_log_admin log
	JOIN m_admin a
		ON a.username = log.admin
	ORDER BY log.id DESC
-- =============================================================

-- view transaksi sewa
CREATE OR REPLACE VIEW v_sewa AS
	SELECT s.id id_sewa, m.nama, p.no_parkir, 
		(CASE WHEN (s.jenis = "B") THEN "BULANAN" ELSE "TAHUNAN" END) jenis,
		s.harga, s.tgl_sewa, s.jatuh_tempo,
		(CASE WHEN (s.status = "1") THEN "MASIH BERLAKU" ELSE "KONTRAK HABIS" END) status,
		s.admin, a.nama nama_admin
	FROM t_sewa s
	JOIN v_member m
		ON m.id_member = s.id_member
	JOIN m_parkir p
		ON p.id = s.id_parkir
	JOIN m_admin a
		ON a.username = s.admin
	ORDER BY s.id DESC, status DESC, s.tgl_sewa DESC
-- =============================================================

-- view keuangan
CREATE OR REPLACE VIEW v_keuangan AS
	SELECT k.id, k.tgl,
		(CASE WHEN (k.jenis = "K") THEN "UANG KELUAR" ELSE "UANG MASUK" END) jenis,
		k.ket, k.admin, a.nama nama_admin
	FROM t_keuangan k
	JOIN m_admin a
		ON a.username = k.admin
	ORDER BY k.id DESC

-- =============================================================
