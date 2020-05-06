-- MySQL dump 10.13  Distrib 8.0.19, for osx10.15 (x86_64)
--
-- Host: localhost    Database: joshua
-- ------------------------------------------------------
-- Server version	8.0.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */;
/*!40103 SET TIME_ZONE = '+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

--
-- Table structure for table campus
--

DROP TABLE IF EXISTS campus;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE campus
(
    id         INT         NOT NULL AUTO_INCREMENT,
    city       VARCHAR(45) NOT NULL,
    country    VARCHAR(45) NOT NULL,
    flag       TEXT        NOT NULL,
    identifier VARCHAR(45) DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY identifier_UNIQUE (identifier)
) ENGINE = INNODB
  DEFAULT CHARSET = UTF8MB4
  COLLATE = UTF8MB4_GENERAL_CI;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table campus
--

LOCK TABLES campus WRITE;
/*!40000 ALTER TABLE campus
    DISABLE KEYS */;
INSERT INTO campus
VALUES (0, 'All campus', 'all', 'europe.svg', 'all'),
       (null, 'Bordeaux', 'France', 'france.svg', 'bdx'),
       (null, 'Biarritz', 'France', 'france.svg', 'biarritz'),
       (null, 'La Loupe', 'France', 'france.svg', 'loupe'),
       (null, 'Lille', 'France', 'france.svg', 'lille'),
       (null, 'Lyon', 'France', 'france.svg', 'lyon'),
       (null, 'Marseille', 'France', 'france.svg', 'marseille'),
       (null, 'Nantes', 'France', 'france.svg', 'nantes'),
       (null, 'Orléans', 'France', 'france.svg', 'orleans'),
       (null, 'Paris', 'France', 'france.svg', 'paris'),
       (null, 'Reims', 'France', 'france.svg', 'reims'),
       (null, 'Strasbourg', 'France', 'france.svg', 'strasbourg'),
       (null, 'Toulouse', 'France', 'france.svg', 'toulouse'),
       (null, 'Tours', 'France', 'france.svg', 'tours'),
       (null, 'Amsterdam', 'Netherlands', 'netherlands.svg', 'amsterdam'),
       (null, 'Barcelona', 'Spain', 'spain.svg', 'barcelona'),
       (null, 'Berlin', 'Germany', 'germany.svg', 'berlin'),
       (null, 'Brussels', 'Belgium', 'belgium.svg', 'brussels'),
       (null, 'Bucharest', 'Romania', 'romania.svg', 'bucharest'),
       (null, 'Budapest', 'Hungary', 'hungary.svg', 'budapest'),
       (null, 'Dublin', 'Ireland', 'ireland.svg', 'dublin'),
       (null, 'Lisbon', 'Portugal', 'portugal.svg', 'lisbon'),
       (null, 'London', 'England', 'england.svg', 'london'),
       (null, 'Madrid', 'Spain', 'spain.svg', 'madrid'),
       (null, 'Milan', 'Italy', 'italy.svg', 'milan');
/*!40000 ALTER TABLE campus
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table challenge
--

DROP TABLE IF EXISTS challenge;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE challenge
(
    id            INT         NOT NULL AUTO_INCREMENT,
    name          VARCHAR(45) NOT NULL,
    description   TEXT        NOT NULL,
    difficulty_id INT         NOT NULL,
    type_id       INT         NOT NULL,
    url           TEXT        NOT NULL,
    flag          TEXT        NOT NULL,
    created_on    DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_on    DATETIME             DEFAULT NULL,
    PRIMARY KEY (id, type_id, difficulty_id),
    KEY fk_challenge_difficulty_idx (difficulty_id),
    KEY fk_challenge_type_idx (type_id),
    CONSTRAINT fk_challenge_difficulty FOREIGN KEY (difficulty_id)
        REFERENCES difficulty (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_challenge_type FOREIGN KEY (type_id)
        REFERENCES type (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = INNODB
  DEFAULT CHARSET = UTF8MB4
  COLLATE = UTF8MB4_GENERAL_CI;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table challenge
--

LOCK TABLES challenge WRITE;
/*!40000 ALTER TABLE challenge
    DISABLE KEYS */;
