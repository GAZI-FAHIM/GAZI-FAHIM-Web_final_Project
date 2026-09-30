-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2025 at 08:59 PM
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
-- Database: `safeway`
--

-- --------------------------------------------------------

--
-- Table structure for table `call_logs`
--

CREATE TABLE `call_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `scheduled_time` datetime NOT NULL,
  `status` enum('completed','failed') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `call_logs`
--

INSERT INTO `call_logs` (`id`, `user_id`, `phone_number`, `scheduled_time`, `status`, `created_at`) VALUES
(1, 101, '1234567890', '2025-05-05 10:30:00', 'completed', '2025-05-04 17:23:42'),
(2, 102, '9876543210', '2025-05-04 09:00:00', 'failed', '2025-05-04 17:23:42'),
(3, 101, '1112223333', '2025-05-06 14:15:00', 'completed', '2025-05-04 17:23:42'),
(4, 103, '4445556666', '2025-05-07 18:00:00', 'completed', '2025-05-04 17:23:42'),
(5, 104, '7778889999', '2025-05-03 20:45:00', 'completed', '2025-05-04 17:23:42'),
(0, 9, '1234567890', '2025-05-05 15:00:00', 'completed', '2025-05-05 17:01:01'),
(0, 9, '1234567890', '2025-05-06 12:10:00', 'completed', '2025-06-23 17:53:17');

-- --------------------------------------------------------

--
-- Table structure for table `community_resources`
--

CREATE TABLE `community_resources` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `hours` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `latitude` double NOT NULL DEFAULT 0,
  `longitude` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_resources`
--

INSERT INTO `community_resources` (`id`, `name`, `category`, `location`, `contact`, `purpose`, `hours`, `description`, `latitude`, `longitude`) VALUES
(1, 'Ain o Salish Kendra', 'Legal Aid', 'Siddeshwari, Dhaka', '01711442233', 'Legal assistance for women and children', '9 AM - 5 PM', 'Provides legal support and mediation services for abuse victims.', 23.7393, 90.4125),
(2, 'Naripokkho', 'Women\'s Support', 'Dhanmondi, Dhaka', '01715013324', 'Women rights and awareness', '10 AM - 4 PM', 'Advocacy and support for women’s rights.', 23.7476, 90.3761),
(3, 'BRAC Legal Aid Services', 'Legal Aid', 'Mohakhali, Dhaka', '01730542211', 'Legal advice and support', '9 AM - 6 PM', 'Free legal advice, especially for vulnerable women.', 23.7806, 90.4003),
(4, 'Dhaka Medical Emergency', 'Emergency Help', 'Bakshibazar, Dhaka', '01313499870', '24/7 emergency medical aid', '24 Hours', 'Government hospital emergency unit.', 23.7274, 90.3931),
(5, 'BNPS Counseling Center', 'Women\'s Support', 'Farmgate, Dhaka', '01918228400', 'Counseling for trauma', '10 AM - 5 PM', 'Psychological help for women facing violence.', 23.7512, 90.3874),
(6, 'JAAGO Foundation', 'Local Initiatives', 'Banani, Dhaka', '01844558788', 'Community training', '10 AM - 5 PM', 'Empowers women through education and training.', 23.7904, 90.4074),
(7, 'Fire Service & Civil Defence HQ', 'Emergency Help', 'Fulbaria, Dhaka', '01730336699', 'Emergency fire response', '24 Hours', 'Call for any fire-related emergencies.', 23.7115, 90.4087),
(8, 'BDRCS First Aid Center', 'Emergency Help', 'Motijheel, Dhaka', '01811458555', 'Basic first aid', '24 Hours', 'Bangladesh Red Crescent Society first response team.', 23.7324, 90.4176),
(9, 'Anannya Women’s Center', 'Women\'s Support', 'Mirpur-10, Dhaka', '01755334422', 'Abuse survivor support', '9 AM - 5 PM', 'Safe space and support for victims of abuse.', 23.8045, 90.3654),
(10, 'Self Defense Class by Police HQ', 'Local Initiatives', 'Rajarbagh, Dhaka', '01730334455', 'Martial arts & safety tips', 'Weekly', 'Free safety training for women.', 23.7426, 90.4262),
(11, 'ASK Women\'s Helpline', 'Emergency Help', 'Dhaka', '10921', '24/7 helpline for emergencies', '24 Hours', 'Confidential helpline for abuse victims.', 23.7811, 90.4),
(12, 'Sajida Foundation', 'Women\'s Support', 'Uttara, Dhaka', '01713533001', 'Health & psychological support', '9 AM - 5 PM', 'Community health and gender support.', 23.8759, 90.3798),
(13, 'BLAST Office', 'Legal Aid', 'Karwan Bazar, Dhaka', '01730000990', 'Human rights and legal aid', '9 AM - 5 PM', 'Provides legal representation and rights awareness.', 23.7568, 90.3905),
(14, 'Shokhi Community Program', 'Local Initiatives', 'Tejgaon, Dhaka', '01712111000', 'Empowerment through training', '10 AM - 4 PM', 'Community training for low-income women.', 23.7613, 90.3922),
(15, 'Safe Dhaka App Helpdesk', 'Local Initiatives', 'Gulshan-2, Dhaka', '01744556677', 'App support and info', '9 AM - 6 PM', 'Information helpdesk for city safety apps.', 23.7967, 90.4154),
(16, 'Rainbow Shelter Home', 'Women\'s Support', 'Old Dhaka, Dhaka', '01888992233', 'Shelter for abuse victims', '24 Hours', 'Safe housing and trauma support for survivors.', 23.7121, 90.4076),
(17, 'National Legal Aid Service', 'Legal Aid', 'Sher-e-Bangla Nagar, Dhaka', '105', 'Govt. legal support', '9 AM - 4 PM', 'Free legal aid from govt. of Bangladesh.', 23.7779, 90.3786),
(18, 'Emergency Trauma Unit - DMCH', 'Emergency Help', 'Bakshibazar, Dhaka', '01300338822', 'Immediate medical trauma care', '24 Hours', 'Dedicated trauma center at Dhaka Medical.', 23.7271, 90.3942),
(19, 'Women’s Rights Forum', 'Women\'s Support', 'Shyamoli, Dhaka', '01933445566', 'Awareness and support', '10 AM - 5 PM', 'Workshops and safe talk sessions.', 23.7734, 90.3561),
(20, 'UN Women Dhaka Office', 'Local Initiatives', 'Baridhara, Dhaka', '0258835273', 'Policy and community training', '9 AM - 5 PM', 'Advocacy and training by UN.', 23.7993, 90.4268),
(21, 'Bangladesh Legal Aid & Services Trust', 'Legal Aid', 'Kakrail, Dhaka', '01718882200', 'Legal assistance and representation', '9 AM - 5 PM', 'Support for underprivileged women.', 23.7411, 90.4122),
(22, 'SHE Counseling Center', 'Women\'s Support', 'Malibagh, Dhaka', '01888993300', 'Abuse trauma therapy', '10 AM - 6 PM', 'Professional mental health service.', 23.7511, 90.4199),
(23, 'Rescue Foundation Hotline', 'Emergency Help', 'Dhaka', '999', 'National emergency services', '24 Hours', 'Police, fire, and ambulance in one number.', 23.7778, 90.407),
(24, 'SafeSteps Women Empowerment', 'Local Initiatives', 'Khilgaon, Dhaka', '01777001122', 'Training & mentoring', '10 AM - 5 PM', 'Start-up help for young women.', 23.7389, 90.4299),
(25, 'BD Women Helpline', 'Emergency Help', 'Dhaka', '109', 'All purpose helpline for women', '24 Hours', 'Crisis, legal, medical help in one number.', 23.761, 90.4004),
(26, 'Aparajita Training Center', 'Local Initiatives', 'Mohammadpur, Dhaka', '01700889966', 'Self-defense and skills training', '9 AM - 5 PM', 'Equips women with safety skills.', 23.7612, 90.3657),
(27, 'Hope Bangladesh', 'Women\'s Support', 'Baridhara DOHS, Dhaka', '01777714433', 'Trauma recovery & safe home', '24 Hours', 'Shelter and psychological recovery.', 23.815, 90.4261),
(28, 'Dhaka North City Corporation Helpline', 'Emergency Help', 'Gulshan, Dhaka', '333', 'City safety, sanitation, women safety', '8 AM - 8 PM', 'Report issues and get help.', 23.7915, 90.4212),
(29, 'WomenWatch Bangladesh', 'Local Initiatives', 'Wari, Dhaka', '01711100900', 'Watchdog, awareness & reporting', '10 AM - 4 PM', 'Report local incidents or unsafe zones.', 23.7166, 90.4193),
(30, 'National Trauma Counseling Center', 'Women\'s Support', 'Green Road, Dhaka', '01552223300', 'Mental health support', '10 AM - 5 PM', 'Therapy and group sessions for victims.', 23.751, 90.385);

-- --------------------------------------------------------

--
-- Table structure for table `crime_incidents`
--

