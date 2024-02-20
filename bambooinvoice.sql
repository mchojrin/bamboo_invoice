-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2019 at 07:48 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bambooinvoice_orig`
--

-- --------------------------------------------------------

--
-- Table structure for table `bamboo_clientcontacts`
--

CREATE TABLE `bamboo_clientcontacts` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `first_name` varchar(25) DEFAULT NULL,
  `last_name` varchar(25) DEFAULT NULL,
  `title` varchar(75) DEFAULT NULL,
  `email` varchar(127) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `access_level` tinyint(1) DEFAULT '0',
  `supervisor` int(11) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `password_reset` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bamboo_clientcontacts`
--

INSERT INTO `bamboo_clientcontacts` (`id`, `client_id`, `first_name`, `last_name`, `title`, `email`, `phone`, `password`, `access_level`, `supervisor`, `last_login`, `password_reset`) VALUES
(1, 0, NULL, NULL, NULL, 'admin@admin.com', NULL, '6b3419f478e0d6a1fd7f969b703a243df83758167841f131c1f2fdd585af21a643aa806c0c4d29248133eaf540f64368ab5110ffce2f0c6a3f34320e689016d7QsaZKWNorKm/tImuLXQbmshIWSmQ59dSElwENdYEhng=', 1, NULL, 1478768371, '');

-- --------------------------------------------------------

--
-- Table structure for table `bamboo_clients`
--

