-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Des 2021 pada 05.52
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `donat`
--
CREATE DATABASE IF NOT EXISTS `donat` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `donat`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` char(5) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `no_telp` varchar(13) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `level` varchar(10) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `id_session` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama`, `alamat`, `no_telp`, `username`, `password`, `level`, `status`, `id_session`) VALUES
('P0001', 'admin', 'Jl. Udowo raya, Kel. Bulu Lor, Kota. Semarang  ', '089525625179', 'admin', '21232f297a57a5a74389', 'admin', 'Y', 'aqcnk'),
('P0002', 'user', 'Jalan Dworowati Raya, Semarang Barat.\r\n\r\n', '089521456322', 'user', 'ee11cbb19052e40b07aa', 'user', 'Y', 'vd32q');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tab_alternatif`
--

CREATE TABLE `tab_alternatif` (
  `id_alternatif` char(5) NOT NULL,
  `alternatif` varchar(25) NOT NULL,
  `alamat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tab_alternatif`
--

INSERT INTO `tab_alternatif` (`id_alternatif`, `alternatif`, `alamat`) VALUES
('A01', 'Heri Kusnanto', 'Jl. Kebonharjo 2, Semarang Utara'),
('A02', 'Sofiatun', 'Jl. Bangetayu Kwaron 1, Genuk'),
('A03', 'Medi Nur Faizin', 'Jl. Kanal Sari Raya, Semarang Timur'),
('A04', 'Hasan Ahmad', 'Jl. Imam Bonjol 3, Semarang Utara'),
('A05', 'Muhsin', 'Jl. Semarang-Demak Raya, Sayung'),
('A06', 'Yudistira Maulana', 'Jl. Banowati 3, Semarang Utara'),
('A07', 'Margiati', 'Jl. Kaliasih, Semarang Utara'),
('A08', 'Sri Turiah', 'Jl. Bimo Raya 2, Mijen'),
('A09', 'Rizal Rafli', 'Jl. Graha Beringin mas, Ngaliyan'),
('A10', 'Sri  wahyuni', 'Jl. Medoho Raya 3, Gayamsari');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tab_kriteria`
--

CREATE TABLE `tab_kriteria` (
  `id_kriteria` char(5) NOT NULL,
  `kriteria` varchar(20) NOT NULL,
  `atribut` char(10) NOT NULL,
  `bobot` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tab_kriteria`
--

INSERT INTO `tab_kriteria` (`id_kriteria`, `kriteria`, `atribut`, `bobot`) VALUES
('C1', 'Kejujuran', 'benefit', 0.2),
('C2', 'Loyalitas', 'benefit', 0.1),
('C3', 'Tanggung Jawab', 'benefit', 0.2),
('C4', 'Absensi', 'cost', 0.15),
('C5', 'Pinalty', 'cost', 0.1),
('C6', 'Target Harian', 'benefit', 0.25);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tab_nilai`
--

CREATE TABLE `tab_nilai` (
  `id_alternatif` char(5) NOT NULL,
  `id_kriteria` char(5) NOT NULL,
  `nilai` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tab_nilai`
--

INSERT INTO `tab_nilai` (`id_alternatif`, `id_kriteria`, `nilai`) VALUES
('A01', 'C1', 4),
('A01', 'C2', 3),
('A01', 'C3', 3),
('A01', 'C4', 1),
('A01', 'C5', 2),
('A01', 'C6', 4),
('A02', 'C1', 5),
('A02', 'C2', 4),
('A02', 'C3', 3),
('A02', 'C4', 3),
('A02', 'C5', 1),
('A02', 'C6', 3),
('A03', 'C1', 4),
('A03', 'C2', 2),
('A03', 'C3', 2),
('A03', 'C4', 2),
('A03', 'C5', 2),
('A03', 'C6', 5),
('A04', 'C1', 5),
('A04', 'C2', 3),
('A04', 'C3', 4),
('A04', 'C4', 3),
('A04', 'C5', 3),
('A04', 'C6', 4),
('A05', 'C1', 5),
('A05', 'C2', 3),
('A05', 'C3', 2),
('A05', 'C4', 1),
('A05', 'C5', 3),
('A05', 'C6', 4),
('A06', 'C1', 2),
('A06', 'C2', 3),
('A06', 'C3', 4),
('A06', 'C4', 1),
('A06', 'C5', 1),
('A06', 'C6', 5),
('A07', 'C1', 5),
('A07', 'C2', 3),
('A07', 'C3', 5),
('A07', 'C4', 1),
('A07', 'C5', 1),
('A07', 'C6', 5),
('A08', 'C1', 2),
('A08', 'C2', 3),
('A08', 'C3', 5),
('A08', 'C4', 2),
('A08', 'C5', 2),
('A08', 'C6', 2),
('A09', 'C1', 5),
('A09', 'C2', 4),
('A09', 'C3', 4),
('A09', 'C4', 4),
('A09', 'C5', 1),
('A09', 'C6', 4),
('A10', 'C1', 5),
('A10', 'C2', 4),
('A10', 'C3', 5),
('A10', 'C4', 1),
('A10', 'C5', 2),
('A10', 'C6', 4);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`);

--
-- Indeks untuk tabel `tab_alternatif`
--
ALTER TABLE `tab_alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indeks untuk tabel `tab_kriteria`
--
ALTER TABLE `tab_kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indeks untuk tabel `tab_nilai`
--
ALTER TABLE `tab_nilai`
  ADD KEY `FK_tab_nilai_tab_alternatif` (`id_alternatif`) USING BTREE,
  ADD KEY `FK_tab_nilai_tab_kriteria` (`id_kriteria`) USING BTREE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