CREATE TABLE `crime_incidents` (
  `id` int(11) NOT NULL,
  `area` varchar(100) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `incident_count` int(11) NOT NULL DEFAULT 1,
  `incident_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crime_incidents`
--

INSERT INTO `crime_incidents` (`id`, `area`, `latitude`, `longitude`, `incident_count`, `incident_date`) VALUES
(1, 'Dhanmondi', 23.7415, 90.3733, 4, '2025-07-10'),
(2, 'Gulshan', 23.7925, 90.4078, 7, '2025-07-11'),
(3, 'Uttara', 23.8747, 90.3984, 2, '2025-07-09'),
(4, 'Mirpur', 23.8065, 90.3695, 5, '2025-07-08'),
(5, 'Motijheel', 23.7361, 90.4194, 6, '2025-07-07'),
(6, 'Mohakhali', 23.7785, 90.4003, 3, '2025-07-10'),
(7, 'Banani', 23.7925, 90.4113, 9, '2025-07-12'),
(8, 'Badda', 23.7803, 90.426, 1, '2025-07-11'),
(9, 'Tejgaon', 23.7578, 90.3947, 4, '2025-07-13'),
(10, 'Shyamoli', 23.774, 90.3461, 2, '2025-07-10');

-- --------------------------------------------------------

--
-- Table structure for table `crime_stats`
--

CREATE TABLE `crime_stats` (
  `id` int(11) NOT NULL,
  `area` varchar(100) NOT NULL,
  `thana` varchar(100) NOT NULL,
  `incident_count` int(11) DEFAULT 0,
  `last_updated` datetime DEFAULT current_timestamp(),
  `crime_index` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crime_stats`
--

INSERT INTO `crime_stats` (`id`, `area`, `thana`, `incident_count`, `last_updated`, `crime_index`) VALUES
(1, 'Banani', 'Gulshan Thana', 45, '2025-07-01 20:40:41', 7.8),
(2, 'Gulshan', 'Gulshan Thana', 32, '2025-07-01 20:40:41', 6.1),
(3, 'Mirpur', 'Mirpur Thana', 67, '2025-07-01 20:40:41', 8.2),
(4, 'Dhanmondi', 'Dhanmondi Thana', 22, '2025-07-01 20:40:41', 5),
(5, 'Uttara', 'Uttara Thana', 51, '2025-07-01 20:40:41', 7.1),
(6, 'Motijheel', 'Motijheel Thana', 39, '2025-07-01 20:40:41', 6.7),
(7, 'Mohammadpur', 'Mohammadpur Thana', 48, '2025-07-01 20:40:41', 7),
(8, 'Farmgate', 'Tejgaon Thana', 29, '2025-07-01 20:40:41', 6),
(9, 'Badda', 'Badda Thana', 41, '2025-07-01 20:40:41', 6.9),
(10, 'Rampura', 'Rampura Thana', 36, '2025-07-01 20:40:41', 6.4),
(11, 'Khilgaon', 'Khilgaon Thana', 27, '2025-07-01 20:40:41', 5.8),
(12, 'Tejgaon', 'Tejgaon Thana', 52, '2025-07-01 20:40:41', 7.3),
(13, 'Baridhara', 'Gulshan Thana', 18, '2025-07-01 20:40:41', 4.3),
(14, 'Shahbagh', 'Shahbagh Thana', 23, '2025-07-01 20:40:41', 5.5),
(15, 'Wari', 'Wari Thana', 30, '2025-07-01 20:40:41', 5.9),
(16, 'Jatrabari', 'Jatrabari Thana', 37, '2025-07-01 20:40:41', 6.2),
(17, 'Paltan', 'Paltan Thana', 44, '2025-07-01 20:40:41', 6.8),
(18, 'Malibagh', 'Ramna Thana', 26, '2025-07-01 20:40:41', 5.7),
(19, 'Bashundhara', 'Bhatara Thana', 15, '2025-07-01 20:40:41', 3.9),
(20, 'Kalabagan', 'Kalabagan Thana', 33, '2025-07-01 20:40:41', 6.3),
(21, 'Shyamoli', 'Sher-e-Bangla Nagar Thana', 28, '2025-07-01 20:40:41', 5.6),
(22, 'Kuril', 'Bhatara Thana', 31, '2025-07-01 20:40:41', 6.1),
(23, 'Notun Bazar', 'Bhatara Thana', 20, '2025-07-01 20:40:41', 4.8),
(24, 'Agargaon', 'Sher-e-Bangla Nagar Thana', 35, '2025-07-01 20:40:41', 6.5),
(25, 'Hatirjheel', 'Ramna Thana', 25, '2025-07-01 20:40:41', 5.4),
(26, 'Kakrail', 'Ramna Thana', 19, '2025-07-01 20:40:41', 4.7),
(27, 'Bijoy Sarani', 'Tejgaon Thana', 24, '2025-07-01 20:40:41', 5.2),
(28, 'Shantinagar', 'Ramna Thana', 34, '2025-07-01 20:40:41', 6.6),
(29, 'Green Road', 'Dhanmondi Thana', 21, '2025-07-01 20:40:41', 4.9),
(30, 'Lalmatia', 'Mohammadpur Thana', 16, '2025-07-01 20:40:41', 4.4);

-- --------------------------------------------------------

--
-- Table structure for table `crime_type_trends`
--

CREATE TABLE `crime_type_trends` (
  `id` int(11) NOT NULL,
  `week` varchar(20) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `theft` int(11) DEFAULT 0,
  `harassment` int(11) DEFAULT 0,
  `assault` int(11) DEFAULT 0,
  `robbery` int(11) DEFAULT 0,
  `vandalism` int(11) DEFAULT 0,
  `kidnapping` int(11) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crime_type_trends`
--

INSERT INTO `crime_type_trends` (`id`, `week`, `area`, `theft`, `harassment`, `assault`, `robbery`, `vandalism`, `kidnapping`, `updated_at`) VALUES
(31, '2025-W01', 'Banani', 12, 5, 3, 2, 4, 1, '2025-07-01 15:14:28'),
(32, '2025-W02', 'Gulshan', 15, 7, 4, 3, 2, 0, '2025-07-01 15:14:28'),
(33, '2025-W03', 'Mirpur', 10, 4, 5, 1, 3, 2, '2025-07-01 15:14:28'),
(34, '2025-W04', 'Dhanmondi', 18, 6, 7, 4, 5, 1, '2025-07-01 15:14:28'),
(35, '2025-W05', 'Uttara', 14, 3, 6, 2, 4, 0, '2025-07-01 15:14:28'),
(36, '2025-W06', 'Motijheel', 9, 5, 3, 3, 1, 1, '2025-07-01 15:14:28'),
(37, '2025-W07', 'Mohammadpur', 13, 4, 4, 2, 3, 0, '2025-07-01 15:14:28'),
(38, '2025-W08', 'Farmgate', 11, 6, 2, 1, 2, 1, '2025-07-01 15:14:28'),
(39, '2025-W09', 'Badda', 16, 5, 6, 3, 4, 2, '2025-07-01 15:14:28'),
(40, '2025-W10', 'Rampura', 8, 4, 3, 2, 1, 0, '2025-07-01 15:14:28'),
(41, '2025-W11', 'Khilgaon', 12, 3, 5, 1, 3, 1, '2025-07-01 15:14:28'),
(42, '2025-W12', 'Tejgaon', 17, 6, 7, 4, 5, 0, '2025-07-01 15:14:28'),
(43, '2025-W13', 'Baridhara', 14, 5, 6, 2, 3, 1, '2025-07-01 15:14:28'),
(44, '2025-W14', 'Shahbagh', 10, 4, 3, 3, 2, 0, '2025-07-01 15:14:28'),
(45, '2025-W15', 'Wari', 13, 5, 4, 2, 4, 1, '2025-07-01 15:14:28');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_numbers`
--

CREATE TABLE `emergency_numbers` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_numbers`
--

INSERT INTO `emergency_numbers` (`id`, `title`, `phone_number`, `description`, `created_at`) VALUES
(1, 'Police', '100', 'Local police emergency line', '2025-05-04 17:24:12'),
(2, 'Ambulance', '102', 'For medical emergencies', '2025-05-04 17:24:12'),
(3, 'Fire Service', '101', 'Fire-related emergencies', '2025-05-04 17:24:12'),
(4, 'Women Helpline', '1091', 'Women safety support', '2025-05-04 17:24:12'),
(5, 'Cyber Crime', '1930', 'Report online threats or fraud', '2025-05-04 17:24:12');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_response`
--

CREATE TABLE `emergency_response` (
  `id` int(11) NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `service_type` enum('Police','Ambulance','Fire','Others') DEFAULT 'Police',
  `average_response_time_min` float DEFAULT NULL,
  `response_rating` int(11) DEFAULT NULL CHECK (`response_rating` between 1 and 5),
  `last_reported` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_response`
--

INSERT INTO `emergency_response` (`id`, `area`, `service_type`, `average_response_time_min`, `response_rating`, `last_reported`) VALUES
(1, 'Banani', 'Police', 6.5, 4, '2025-07-01 20:42:22'),
(2, 'Gulshan', 'Police', 7, 4, '2025-07-01 20:42:22'),
(3, 'Mirpur', 'Police', 8.2, 3, '2025-07-01 20:42:22'),
(4, 'Dhanmondi', 'Police', 6, 4, '2025-07-01 20:42:22'),
(5, 'Uttara', 'Police', 7.3, 3, '2025-07-01 20:42:22'),
(6, 'Motijheel', 'Police', 6.9, 3, '2025-07-01 20:42:22'),
(7, 'Mohammadpur', 'Police', 7.1, 3, '2025-07-01 20:42:22'),
(8, 'Farmgate', 'Police', 5.8, 4, '2025-07-01 20:42:22'),
(9, 'Badda', 'Police', 6.4, 3, '2025-07-01 20:42:22'),
(10, 'Rampura', 'Police', 6.2, 3, '2025-07-01 20:42:22'),
(11, 'Khilgaon', 'Police', 5.7, 4, '2025-07-01 20:42:22'),
(12, 'Tejgaon', 'Police', 6.5, 4, '2025-07-01 20:42:22'),
(13, 'Baridhara', 'Police', 5, 5, '2025-07-01 20:42:22'),
(14, 'Shahbagh', 'Police', 5.9, 4, '2025-07-01 20:42:22'),
(15, 'Wari', 'Police', 6.6, 4, '2025-07-01 20:42:22'),
(16, 'Jatrabari', 'Police', 7, 3, '2025-07-01 20:42:22'),
(17, 'Paltan', 'Police', 6.3, 4, '2025-07-01 20:42:22'),
(18, 'Malibagh', 'Police', 5.8, 4, '2025-07-01 20:42:22'),
(19, 'Bashundhara', 'Police', 4.9, 5, '2025-07-01 20:42:22'),
(20, 'Kalabagan', 'Police', 6.1, 4, '2025-07-01 20:42:22'),
(21, 'Shyamoli', 'Ambulance', 9.2, 3, '2025-07-01 20:42:22'),
(22, 'Kuril', 'Ambulance', 8.5, 3, '2025-07-01 20:42:22'),
(23, 'Notun Bazar', 'Ambulance', 7.4, 4, '2025-07-01 20:42:22'),
(24, 'Agargaon', 'Ambulance', 8, 3, '2025-07-01 20:42:22'),
(25, 'Hatirjheel', 'Ambulance', 7.8, 4, '2025-07-01 20:42:22'),
(26, 'Kakrail', 'Fire', 10.1, 2, '2025-07-01 20:42:22'),
(27, 'Bijoy Sarani', 'Fire', 9.8, 3, '2025-07-01 20:42:22'),
(28, 'Shantinagar', 'Fire', 10.5, 2, '2025-07-01 20:42:22'),
(29, 'Green Road', 'Others', 6.7, 4, '2025-07-01 20:42:22'),
(30, 'Lalmatia', 'Others', 6.3, 4, '2025-07-01 20:42:22');

-- --------------------------------------------------------

--
-- Table structure for table `feature_usage`
--

CREATE TABLE `feature_usage` (
  `id` int(11) NOT NULL,
  `feature` varchar(100) DEFAULT NULL,
  `usage_count` int(11) DEFAULT 0,
  `user_type` enum('Admin','Students','Tourists','Shift Workers','New City Dwellers') DEFAULT 'Students',
  `last_used_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feature_usage`
--

INSERT INTO `feature_usage` (`id`, `feature`, `usage_count`, `user_type`, `last_used_at`) VALUES
(1, 'Dashboard', 150, 'Admin', '2025-07-01 20:55:43'),
(2, 'Basic Location Search', 120, 'New City Dwellers', '2025-07-01 20:55:43'),
(3, 'Map Exploration', 135, 'Students', '2025-07-01 20:55:43'),
(4, 'Visual Safety Ratings', 110, 'Tourists', '2025-07-01 20:55:43'),
(5, 'Check Before Going Out', 95, 'Shift Workers', '2025-07-01 20:55:43'),
(6, 'Identify Safer Routes', 140, 'Students', '2025-07-01 20:55:43'),
(7, 'Filter Incidents', 85, 'New City Dwellers', '2025-07-01 20:55:43'),
(8, 'Crime Hotspot Tracker', 160, 'Admin', '2025-07-01 20:55:43'),
(9, 'Community Resources', 90, 'Tourists', '2025-07-01 20:55:43'),
(10, 'Understanding Safety Factors', 70, 'Students', '2025-07-01 20:55:43'),
(11, 'Using the Legend', 60, 'New City Dwellers', '2025-07-01 20:55:43'),
(12, 'Send Notifications', 100, 'Admin', '2025-07-01 20:55:43'),
(13, 'Emergency Calls', 125, 'Shift Workers', '2025-07-01 20:55:43'),
(14, 'Live Location Share', 78, 'Shift Workers', '2025-07-01 20:55:43'),
(15, 'SOS Panic Button', 200, 'Tourists', '2025-07-01 20:55:43'),
(16, 'Medical ID', 80, 'Students', '2025-07-01 20:55:43'),
(17, 'Nearby Police Station', 64, 'New City Dwellers', '2025-07-01 20:55:43'),
(18, 'Ambulance Tracker', 67, 'Shift Workers', '2025-07-01 20:55:43'),
(19, 'Fire Station Locator', 42, 'Tourists', '2025-07-01 20:55:43'),
(20, 'AI Safety Predictor', 55, 'Students', '2025-07-01 20:55:43'),
(21, 'Daily Safety Tips', 88, 'Tourists', '2025-07-01 20:55:43'),
(22, 'Night Safety Checker', 33, 'Shift Workers', '2025-07-01 20:55:43'),
(23, 'Area Comparison Tool', 53, 'New City Dwellers', '2025-07-01 20:55:43'),
(24, 'Safe Route Suggestion', 98, 'Students', '2025-07-01 20:55:43'),
(25, 'Emergency Message Templates', 47, 'Shift Workers', '2025-07-01 20:55:43'),
(26, 'Safety Notification Alerts', 108, 'New City Dwellers', '2025-07-01 20:55:43'),
(27, 'Crowd Density Monitor', 66, 'Students', '2025-07-01 20:55:43'),
(28, 'Public Feedback Bubble', 39, 'Tourists', '2025-07-01 20:55:43'),
(29, 'Feature Tutorial Videos', 28, 'New City Dwellers', '2025-07-01 20:55:43'),
(30, 'Settings Dashboard', 59, 'Admin', '2025-07-01 20:55:43');

-- --------------------------------------------------------

--
-- Table structure for table `feedback_bubble`
--

CREATE TABLE `feedback_bubble` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  `feedback_positive` int(11) DEFAULT 0,
  `feedback_negative` int(11) DEFAULT 0,
  `safety_score` float DEFAULT 0,
  `traffic_density` float DEFAULT 0,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback_bubble`
--

INSERT INTO `feedback_bubble` (`id`, `user_id`, `label`, `feedback_positive`, `feedback_negative`, `safety_score`, `traffic_density`, `submitted_at`) VALUES
(31, 1, 'Banani', 65, 3, 72, 5.4, '2025-07-01 15:07:43'),
(32, 2, 'Gulshan', 78, 5, 81, 4.9, '2025-07-01 15:07:43'),
(33, 3, 'Mirpur', 55, 7, 63, 6.5, '2025-07-01 15:07:43'),
(34, 4, 'Dhanmondi', 70, 2, 78, 5.1, '2025-07-01 15:07:43'),
(35, 5, 'Uttara', 62, 4, 69, 5.7, '2025-07-01 15:07:43'),
(36, 6, 'Motijheel', 50, 6, 60, 6.8, '2025-07-01 15:07:43'),
(37, 7, 'Mohammadpur', 58, 3, 70, 5.3, '2025-07-01 15:07:43'),
(38, 8, 'Farmgate', 48, 4, 59, 6.2, '2025-07-01 15:07:43'),
(39, 9, 'Badda', 53, 5, 65, 6, '2025-07-01 15:07:43'),
(40, 10, 'Rampura', 45, 6, 56, 6.6, '2025-07-01 15:07:43'),
(41, 11, 'Khilgaon', 43, 3, 62, 5.9, '2025-07-01 15:07:43'),
(42, 12, 'Tejgaon', 75, 2, 80, 4.7, '2025-07-01 15:07:43'),
(43, 13, 'Baridhara', 80, 1, 85, 4.2, '2025-07-01 15:07:43'),
(44, 14, 'Shahbagh', 68, 3, 73, 5, '2025-07-01 15:07:43'),
(45, 15, 'Wari', 55, 5, 64, 5.8, '2025-07-01 15:07:43'),
(46, 16, 'Jatrabari', 58, 4, 67, 5.6, '2025-07-01 15:07:43'),
(47, 17, 'Paltan', 65, 3, 71, 5.4, '2025-07-01 15:07:43'),
(48, 18, 'Malibagh', 50, 4, 63, 5.9, '2025-07-01 15:07:43'),
(49, 19, 'Bashundhara', 78, 2, 80, 4.4, '2025-07-01 15:07:43'),
(50, 20, 'Kalabagan', 62, 3, 72, 5.5, '2025-07-01 15:07:43'),
(51, 21, 'Shyamoli', 53, 4, 61, 6, '2025-07-01 15:07:43'),
(52, 22, 'Kuril', 48, 5, 60, 6.3, '2025-07-01 15:07:43'),
(53, 23, 'Notun Bazar', 45, 6, 57, 6.5, '2025-07-01 15:07:43'),
(54, 24, 'Agargaon', 53, 5, 64, 5.8, '2025-07-01 15:07:43'),
(55, 25, 'Hatirjheel', 55, 3, 69, 5.1, '2025-07-01 15:07:43'),
(56, 26, 'Kakrail', 43, 6, 55, 6.4, '2025-07-01 15:07:43'),
(57, 27, 'Bijoy Sarani', 57, 3, 68, 5.2, '2025-07-01 15:07:43'),
(58, 28, 'Shantinagar', 65, 2, 75, 4.9, '2025-07-01 15:07:43'),
(59, 29, 'Green Road', 60, 3, 70, 5.6, '2025-07-01 15:07:43'),
(60, 30, 'Lalmatia', 62, 2, 71, 5.3, '2025-07-01 15:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`id`, `category`, `area`, `date`, `description`, `latitude`, `longitude`) VALUES
(1, 'Theft', 'Dhanmondi', '2025-06-01', 'A mobile phone was stolen near the supermarket.', 23.7465, 90.376),
(2, 'Theft', 'Gulshan', '2025-06-03', 'Wallet snatching reported near the park.', 23.7929, 90.4071),
(3, 'Theft', 'Mirpur', '2025-06-04', 'Bicycle stolen from parking area.', 23.8103, 90.3662),
(4, 'Theft', 'Banani', '2025-06-05', 'Laptop missing from a cafe table.', 23.7945, 90.4078),
(5, 'Theft', 'Uttara', '2025-06-06', 'Pickpocket incident in market.', 23.869, 90.3994),
(6, 'Theft', 'Mohammadpur', '2025-06-07', 'Shoplifting in a local store.', 23.746, 90.3576),
(7, 'Theft', 'Tejgaon', '2025-06-08', 'Bag stolen from bus stop.', 23.7622, 90.3953),
(8, 'Assault', 'Dhanmondi', '2025-06-02', 'Physical altercation near the college gate.', 23.7468, 90.374),
(9, 'Assault', 'Gulshan', '2025-06-03', 'Person attacked during night walk.', 23.7932, 90.4065),
(10, 'Assault', 'Mirpur', '2025-06-06', 'Group fight reported in park.', 23.8107, 90.367),
(11, 'Assault', 'Banani', '2025-06-08', 'Man punched during street argument.', 23.795, 90.4085),
(12, 'Assault', 'Uttara', '2025-06-09', 'Woman harassed and physically assaulted.', 23.8687, 90.3999),
(13, 'Assault', 'Mohammadpur', '2025-06-10', 'Assault incident reported outside market.', 23.7464, 90.358),
(14, 'Robbery', 'Dhanmondi', '2025-06-03', 'Robbery at ATM machine.', 23.7462, 90.3755),
(15, 'Robbery', 'Gulshan', '2025-06-04', 'Jewelry stolen during home break-in.', 23.7925, 90.4068),
(16, 'Robbery', 'Mirpur', '2025-06-05', 'Convenience store robbed at night.', 23.8108, 90.3665),
(17, 'Robbery', 'Banani', '2025-06-07', 'Bike snatched from rider.', 23.7942, 90.408),
(18, 'Robbery', 'Tejgaon', '2025-06-09', 'Bag robbed near bus stop.', 23.762, 90.395),
(19, 'Harassment', 'Dhanmondi', '2025-06-04', 'Verbal harassment near college.', 23.7469, 90.3745),
(20, 'Harassment', 'Gulshan', '2025-06-06', 'Unwanted attention in the market.', 23.793, 90.407),
(21, 'Harassment', 'Mirpur', '2025-06-07', 'Street harassment incident reported.', 23.8105, 90.3667),
(22, 'Harassment', 'Uttara', '2025-06-08', 'Harassment in public transport.', 23.8692, 90.399),
(23, 'Harassment', 'Tejgaon', '2025-06-10', 'Harassment near workplace.', 23.7625, 90.3955),
(24, 'Vandalism', 'Dhanmondi', '2025-06-05', 'Public property damaged near park.', 23.7466, 90.375),
(25, 'Vandalism', 'Gulshan', '2025-06-07', 'Graffiti on building walls.', 23.7928, 90.4072),
(26, 'Vandalism', 'Banani', '2025-06-08', 'Car window smashed.', 23.794, 90.4083),
(27, 'Drug-related', 'Mirpur', '2025-06-05', 'Suspicious drug activity reported.', 23.81, 90.3672),
(28, 'Drug-related', 'Uttara', '2025-06-07', 'Drug sale attempt stopped by police.', 23.8685, 90.3995),
(29, 'Drug-related', 'Tejgaon', '2025-06-08', 'Drug paraphernalia found in alley.', 23.7623, 90.3957),
(30, 'Drug-related', 'Mohammadpur', '2025-06-09', 'Suspected drug user apprehended.', 23.7458, 90.357);

-- --------------------------------------------------------

--
-- Table structure for table `incident_reports`
--

CREATE TABLE `incident_reports` (
  `id` int(11) NOT NULL,
  `safety_location_id` int(11) NOT NULL,
  `occurred_at` datetime NOT NULL,
  `type` varchar(100) NOT NULL,
  `severity` tinyint(4) DEFAULT 1,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `safety_score` float DEFAULT NULL,
  `hospitals` text DEFAULT NULL,
  `police_stations` text DEFAULT NULL,
  `crime_trend` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `area_name`, `safety_score`, `hospitals`, `police_stations`, `crime_trend`) VALUES
(1, 'Gulshan', 9.2, 'United Hospital, Evercare Hospital', 'Gulshan Police Station, Banani Police Station', 'Low and decreasing'),
(2, 'Dhanmondi', 8.5, 'Popular Diagnostic, LabAid Hospital', 'Dhanmondi Police Station', 'Moderate and stable'),
(3, 'Mirpur', 6.8, 'Dhaka Dental College, National Heart Foundation', 'Pallabi Police Station, Mirpur Model Police Station', 'High and fluctuating'),
(4, 'Uttara', 8.7, 'Lubana General Hospital, Uttara Crescent Hospital', 'Uttara West Police Station, Uttara East Police Station', 'Low and stable'),
(5, 'Motijheel', 7.5, 'Islamia Eye Hospital, Kakrail General Hospital', 'Motijheel Police Station', 'Moderate and decreasing'),
(6, 'Mohammadpur', 7, 'Mohammadpur Central Hospital', 'Mohammadpur Police Station', 'Moderate and rising'),
(7, 'Badda', 6.2, 'Badda General Hospital', 'Badda Police Station', 'High and rising'),
(8, 'Tejgaon', 7.8, 'Tejgaon Industrial Hospital', 'Tejgaon Police Station', 'Low and steady'),
(9, 'Shahbagh', 8.3, 'PG Hospital, Bangabandhu Medical University', 'Shahbagh Police Station', 'Low and declining'),
(10, 'Rampura', 6.5, 'Rampura General Hospital', 'Rampura Police Station', 'Moderate and unstable'),
(11, 'Banani', 9, 'Banani General Hospital', 'Banani Police Station', 'Low and decreasing'),
(12, 'Khilgaon', 7.2, 'Khilgaon General Hospital', 'Khilgaon Police Station', 'Moderate and stable'),
(13, 'Kotwali', 8, 'Sadarghat General Hospital', 'Kotwali Police Station', 'Low and declining'),
(14, 'Rajarbagh', 7.3, 'Rajarbagh Police Hospital', 'Rajarbagh Police Station', 'Moderate and fluctuating'),
(15, 'Savar', 8.4, 'Savar Hospital', 'Savar Police Station', 'Low and stable'),
(16, 'Narayanganj', 7.6, 'Narayanganj General Hospital', 'Narayanganj Police Station', 'Moderate and rising'),
(17, 'Manikganj', 8.2, 'Manikganj Hospital', 'Manikganj Police Station', 'Low and steady'),
(18, 'Gazipur', 7.9, 'Gazipur City Hospital', 'Gazipur Police Station', 'Moderate and fluctuating'),
(19, 'Tongi', 6.9, 'Tongi General Hospital', 'Tongi Police Station', 'High and increasing'),
(20, 'Ashulia', 7.4, 'Ashulia General Hospital', 'Ashulia Police Station', 'Moderate and decreasing');

-- --------------------------------------------------------

--
-- Table structure for table `location_safety`
--

CREATE TABLE `location_safety` (
  `id` int(11) NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lon` decimal(10,7) DEFAULT NULL,
  `safety_score` int(11) DEFAULT NULL,
  `hospitals` text DEFAULT NULL,
  `police_stations` text DEFAULT NULL,
  `crime_trend` text DEFAULT NULL,
  `weather_advisory` text DEFAULT NULL,
  `crowd_density` varchar(50) DEFAULT NULL,
  `incidents` text DEFAULT NULL,
  `transport_scores` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location_safety`
--

INSERT INTO `location_safety` (`id`, `area_name`, `lat`, `lon`, `safety_score`, `hospitals`, `police_stations`, `crime_trend`, `weather_advisory`, `crowd_density`, `incidents`, `transport_scores`, `created_at`, `updated_at`) VALUES
(1, 'Gulshan', 23.7925000, 90.4078000, 85, 'United Hospital, Praava Health, Evercare Hospital', 'Gulshan Police Station', '↓ declining', 'Sunny with light breeze', 'Low', 'Pickpocket case at Gulshan-2 Circle, Minor traffic accident near Gulshan Lake', '{\"Bus\":65,\"Metro\":90,\"Ride-share\":88}', '2025-08-13 16:24:31', '2025-08-13 16:24:31'),
(2, 'Banani', 23.7933000, 90.4042000, 78, 'Ibn Sina Diagnostic, Banani Clinic', 'Banani Police Outpost', '→ stable', 'Humid and cloudy', 'Moderate', 'Motorbike theft near Banani Road 11, Harassment complaint filed', '{\"Bus\":70,\"Metro\":85,\"Ride-share\":80}', '2025-08-13 16:24:31', '2025-08-13 16:24:31'),
(3, 'Dhanmondi', 23.7465000, 90.3742000, 74, 'Square Hospital, Popular Diagnostic Centre', 'Dhanmondi Police Station', '↑ rising', 'Scattered showers expected', 'High', 'Snatching incident at Dhanmondi 27, Shoplifting at Dhanmondi Hawkers Market', '{\"Bus\":80,\"Metro\":60,\"Ride-share\":75}', '2025-08-13 16:24:31', '2025-08-13 16:24:31'),
(4, 'Mirpur', 23.8067000, 90.3681000, 65, 'Mirpur General Hospital, Islami Bank Hospital', 'Mirpur Model Police Station', '↑ rising', 'Thunderstorm advisory', 'High', 'Road accident at Mirpur-10, Mobile snatching at Mirpur-1', '{\"Bus\":85,\"Metro\":55,\"Ride-share\":68}', '2025-08-13 16:24:31', '2025-08-13 16:24:31'),
(5, 'Uttara', 23.8766000, 90.3798000, 82, 'Ahsania Mission Cancer Hospital, Labaid Diagnostic', 'Uttara West Police Station', '↓ declining', 'Clear skies', 'Low', 'Lost wallet complaint, Minor altercation at Sector 7 park', '{\"Bus\":78,\"Metro\":92,\"Ride-share\":85}', '2025-08-13 16:24:31', '2025-08-13 16:24:31'),
(6, 'Mohakhali', 23.7784000, 90.4051000, 68, 'Mohakhali General Hospital, DNCC Hospital', 'Tejgaon Industrial Police Station', '→ stable', 'Light rain forecast', 'Moderate', 'Traffic congestion causing delays, Purse snatching at Mohakhali Bus Terminal', '{\"Bus\":88,\"Metro\":75,\"Ride-share\":72}', '2025-08-13 16:24:31', '2025-08-13 16:24:31'),
(7, 'Motijheel', 23.7322000, 90.4179000, 60, 'Ibn Sina Hospital, Islami Bank Hospital Motijheel', 'Motijheel Police Station', '↑ rising', 'Heat advisory issued', 'High', 'ATM fraud attempt, Bag snatching at Shapla Chattar', '{\"Bus\":90,\"Metro\":65,\"Ride-share\":60}', '2025-08-13 16:24:31', '2025-08-13 16:24:31'),
(8, 'Khilgaon', 23.7556000, 90.4308000, 67, 'Khilgaon General Hospital, Al-Manar Hospital', 'Khilgaon Police Station', '→ stable', 'Possible light drizzle', 'Moderate', 'Pickpocket caught at Khilgaon flyover, Minor bus accident', '{\"Bus\":85,\"Metro\":68,\"Ride-share\":70}', '2025-08-13 16:24:31', '2025-08-13 16:24:31'),
(9, 'Farmgate', 23.7571000, 90.3915000, 62, 'Shahid Suhrawardy Medical College Hospital', 'Tejgaon Police Station', '↑ rising', 'Heavy traffic due to rally', 'High', 'Snatching case reported near Kazi Nazrul Islam Ave, Protest causing roadblocks', '{\"Bus\":88,\"Metro\":58,\"Ride-share\":65}', '2025-08-13 16:24:31', '2025-08-13 16:24:31'),
(10, 'Bashundhara R/A', 23.8140000, 90.4317000, 88, 'Evercare Hospital, Bashundhara Eye Hospital', 'Bhatara Police Station', '↓ declining', 'Pleasant and breezy', 'Low', 'Lost phone at Jamuna Future Park, Bicycle theft in Block D', '{\"Bus\":60,\"Metro\":88,\"Ride-share\":90}', '2025-08-13 16:24:31', '2025-08-13 16:24:31');

-- --------------------------------------------------------

--
-- Table structure for table `medical_info`
--

CREATE TABLE `medical_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `medical_conditions` text DEFAULT NULL,
  `medications` text DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_info`
--

INSERT INTO `medical_info` (`id`, `user_id`, `blood_type`, `allergies`, `medical_conditions`, `medications`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES
(0, 9, 'O+', 'Peanuts', 'Asthma', 'Inhaler', 'Mom', '1234567890', '2025-05-04 17:33:19', '2025-05-04 17:33:19');

-- --------------------------------------------------------

--
-- Table structure for table `message_history`
--

CREATE TABLE `message_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_history`
--

INSERT INTO `message_history` (`id`, `user_id`, `message_text`, `created_at`) VALUES
(1, 101, 'Emergency alert sent to Mom', '2025-05-04 17:24:47'),
(2, 102, 'Location shared with Best Friend', '2025-05-04 17:24:47'),
(3, 103, 'Scheduled call reminder sent', '2025-05-04 17:24:47'),
(4, 104, 'Medication alert sent to Neighbor', '2025-05-04 17:24:47'),
(5, 105, 'All trusted contacts notified', '2025-05-04 17:24:47');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `urgency` varchar(50) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `location`, `category`, `urgency`, `link`, `timestamp`) VALUES
(1, 9, 'Road Block in Gulshan', 'There is a major road block due to an accident near Gulshan-1.', 'Gulshan', 'Traffic', 'High', 'http://example.com/roadblock-gulshan', '2025-05-04 08:00:00'),
(2, 9, 'Police Alert in Dhanmondi', 'Police are conducting a security check in Dhanmondi area.', 'Dhanmondi', 'Security', 'Medium', 'http://example.com/police-dhanmondi', '2025-05-04 09:15:00'),
(3, 9, 'Market Closed in Mirpur', 'Mirpur market will be closed today due to ongoing maintenance work.', 'Mirpur', 'Public Notice', 'Low', 'http://example.com/market-mirpur', '2025-05-04 10:30:00'),
(4, 9, 'Traffic Jam in Uttara', 'Heavy traffic reported on Airport Road in Uttara due to construction work.', 'Uttara', 'Traffic', 'High', 'http://example.com/traffic-uttara', '2025-05-04 11:45:00'),
(5, 9, 'Power Outage in Motijheel', 'A planned power outage will occur in Motijheel from 12 PM to 4 PM.', 'Motijheel', 'Utilities', 'Medium', 'http://example.com/power-outage-motijheel', '2025-05-04 12:00:00'),
(6, 9, 'Flood Warning in Mohammadpur', 'Flood warning has been issued for Mohammadpur and nearby areas.', 'Mohammadpur', 'Weather', 'High', 'http://example.com/flood-warning-mohammadpur', '2025-05-04 12:30:00'),
(7, 9, 'Streetlight Repair in Badda', 'Streetlights in Badda are being repaired tonight, please be cautious.', 'Badda', 'Maintenance', 'Low', 'http://example.com/streetlight-badda', '2025-05-04 13:00:00'),
(8, 9, 'New Hospital Opening in Tejgaon', 'Tejgaon Industrial Hospital is opening a new emergency department today.', 'Tejgaon', 'Event', 'Medium', 'http://example.com/hospital-tejgaon', '2025-05-04 14:00:00'),
(9, 9, 'Crime Alert in Shahbagh', 'There was a robbery incident reported in Shahbagh area, please stay cautious.', 'Shahbagh', 'Crime', 'High', 'http://example.com/crime-shahbagh', '2025-05-04 14:30:00'),
(10, 9, 'Road Closed in Rampura', 'Rampura road is closed for maintenance work until further notice.', 'Rampura', 'Traffic', 'Medium', 'http://example.com/road-closed-rampura', '2025-05-04 15:00:00'),
(11, 9, 'Security Check in Banani', 'There will be an increased police presence in Banani for a routine security check.', 'Banani', 'Security', 'Low', 'http://example.com/security-banani', '2025-05-04 15:30:00'),
(12, 9, 'Noise Pollution in Khilgaon', 'There is ongoing construction work in Khilgaon causing significant noise pollution.', 'Khilgaon', 'Environmental', 'Low', 'http://example.com/noise-khilgaon', '2025-05-04 16:00:00'),
(13, 9, 'Protest in Kotwali', 'A peaceful protest is happening in Kotwali, please avoid the area if possible.', 'Kotwali', 'Event', 'Medium', 'http://example.com/protest-kotwali', '2025-05-04 16:30:00'),
(14, 9, 'Bank Strike in Rajarbagh', 'Bank employees are on strike in Rajarbagh, affecting services in the area.', 'Rajarbagh', 'Labor', 'Medium', 'http://example.com/bank-strike-rajarbagh', '2025-05-04 17:00:00'),
(15, 9, 'New Mall Opening in Savar', 'The new shopping mall in Savar will have its grand opening this weekend.', 'Savar', 'Event', 'Low', 'http://example.com/mall-savar', '2025-05-04 17:30:00'),
(16, 9, 'New Mall Opening in Uttara', 'The new shopping mall in Savar will have its grand opening this weekend.', 'Uttara', 'traffic', 'medium', 'http://example.com/mall-savar', '2025-06-30 19:44:24'),
(17, 9, '1', '1', '1', 'crime', 'high', 'http://example.com/mall-savar', '2025-06-30 20:37:30');

-- --------------------------------------------------------

--
-- Table structure for table `notification_engagement`
--

CREATE TABLE `notification_engagement` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `viewed` int(11) DEFAULT 0,
  `clicked` int(11) DEFAULT 0,
  `dismissed` int(11) DEFAULT 0,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_engagement`
--

INSERT INTO `notification_engagement` (`id`, `user_id`, `type`, `location`, `viewed`, `clicked`, `dismissed`, `sent_at`) VALUES
(1, 1, 'Safety Alert', 'Banani', 1, 1, 0, '2025-07-01 20:42:22'),
(2, 2, 'Traffic Update', 'Gulshan', 1, 0, 0, '2025-07-01 20:42:22'),
(3, 3, 'Emergency Notice', 'Mirpur', 1, 1, 0, '2025-07-01 20:42:22'),
(4, 4, 'New Feature', 'Dhanmondi', 1, 1, 0, '2025-07-01 20:42:22'),
(5, 5, 'General Info', 'Uttara', 1, 0, 1, '2025-07-01 20:42:22'),
(6, 6, 'Safety Alert', 'Motijheel', 1, 1, 0, '2025-07-01 20:42:22'),
(7, 7, 'Crime Alert', 'Mohammadpur', 1, 1, 0, '2025-07-01 20:42:22'),
(8, 8, 'Safety Tip', 'Farmgate', 1, 0, 1, '2025-07-01 20:42:22'),
(9, 9, 'Feature Update', 'Badda', 1, 1, 0, '2025-07-01 20:42:22'),
(10, 10, 'Safety Alert', 'Rampura', 1, 0, 1, '2025-07-01 20:42:22'),
(11, 11, 'Emergency Notice', 'Khilgaon', 1, 1, 0, '2025-07-01 20:42:22'),
(12, 12, 'Traffic Update', 'Tejgaon', 1, 0, 0, '2025-07-01 20:42:22'),
(13, 13, 'Crime Alert', 'Baridhara', 1, 1, 0, '2025-07-01 20:42:22'),
(14, 14, 'Safety Alert', 'Shahbagh', 1, 1, 0, '2025-07-01 20:42:22'),
(15, 15, 'Feature Update', 'Wari', 1, 0, 1, '2025-07-01 20:42:22'),
(16, 16, 'New Feature', 'Jatrabari', 1, 1, 0, '2025-07-01 20:42:22'),
(17, 17, 'Safety Tip', 'Paltan', 1, 0, 1, '2025-07-01 20:42:22'),
(18, 18, 'General Info', 'Malibagh', 1, 1, 0, '2025-07-01 20:42:22'),
(19, 19, 'Safety Alert', 'Bashundhara', 1, 1, 0, '2025-07-01 20:42:22'),
(20, 20, 'Emergency Notice', 'Kalabagan', 1, 0, 1, '2025-07-01 20:42:22'),
(21, 21, 'Traffic Update', 'Shyamoli', 1, 1, 0, '2025-07-01 20:42:22'),
(22, 22, 'Feature Update', 'Kuril', 1, 1, 0, '2025-07-01 20:42:22'),
(23, 23, 'Crime Alert', 'Notun Bazar', 1, 0, 1, '2025-07-01 20:42:22'),
(24, 24, 'New Feature', 'Agargaon', 1, 1, 0, '2025-07-01 20:42:22'),
(25, 25, 'Safety Tip', 'Hatirjheel', 1, 0, 1, '2025-07-01 20:42:22'),
(26, 26, 'General Info', 'Kakrail', 1, 1, 0, '2025-07-01 20:42:22'),
(27, 27, 'Emergency Notice', 'Bijoy Sarani', 1, 1, 0, '2025-07-01 20:42:22'),
(28, 28, 'Safety Alert', 'Shantinagar', 1, 0, 1, '2025-07-01 20:42:22'),
(29, 29, 'Traffic Update', 'Green Road', 1, 1, 0, '2025-07-01 20:42:22'),
(30, 30, 'Crime Alert', 'Lalmatia', 1, 1, 0, '2025-07-01 20:42:22');

-- --------------------------------------------------------

--
-- Table structure for table `route_requests`
--

CREATE TABLE `route_requests` (
  `id` int(11) NOT NULL,
  `start_location` varchar(255) DEFAULT NULL,
  `end_location` varchar(255) DEFAULT NULL,
  `time_of_day` varchar(50) DEFAULT NULL,
  `travel_mode` varchar(50) DEFAULT NULL,
  `companion_status` varchar(50) DEFAULT NULL,
  `request_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `safety_checks`
--

CREATE TABLE `safety_checks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `check_day` varchar(15) DEFAULT NULL,
  `check_time` time DEFAULT NULL,
  `check_type` enum('Manual','Auto','Scheduled') DEFAULT 'Manual',
  `location` varchar(100) DEFAULT NULL,
  `status` enum('Safe','Unsafe') DEFAULT 'Safe'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `safety_checks`
--

INSERT INTO `safety_checks` (`id`, `user_id`, `check_day`, `check_time`, `check_type`, `location`, `status`) VALUES
(1, 1, 'Monday', '08:00:00', 'Manual', 'Banani', 'Safe'),
(2, 2, 'Tuesday', '09:30:00', 'Auto', 'Gulshan', 'Safe'),
(3, 3, 'Wednesday', '10:15:00', 'Scheduled', 'Mirpur', 'Unsafe'),
(4, 4, 'Thursday', '11:00:00', 'Manual', 'Dhanmondi', 'Safe'),
(5, 5, 'Friday', '12:00:00', 'Auto', 'Uttara', 'Safe'),
(6, 6, 'Saturday', '13:00:00', 'Scheduled', 'Motijheel', 'Unsafe'),
(7, 7, 'Sunday', '14:00:00', 'Manual', 'Mohammadpur', 'Safe'),
(8, 8, 'Monday', '15:00:00', 'Auto', 'Farmgate', 'Safe'),
(9, 9, 'Tuesday', '16:00:00', 'Scheduled', 'Badda', 'Unsafe'),
(10, 10, 'Wednesday', '17:00:00', 'Manual', 'Rampura', 'Safe'),
(11, 11, 'Thursday', '18:00:00', 'Auto', 'Khilgaon', 'Safe'),
(12, 12, 'Friday', '19:00:00', 'Scheduled', 'Tejgaon', 'Unsafe'),
(13, 13, 'Saturday', '20:00:00', 'Manual', 'Baridhara', 'Safe'),
(14, 14, 'Sunday', '21:00:00', 'Auto', 'Shahbagh', 'Safe'),
(15, 15, 'Monday', '22:00:00', 'Scheduled', 'Wari', 'Unsafe'),
(16, 16, 'Tuesday', '23:00:00', 'Manual', 'Jatrabari', 'Safe'),
(17, 17, 'Wednesday', '08:30:00', 'Auto', 'Paltan', 'Safe'),
(18, 18, 'Thursday', '09:45:00', 'Scheduled', 'Malibagh', 'Unsafe'),
(19, 19, 'Friday', '10:50:00', 'Manual', 'Bashundhara', 'Safe'),
(20, 20, 'Saturday', '11:20:00', 'Auto', 'Kalabagan', 'Safe'),
(21, 21, 'Sunday', '12:10:00', 'Scheduled', 'Shyamoli', 'Unsafe'),
(22, 22, 'Monday', '13:05:00', 'Manual', 'Kuril', 'Safe'),
(23, 23, 'Tuesday', '14:15:00', 'Auto', 'Notun Bazar', 'Safe'),
(24, 24, 'Wednesday', '15:25:00', 'Scheduled', 'Agargaon', 'Unsafe'),
(25, 25, 'Thursday', '16:35:00', 'Manual', 'Hatirjheel', 'Safe'),
(26, 26, 'Friday', '17:40:00', 'Auto', 'Kakrail', 'Safe'),
(27, 27, 'Saturday', '18:45:00', 'Scheduled', 'Bijoy Sarani', 'Unsafe'),
(28, 28, 'Sunday', '19:50:00', 'Manual', 'Shantinagar', 'Safe'),
(29, 29, 'Monday', '20:55:00', 'Auto', 'Green Road', 'Safe'),
(30, 30, 'Tuesday', '21:30:00', 'Scheduled', 'Lalmatia', 'Unsafe');

-- --------------------------------------------------------

--
-- Table structure for table `safety_data`
--

CREATE TABLE `safety_data` (
  `id` int(11) NOT NULL,
  `area` varchar(100) NOT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `safety_score` int(11) DEFAULT NULL,
  `police_stations` text DEFAULT NULL,
  `weather_advisory` text DEFAULT NULL,
  `crowd_density` varchar(50) DEFAULT NULL,
  `incidents` text DEFAULT NULL,
  `transport_scores` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `safety_data`
--

INSERT INTO `safety_data` (`id`, `area`, `lat`, `lon`, `safety_score`, `police_stations`, `weather_advisory`, `crowd_density`, `incidents`, `transport_scores`) VALUES
(1, 'Dhanmondi', 23.7465, 90.376, 75, '[\"Dhanmondi Thana – 1.2km\", \"New Market Police Box – 2.1km\", \"Lalmatia Police Post – 2.9km\"]', 'It might rain in the evening. Carry an umbrella. Avoid slippery footpaths.', 'Medium', '[\"Pickpocketing near Mirpur Road – 2 days ago\", \"Suspicious activity in Kalabagan – 5 days ago\", \"Street harassment near Science Lab – 6 days ago\"]', '{\"Buses in Dhanmondi\":\"Safe (Score: 82)\", \"Rickshaws in Lalmatia\":\"Moderate (Score: 57)\"}'),
(2, 'Gulshan', 23.7925, 90.4078, 85, '[\"Gulshan Model Thana – 1.1km\", \"Banani Police Box – 2.0km\"]', 'Clear skies expected. Good to go.', 'Low', '[\"Theft near Gulshan Circle 2 – 4 days ago\", \"Unauthorized gathering reported – 6 days ago\"]', '{\"Ride-sharing cars\":\"Safe (Score: 90)\", \"CNGs\":\"Moderate (Score: 60)\"}'),
(3, 'Mirpur', 23.8041, 90.3667, 55, '[\"Mirpur Model Thana – 0.9km\", \"Shialbari Police Outpost – 1.8km\"]', 'Dust in the air. Consider wearing a mask.', 'High', '[\"Pickpocketing near Zoo Road – 1 day ago\", \"Bike snatching near Section 10 – 3 days ago\"]', '{\"Buses\":\"Moderate (Score: 60)\", \"CNGs\":\"Low (Score: 45)\"}'),
(4, 'Uttara', 23.8747, 90.3984, 78, '[\"Uttara West Police Station – 1.3km\", \"House Building Police Box – 2.2km\"]', 'Possible thunderstorms in the afternoon. Be cautious.', 'Medium', '[\"Eve teasing reported in Sector 7 – 2 days ago\"]', '{\"Metro Rail\":\"Very Safe (Score: 95)\", \"Buses\":\"Safe (Score: 80)\"}'),
(5, 'Mohammadpur', 23.76, 90.358, 62, '[\"Mohammadpur Police Station – 1.5km\", \"Asad Gate Outpost – 2.0km\"]', 'Light rain expected. Carry an umbrella.', 'Medium', '[\"Robbery near Town Hall – 3 days ago\", \"Fight near Salimullah Road – 4 days ago\"]', '{\"Rickshaws\":\"Moderate (Score: 58)\", \"Buses\":\"Low (Score: 49)\"}'),
(6, 'Badda', 23.7806, 90.4244, 48, '[\"Badda Police Station – 1.1km\", \"Rampura Police Box – 1.9km\"]', 'Heavy rain predicted. Roads may be slippery.', 'High', '[\"Snatching incident near Badda Link Road – 1 day ago\", \"Vandalism reported – 3 days ago\"]', '{\"CNGs\":\"Low (Score: 40)\", \"Buses\":\"Low (Score: 38)\"}'),
(7, 'Motijheel', 23.7333, 90.4178, 70, '[\"Motijheel Thana – 0.8km\", \"Shapla Chattar Police Post – 1.4km\"]', 'Clear weather. No major alerts.', 'Medium', '[\"Fraud reported in commercial bank – 2 days ago\"]', '{\"Office Shuttles\":\"Safe (Score: 83)\", \"CNGs\":\"Moderate (Score: 55)\"}'),
(8, 'Tejgaon', 23.7576, 90.3928, 67, '[\"Tejgaon Industrial Police Station – 1.0km\", \"Agargaon Police Outpost – 1.5km\"]', 'Possible drizzle after 7 PM.', 'Medium', '[\"Factory accident reported – 5 days ago\", \"Unauthorized protest – 2 days ago\"]', '{\"Ride-sharing bikes\":\"Moderate (Score: 62)\", \"Buses\":\"Moderate (Score: 59)\"}'),
(9, 'Kalabagan', 23.7489, 90.3793, 60, '[\"Kalabagan Police Box – 0.9km\", \"Science Lab Outpost – 1.3km\"]', 'Partly cloudy, no rain forecast.', 'Medium', '[\"Eve teasing near Lake Circus – 1 day ago\", \"Attempted snatch near bus stop – 2 days ago\"]', '{\"Rickshaws\":\"Moderate (Score: 55)\", \"Buses\":\"Low (Score: 42)\"}'),
(10, 'Banani', 23.7916, 90.4043, 82, '[\"Banani Police Station – 1.0km\", \"Chairman Bari Outpost – 2.0km\"]', 'Pleasant weather today.', 'Low', '[\"Street altercation near Road 27 – 4 days ago\"]', '{\"Ride-sharing cars\":\"Very Safe (Score: 93)\", \"CNGs\":\"Safe (Score: 75)\"}'),
(11, 'Banani', 23.7916, 90.4043, 82, '[\"Banani Police Station – 1.0km\", \"Chairman Bari Outpost – 2.0km\"]', 'Pleasant weather today.', 'Low', '[\"Street altercation near Road 27 – 4 days ago\"]', '{\"Ride-sharing cars\":\"Very Safe (Score: 93)\", \"CNGs\":\"Safe (Score: 75)\"}'),
(12, 'Khilgaon', 23.7554, 90.4056, 68, '[\"Khilgaon Police Station – 1.0km\", \"Khilgaon Chowdhury Police Box – 1.8km\"]', 'Light rain expected in the evening.', 'Medium', '[\"Burglary near Khilgaon Road – 2 days ago\"]', '{\"Rickshaws\":\"Moderate (Score: 60)\", \"Buses\":\"Low (Score: 45)\"}'),
(13, 'Tongi', 23.8775, 90.3716, 55, '[\"Tongi Police Station – 1.3km\", \"Bodur Bagan Police Outpost – 2.1km\"]', 'Heavy rain expected in the afternoon. Carry an umbrella.', 'High', '[\"Robbery near Tongi Station – 1 day ago\", \"Fight near Bazaar Road – 3 days ago\"]', '{\"CNGs\":\"Low (Score: 48)\", \"Buses\":\"Low (Score: 40)\"}'),
(14, 'Narayanganj', 23.6225, 90.502, 70, '[\"Narayanganj Police Station – 1.5km\", \"Narayanganj Bazar Police Post – 2.3km\"]', 'Partly cloudy with no rain forecast.', 'Medium', '[\"Snatching reported at the Market – 2 days ago\"]', '{\"Rickshaws\":\"Moderate (Score: 55)\", \"Buses\":\"Moderate (Score: 50)\"}'),
(15, 'Gazipur', 23.8474, 90.415, 65, '[\"Gazipur Police Station – 1.2km\", \"Gazipur City Corporation Police Box – 2.5km\"]', 'Clear skies expected. Safe for travel.', 'Low', '[\"Pickpocketing near Gazipur Bazar – 3 days ago\"]', '{\"CNGs\":\"Safe (Score: 75)\", \"Buses\":\"Moderate (Score: 60)\"}'),
(16, 'Savar', 23.8756, 90.3031, 72, '[\"Savar Police Station – 1.4km\", \"Nabinagar Police Box – 2.0km\"]', 'Overcast with light rain later in the evening.', 'Medium', '[\"Suspicious vehicle parked near Savar Bazar – 2 days ago\"]', '{\"Rickshaws\":\"Moderate (Score: 58)\", \"Buses\":\"Safe (Score: 70)\"}'),
(17, 'Ashulia', 23.8656, 90.3154, 68, '[\"Ashulia Police Station – 1.1km\", \"Ashulia Industrial Police Outpost – 2.2km\"]', 'Expected thunderstorms, stay indoors if possible.', 'High', '[\"Street harassment near Ashulia Bazar – 1 day ago\"]', '{\"Rickshaws\":\"Low (Score: 45)\", \"Buses\":\"Moderate (Score: 60)\"}'),
(18, 'Mirpur-10', 23.8022, 90.3638, 60, '[\"Mirpur Model Police Station – 0.5km\", \"Pallabi Police Station – 1.8km\"]', 'Partly cloudy, no rain forecast.', 'Medium', '[\"Robbery near Mirpur-10 – 4 days ago\"]', '{\"Rickshaws\":\"Moderate (Score: 55)\", \"CNGs\":\"Low (Score: 50)\"}'),
(19, 'Moghbazar', 23.7597, 90.3961, 74, '[\"Moghbazar Police Station – 1.0km\", \"Banglamotor Police Post – 2.0km\"]', 'Clear skies expected. Good weather for outdoor activities.', 'Low', '[\"Snatching incident near Moghbazar Circle – 5 days ago\"]', '{\"CNGs\":\"Safe (Score: 85)\", \"Buses\":\"Moderate (Score: 60)\"}'),
(20, 'Shyamoli', 23.7707, 90.3573, 64, '[\"Shyamoli Police Station – 1.2km\", \"Khamar Bari Police Box – 2.3km\"]', 'Possible drizzle later in the day.', 'Medium', '[\"Attempted robbery near Shyamoli Bus Stand – 3 days ago\"]', '{\"Rickshaws\":\"Moderate (Score: 58)\", \"Buses\":\"Low (Score: 42)\"}');

-- --------------------------------------------------------

--
-- Table structure for table `safety_distribution`
--

CREATE TABLE `safety_distribution` (
  `id` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `weightage_percent` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `safety_distribution`
--

INSERT INTO `safety_distribution` (`id`, `category`, `label`, `value`, `weightage_percent`) VALUES
(1, 'Lighting', 'Street Lights Functional', 85, 20),
(2, 'Lighting', 'Well-lit Alleys', 75, 15),
(3, 'Lighting', 'Main Road Illumination', 90, 25),
(4, 'Crowd', 'Daytime Crowds', 80, 10),
(5, 'Crowd', 'Nighttime Presence', 60, 8),
(6, 'Crowd', 'Marketplace Activity', 70, 12),
(7, 'Police Presence', 'Checkpoint Nearby', 65, 18),
(8, 'Police Presence', 'Patrol Frequency', 60, 20),
(9, 'Police Presence', 'Response to Alert', 75, 22),
(10, 'Lighting', 'Park Lighting', 50, 10),
(11, 'Crowd', 'Bus Stop Crowds', 77, 9),
(12, 'Police Presence', 'Station Proximity', 80, 23),
(13, 'Lighting', 'Rail Crossing Lights', 55, 11),
(14, 'Crowd', 'Foot Traffic Volume', 65, 7),
(15, 'Crowd', 'School Zone Activity', 78, 9.5),
(16, 'Police Presence', 'Quick FIR Lodging', 70, 19.5),
(17, 'Lighting', 'Tunnel Lighting', 62, 13),
(18, 'Lighting', 'Bridge Lighting', 58, 12.5),
(19, 'Crowd', 'Shopping Center Activity', 73, 11),
(20, 'Police Presence', 'Security Van Patrol', 67, 18.5),
(21, 'Lighting', 'Emergency Light Access', 61, 12.2),
(22, 'Crowd', 'Festival Crowd Safety', 85, 10.3),
(23, 'Police Presence', 'Women Police Unit', 78, 22.5),
(24, 'Lighting', 'Cinema Hall Lights', 69, 9.8),
(25, 'Crowd', 'Rail Station Safety', 72, 10.1),
(26, 'Police Presence', 'App Notification Response', 74, 19.7),
(27, 'Lighting', 'Commercial Light Signs', 88, 14.2),
(28, 'Crowd', 'Night Shift Movement', 59, 7.6),
(29, 'Police Presence', 'Thana Helpdesk Rating', 66, 16.3),
(30, 'Crowd', 'Public Gathering Spot', 70, 8.4);

-- --------------------------------------------------------

--
-- Table structure for table `safety_incidents`
--

CREATE TABLE `safety_incidents` (
  `id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `incident_count` int(11) NOT NULL DEFAULT 1,
  `incident_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `area_type` varchar(50) DEFAULT NULL,
  `reported_incidents` int(11) DEFAULT 0,
  `safety_rating` varchar(10) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `safety_incidents`
--

INSERT INTO `safety_incidents` (`id`, `category`, `area`, `latitude`, `longitude`, `incident_count`, `incident_date`, `description`, `area_type`, `reported_incidents`, `safety_rating`, `notes`) VALUES
(1, 'Theft', 'Gulshan-2', 23.7925, 90.4078, 3, '2025-08-05', 'Multiple phone snatching cases reported near Gulshan Circle 2.', 'urban', 5, 'High', 'Increased patrolling requested by residents.'),
(2, 'Harassment', 'Banani', 23.7933, 90.4042, 2, '2025-08-07', 'Street harassment incidents reported at Banani Road 11.', 'urban', 4, 'Moderate', 'Awareness programs scheduled by NGOs.'),
(3, 'Traffic Accident', 'Dhanmondi 27', 23.7465, 90.3742, 1, '2025-08-06', 'Minor bus-motorbike collision; no fatalities reported.', 'urban', 1, 'Low', 'Traffic police deployed for crowd control.'),
(4, 'Robbery', 'Mirpur-10', 23.8067, 90.3681, 4, '2025-08-08', 'Armed robbery in a local shop; cash and goods stolen.', 'urban', 6, 'High', 'Shopkeepers urged to install CCTV cameras.'),
(5, 'Pickpocketing', 'Farmgate', 23.7571, 90.3915, 5, '2025-08-09', 'Multiple pickpocket cases during peak rush hours.', 'urban', 8, 'High', 'Extra plainclothes officers deployed in the area.'),
(6, 'Vandalism', 'Motijheel', 23.7322, 90.4179, 2, '2025-08-03', 'Damage to ATM booth and nearby shop during protest.', 'urban', 3, 'Moderate', 'Businesses advised to close early during demonstrations.'),
(7, 'Assault', 'Khilgaon', 23.7556, 90.4308, 1, '2025-08-04', 'Physical altercation between two groups at local market.', 'urban', 2, 'Moderate', 'Community leaders mediating the conflict.'),
(8, 'Fraud', 'Uttara Sector 7', 23.8766, 90.3798, 2, '2025-08-02', 'Online scam traced to a small cybercafe.', 'urban', 2, 'Low', 'Cybercrime unit involved in investigation.'),
(9, 'Eve-teasing', 'Mohakhali', 23.7784, 90.4051, 3, '2025-08-01', 'Reports of harassment near Mohakhali bus terminal.', 'urban', 4, 'Moderate', 'Awareness drive by women’s rights groups.'),
(10, 'Burglary', 'Bashundhara R/A', 23.814, 90.4317, 1, '2025-08-05', 'House burglary in Block C during daytime.', 'suburban', 1, 'Low', 'Security guards instructed to enhance entry checks.');

-- --------------------------------------------------------

--
-- Table structure for table `safety_locations`
--

CREATE TABLE `safety_locations` (
  `id` int(11) NOT NULL,
  `location` varchar(100) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `area_type` varchar(100) DEFAULT NULL,
  `reported_incidents` int(11) DEFAULT 0,
  `safety_rating` enum('High','Moderate','Low') DEFAULT 'Moderate',
  `notes` text DEFAULT NULL,
  `patrolling` varchar(100) DEFAULT NULL,
  `street_lighting` varchar(100) DEFAULT NULL,
  `women_helpline` varchar(50) DEFAULT '109',
  `transport_access` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `safety_locations`
--

INSERT INTO `safety_locations` (`id`, `location`, `latitude`, `longitude`, `area_type`, `reported_incidents`, `safety_rating`, `notes`, `patrolling`, `street_lighting`, `women_helpline`, `transport_access`, `description`, `last_updated`) VALUES
(1, 'Gulshan 2', 23.7925, 90.4078, 'Commercial', 12, 'High', 'Business hub, embassies nearby.', 'Frequent', 'Good', '109', 'Good', 'Generally safe with visible security.', '2025-08-13 16:54:50'),
(2, 'Mirpur 10', 23.8045, 90.3667, 'Residential', 35, 'Moderate', 'Dense area, mixed reports.', 'Occasional', 'Average', '109', 'Good', 'Crowded intersections; stay alert at night.', '2025-08-13 16:54:50'),
(3, 'Old Dhaka', 23.7086, 90.406, 'Mixed', 52, 'Low', 'Narrow alleys, busy markets.', 'Occasional', 'Poor', '109', 'Limited', 'Avoid late-night alleys; prefer main roads.', '2025-08-13 16:54:50');

-- --------------------------------------------------------

--
-- Table structure for table `safety_ratings`
--

CREATE TABLE `safety_ratings` (
  `id` int(11) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `area_type` varchar(100) DEFAULT NULL,
  `reported_incidents` int(11) DEFAULT NULL,
  `safety_rating` varchar(10) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `safety_ratings`
--

INSERT INTO `safety_ratings` (`id`, `location`, `latitude`, `longitude`, `area_type`, `reported_incidents`, `safety_rating`, `notes`) VALUES
(1, 'Gulshan', 23.7925, 90.4078, 'Residential', 3, 'High', 'Well-lit, security patrols'),
(2, 'Mirpur', 23.8041, 90.3665, 'Mixed', 10, 'Medium', 'Moderate activity at night'),
(3, 'Uttara', 23.8759, 90.3795, 'Residential', 4, 'High', 'Safe and monitored'),
(4, 'Farmgate', 23.7568, 90.3885, 'Commercial', 15, 'Low', 'High crowd density'),
(5, 'Dhanmondi', 23.7461, 90.3748, 'Mixed', 6, 'Medium', 'Some isolated areas'),
(6, 'Old Dhaka', 23.7099, 90.4071, 'Dense Residential', 18, 'Low', 'Poor street lighting'),
(7, 'Banani', 23.7936, 90.4007, 'Commercial', 5, 'Medium', 'Generally safe, but avoid late hours'),
(8, 'Mohakhali', 23.7803, 90.4006, 'Commercial', 7, 'Medium', 'Busy during day, caution at night'),
(9, 'Badda', 23.7806, 90.4266, 'Residential', 9, 'Low', 'Reports of theft, poor lighting'),
(10, 'Tejgaon', 23.7598, 90.3925, 'Industrial', 6, 'Medium', 'Limited pedestrian presence'),
(11, 'Shantinagar', 23.737, 90.414, 'Mixed', 3, 'High', 'Active neighborhood watch'),
(12, 'Motijheel', 23.7325, 90.4142, 'Commercial', 12, 'Low', 'Busy hub, prone to petty crimes'),
(13, 'Khilgaon', 23.7554, 90.4056, 'Residential', 8, 'Medium', 'Moderate crowd density, some thefts reported'),
(14, 'Savar', 23.8756, 90.3031, 'Suburban', 4, 'High', 'Safe and peaceful, good street lighting'),
(15, 'Gazipur', 23.8474, 90.415, 'Suburban', 5, 'Medium', 'Low crime rate, some areas less monitored'),
(16, 'Ashulia', 23.8656, 90.3154, 'Industrial', 7, 'Low', 'Occasional accidents, some areas poorly lit'),
(17, 'Narayanganj', 23.6225, 90.502, 'Mixed', 9, 'Medium', 'High traffic, occasional petty thefts'),
(18, 'Tongi', 23.8775, 90.3716, 'Suburban', 10, 'Low', 'Some isolated incidents, poor street lighting'),
(19, 'Moghbazar', 23.7597, 90.3961, 'Commercial', 5, 'High', 'Generally safe with high police presence'),
(20, 'Shyamoli', 23.7707, 90.3573, 'Residential', 6, 'Medium', 'Some petty crimes, better safety at night');

-- --------------------------------------------------------

--
-- Stand-in structure for view `safety_ratings_view`
-- (See below for the actual view)
--
CREATE TABLE `safety_ratings_view` (
`id` int(11)
,`location` varchar(100)
,`latitude` float
,`longitude` float
,`area_type` varchar(100)
,`reported_incidents` int(11)
,`safety_rating` enum('High','Moderate','Low')
,`notes` text
);

-- --------------------------------------------------------

--
-- Table structure for table `safety_zones`
--

CREATE TABLE `safety_zones` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `patrolling` varchar(100) DEFAULT NULL,
  `streetLighting` varchar(100) DEFAULT NULL,
  `incidentReports` text DEFAULT NULL,
  `womenHelpline` varchar(100) DEFAULT NULL,
  `transportAccess` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `safety_zones`
--

INSERT INTO `safety_zones` (`id`, `name`, `latitude`, `longitude`, `level`, `patrolling`, `streetLighting`, `incidentReports`, `womenHelpline`, `transportAccess`, `description`, `color`) VALUES
(1, 'Gulshan 2', 23.7916, 90.4152, 'High', 'Frequent', 'Excellent', 'Low', 'Active Booth', 'High (CNG, ride-sharing)', 'Upscale diplomatic area with tight security', 'green'),
(2, 'Mirpur 10', 23.8065, 90.3681, 'Moderate', 'Moderate', 'Adequate', 'Occasional petty theft', 'Nearby police box', 'High (metro, buses)', 'Busy area, safe in day, stay alert at night', 'orange'),
(3, 'Uttara', 23.8759, 90.3795, 'High', 'Frequent', 'Excellent', 'Low', 'Active Booth', 'High (CNG, ride-sharing)', 'Upscale diplomatic area with tight security', 'green'),
(4, 'Khilkhet', 23.8311, 90.4243, 'Moderate', 'Moderate', 'Adequate', 'Occasional petty theft', 'Nearby police box', 'High (metro, buses)', 'Busy area, safe in day, stay alert at night', 'orange'),
(5, 'Farmgate', 23.7574, 90.3911, 'Moderate', 'Moderate', 'Average', 'Pickpocketing in crowds', 'Moderate response', 'Very High', 'Congested but central; crowded areas require alertness', 'orange'),
(6, 'Moghbazar', 23.7415, 90.4062, 'Low', 'Low', 'Poor', 'Harassment reported', 'Weak presence', 'Moderate', 'Some alleys unsafe, avoid walking alone late night', 'red'),
(7, 'Dhanmondi 27', 23.7464, 90.3732, 'High', 'High', 'Good', 'Minimal', 'Active', 'Good', 'Well-regulated residential & educational zone', 'green'),
(8, 'Mohakhali Bus Terminal', 23.7775, 90.4028, 'Low', 'Low', 'Dim', 'Frequent scams', 'Scattered presence', 'Very High', 'Major transit hub; crowded and chaotic', 'red'),
(9, 'Banani', 23.7925, 90.4043, 'Moderate', 'Regular', 'Satisfactory', 'Low after midnight', 'Nearby police patrol', 'Good', 'Mixed residential and nightlife area', 'orange'),
(10, 'Tejgaon', 23.755, 90.392, 'Moderate', 'Moderate', 'Average', 'Workplace harassment', 'Limited booths', 'High (offices and buses)', 'Industrial zone; relatively safe during work hours', 'orange'),
(11, 'Badda', 23.7808, 90.4268, 'Low', 'Low', 'Poor', 'Snatching reported', 'Weak presence', 'Moderate', 'Crowded but lacks adequate safety measures at night', 'red'),
(12, 'Shyamoli', 23.774, 90.3595, 'Moderate', 'Regular', 'Fair', 'Petty theft at night', 'Occasional patrol', 'High (buses, rickshaws)', 'Residential area, avoid dark alleys', 'orange'),
(13, 'Motijheel', 23.7326, 90.4174, 'Moderate', 'High', 'Bright', 'Rare incidents', 'Nearby booths', 'Very High (offices and transport)', 'Financial hub with good security during work hours', 'orange'),
(14, 'Rampura', 23.7548, 90.4269, 'Low', 'Rare', 'Dim', 'Assaults reported', 'No booths nearby', 'Moderate (rickshaws, local buses)', 'Narrow lanes and poor visibility at night', 'red'),
(15, 'Lalmatia', 23.75, 90.37, 'High', 'Regular', 'Excellent', 'Very low', 'Well-connected booths', 'Good (CNG, cycle rickshaw)', 'Quiet residential area with good lighting and security', 'green'),
(16, 'Mohammadpur', 23.7642, 90.3667, 'Moderate', 'Moderate', 'Adequate', 'Occasional theft', 'Nearby police box', 'Moderate (rickshaws, buses)', 'Residential area with some busy zones, stay alert at night', 'orange'),
(17, 'Gulshan 1', 23.7836, 90.4069, 'High', 'Frequent', 'Excellent', 'Low', 'Active Booth', 'High (CNG, ride-sharing)', 'Upscale area with good police presence and high security', 'green'),
(18, 'Banani DOHS', 23.79, 90.4113, 'High', 'Frequent', 'Excellent', 'Very low', 'Active Booth', 'High (ride-sharing, CNG)', 'Residential area with good lighting and security patrols', 'green'),
(19, 'Bashundhara', 23.7945, 90.4138, 'High', 'Frequent', 'Good', 'Low', 'Active Booth', 'Very High (CNG, ride-sharing)', 'Commercial and residential zone with strong security measures', 'green'),
(20, 'Puran Dhaka', 23.71, 90.41, 'Low', 'Low', 'Poor', 'High crime rate', 'Weak presence', 'Low (local transport)', 'Historical area with some unsafe spots, especially at night', 'red');

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_calls`
--

CREATE TABLE `scheduled_calls` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `scheduled_time` datetime NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scheduled_calls`
--

INSERT INTO `scheduled_calls` (`id`, `user_id`, `phone_number`, `scheduled_time`, `status`, `created_at`) VALUES
(0, 9, '1234567890', '2025-05-05 15:00:00', 'completed', '2025-05-04 17:36:20'),
(0, 9, '1234567890', '2025-05-06 12:10:00', 'completed', '2025-05-05 17:10:01');

-- --------------------------------------------------------

--
-- Table structure for table `trustednumbers`
--

CREATE TABLE `trustednumbers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `special_note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trustednumbers`
--

INSERT INTO `trustednumbers` (`id`, `user_id`, `title`, `phone_number`, `special_note`, `created_at`) VALUES
(0, 9, 'Mom', '1234567890', 'Always answers calls', '2025-05-04 17:28:11'),
(0, 9, 'Best Friend', '9876543210', 'Lives nearby', '2025-05-04 17:28:38'),
(0, 9, 'Dad', '1112223333', 'Emergency only', '2025-05-04 17:28:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`) VALUES
(9, 'Abdullah Shanto', 'abdullah@gmail.com', '$2y$10$gCQZEb1U5Dpy8gkQXZBfYe6dvXI5FnCVeQ0aTCHhAt5srBadIpypy', 'Students');

-- --------------------------------------------------------

--
-- Structure for view `safety_ratings_view`
--
DROP TABLE IF EXISTS `safety_ratings_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `safety_ratings_view`  AS SELECT `safety_locations`.`id` AS `id`, `safety_locations`.`location` AS `location`, `safety_locations`.`latitude` AS `latitude`, `safety_locations`.`longitude` AS `longitude`, `safety_locations`.`area_type` AS `area_type`, `safety_locations`.`reported_incidents` AS `reported_incidents`, `safety_locations`.`safety_rating` AS `safety_rating`, `safety_locations`.`notes` AS `notes` FROM `safety_locations` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `community_resources`
--
ALTER TABLE `community_resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crime_incidents`
--
ALTER TABLE `crime_incidents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crime_stats`
--
ALTER TABLE `crime_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crime_type_trends`
--
ALTER TABLE `crime_type_trends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergency_response`
--
ALTER TABLE `emergency_response`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feature_usage`
--
ALTER TABLE `feature_usage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback_bubble`
--
ALTER TABLE `feedback_bubble`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_loc_time` (`safety_location_id`,`occurred_at`);

--
-- Indexes for table `location_safety`
--
ALTER TABLE `location_safety`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_area_name` (`area_name`),
  ADD KEY `idx_area_name_like` (`area_name`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification_engagement`
--
ALTER TABLE `notification_engagement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `route_requests`
--
ALTER TABLE `route_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `safety_checks`
--
ALTER TABLE `safety_checks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `safety_distribution`
--
ALTER TABLE `safety_distribution`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `safety_incidents`
--
ALTER TABLE `safety_incidents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `safety_locations`
--
ALTER TABLE `safety_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_coord` (`latitude`,`longitude`),
  ADD KEY `idx_location` (`location`),
  ADD KEY `idx_rating` (`safety_rating`),
  ADD KEY `idx_area_type` (`area_type`);

--
-- Indexes for table `safety_ratings`
--
ALTER TABLE `safety_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `safety_zones`
--
ALTER TABLE `safety_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `community_resources`
--
ALTER TABLE `community_resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `crime_incidents`
--
ALTER TABLE `crime_incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `crime_stats`
--
ALTER TABLE `crime_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `crime_type_trends`
--
ALTER TABLE `crime_type_trends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `emergency_response`
--
ALTER TABLE `emergency_response`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `feature_usage`
--
ALTER TABLE `feature_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `feedback_bubble`
--
ALTER TABLE `feedback_bubble`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `incident_reports`
--
ALTER TABLE `incident_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_safety`
--
ALTER TABLE `location_safety`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `notification_engagement`
--
ALTER TABLE `notification_engagement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `route_requests`
--
ALTER TABLE `route_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `safety_checks`
--
ALTER TABLE `safety_checks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `safety_distribution`
--
ALTER TABLE `safety_distribution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `safety_incidents`
--
ALTER TABLE `safety_incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `safety_locations`
--
ALTER TABLE `safety_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `safety_ratings`
--
ALTER TABLE `safety_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `safety_zones`
--
ALTER TABLE `safety_zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD CONSTRAINT `incident_reports_ibfk_1` FOREIGN KEY (`safety_location_id`) REFERENCES `safety_locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
