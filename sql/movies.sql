-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 08, 2025 at 08:10 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `movies_db`
--
CREATE DATABASE IF NOT EXISTS `movies_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `movies_db`;

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

DROP TABLE IF EXISTS `movies`;
CREATE TABLE IF NOT EXISTS `movies` (
  `movie_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `release_date` date NOT NULL,
  `genre` varchar(50) NOT NULL,
  `language` varchar(50) NOT NULL,
  `running_time` varchar(10) NOT NULL,
  `synopsis` text NOT NULL,
  `price_per_ticket` decimal(5,2) NOT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  `banner_url` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'now_showing',
  PRIMARY KEY (`movie_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `release_date`, `genre`, `language`, `running_time`, `synopsis`, `price_per_ticket`, `poster_url`, `banner_url`, `status`) VALUES
(1, 'Mission: Impossible - The Final Reckoning', '2025-05-12', 'Action, Adventure, Thriller', 'English', '2h 48m', 'Every choice, every mission, has all led to this. The further adventures of IMF agent Ethan Hunt.', 20.00, 'mission_impossible.jpg', 'mission_impossible_banner.jpg', 'now_showing'),
(7, '28 Years Later', '2025-06-19', 'Thriller', 'English', '1h 55m', 'In the post-apocalyptic world, the Rage Virus has returned and a group of survivors must survive in a world ravaged by hordes of the infected.', 18.00, '28.jpg', '28_banner.jpg', 'now_showing'),
(6, 'Jurassic World: Rebirth', '2025-07-02', 'Action', 'English', '2 h 14 m', 'Five years post-Jurassic World Dominion, an expedition braves isolated equatorial regions to extract DNA from three massive prehistoric creatures for a groundbreaking medical breakthrough.', 15.00, 'jurassic.jpg', 'jurassic_banner.jpg', 'now_showing'),
(5, 'F1 THE MOVIE', '2025-06-26', 'Sports', 'English', '2h 36m', 'Follows a Formula One driver who comes out of retirement to mentor and team with a younger driver.', 39.00, 'f1.jpg', 'f1_banner.jpg', 'now_showing'),
(8, 'The Fantastic Four: First Step', '2025-07-24', 'Adventure', 'English', 'N/A', 'Transported to an alternate universe, four young outsiders gain superhuman powers as they alter their physical form in shocking ways...', 4.00, '4', '4_banner', 'advance_sale'),
(9, 'Superman', '2025-07-11', 'Adventure', 'English', '2h 10m', 'Follows the titular superhero as he reconciles his heritage with his human upbringing. He is the embodiment of truth, justice and the American way in a world that views this as old-fashioned.', 12.00, 'superman', 'superman_banner', 'now_showing'),
(10, 'Demon Slayer: Kimetsu No Yaiba Infinity Castle', '2025-08-14', 'Animation', 'Japanese', '2h 35m', 'Tanjiro Kamado – a boy who joined an organization dedicated to hunting down demons called the Demon Slayer Corps after his younger sister Nezuko was turned into a demon.', 50.00, 'demon', 'demon_banner', 'coming_soon'),
(11, 'Smurfs', '2025-07-18', 'Animation', 'English', '1h 32m', 'When Papa Smurf is mysteriously taken by evil wizards, Razamel and Gargamel, Smurfette leads the Smurfs on a mission into the real world to save him. With the help of new friends, the Smurfs must discover what defines their destiny to save the universe.', 2.00, 'smurfs', 'smurfs_banner', 'now_showing'),
(12, 'I Know What You Did Last Summer', '2025-07-17', 'Horror', 'English', '1h 51m', 'A group of friends is terrorized by a stalker who knows about a gruesome incident from their past.', 13.00, 'iknow', 'iknow_banner', 'now_showing'),
(13, 'Elio', '2025-06-20', 'Animation', 'English', '1h 38m', 'Elio struggles to fit in until he is transported by aliens and becomes the chosen one to be Earth`s galactic ambassador while his mother Olga works on the top secret project to decode alien messages.', 10.00, 'elio', 'elio_banner', 'now_showing'),
(14, 'The Shadow\'s Edge', '2025-08-07', 'Crime', 'Cantonese', '2h 23m', 'A crew of criminal prodigies disappears with billions, evading capture by outsmarting the formidable “Sky Eye” surveillance system.', 12.00, 'the_shadow', 'the_shadow_banner', 'now_showing'),
(15, 'Weapons', '2025-08-08', 'Horror', 'English', '2h 9m', 'An interrelated, multistory horror epic about the disappearance of high school students in a small town.', 10.00, 'weapons', 'weapons_banner', 'now_showing');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `reservation_id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `movie_id` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `theater_id` int NOT NULL,
  `seats` varchar(50) NOT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `user_id` (`user_id`),
  KEY `movie_id` (`movie_id`),
  KEY `theater_id` (`theater_id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `movie_id`, `date`, `time`, `theater_id`, `seats`) VALUES