INSERT INTO challenge
VALUES (null, 'Flag #1', 'Le premier drapeau de la quete', 1, 1, 'http://frvaillant.com/', 'test', now(), null),
       (null, 'Flag #2', 'Mon second flag', 4, 2, 'http://frvaillant.com/', 'test', now(), null),
       (null, 'Flag #3', 'Mon 3 flag', 3, 1, 'http://frvaillant.com/', 'test', now(), null),
       (null, 'Flag #4', 'Mon 4 flag', 2, 2, 'http://frvaillant.com/', 'test', now(), null),
       (null, 'Flag #5', 'Mon 5 flag', 5, 1, 'http://frvaillant.com/', 'test', now(), null);
/*!40000 ALTER TABLE challenge
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table contest
--

DROP TABLE IF EXISTS contest;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE contest
(
    id          INT         NOT NULL AUTO_INCREMENT,
    is_visible  TINYINT     NOT NULL DEFAULT 0,
    is_active    TINYINT     NOT NULL DEFAULT 0,
    name        VARCHAR(45) NOT NULL,
    image       TEXT                 DEFAULT NULL,
    description TEXT        NOT NULL,
    campus_id   INT         NOT NULL DEFAULT 0,
    duration    INT         NOT NULL,
    created_on  DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    started_on  DATETIME             DEFAULT NULL,
    PRIMARY KEY (id, campus_id),
    KEY fk_contest_campus_idx (campus_id),
    CONSTRAINT fk_contest_campus FOREIGN KEY (campus_id)
        REFERENCES campus (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = INNODB
  DEFAULT CHARSET = UTF8MB4
  COLLATE = UTF8MB4_GENERAL_CI;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table contest
--

LOCK TABLES contest WRITE;
/*!40000 ALTER TABLE contest
    DISABLE KEYS */;
INSERT INTO contest
VALUES (null,1,0,'Lord of the flag','https://i2.wp.com/gusandco.net/wp-content/uploads/2019/02/herr-der-ringe-knechten-lord-of-ring-1228268.jpg?fit=3840%2C2160&ssl=1','Will you be able to take all the flags? Would you manage to crush all your competitors and become the lord of the flags?',0,180,now(),null),
       (null,1,0,'Flag wars','https://cdn.pocket-lint.com/r/s/1200x/assets/images/147767-tv-feature-what-order-should-you-watch-all-the-star-wars-films-image1-1wdfjceytb.jpg','Get on your spaceship and go hunting for flags, may the force be with you!',1,120,now(),null),
       (null,1,0,'Wild contest',null,'Cause when you look like that, i\'ve never ever wanted to be so bad, oh it drives me wild !',3,480,now(),null),
       (null,1,0,'Coca Cola Symphony','http://frvaillant.com/wcs/joshua/Coca.png','Open a coke, open happiness, CHEERS !',1,1440,now(),null);
/*!40000 ALTER TABLE contest
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table contest_has_challenge
--

DROP TABLE IF EXISTS contest_has_challenge;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE contest_has_challenge
(
    contest_id      INT NOT NULL,
    order_challenge INT NOT NULL,
    challenge_id    INT NOT NULL,
    PRIMARY KEY (contest_id, challenge_id),
    KEY fk_contest_has_challenge_challenge_idx (challenge_id),
    KEY fk_contest_has_challenge_contest_idx (contest_id),
    CONSTRAINT fk_contest_has_challenge_challenge FOREIGN KEY (challenge_id)
        REFERENCES challenge (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_contest_has_challenge_contest FOREIGN KEY (contest_id)
        REFERENCES contest (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = INNODB
  DEFAULT CHARSET = UTF8MB4
  COLLATE = UTF8MB4_GENERAL_CI;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table contest_has_challenge
--

LOCK TABLES contest_has_challenge WRITE;
/*!40000 ALTER TABLE contest_has_challenge
    DISABLE KEYS */;
