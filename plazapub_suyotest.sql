-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 26-02-2018 a las 22:32:08
-- Versión del servidor: 5.5.52-cll
-- Versión de PHP: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `plazapub_suyotest`
--
CREATE DATABASE IF NOT EXISTS `plazapub_suyotest` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `plazapub_suyotest`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_banks`
--

DROP TABLE IF EXISTS `cache_banks`;
CREATE TABLE IF NOT EXISTS `cache_banks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `idt` int(11) NOT NULL AUTO_INCREMENT,
  `state` int(11) DEFAULT '0',
  `transactionID` int(11) DEFAULT NULL,
  `sessionID` varchar(32) DEFAULT NULL,
  `returnCode` varchar(30) DEFAULT NULL,
  `trazabilityCode` varchar(40) DEFAULT NULL,
  `transactionCycle` int(11) DEFAULT NULL,
  `transactionState` varchar(20) DEFAULT NULL,
  `bankCurrency` varchar(3) DEFAULT NULL,
  `bankFactor` float DEFAULT NULL,
  `bankURL` varchar(255) DEFAULT NULL,
  `responseCode` int(11) DEFAULT NULL,
  `responseReasonCode` varchar(3) DEFAULT NULL,
  `responseReasonText` varchar(255) DEFAULT NULL,
  `bankCode` varchar(4) DEFAULT NULL,
  `bankInterface` varchar(1) DEFAULT NULL,
  `reference` varchar(32) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `totalAmount` double DEFAULT NULL,
  `requestDate` datetime DEFAULT NULL,
  `bankProcessDate` datetime DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`idt`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `variables`
--

DROP TABLE IF EXISTS `variables`;
CREATE TABLE IF NOT EXISTS `variables` (
  `name` varchar(255) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