(1, 'user1', 1, '2025-05-22', '10:00:00', 1, 'C1, C2'),
(2, 'user1', 2, '2025-05-23', '12:00:00', 4, 'C1, D2'),
(4, 'user1', 6, '2025-07-12', '10:00:00', 4, 'C1'),
(5, 'user1', 5, '2025-07-06', '10:00:00', 3, 'B1,A1'),
(24, 'xc', 14, '2025-08-09', '15:10:00', 2, 'G6,G5'),
(8, 'user1', 8, '2025-07-14', '21:50:00', 3, 'C1'),
(31, 'xc', 8, '2025-08-08', '15:10:00', 3, 'G1'),
(28, 'xc', 13, '2025-08-10', '12:00:00', 2, 'G8,G7'),
(23, 'xc', 10, '2025-08-04', '10:00:00', 3, 'G1,A13,D7,D6,D5,D4'),
(16, 'xc', 11, '2025-08-03', '12:00:00', 3, 'G3,G4,G5'),
(17, 'admin1', 6, '2025-08-06', '15:10:00', 3, 'G6,G7'),
(34, 'user2', 10, '2025-08-08', '10:00:00', 3, 'G1'),
(20, 'admin1', 10, '2025-08-03', '21:50:00', 3, 'G3');

-- --------------------------------------------------------

--
-- Table structure for table `theaters`
--

DROP TABLE IF EXISTS `theaters`;
CREATE TABLE IF NOT EXISTS `theaters` (
  `theater_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  PRIMARY KEY (`theater_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `theaters`
--

INSERT INTO `theaters` (`theater_id`, `name`, `location`) VALUES
(1, 'Golden Theater Yishun', 'Yishun, Singapore'),
(2, 'Golden Theater Bishan', 'Bishan, Singapore'),
(3, 'Golden Theater Ang Mo Kio', 'Ang Mo Kio, Singapore'),
(4, 'Golden Theater Changi', 'Changi, Singapore');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(8) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `mobile`, `fullname`, `is_admin`) VALUES
('user1', 'meow@example.com', '123', '81234567', 'meow', 0),
('user2', 'user2_updated@example.com', '123', '91119111', 'John Doe', 0),
('admin1', 'admin@example.com', '123', '91234568', 'Admin User', 1),
('123', '123@gmail.com', '123', '1234', 'user123', 0),
('xc', 'changxiuchen39@gmail.comm', '123', '81231231', 'xc39', 0),
('xc123', '123changxiuchen39@gmail.com', '123', '88888888', 'xc123', 0),
('xceh', 'xc@gmail.com', '123', '88888889', 'xceh', 0),
('xiuchen', 'xceh@gmail.com', '123', '88669245', 'xiuchen', 0),
('cat', 'meow@gmail.com', '123', '99999999', 'meow', 0),
('ahkow', 'ahkow39@gmail.com', '1', '89898989', 'tanAhkow', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