INSERT INTO contest_has_challenge
VALUES (1, 1, 1),
       (1, 2, 2),
       (2, 1, 2);
/*!40000 ALTER TABLE contest_has_challenge
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table difficulty
--

DROP TABLE IF EXISTS difficulty;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE difficulty
(
    id    INT         NOT NULL AUTO_INCREMENT,
    title VARCHAR(45) NOT NULL,
    level VARCHAR(45) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY difficulty_UNIQUE (title),
    UNIQUE KEY level_UNIQUE (level)
) ENGINE = INNODB
  DEFAULT CHARSET = UTF8MB4
  COLLATE = UTF8MB4_GENERAL_CI;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table difficulty
--

LOCK TABLES difficulty WRITE;
/*!40000 ALTER TABLE difficulty
    DISABLE KEYS */;
INSERT INTO difficulty
VALUES (null, 'Easy', '1'),
       (null, 'Medium', '2'),
       (null, 'Hard', '3'),
       (null, 'Pro', '4'),
       (null, 'Nightmare', '5');
/*!40000 ALTER TABLE difficulty
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table specialty
--

DROP TABLE IF EXISTS specialty;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE specialty
(
    id         INT         NOT NULL AUTO_INCREMENT,
    title      VARCHAR(45) NOT NULL,
    identifier VARCHAR(45) DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY identifier_UNIQUE (identifier)
) ENGINE = INNODB
  DEFAULT CHARSET = UTF8MB4
  COLLATE = UTF8MB4_GENERAL_CI;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table specialty
--

LOCK TABLES specialty WRITE;
/*!40000 ALTER TABLE specialty
    DISABLE KEYS */;
INSERT INTO specialty
VALUES (null, 'PHP - Symfony', 'php'),
       (null, 'Java - Angular', 'java'),
       (null, 'React - Node JS', 'js'),
       (null, 'Data Analyst', 'data');
/*!40000 ALTER TABLE specialty
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table type
--

DROP TABLE IF EXISTS type;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE type
(
    id         INT         NOT NULL AUTO_INCREMENT,
    title      VARCHAR(45) NOT NULL,
    identifier VARCHAR(45) DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY identifier_UNIQUE (identifier)
) ENGINE = INNODB
  DEFAULT CHARSET = UTF8MB4
  COLLATE = UTF8MB4_GENERAL_CI;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table type
--

LOCK TABLES type WRITE;
/*!40000 ALTER TABLE type
    DISABLE KEYS */;
INSERT INTO type
VALUES (1, 'sql', 'sql'),
       (2, 'pass', 'pass');
/*!40000 ALTER TABLE type
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table user
--

DROP TABLE IF EXISTS user;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE user
(
    id           INT          NOT NULL AUTO_INCREMENT,
    is_admin     TINYINT      NOT NULL DEFAULT 0,
    is_active    TINYINT      NOT NULL DEFAULT 1,
    lastname     VARCHAR(45)  NOT NULL,
    firstname    VARCHAR(45)  NOT NULL,
    pseudo       VARCHAR(45)  NOT NULL,
    github       VARCHAR(45)           DEFAULT NULL,
    email        VARCHAR(80)  NOT NULL,
    password     VARCHAR(255) NOT NULL,
    specialty_id INT          NOT NULL,
    campus_id    INT          NOT NULL,
    created_on   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_on   DATETIME              DEFAULT NULL,
    PRIMARY KEY (id, specialty_id, campus_id),
    UNIQUE KEY pseudo_UNIQUE (pseudo),
    UNIQUE KEY email_UNIQUE (email),
    KEY fk_user_campus_idx (campus_id),
    KEY fk_user_specialty_idx (specialty_id),
    CONSTRAINT fk_user_campus FOREIGN KEY (campus_id)
        REFERENCES campus (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_user_specialty FOREIGN KEY (specialty_id)
        REFERENCES specialty (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = INNODB
  DEFAULT CHARSET = UTF8MB4
  COLLATE = UTF8MB4_GENERAL_CI;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table user
--

LOCK TABLES user WRITE;
/*!40000 ALTER TABLE user
    DISABLE KEYS */;
