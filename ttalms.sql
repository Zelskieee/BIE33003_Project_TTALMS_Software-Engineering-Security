-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2024 at 10:41 PM
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
-- Database: `ttalms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `AdminEmail` varchar(120) DEFAULT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `otp` int(6) DEFAULT NULL,
  `verify_at` date DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `FullName`, `AdminEmail`, `UserName`, `Password`, `otp`, `verify_at`, `updationDate`) VALUES
(1, 'UTHM Admin', 'chojjaarif2002@gmail.com', 'admin', '1E3AVbVNDmn03+DSsLL07Q==', 0, '2024-05-30', '2024-05-30 19:28:52');

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `AuthorName` varchar(159) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `AuthorName`, `creationDate`, `UpdationDate`) VALUES
(16, 'ARIF AZINUDDIN', '2022-04-20 08:09:03', '2024-04-15 17:04:32'),
(17, 'Antonio Calcara', '2022-04-20 08:35:56', NULL),
(18, 'Andrea Monti', '2022-04-20 08:36:33', NULL),
(19, 'JUSTIN SEITZ', '2022-04-20 08:36:45', '2022-06-14 16:35:04'),
(20, 'Chuan-Ku Wu', '2022-04-20 08:36:57', NULL),
(21, 'Tarek M. Khalil', '2022-04-20 08:37:06', NULL),
(22, 'Mark Dodgson', '2022-04-20 08:37:20', NULL),
(26, 'MUKHTAR', '2024-05-07 23:04:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `BookName` varchar(255) DEFAULT NULL,
  `CatId` int(11) DEFAULT NULL,
  `AuthorId` int(11) DEFAULT NULL,
  `ISBNNumber` varchar(25) DEFAULT NULL,
  `BookPrice` decimal(10,2) DEFAULT NULL,
  `bookImage` varchar(250) NOT NULL,
  `isIssued` int(1) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `BookName`, `CatId`, `AuthorId`, `ISBNNumber`, `BookPrice`, `bookImage`, `isIssued`, `RegDate`, `UpdationDate`) VALUES
(12, 'Cyber Security: Analytics, Technology and Automation', 13, 16, '9783319183022', 347.22, '6806aa2067f11e54ae4e5b7738b9ade4.jpg', 1, '2022-04-20 08:33:45', '2024-05-18 07:15:13'),
(13, 'Emerging Security Technologies and EU Governance (Action, Practices and Processes)', 12, 17, '9780367368814', 556.37, '1a197aaed47d2fbc1592cd3596d7b4ef.jpg', 1, '2022-04-20 08:39:01', '2024-05-21 12:03:47'),
(14, 'SECURITY IN THE NEW WORLD ORDER (GOVERNMENT AND THE TECHNOLOGY OF INFORMATION)', 11, 18, '9780367809713', 162.23, '558f3c1a61fef69ab8e3f337db536ece.png', 0, '2022-05-10 12:13:36', '2024-05-21 12:03:07'),
(15, 'Black Hat Python (Python Programming for Hackers and Pentesters)', 12, 19, '978-1718501126', 102.14, 'c1a259237cca5f1769171475daeda20a.jpg', 0, '2022-05-17 03:23:52', '2024-05-30 20:40:21'),
(16, 'Internet of Things Security (Adventures and Security Measures)', 11, 20, '978-981-16-1372-2', 473.56, 'c7773fdae421a32383c61583229428d6.png', 1, '2022-05-17 03:28:37', '2024-05-08 00:08:25'),
(17, 'Management Of Technology (The Key Of Competitiveness and Website Creation)', 11, 21, '9780073661490', 321.76, '3762fc993b3651a8b541f400130ef8b0.jpg', 0, '2022-05-17 03:32:33', '2024-05-30 20:40:33'),
(22, 'A NEW APPROACH TO DESIGN', 10, 26, '12345679', 250.00, 'c99d1b698cc92514c1b65be4406b0c01.jpg', 0, '2024-05-07 23:07:57', '2024-05-30 20:40:29');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `CategoryName` varchar(150) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `CategoryName`, `Status`, `CreationDate`, `UpdationDate`) VALUES
(10, 'Technology', 1, '2022-04-20 07:59:18', '2024-04-15 16:38:19'),
(11, 'Management', 1, '2022-04-20 07:59:28', '0000-00-00 00:00:00'),
(12, 'Programming', 1, '2022-04-20 07:59:39', '0000-00-00 00:00:00'),
(13, 'Information Security', 1, '2022-04-20 07:59:51', '0000-00-00 00:00:00'),
(17, 'EDUCATION', 1, '2024-05-07 23:05:16', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `issuedbookdetails`
--

CREATE TABLE `issuedbookdetails` (
  `id` int(11) NOT NULL,
  `BookId` int(11) DEFAULT NULL,
  `StudentID` varchar(150) DEFAULT NULL,
  `IssuesDate` timestamp NULL DEFAULT current_timestamp(),
  `ReturnDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `RetrunStatus` int(1) DEFAULT NULL,
  `fine` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `issuedbookdetails`
