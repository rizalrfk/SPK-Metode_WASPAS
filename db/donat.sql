-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 20 Okt 2021 pada 06.36
-- Versi Server: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `id_alternatif` char(11) NOT NULL,
  `alternatif` varchar(50) NOT NULL,
  `c1` varchar(15) NOT NULL,
  `c2` varchar(15) NOT NULL,
  `c3` varchar(15) NOT NULL,
  `c4` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id_alternatif`, `alternatif`, `c1`, `c2`, `c3`, `c4`) VALUES
('A1', 'Cakra Kembar', '179000', '1', '25', '1.50'),
('A2', 'Segitiga Biru', '170000', '2', '25', '4.00'),
('A3', 'Lencana Merah', '148950', '4', '25', '2.50'),
('A4', 'Kompas', '189000', '6', '25', '5.00'),
('A5', 'Kunci Biru', '189000', '3', '25', '6.00'),
('A6', 'Sania', '180000', '5', '25', '4.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` char(10) NOT NULL,
  `kriteria` varchar(50) NOT NULL,
  `atribut` varchar(20) NOT NULL,
  `bobot` decimal(20,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `kriteria`, `atribut`, `bobot`) VALUES
('CR001', 'Harga', 'cost', '0.26'),
('CR002', 'Kualitas', 'Benefit', '0.30'),
('CR003', 'Berat Bersih', 'benefit', '0.23'),
('CR004', 'Lokasi', 'cost', '0.22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` char(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `level` varchar(10) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `id_session` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama`, `alamat`, `no_telp`, `username`, `password`, `level`, `status`, `id_session`) VALUES
('P0001', 'admin', 'Jl. Udowo raya, Kel. Bulu Lor, Kota. Semarang  ', '089525625179', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'Y', 'm6r7pornkotpj9n7uh2hfedlr5');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`);


--
-- Metadata
--
USE `phpmyadmin`;

--
-- Metadata for alternatif
--

--
-- Metadata for kriteria
--

--
-- Metadata for pegawai
--

--
-- Metadata for donat
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
