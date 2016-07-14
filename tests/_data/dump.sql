/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Дамп таблицы doctors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `doctors`;

CREATE TABLE `doctors` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
	`description` text COLLATE utf8_bin,
	`added` datetime NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

LOCK TABLES `doctors` WRITE;
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;

INSERT INTO `doctors` (`id`, `name`, `description`, `added`)
VALUES
	(19,X'D09DD0B8D0BAD0BED0BBD0B0D0B920D09FD0B5D182D180D0BED0B2',X'D09FD0B5D0B4D0B8D0B0D182D180','2016-07-08 10:40:24');

/*!40000 ALTER TABLE `doctors` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы schedules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `schedules`;

CREATE TABLE `schedules` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`doctor_id` int(11) DEFAULT NULL,
	`date` date DEFAULT NULL,
	`time_from` int(11) DEFAULT NULL,
	`time_to` int(11) DEFAULT NULL,
	`client_data` text COLLATE utf8_bin,
	`reserve_status` int(1) DEFAULT '1',
	`added` datetime NOT NULL,
	PRIMARY KEY (`id`),
	KEY `doctor` (`doctor_id`),
	CONSTRAINT `doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;