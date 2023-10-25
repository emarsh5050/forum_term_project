-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: erm2015_termProject
-- ------------------------------------------------------
-- Server version	5.7.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Forums`
--

DROP TABLE IF EXISTS `Forums`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Forums` (
  `FID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) NOT NULL,
  PRIMARY KEY (`FID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Forums`
--

LOCK TABLES `Forums` WRITE;
/*!40000 ALTER TABLE `Forums` DISABLE KEYS */;
INSERT INTO `Forums` (`FID`, `Name`) VALUES (1,'Parents');
/*!40000 ALTER TABLE `Forums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Images`
--

DROP TABLE IF EXISTS `Images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Images` (
  `ImID` int(11) NOT NULL AUTO_INCREMENT,
  `URL` int(11) NOT NULL,
  `AltTxt` varchar(200) NOT NULL,
  `PID` int(11) NOT NULL,
  PRIMARY KEY (`ImID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Images`
--

LOCK TABLES `Images` WRITE;
/*!40000 ALTER TABLE `Images` DISABLE KEYS */;
/*!40000 ALTER TABLE `Images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Post`
--

DROP TABLE IF EXISTS `Post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Post` (
  `PID` int(11) NOT NULL AUTO_INCREMENT,
  `postDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UID` int(11) NOT NULL,
  `TID` int(11) NOT NULL,
  `ApprovalStatus` tinyint(1) NOT NULL,
  `Rejected` tinyint(4) NOT NULL DEFAULT '0',
  `Content` varchar(5000) NOT NULL,
  PRIMARY KEY (`PID`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Post`
--

LOCK TABLES `Post` WRITE;
/*!40000 ALTER TABLE `Post` DISABLE KEYS */;
INSERT INTO `Post` (`PID`, `postDate`, `UID`, `TID`, `ApprovalStatus`, `Rejected`, `Content`) VALUES (28,'2022-11-25 06:56:29',1,3,1,1,'Var test first post now edited to not be offensive but better this time so it will work'),(61,'2022-11-28 08:21:00',1,55,1,0,'what kind of podcasts do you guys like?'),(65,'2022-11-28 23:39:43',13,57,1,0,'If you find a cat in need of a good home please let me know. I really want to take one in.'),(66,'2022-11-28 23:40:00',13,57,1,0,'What do cats eat? '),(67,'2022-11-28 23:40:57',1,58,1,0,'It\'s a tasty kind of sandwich.'),(70,'2022-11-29 20:16:58',23,60,0,1,'Content'),(73,'2022-11-29 20:29:23',24,61,1,0,'What do you love most about this beautiful park? Let everyone know!'),(74,'2022-11-29 20:59:16',25,57,1,0,'Does it cost a lot to take care of a cat?'),(75,'2022-11-29 21:04:11',25,62,1,0,'Does anyone have a robot who can take care of a cat?'),(79,'2022-12-02 02:11:54',1,3,1,0,'I want a snack\r\nchocolate?'),(80,'2022-12-02 02:53:17',1,3,0,1,'I think I did the thing I was trying to do!'),(81,'2022-12-02 02:59:16',13,3,1,0,'I like chocolate too!'),(82,'2022-12-02 22:02:20',1,64,1,0,'What is the best way?'),(85,'2022-12-02 22:55:15',13,67,1,0,'will it work'),(86,'2022-12-02 22:57:08',13,69,1,0,'I think I got it'),(87,'2022-12-02 23:00:49',13,69,1,0,'Trusted user post edited'),(91,'2022-12-03 02:57:14',1,62,1,0,'I\'ve got an off-brand roomba?'),(92,'2022-12-05 04:11:55',24,55,1,0,'PODCASTS ARE DUMB! YOU ARE DUMB! THIS IS ALL STUPID! '),(93,'2022-12-07 20:07:39',1,60,1,0,'lksdfajflkjfasd'),(95,'2022-12-07 20:13:15',13,64,1,0,'I like to use a straw, but just from a cup is also fine. Typically it goes in your mouth but not while you\'re breathing.'),(97,'2022-12-08 01:05:54',13,73,1,0,'I want to learn how to kayak!'),(98,'2022-12-08 01:06:36',13,73,1,0,'Should I buy a kayak? I want to try this.'),(99,'2022-12-08 01:11:27',25,73,1,1,'kayaks are stinky and dumb!!'),(100,'2022-12-19 21:44:14',29,61,0,0,'Water front\r\nFrog\r\n\"a\" \'b\' <c> /d/ [e]'),(101,'2022-12-19 21:45:14',29,61,0,0,'Another post by me'),(102,'2022-12-19 21:48:54',29,74,0,0,'Who has them???\r\n &quot;a&quot; \'b\' /d/ [e] &lt;c&gt;');
/*!40000 ALTER TABLE `Post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Thread`
--

DROP TABLE IF EXISTS `Thread`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Thread` (
  `TID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(500) NOT NULL,
  `PostDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `FPID` int(11) NOT NULL,
  `FID` int(11) NOT NULL,
  PRIMARY KEY (`TID`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Thread`
--

LOCK TABLES `Thread` WRITE;
/*!40000 ALTER TABLE `Thread` DISABLE KEYS */;
INSERT INTO `Thread` (`TID`, `Title`, `PostDate`, `FPID`, `FID`) VALUES (3,'Testing var','2022-11-25 06:56:29',28,1),(47,'will FPID be correct?','2022-11-27 20:08:33',49,1),(48,'approval test','2022-11-27 21:16:31',51,1),(55,'I like to listen to podcasts','2022-11-28 08:21:00',61,1),(56,'I would like to try some composting','2022-11-28 23:33:51',63,1),(57,'I want a cat','2022-11-28 23:39:43',65,1),(58,'I want to make a grilled cheese ','2022-11-28 23:40:57',67,1),(59,'HI','2022-11-29 20:14:27',68,1),(60,'Title','2022-11-29 20:16:58',70,1),(61,'What is Your Favorite Thing About River Legacy?','2022-11-29 20:29:23',73,1),(62,'Robot','2022-11-29 21:04:11',75,1),(63,'WATER','2022-11-29 21:18:10',78,1),(64,'I want to drink some water','2022-12-02 22:02:20',82,1),(70,'Shound now not be trsuted','2022-12-02 23:20:12',89,1),(71,'I want to do one last test','2022-12-07 20:12:29',94,1),(72,'Okay this is the last test!','2022-12-07 20:14:39',96,1),(73,'Kayaking tips for a beginner.','2022-12-08 01:05:54',97,1),(74,'Wisdom teeth','2022-12-19 21:48:54',102,1);
/*!40000 ALTER TABLE `Thread` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `UID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `EmailAdress` varchar(100) NOT NULL,
  `StreetAdress` varchar(200) NOT NULL,
  `City` varchar(100) NOT NULL,
  `State` varchar(100) NOT NULL,
  `Zip` bigint(20) NOT NULL DEFAULT '0',
  `UserImageURL` varchar(500) NOT NULL,
  `Admin` tinyint(1) NOT NULL DEFAULT '0',
  `TrustedUser` int(11) NOT NULL DEFAULT '0',
  `ParentID` int(11) NOT NULL,
  `ForumAcess` int(11) NOT NULL,
  `RejectedPosts` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`UID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` (`UID`, `UserName`, `Password`, `FirstName`, `LastName`, `EmailAdress`, `StreetAdress`, `City`, `State`, `Zip`, `UserImageURL`, `Admin`, `TrustedUser`, `ParentID`, `ForumAcess`, `RejectedPosts`) VALUES (1,'test','password','','','email@email.com','1234 Test Street','Testville','Ak',12345,'',1,3,0,1,2),(13,'Test2','1234','Sally','The Cow','email@email.co','Place St ','THISWORKSVILLE','',16000,'',0,3,0,1,5),(19,'repeatTest',' ksdsdam','','',' smksda,m',' ',' ',' ',0,'',0,0,0,1,0),(22,'Admin','adminPassword','','','place@thing.com','knddnkdsMLL','cdnkscv nmsD','',0,'',1,0,0,1,0),(23,'Derek','1234','','','derek.perkins@mavs.uta.edu','16789 street ','fort worth','Texas',0,'',0,3,0,1,2),(24,'FriendOFtheWorms17','testpassword','','','something@email.com','55 Somewhere Dr.','Somewhere','TX',0,'',0,0,0,1,0),(25,'test123','1234','','','me@here.com','123 Happy Ln','Arlington','TX',76123,'',0,3,0,1,1),(28,' StressedEmma',' 1234','','','skdjdsja@place.com','skjdKA','dsfjhakshfzs','dskafkd',123,'',0,0,0,1,0),(29,'Baby','1234','','','little@email.com','','','123',23456,'',0,0,0,0,0);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'erm2015_termProject'
--

--
-- Dumping routines for database 'erm2015_termProject'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-25 16:11:04
