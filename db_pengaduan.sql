-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2026 at 03:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pengaduan`
--

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(11) NOT NULL,
  `kode_tracking` varchar(20) NOT NULL,
  `nama_pelapor` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lokasi` text NOT NULL,
  `isi_pengaduan` text NOT NULL,
  `bukti_file` varchar(255) DEFAULT NULL,
  `status` enum('pending','proses','selesai') NOT NULL DEFAULT 'pending',
  `tgl_input` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `kode_tracking`, `nama_pelapor`, `email`, `lokasi`, `isi_pengaduan`, `bukti_file`, `status`, `tgl_input`) VALUES
(2, 'TRK-07EC77', 'ujang', 'faizfaresi8@gmail.com', 'Jl. Perjuangan, Sunyaragi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45132', 'pencurian', '1775306490_save-icon-5401.png', 'selesai', '2026-04-04 12:41:30'),
(3, 'TRK-FBC416', 'arip', 'faizalfarzei@gmail.com', 'Jl. Perjuangan, Sunyaragi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45132', 'jalan berlubang', '1775307225_Screenshot2025-06-24023953.png', 'selesai', '2026-04-04 12:53:45'),
(4, 'TRK-D56028', 'suki', 'suki@gmail.com', 'Jl. Prof. Soedarto, Tembalang, Kec. Tembalang, Kota Semarang, Jawa Tengah 50275', 'kebakaran', '1775312874_Cuplikanlayar2026-03-29102742.png', 'selesai', '2026-04-04 14:27:54'),
(5, 'TRK-13574D', 'suki', 'suki@gmail.com', 'Jl. Perjuangan No.1, Karyamulya, Kec. Kesambi, Kota Cirebon, Jawa Barat 45135', 'apa saja', '1775389896_Screenshot2025-06-24024216.png', 'selesai', '2026-04-05 11:51:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `level` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `email`, `level`, `created_at`) VALUES
(1, 'faiz', '$2y$10$kjL48nQF3z7m8.amd4T.GeO7Iq9s3CtyB.uliAFm6su5n/Y7J5mXi', 'Faiz Administrator', 'faiz@sispek.com', 'admin', '2026-04-04 06:35:54'),
(2, 'ucup', '$2y$10$H.vsHneM/mHdR4RZRG3hrepFSZgqOe6g9a7nrHhi0S7z2AGTthPnG', 'ucup surucup ganteng', 'ucup@sispek.com', 'admin', '2026-04-04 06:42:39'),
(3, 'ujang D jajang', '$2y$10$ItnZVP3m2gTFOKx.ZYWQLuGDQabAvAOFAl.fmsavRSpaAyy56LQIe', 'ujang D jajang', 'jajang@sispek.com', 'staff', '2026-04-04 08:53:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD UNIQUE KEY `kode_tracking` (`kode_tracking`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
