-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Värd: localhost
-- Skapad: 02 nov 2012 kl 14:12
-- Serverversion: 5.5.24-log
-- PHP-version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `foodtime`
--
CREATE DATABASE `foodtime` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `foodtime`;


-- --------------------------------------------------------

--
-- Tabellstruktur `recipe`
--

CREATE TABLE IF NOT EXISTS `recipe` (
  `RecipeID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `RecipeName` varchar(75) NOT NULL,
  `RecipeIngredient` text NOT NULL,
  `RecipeDescription` text NOT NULL,
  `Severity` int(11) NOT NULL,
  PRIMARY KEY (`RecipeID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumpning av Data i tabell `recipe`
--

INSERT INTO `recipe` (`RecipeID`, `UserID`, `RecipeName`, `RecipeIngredient`, `RecipeDescription`, `Severity`) VALUES
(1, 1, 'Fiskbullar med senapsmak', '1Burk Arla Fiskbullar senap', 'Lägg det i en kastrull och låt koka i 15minuter', 1),
(2, 1, 'Fiskbullar med fisksmak', '1burk Arla Fiskbullar fisk', 'Lägg det i en kastrull och låt koka i 15minuter', 2);

-- --------------------------------------------------------

--
-- Tabellstruktur `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int(5) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(150) NOT NULL,
  `Email` varchar(75) NOT NULL,
  `Skill` tinyint(2) NOT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `IsAdmin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumpning av Data i tabell `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Email`, `Skill`, `UpdatedAt`, `IsAdmin`) VALUES
(1, 'Fisk', '642bf00d4210e8cb5b5266c6252887c9', 'murum@murum.nu', 4, '2012-10-26 10:46:52', 1);

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `recipe`
--
ALTER TABLE `recipe`
  ADD CONSTRAINT `recipe_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
