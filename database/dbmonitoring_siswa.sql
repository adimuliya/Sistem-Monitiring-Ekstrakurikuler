-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Feb 2024 pada 07.06
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbmonitoring_siswa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_absensi`
--

CREATE TABLE `tb_absensi` (
  `id_absensi` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_ekstra` int(11) DEFAULT NULL,
  `hari` date DEFAULT NULL,
  `batas_absensi` datetime DEFAULT NULL,
  `catatan_jurnal` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_absensi`
--

INSERT INTO `tb_absensi` (`id_absensi`, `id_user`, `id_ekstra`, `hari`, `batas_absensi`, `catatan_jurnal`) VALUES
(51, 27, 19, '2024-02-27', '2024-02-29 11:28:00', 'Menggiring Bola');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_absensi_siswa`
--

CREATE TABLE `tb_absensi_siswa` (
  `id_absensi_siswa` int(11) NOT NULL,
  `id_absensi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_ekstra` int(11) NOT NULL,
  `waktu_absensi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `jenis_absensi` varchar(25) NOT NULL,
  `bukti` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_absensi_siswa`
--

INSERT INTO `tb_absensi_siswa` (`id_absensi_siswa`, `id_absensi`, `id_user`, `id_siswa`, `id_ekstra`, `waktu_absensi`, `jenis_absensi`, `bukti`) VALUES
(33, 47, 35, 13, 19, '2024-02-27 01:19:58', 'alpha', ''),
(49, 51, 35, 13, 19, '2024-02-27 04:29:23', 'hadir', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ekstrakurikuler`
--

CREATE TABLE `tb_ekstrakurikuler` (
  `id_ekstra` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_ekstra` varchar(25) NOT NULL,
  `nama_pembimbing` varchar(255) NOT NULL,
  `jadwal_hari` varchar(255) NOT NULL,
  `jadwal_jam` time NOT NULL DEFAULT current_timestamp(),
  `deskripsi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_ekstrakurikuler`
--

INSERT INTO `tb_ekstrakurikuler` (`id_ekstra`, `id_user`, `nama_ekstra`, `nama_pembimbing`, `jadwal_hari`, `jadwal_jam`, `deskripsi`) VALUES
(13, 0, 'Rohis', 'Afghan', 'Jumat', '14:00:00', ''),
(15, 20, 'Pramuka', 'Dimas Aji Wibowo', 'sabtu', '15:00:00', ''),
(16, 22, 'Catur', 'Aji wibowo', 'selasa', '13:30:00', ''),
(17, 24, 'Voli', 'Ahmad Sulthon', 'Rabu', '15:00:00', ''),
(19, 27, 'sepak bola', 'Adi Muliya', 'sabtu', '15:10:00', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ekstra_yang_diikuti`
--

CREATE TABLE `tb_ekstra_yang_diikuti` (
  `id_ekstra_yang_diikuti` int(25) NOT NULL,
  `id_user` int(25) NOT NULL,
  `id_siswa` int(25) NOT NULL,
  `id_ekstra` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_ekstra_yang_diikuti`
--

INSERT INTO `tb_ekstra_yang_diikuti` (`id_ekstra_yang_diikuti`, `id_user`, `id_siswa`, `id_ekstra`) VALUES
(38, 11, 6, '17'),
(39, 11, 6, '19'),
(40, 3, 2, '19'),
(42, 35, 12, '19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `id_siswa` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_siswa` varchar(25) NOT NULL,
  `nis` int(11) NOT NULL,
  `kelas` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_siswa`
--

INSERT INTO `tb_siswa` (`id_siswa`, `id_user`, `nama_siswa`, `nis`, `kelas`) VALUES
(2, 3, 'Alamsyah', 2121, 'xii-rpl-a'),
(5, 10, 'ahdbshs', 354, 'xi tpm b'),
(6, 11, 'alvian', 3232, 'xii tei b'),
(7, 29, 'Bagus Adi', 1213, 'xi tlas b'),
(8, 30, 'ambuaniabah', 9877, 'xitoic'),
(9, 31, 'Abidin', 9374, 'xi tbsm c'),
(10, 32, 'jdbajs', 1523, 'xii tptu c'),
(11, 33, 'Eka Mandala', 6231, 'xii rpl a'),
(12, 34, 'eko', 4352, 'xii rpl a'),
(13, 35, 'eki', 4352, 'xii tbsm b');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(12) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`) VALUES
(15, 'siandi', 'siandi123', 'kesiswaan'),
(16, 'afif', 'afif123', 'kesiswaan'),
(17, 'wildan', 'wildan123', 'kesiswaan'),
(18, 'hjasdjhas', 'asmnndnma', 'kesiswaan'),
(23, 'masalan', 'alan123', 'pembimbing'),
(24, 'masulton', 'sulton123', 'pembimbing'),
(25, 'masaji', 'aji123', 'pembimbing'),
(27, 'masadi', 'adi123', 'pembimbing'),
(31, 'siabi', '$2y$10$Dvp/1xz5sMQ69daq6sj2jeQYSk/EBkTcfxDp/kM3JPmRTmvsUIDHe', 'Siswa'),
(32, 'jandkaj', '$2y$10$78FsLN1Xdr7nCQc8REttvOwylsI.8XoIoLeCe3Lf9CsUJfKSUopsS', 'Siswa'),
(33, 'sieka', '$2y$10$Pbr2YyEh.wF3K7c.RxWwDuJKvjq3rYvvIeW4297OjXvzm0vAYTAxK', 'Siswa'),
(34, 'sieko', '$2y$10$NeZYO5qWhvwnFwAFmp4fRuoVA1w9uF1vvYHEqK7JGYZ8TIPxut9nC', 'siswa'),
(35, 'sieki', 'eki123', 'siswa');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_absensi`
--
ALTER TABLE `tb_absensi`
  ADD PRIMARY KEY (`id_absensi`);

--
-- Indeks untuk tabel `tb_absensi_siswa`
--
ALTER TABLE `tb_absensi_siswa`
  ADD PRIMARY KEY (`id_absensi_siswa`);

--
-- Indeks untuk tabel `tb_ekstrakurikuler`
--
ALTER TABLE `tb_ekstrakurikuler`
  ADD PRIMARY KEY (`id_ekstra`);

--
-- Indeks untuk tabel `tb_ekstra_yang_diikuti`
--
ALTER TABLE `tb_ekstra_yang_diikuti`
  ADD PRIMARY KEY (`id_ekstra_yang_diikuti`);

--
-- Indeks untuk tabel `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_absensi`
--
ALTER TABLE `tb_absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `tb_absensi_siswa`
--
ALTER TABLE `tb_absensi_siswa`
  MODIFY `id_absensi_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `tb_ekstrakurikuler`
--
ALTER TABLE `tb_ekstrakurikuler`
  MODIFY `id_ekstra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `tb_ekstra_yang_diikuti`
--
ALTER TABLE `tb_ekstra_yang_diikuti`
  MODIFY `id_ekstra_yang_diikuti` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `tb_siswa`
--
ALTER TABLE `tb_siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