INSERT INTO user
VALUES (null, '1', '1', 'Erpeldinger', 'Guillaume', 'Nighter', 'Nighter33', 'erpeldinger.g@free.fr', '$2y$10$YpNUQG6fRpKSp4HKrNXjMORak8FT2/ZUj3vZ/bYCO7CaJRuq8XEPO' , '1', '1', now(), null),
       (null, '1', '1', 'Vaillant', 'François', 'Frvaillant', 'Frvaillant', 'frvaillant@gmail.com', '$2y$10$YpNUQG6fRpKSp4HKrNXjMORak8FT2/ZUj3vZ/bYCO7CaJRuq8XEPO' , '1', '1', now(), null),
       (null, '1', '1', 'Regnault', 'Marien', 'Green-onions', 'Green-onions', 'regnault.marien@gmail.com', '$2y$10$YpNUQG6fRpKSp4HKrNXjMORak8FT2/ZUj3vZ/bYCO7CaJRuq8XEPO' , '1', '1', now(), null),
       (null, '1', '1', 'Fourcade', 'Caroline', 'Caro', 'Carolinefourcade', 'carolinefourcade@yahoo.fr', '$2y$10$YpNUQG6fRpKSp4HKrNXjMORak8FT2/ZUj3vZ/bYCO7CaJRuq8XEPO' , '1', '1', now(), null),
       (null, '1', '1', 'Harari', 'Guillaume', 'Guillaumebdx', 'Guillaumebdx', 'guillaumeharari@hotmail.com', '$2y$10$YpNUQG6fRpKSp4HKrNXjMORak8FT2/ZUj3vZ/bYCO7CaJRuq8XEPO' , '1', '1', now(), null),
       (null, '0', '1', 'Test', 'Test', 'Test', 'Test', 'test@test.fr', '$2y$10$YpNUQG6fRpKSp4HKrNXjMORak8FT2/ZUj3vZ/bYCO7CaJRuq8XEPO' , '3', '1', now(), null);
/*!40000 ALTER TABLE user
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table user_has_contest
--

DROP TABLE IF EXISTS user_has_contest;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE user_has_contest
(
    user_id      INT      NOT NULL,
    contest_id   INT      NOT NULL,
    challenge_id INT      NOT NULL,
    started_on   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ended_on     DATETIME          DEFAULT NULL,
    PRIMARY KEY (user_id, contest_id, challenge_id),
    KEY fk_user_has_contest_contest_idx (contest_id),
    KEY fk_user_has_contest_user_idx (user_id),
    KEY fk_user_has_contest_challenge_idx (challenge_id),
    CONSTRAINT fk_user_has_contest_challenge FOREIGN KEY (challenge_id)
        REFERENCES challenge (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_user_has_contest_contest FOREIGN KEY (contest_id)
        REFERENCES contest (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_user_has_contest_user FOREIGN KEY (user_id)
        REFERENCES user (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = INNODB
  DEFAULT CHARSET = UTF8MB4
  COLLATE = UTF8MB4_GENERAL_CI;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table user_has_contest
--

LOCK TABLES user_has_contest WRITE;
/*!40000 ALTER TABLE user_has_contest
    DISABLE KEYS */;
