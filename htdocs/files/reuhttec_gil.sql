CREATE DATABASE  IF NOT EXISTS `reuhttec_gil` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `reuhttec_gil`;
-- MySQL dump 10.14  Distrib 5.5.30-MariaDB, for debian-linux-gnu (i686)
--
-- Host: 127.0.0.1    Database: reuhttec_gil
-- ------------------------------------------------------
-- Server version	5.5.30-MariaDB-mariadb1~precise-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `cno` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cname` varchar(30) DEFAULT NULL,
  `street` varchar(30) DEFAULT NULL,
  `zip` int(10) unsigned NOT NULL,
  `phone` char(12) DEFAULT NULL,
  `pswd` varchar(65) DEFAULT NULL,
  PRIMARY KEY (`cno`),
  KEY `fk_customers_zipcodes1_idx` (`zip`),
  CONSTRAINT `fk_customers_zipcodes1` FOREIGN KEY (`zip`) REFERENCES `zipcodes` (`zip`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3334 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1111,'Charles','123 Main St.',67226,'316-636-5555','$2a$08$ZHuK7gvj.wFT1aZ9huNjnuAX8fwDjv/DSSodQwex.A.aGYw1YMWeG'),(2222,'Bertram','237 Ash Avenue',67226,'316-689-5555','$2a$08$ZHuK7gvj.wFT1aZ9huNjnuAX8fwDjv/DSSodQwex.A.aGYw1YMWeG'),(3333,'Barbara','111 Inwood St.',60606,'316-111-1234','$2a$08$Ep5vvDAWJ1OcsIo5bqvS1.Iy0aoyHA5iU27IZQZl0qI7h536.fy4e');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `eno` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ename` varchar(30) DEFAULT NULL,
  `zip` int(10) unsigned NOT NULL,
  `hdate` datetime DEFAULT NULL,
  PRIMARY KEY (`eno`),
  KEY `fk_employees_zipcodes_idx` (`zip`),
  CONSTRAINT `fk_employees_zipcodes` FOREIGN KEY (`zip`) REFERENCES `zipcodes` (`zip`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1003 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1000,'Jones',67226,'1995-12-12 00:00:00'),(1001,'Smith',60606,'1992-01-01 00:00:00'),(1002,'Brown',50302,'1994-09-01 00:00:00');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `odetails`
--

DROP TABLE IF EXISTS `odetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `odetails` (
  `ono` int(10) unsigned NOT NULL,
  `pno` int(10) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`ono`,`pno`),
  KEY `fk_odetails_orders1_idx` (`ono`),
  KEY `fk_odetails_parts1_idx` (`pno`),
  CONSTRAINT `fk_odetails_orders1` FOREIGN KEY (`ono`) REFERENCES `orders` (`ono`) ON UPDATE CASCADE,
  CONSTRAINT `fk_odetails_parts1` FOREIGN KEY (`pno`) REFERENCES `parts` (`pno`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `odetails`
--

LOCK TABLES `odetails` WRITE;
/*!40000 ALTER TABLE `odetails` DISABLE KEYS */;
INSERT INTO `odetails` VALUES (1020,10506,1),(1020,10507,1),(1020,10508,2),(1020,10509,3),(1021,10601,4),(1022,10601,1),(1022,10701,1),(1023,10800,1),(1023,10900,1);
/*!40000 ALTER TABLE `odetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `ono` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cno` int(10) unsigned NOT NULL,
  `eno` int(10) unsigned NOT NULL,
  `received` datetime DEFAULT NULL,
  `shipped` datetime DEFAULT NULL,
  PRIMARY KEY (`ono`),
  KEY `fk_orders_customers1_idx` (`cno`),
  KEY `fk_orders_employees1_idx` (`eno`),
  CONSTRAINT `fk_orders_customers1` FOREIGN KEY (`cno`) REFERENCES `customers` (`cno`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_employees1` FOREIGN KEY (`eno`) REFERENCES `employees` (`eno`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1024 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1020,1111,1000,'1994-12-10 00:00:00','1994-12-12 00:00:00'),(1021,1111,1000,'1995-01-12 00:00:00','1995-01-15 00:00:00'),(1022,2222,1001,'1995-02-13 00:00:00','1995-02-20 00:00:00'),(1023,3333,1000,'1996-06-20 00:00:00',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parts`
--

DROP TABLE IF EXISTS `parts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parts` (
  `pno` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pname` varchar(30) DEFAULT NULL,
  `qoh` int(10) unsigned DEFAULT NULL,
  `price` decimal(6,2) unsigned DEFAULT NULL,
  `olevel` int(11) DEFAULT NULL,
  PRIMARY KEY (`pno`)
) ENGINE=InnoDB AUTO_INCREMENT=10901 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parts`
--

LOCK TABLES `parts` WRITE;
/*!40000 ALTER TABLE `parts` DISABLE KEYS */;
INSERT INTO `parts` VALUES (10506,'Land Before Time I',200,19.99,20),(10507,'Land Before Time II',156,19.99,20),(10508,'Land Before Time III',190,19.99,20),(10509,'Land Before Time IV',60,19.99,20),(10601,'Sleeping Beauty',300,24.99,20),(10701,'When Harry Met Sally',120,19.99,30),(10800,'Dirty Harry',140,14.99,30),(10900,'Dr. Zhivago',100,24.99,30);
/*!40000 ALTER TABLE `parts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `pswd` varchar(65) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `sessionid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zipcodes`
--

