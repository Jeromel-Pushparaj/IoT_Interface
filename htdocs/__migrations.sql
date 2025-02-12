DROP TABLE IF EXISTS `auth`;
CREATE TABLE `auth` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `blocked` int NOT NULL DEFAULT '0',
  `sec_email` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `bio` longtext NOT NULL,
  `avatar` varchar(1024) NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `dob` date DEFAULT NULL,
  `instagram` varchar(1024) DEFAULT NULL,
  `twitter` varchar(1024) DEFAULT NULL,
  `facebook` varchar(1024) DEFAULT NULL,
  KEY `id` (`id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id`) REFERENCES `auth` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `uid` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `login_time` datetime NOT NULL,
  `ip` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `active` tinyint(1) NOT NULL,
  `fingerprint` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`,`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE EXISTS `devices`;
CREATE TABLE `devices` (
  `deviceno` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `devicename` varchar(255) NOT NULL,
  `button` int NOT NULL DEFAULT '0',
  `slider` int NOT NULL DEFAULT '0',
  `display` int NOT NULL DEFAULT '0',
  `indicator` int NOT NULL DEFAULT '0',
  `timer` int NOT NULL DEFAULT '0',
  `desc` varchar(1025) NOT NULL,
  `id` INT NULL,
  INDEX (id)
) ENGINE='InnoDB' COLLATE 'utf8mb4_0900_ai_ci';

ALTER TABLE `devices`
ADD FOREIGN KEY (`deviceno`) REFERENCES `auth` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION