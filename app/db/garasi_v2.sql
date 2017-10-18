-- Database SisGar v2
-- Update : 
-- Normalisasi di setiap tabel, penyederhanaan field2 di tabel2 tertentu,
-- penghapusan tabel m_harga

-- =============================================================

-- tabel admin
CREATE TABLE admin(
	username varchar(50) NOT NULL,
	password text NOT NULL,
	nama varchar(150),
	email varchar(100) UNIQUE,
	hak_akses text,
	level enum('ADMIN', 'SUPERADMIN'),
	foto text,
	status char(1), -- 1 : aktif, 0 : tidak aktif
	CONSTRAINT pk_admin_username PRIMARY KEY(username)
);

-- =============================================================

-- tabel log admin
CREATE TABLE log_admin(
	id int AUTO_INCREMENT NOT NULL,
	admin varchar(50),
	login datetime, -- waktu login
	ip varchar(50), -- alamat ip
	-- logout datetime, -- waktu logout
	-- kode_akses text, -- kode akses
	CONSTRAINT pk_log_admin_id PRIMARY KEY(id),
	CONSTRAINT fk_log_admin_admin FOREIGN KEY(admin) REFERENCES admin(username)
);

-- =============================================================

-- tabel parkir
CREATE TABLE parkir(
	id int AUTO_INCREMENT NOT NULL,
	no_parkir char(3) UNIQUE,
	status char(1), -- 0 : tidak tersedia, 1 : tersedia
	CONSTRAINT pk_parkir_id PRIMARY KEY(id)
);

-- =============================================================

-- tabel harga
CREATE TABLE harga(
	id int AUTO_INCREMENT NOT NULL,
	jenis char(1), -- b : bulanan, t : tahunan
	harga double(10,2) DEFAULT '0.00',
	CONSTRAINT pk_harga_id PRIMARY KEY(id)
);

-- =============================================================

-- tabel member
CREATE TABLE member(
	id int AUTO_INCREMENT NOT NULL,
	no_ktp varchar(20),
	nama varchar(150),
	no_telp varchar(20),
	alamat text,
	pekerjaan varchar(150),
	CONSTRAINT pk_member_id PRIMARY KEY(id)
);

-- =============================================================

-- tabel nomor polisi kendaraan
CREATE TABLE nopol(
	id int AUTO_INCREMENT NOT NULL,
	id_member int,
	nopol varchar(10),
	foto varchar(25),
	CONSTRAINT pk_nopol_id PRIMARY KEY(id),
	CONSTRAINT fk_nopol_id_member FOREIGN KEY(id_member) REFERENCES member(id)
);

-- =============================================================

-- tabel sewa
CREATE TABLE sewa(
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
	CONSTRAINT pk_sewa_id PRIMARY KEY(id),
	CONSTRAINT fk_sewa_id_member FOREIGN KEY(id_member) REFERENCES member(id),
	CONSTRAINT fk_sewa_id_nopol FOREIGN KEY(id_nopol) REFERENCES nopol(id),
	CONSTRAINT fk_sewa_id_parkir FOREIGN KEY(id_parkir) REFERENCES parkir(id),
	CONSTRAINT fk_sewa_admin FOREIGN KEY(admin) REFERENCES admin(username)
);

-- =============================================================