DROP TABLE IF EXISTS `zipcodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zipcodes` (
  `zip` int(10) unsigned NOT NULL,
  `city` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`zip`),
  UNIQUE KEY `zip_UNIQUE` (`zip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zipcodes`
--

LOCK TABLES `zipcodes` WRITE;
/*!40000 ALTER TABLE `zipcodes` DISABLE KEYS */;
INSERT INTO `zipcodes` VALUES (50302,'Kansas City'),(54444,'Columbia'),(60606,'Fort Dodge'),(61111,'Fort Hays'),(66002,'Liberal'),(67226,'Wichita');
/*!40000 ALTER TABLE `zipcodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'reuhttec_gil'
--
/*!50003 DROP PROCEDURE IF EXISTS `SP_createUser` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_createUser`(
	_username VARCHAR(25),
	_pswd VARCHAR(65),
	_first_name VARCHAR(45),
	_last_name VARCHAR(45),
	_email VARCHAR(45)
)
BEGIN

DECLARE _error BOOLEAN;
DECLARE _error_msg VARCHAR(45);

	INSERT INTO user
	(username, pswd, first_name, last_name, email)
	VALUES
	(_username, _pswd, _first_name, _last_name, _email);

		IF(ROW_COUNT()=0) THEN
			SET _error = TRUE;
			SET _error_msg = 'Error al insertar usuario';
			SELECT _error, _error_msg;
		ELSE
			SET _error = FALSE;
			SELECT _error;
		END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `SP_Orders` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_Orders`(
 _cno INT
)
BEGIN

			SELECT o.cno, COUNT(o.received) AS received, COUNT(o.shipped) AS shipped
			FROM orders o
			WHERE cno = _cno
			GROUP BY o.cno
			;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `SP_top_sales` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_top_sales`(
)
BEGIN

			SELECT e.eno, e.ename, SUM(p.price*od.qty) AS sales
			FROM orders o
			INNER JOIN employees e ON e.eno = o.eno
			INNER JOIN odetails od ON od.ono = o.ono
			INNER JOIN parts p ON p.pno = od.pno
			GROUP BY e.eno, e.ename
			ORDER BY sales DESC
			LIMIT 3;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `SP_User_checkLogin` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_User_checkLogin`(
	_username VARCHAR(25)
)
BEGIN

DECLARE _error BOOLEAN;
DECLARE _error_msg TEXT;

IF (SELECT COUNT(cno) FROM customers WHERE cno = _username ) = 1 THEN

	SET _error = FALSE;
	SELECT _error, cno, pswd
	FROM customers WHERE cno = _username;

ELSE
	SET _error = TRUE;
	SET _error_msg = 'Error en el Usuario: Inexistente o bloqueado.';
	SELECT _error, _error_msg;
END IF;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-05-03 11:24:20