--

INSERT INTO `issuedbookdetails` (`id`, `BookId`, `StudentID`, `IssuesDate`, `ReturnDate`, `RetrunStatus`, `fine`) VALUES
(33, 14, 'DI200081', '2023-01-06 13:54:48', '2023-01-06 13:56:06', 1, 1),
(34, 12, 'DI200081', '2023-01-06 13:55:33', '2023-01-06 13:56:00', 1, 0),
(35, 22, 'AI210029', '2024-05-07 23:17:41', '2024-05-07 23:19:59', 1, 0),
(36, 22, 'AI210029', '2024-05-07 23:20:48', '2024-05-07 23:38:24', 1, 10),
(37, 22, 'AI210029', '2024-05-07 23:46:01', NULL, NULL, NULL),
(38, 16, 'AI210029', '2024-05-08 00:08:25', NULL, NULL, NULL),
(39, 14, 'AI210029', '2024-05-08 00:10:59', '2024-05-21 12:03:07', 1, 0),
(40, 17, 'AI210029', '2024-05-18 07:09:11', '2024-05-18 07:14:11', 1, 10),
(41, 12, 'AI210125', '2024-05-18 07:15:13', NULL, NULL, NULL),
(42, 17, 'AI210028', '2024-05-21 11:55:48', NULL, NULL, NULL),
(43, 13, 'AI210028', '2024-05-21 11:56:37', '2024-05-21 12:03:00', 1, 0),
(44, 15, 'AI210029', '2024-05-21 11:59:04', '2024-05-21 12:02:31', 1, 100),
(45, 13, 'AI210029', '2024-05-21 12:03:47', NULL, NULL, NULL),
(46, 15, 'AI210029', '2024-05-21 12:04:32', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `FullName` varchar(120) DEFAULT NULL,
  `EmailId` varchar(120) DEFAULT NULL,
  `MobileNumber` char(11) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `matricNo` varchar(8) NOT NULL,
  `icNo` varchar(15) NOT NULL,
  `is_verified` int(1) NOT NULL DEFAULT 0,
  `otp` int(6) DEFAULT NULL,
  `verified_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`FullName`, `EmailId`, `MobileNumber`, `Password`, `Status`, `RegDate`, `UpdationDate`, `matricNo`, `icNo`, `is_verified`, `otp`, `verified_at`) VALUES
('MUKHTAR SANI', 'mukhtarsani20@gmail.com', '09864567892', 'sC1aUPqcpj4fHZewgpsV0lI1vdmkLVRn7Mans9287YQ=', 1, '2024-05-21 11:53:38', '2024-05-21 11:54:31', 'AI210028', '145550-01-2354', 1, 0, '2024-05-21'),
('MUKHTAR ABDULKARIM SANI', 'ai210029@student.uthm.edu.my', '01151612215', 'sC1aUPqcpj4fHZewgpsV0lI1vdmkLVRn7Mans9287YQ=', 1, '2024-05-07 21:55:52', '2024-05-21 11:57:52', 'AI210029', '140550-01-2345', 1, 0, '2024-05-21'),
('MOHAMAD ARIF AZINUDDIN BIN ZAIDI', 'chojjaarif2002@gmail.com', '01110794886', 'mT943XZz7ME/UeCXuCqfjw==', 1, '2024-04-15 04:10:26', '2024-05-30 09:16:40', 'AI210125', '020220-01-1595', 1, 638620, '2024-05-30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_author` (`AuthorId`),
  ADD KEY `fk_cat` (`CatId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issuedbookdetails`
--
ALTER TABLE `issuedbookdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_book_id` (`BookId`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`matricNo`),
  ADD UNIQUE KEY `icNo` (`icNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `issuedbookdetails`
--
ALTER TABLE `issuedbookdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_author` FOREIGN KEY (`AuthorId`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `fk_cat` FOREIGN KEY (`CatId`) REFERENCES `category` (`id`);

--
-- Constraints for table `issuedbookdetails`
--
ALTER TABLE `issuedbookdetails`
  ADD CONSTRAINT `fk_book_id` FOREIGN KEY (`BookId`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