CREATE TABLE `bamboo_clients` (
  `id` int(11) NOT NULL,
  `name` varchar(75) DEFAULT NULL,
  `address1` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(25) DEFAULT NULL,
  `country` varchar(25) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `tax_status` int(1) DEFAULT '1',
  `client_notes` mediumtext,
  `tax_code` varchar(75) DEFAULT '',
  `currency_type` varchar(20) DEFAULT '',
  `currency_symbol` varchar(9) DEFAULT '',
  `invoice_note_default` varchar(255) DEFAULT '',
  `days_payment_due` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bamboo_invoices`
--

CREATE TABLE `bamboo_invoices` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `dateIssued` date DEFAULT NULL,
  `payment_term` varchar(50) DEFAULT NULL,
  `tax1_desc` varchar(50) DEFAULT NULL,
  `tax1_rate` decimal(6,3) DEFAULT NULL,
  `tax2_desc` varchar(50) DEFAULT NULL,
  `tax2_rate` decimal(6,3) DEFAULT NULL,
  `invoice_note` text,
  `days_payment_due` int(3) UNSIGNED DEFAULT '30',
  `currency_type` varchar(20) DEFAULT '',
  `currency_symbol` varchar(9) DEFAULT '',
  `accounting_invoice_exchange_rate` decimal(20,6) DEFAULT '0.000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bamboo_invoice_histories`
--

CREATE TABLE `bamboo_invoice_histories` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `clientcontacts_id` varchar(255) DEFAULT NULL,
  `date_sent` date DEFAULT NULL,
  `contact_type` int(1) DEFAULT NULL,
  `email_body` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bamboo_invoice_items`
--

CREATE TABLE `bamboo_invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT '0',
  `amount` decimal(11,2) DEFAULT '0.00',
  `quantity` decimal(7,2) DEFAULT '1.00',
  `work_description` mediumtext,
  `taxable` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bamboo_invoice_payments`
--

CREATE TABLE `bamboo_invoice_payments` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `date_paid` date DEFAULT NULL,
  `amount_paid` float(7,2) DEFAULT NULL,
  `payment_note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bamboo_sessions`
--

CREATE TABLE `bamboo_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `timestamp` int(10) UNSIGNED DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `data` blob,
  `logged_in` int(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bamboo_sessions`
--

-- --------------------------------------------------------

--
-- Table structure for table `bamboo_settings`
--

CREATE TABLE `bamboo_settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(75) DEFAULT NULL,
  `address1` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(25) DEFAULT NULL,
  `country` varchar(25) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `primary_contact` varchar(75) DEFAULT NULL,
  `primary_contact_email` varchar(50) DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `logo_pdf` varchar(50) DEFAULT NULL,
  `invoice_note_default` varchar(255) DEFAULT NULL,
  `currency_type` varchar(20) DEFAULT NULL,
  `currency_symbol` varchar(9) DEFAULT '$',
  `tax_code` varchar(50) DEFAULT NULL,
  `tax1_desc` varchar(50) DEFAULT NULL,
  `tax1_rate` float(6,3) DEFAULT '0.000',
  `tax2_desc` varchar(50) DEFAULT NULL,
  `tax2_rate` float(6,3) DEFAULT '0.000',
  `save_invoices` char(1) DEFAULT 'n',
  `days_payment_due` int(3) UNSIGNED DEFAULT '30',
  `demo_flag` char(1) DEFAULT 'n',
  `display_branding` char(1) DEFAULT 'y',
  `bambooinvoice_version` varchar(9) DEFAULT NULL,
  `new_version_autocheck` char(1) DEFAULT 'n',
  `logo_realpath` char(1) DEFAULT 'n',
  `currency_type_accounting` varchar(20) DEFAULT '',
  `api_key` varchar(200) DEFAULT '',
  `exchange_surplus_middle_exchange_rate` decimal(20,6) DEFAULT '0.000000',
  `display_zero_tax` char(1) DEFAULT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bamboo_settings`
--

INSERT INTO `bamboo_settings` (`id`, `company_name`, `address1`, `address2`, `city`, `province`, `country`, `postal_code`, `website`, `primary_contact`, `primary_contact_email`, `logo`, `logo_pdf`, `invoice_note_default`, `currency_type`, `currency_symbol`, `tax_code`, `tax1_desc`, `tax1_rate`, `tax2_desc`, `tax2_rate`, `save_invoices`, `days_payment_due`, `demo_flag`, `display_branding`, `bambooinvoice_version`, `new_version_autocheck`, `logo_realpath`, `currency_type_accounting`, `api_key`, `exchange_surplus_middle_exchange_rate`, `display_zero_tax`) VALUES
(1, '', '', '', '', '', '', '', '', 'admin', 'admin@admin.com', 'logo11.jpg', 'logo11.jpg', '', 'CHF', '$', 'CHE-777.888.999 MWST', 'MwSt.', 7.700, '', 0.000, 'n', 30, 'n', 'y', '1.0.8', 'y', 'n', 'CHF', '', '3.500000', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bamboo_clientcontacts`
--
ALTER TABLE `bamboo_clientcontacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `bamboo_clients`
--
ALTER TABLE `bamboo_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bamboo_invoices`
--
ALTER TABLE `bamboo_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `invoice_number` (`invoice_number`);

--
-- Indexes for table `bamboo_invoice_histories`
--
ALTER TABLE `bamboo_invoice_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `clientcontacts_id` (`clientcontacts_id`);

--
-- Indexes for table `bamboo_invoice_items`
--
ALTER TABLE `bamboo_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `bamboo_invoice_payments`
--
ALTER TABLE `bamboo_invoice_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `bamboo_sessions`
--
ALTER TABLE `bamboo_sessions`
  ADD PRIMARY KEY (`id`,`ip_address`);

--
-- Indexes for table `bamboo_settings`
--
ALTER TABLE `bamboo_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `primary_contact_email` (`primary_contact_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bamboo_clientcontacts`
--
ALTER TABLE `bamboo_clientcontacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bamboo_clients`
--
ALTER TABLE `bamboo_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bamboo_invoices`
--
ALTER TABLE `bamboo_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bamboo_invoice_histories`
--
ALTER TABLE `bamboo_invoice_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bamboo_invoice_items`
--
ALTER TABLE `bamboo_invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bamboo_invoice_payments`
--
ALTER TABLE `bamboo_invoice_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bamboo_settings`
--
ALTER TABLE `bamboo_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