INSERT INTO user_has_contest
VALUES (1,1,1,'2020-05-05 10:05:18','2020-05-05 10:15:25'),
       (1,1,2,'2020-05-05 10:15:25','2020-05-05 10:22:46'),
       (1,1,3,'2020-05-05 10:22:46','2020-05-05 10:36:55'),
       (1,1,4,'2020-05-05 10:36:55','2020-05-05 10:55:05'),
       (1,1,5,'2020-05-05 10:55:05','2020-05-05 11:23:36'),
       (1,1,6,'2020-05-05 11:23:36','2020-05-05 11:44:59'),
       (1,1,7,'2020-05-05 11:44:59','2020-05-05 11:59:25'),
       (1,1,8,'2020-05-05 11:59:25','2020-05-05 12:32:18'),
       (1,1,9,'2020-05-05 12:32:18','2020-05-05 12:37:29'),
       (2,1,1,'2020-05-05 10:07:02','2020-05-05 10:18:24'),
       (2,1,2,'2020-05-05 10:18:24','2020-05-05 10:21:05'),
       (2,1,3,'2020-05-05 10:21:05','2020-05-05 10:32:07'),
       (2,1,4,'2020-05-05 10:32:07','2020-05-05 10:48:34'),
       (2,1,5,'2020-05-05 10:48:34','2020-05-05 11:28:10'),
       (2,1,6,'2020-05-05 11:28:10','2020-05-05 11:47:41'),
       (2,1,7,'2020-05-05 11:47:41','2020-05-05 12:02:28'),
       (2,1,8,'2020-05-05 12:02:28','2020-05-05 12:38:14'),
       (2,1,9,'2020-05-05 12:38:14','2020-05-05 12:42:39'),
       (3,1,1,'2020-05-05 10:03:39','2020-05-05 10:15:03'),
       (3,1,2,'2020-05-05 10:15:03','2020-05-05 10:24:53'),
       (3,1,3,'2020-05-05 10:24:53','2020-05-05 10:38:00'),
       (3,1,4,'2020-05-05 10:38:00','2020-05-05 10:57:09'),
       (3,1,5,'2020-05-05 10:57:09','2020-05-05 11:24:51'),
       (3,1,6,'2020-05-05 11:24:51','2020-05-05 11:47:32'),
       (3,1,7,'2020-05-05 11:47:32','2020-05-05 12:05:25'),
       (3,1,8,'2020-05-05 12:05:25','2020-05-05 12:36:29'),
       (3,1,9,'2020-05-05 12:36:29','2020-05-05 12:48:23'),
       (4,1,1,'2020-05-05 10:02:18','2020-05-05 10:25:17'),
       (4,1,2,'2020-05-05 10:25:17','2020-05-05 10:45:22'),
       (4,1,3,'2020-05-05 10:45:22','2020-05-05 11:00:10'),
       (4,1,4,'2020-05-05 11:00:10','2020-05-05 11:28:37'),
       (4,1,5,'2020-05-05 11:28:37','2020-05-05 11:57:36'),
       (4,1,6,'2020-05-05 11:57:36','2020-05-05 12:17:01'),
       (4,1,7,'2020-05-05 12:17:01','2020-05-05 12:43:25'),
       (4,1,8,'2020-05-05 12:43:25',null),
       (5,1,1,'2020-05-05 10:01:02','2020-05-05 10:05:02'),
       (5,1,2,'2020-05-05 10:05:02','2020-05-05 10:12:46'),
       (5,1,3,'2020-05-05 10:12:46',null),
       (6,1,1,'2020-05-05 10:00:54','2020-05-05 10:31:41'),
       (6,1,2,'2020-05-05 10:31:41','2020-05-05 11:00:41'),
       (6,1,3,'2020-05-05 11:00:41','2020-05-05 11:36:02'),
       (6,1,4,'2020-05-05 11:36:02','2020-05-05 12:02:05'),
       (6,1,5,'2020-05-05 12:02:05','2020-05-05 12:31:07'),
       (6,1,6,'2020-05-05 12:31:07','2020-05-05 12:59:03'),
       (6,1,7,'2020-05-05 12:59:03',null);
/*!40000 ALTER TABLE user_has_contest
    ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE = @OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;

-- Dump completed on 2020-04-24 16:08:25