-- tabel keuangan
-- transaksi selain sewa
CREATE TABLE keuangan(
	id int AUTO_INCREMENT NOT NULL,
	tgl date,
	jenis char(1), -- k : keluar, m : masuk
	ket text,
	nominal double(10,2),
	admin varchar(50),
	CONSTRAINT pk_keuangan_id PRIMARY KEY(id),
	CONSTRAINT fk_keuangan_admin FOREIGN KEY(admin) REFERENCES admin(username)
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

	-- insert sewa
	INSERT INTO sewa (
		id_member, id_parkir, id_nopol, jenis, harga, tgl_sewa, jatuh_tempo, status, admin) 
	VALUES (
		id_member_param, id_parkir_param, id_nopol_param, jenis_param, harga_param, tgl_sewa_param, 
		jatuh_tempo_param, status_param, admin_param);

	-- update status parkir
	UPDATE parkir SET status = "0" WHERE id = id_parkir_param;

END;

-- =============================================================

-- procedure edit sewa
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
	
	-- get data parkir sebelumnya
	SELECT id_parkir INTO id_parkir_lama FROM sewa WHERE id = id_param;

	-- get data tgl sebelumnya
	SELECT tgl_sewa INTO tgl_sewa_lama FROM sewa WHERE id = id_param;

	-- get data harga sebelumnya
	SELECT harga INTO harga_lama FROM sewa WHERE id = id_param;

	-- cek parkir berubah
	IF id_parkir_param != id_parkir_lama THEN -- jika ada perubahan
		-- update parkir lama jd tersedia
		UPDATE parkir SET status = "1" WHERE id = id_parkir_lama;
		-- update parkir baru jd tidak tersedia
		UPDATE parkir SET status = "0" WHERE id = id_parkir_param;
	END IF;

	-- update t_sewa
	UPDATE sewa SET 
		id_member=id_member_param, id_parkir=id_parkir_param, id_nopol=id_nopol_param, 
		jenis=jenis_param, harga=harga_param, tgl_sewa=tgl_sewa_param, jatuh_tempo=jatuh_tempo_param 
	WHERE id=id_param;
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

	-- get data id parkir
	SELECT id_parkir INTO id_parkir_param FROM sewa WHERE id = id_param;

	-- update parkir
	UPDATE parkir SET status = "1" WHERE id = id_parkir_param;

	-- hapus t_sewa
	DELETE FROM sewa WHERE id = id_param;
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
	SELECT id_parkir INTO id_parkir_param FROM sewa where id = id_param;

	-- cek status
	IF status_param = "0" THEN -- jika req. status 0 / kontrak habis
		-- update status sewa
		UPDATE sewa SET status = "0" WHERE id = id_param;

		-- update parkir
		UPDATE parkir SET status = "1" WHERE id = id_parkir_param;
	ELSE
		-- update status sewa
		UPDATE sewa SET status = "1" WHERE id = id_param;

		-- update parkir
		UPDATE parkir SET status = "0" WHERE id = id_parkir_param;
	END IF;
END;

-- =============================================================

-- ============================ VIEW ============================

-- =============================================================

-- view member
CREATE OR REPLACE VIEW v_member AS
	SELECT m.id id_member, m.no_ktp, m.nama, m.no_telp, m.alamat, m.pekerjaan, 
		GROUP_CONCAT(n.nopol) nopol
	FROM member m
	JOIN nopol n
		ON n.id_member = m.id
	GROUP BY m.id
	ORDER BY m.nama ASC;

-- =============================================================

-- view admin
CREATE OR REPLACE VIEW v_admin AS
	SELECT username, nama, email, hak_akses, level,
		(CASE WHEN (status = "1") THEN "AKTIF" ELSE "NON AKTIF" END) AS status
	FROM admin
	ORDER BY level DESC, status ASC;

-- =============================================================

-- view log_admin
CREATE OR REPLACE VIEW v_log_admin AS
	SELECT log.id, log.admin, a.nama, log.login, log.ip
	FROM log_admin log
	JOIN admin a
		ON a.username = log.admin
	ORDER BY log.id DESC;
-- =============================================================

-- view transaksi sewa
CREATE OR REPLACE VIEW v_sewa AS
	SELECT s.id id_sewa, s.id_member, m.nama, s.id_nopol, n.nopol, p.no_parkir, 
		(CASE WHEN (s.jenis = "B") THEN "BULANAN" ELSE "TAHUNAN" END) jenis,
		s.harga, s.tgl_sewa, s.jatuh_tempo,
		(CASE WHEN (s.status = "1") THEN "MASIH BERLAKU" ELSE "KONTRAK HABIS" END) status,
		s.admin, a.nama nama_admin
	FROM sewa s
	JOIN member m
		ON m.id = s.id_member
	JOIN nopol n
		ON n.id = s.id_nopol
	JOIN parkir p
		ON p.id = s.id_parkir
	JOIN admin a
		ON a.username = s.admin
	ORDER BY s.id DESC, status DESC, s.tgl_sewa DESC;
-- =============================================================

-- view keuangan
CREATE OR REPLACE VIEW v_keuangan AS
	SELECT k.id, k.tgl,
		(CASE WHEN (k.jenis = "K") THEN "UANG KELUAR" ELSE "UANG MASUK" END) jenis,
		k.ket, k.nominal, k.admin, a.nama nama_admin
	FROM keuangan k
	JOIN admin a
		ON a.username = k.admin
	ORDER BY k.id DESC;

-- =============================================================

CREATE OR REPLACE VIEW v_saldo AS
	SELECT
		(SELECT COALESCE(SUM(harga),0) FROM sewa) uang_sewa,
	    (SELECT COALESCE(SUM(nominal),0) FROM keuangan WHERE jenis='K') uang_keluar,
		(SELECT COALESCE(SUM(nominal),0) FROM keuangan WHERE jenis='M') uang_masuk;
