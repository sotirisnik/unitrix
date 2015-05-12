-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Φιλοξενητής: 127.0.0.1
-- Χρόνος δημιουργίας: 12 Μάη 2015 στις 22:39:24
-- Έκδοση διακομιστή: 5.6.20
-- Έκδοση PHP: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Βάση δεδομένων: `grader`
--

CREATE DATABASE IF NOT EXISTS grader;

use grader;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
`id` int(11) NOT NULL,
  `user_id` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
`id` int(11) NOT NULL,
  `title` text NOT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `competitions`
--

CREATE TABLE IF NOT EXISTS `competitions` (
`id` int(11) NOT NULL,
  `name` text NOT NULL,
  `duration` text NOT NULL,
  `start_date` datetime NOT NULL,
  `finish_date` datetime NOT NULL,
  `visible` int(11) NOT NULL,
  `access` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `competition_access`
--

CREATE TABLE IF NOT EXISTS `competition_access` (
  `user_id` text NOT NULL,
  `competition_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
`id` int(11) NOT NULL,
  `submit_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `passed` int(11) NOT NULL,
  `time` float NOT NULL,
  `output` text NOT NULL,
  `script_local_error` text NOT NULL,
  `exit_status` int(11) NOT NULL DEFAULT '0',
  `limit_exceeded` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `files_io`
--

CREATE TABLE IF NOT EXISTS `files_io` (
`id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `filename` text NOT NULL,
  `content` text NOT NULL,
  `isinput` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `fullnames`
--

CREATE TABLE IF NOT EXISTS `fullnames` (
  `username` varchar(256) NOT NULL,
  `fullname` text NOT NULL,
  `date` datetime NOT NULL,
  `date_login` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
`id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Άδειασμα δεδομένων του πίνακα `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
(0, 'C++'),
(1, 'Java'),
(2, 'C#'),
(3, 'C'),
(4, 'Python 2.7');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `problems`
--

CREATE TABLE IF NOT EXISTS `problems` (
`id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `short_name` text NOT NULL,
  `creator` text NOT NULL,
  `source` text NOT NULL,
  `intro` text NOT NULL,
  `input_info` text NOT NULL,
  `output_info` text NOT NULL,
  `sample_input` text NOT NULL,
  `sample_output` text NOT NULL,
  `explanation_output` text NOT NULL,
  `sample_input2` text NOT NULL,
  `sample_output2` text NOT NULL,
  `explanation_output2` text NOT NULL,
  `sample_input3` text NOT NULL,
  `sample_output3` text NOT NULL,
  `explanation_output3` text NOT NULL,
  `time_limit` int(11) NOT NULL,
  `mb_limit` int(11) NOT NULL,
  `limits` text NOT NULL,
  `tests` int(11) NOT NULL,
  `accepted_languages` text CHARACTER SET latin1 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `problemtest`
--

CREATE TABLE IF NOT EXISTS `problemtest` (
`id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `input` longtext NOT NULL,
  `output` longtext NOT NULL,
  `points` int(11) NOT NULL,
  `test_selection` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `admin`
--
ALTER TABLE `admin`
 ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `articles`
--
ALTER TABLE `articles`
 ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `competitions`
--
ALTER TABLE `competitions`
 ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `feedback`
--
ALTER TABLE `feedback`
 ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `files_io`
--
ALTER TABLE `files_io`
 ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `fullnames`
--
ALTER TABLE `fullnames`
 ADD PRIMARY KEY (`username`);

--
-- Ευρετήρια για πίνακα `languages`
--
ALTER TABLE `languages`
 ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `problems`
--
ALTER TABLE `problems`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD KEY `parent_id` (`parent_id`);

--
-- Ευρετήρια για πίνακα `problemtest`
--
ALTER TABLE `problemtest`
 ADD PRIMARY KEY (`id`), ADD KEY `problem_id` (`problem_id`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `admin`
--
ALTER TABLE `admin`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT για πίνακα `articles`
--
ALTER TABLE `articles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT για πίνακα `competitions`
--
ALTER TABLE `competitions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT για πίνακα `feedback`
--
ALTER TABLE `feedback`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT για πίνακα `files_io`
--
ALTER TABLE `files_io`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT για πίνακα `languages`
--
ALTER TABLE `languages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT για πίνακα `problems`
--
ALTER TABLE `problems`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT για πίνακα `problemtest`
--
ALTER TABLE `problemtest`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
