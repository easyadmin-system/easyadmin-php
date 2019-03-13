-- -------------------------------------- --
-- MySQL data pro redakční systém EAS 3.0 --
-- -------------------------------------- --

CREATE TABLE IF NOT EXISTS `eas_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `pass` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `security_level` tinyint(4) NOT NULL DEFAULT '1',
  `authority` int(11) NOT NULL DEFAULT '0',
  `allowed` tinyint(4) NOT NULL DEFAULT '0',
  `registration` int(11) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL DEFAULT '0',
  `last_ip` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `login_count` int(11) NOT NULL DEFAULT '0',
  `sex` tinyint(4) NOT NULL DEFAULT '0',
  `star` int(11) NOT NULL DEFAULT '0',
  `email` varchar(160) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `notes` text COLLATE utf8_czech_ci NOT NULL,
  `avatar` varchar(160) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `editor` tinyint(4) NOT NULL DEFAULT '0',
  `loginscreen` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

INSERT INTO `eas_users` (`id`, `username`, `pass`, `security_level`, `authority`, `allowed`, `registration`, `last_login`, `last_ip`, `login_count`, `sex`, `star`, `email`, `notes`, `avatar`, `editor`, `loginscreen`) VALUES
(1, 'jan.elznic', 'fbb57a2d056c54e2d9ecf0054cf9f0da', 2, 5, 1, 0, 0, '127.0.0.1', 0, 0, 0, 'jan.elznic@elzadesign.cz', '', 'jan_elznic.jpg', 1, 1),
(2, 'Admin', 'e10adc3949ba59abbe56e057f20f883e', 1, 3, 1, 0, 0, '127.0.0.1', 0, 2, 0, '', '', 'admin.png', 1, 1);

CREATE TABLE IF NOT EXISTS `eas_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `privileges` blob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `eas_groups_members` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`, `group_id`),
  FOREIGN KEY (`group_id`) REFERENCES eas_groups(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES eas_users(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

CREATE TABLE IF NOT EXISTS `eas_user_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL DEFAULT '0',
  `name` varchar(20) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `ip` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `httpua` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `eas_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `public` tinyint(4) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `meta_description` text COLLATE utf8_czech_ci NOT NULL,
  `priority` set('0','0.1','0.2','0.3','0.4','0.5','0.6','0.7','0.8','0.9','1.0') NOT NULL,
  `edit_frequency` set('always','hourly','daily','weekly','monthly','yearly','never') NOT NULL,
  `noindex` tinyint(4) NOT NULL DEFAULT '0',
  `keywords` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `publish_date` int(11) NOT NULL DEFAULT '0',
  `author` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `eas_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `public` tinyint(4) NOT NULL DEFAULT '0',
  `static` tinyint(4) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `meta_description` text COLLATE utf8_czech_ci NOT NULL,
  `priority` set('0','0.1','0.2','0.3','0.4','0.5','0.6','0.7','0.8','0.9','1.0') NOT NULL,
  `edit_frequency` set('always','hourly','daily','weekly','monthly','yearly','never') NOT NULL,
  `noindex` tinyint(4) NOT NULL DEFAULT '0',
  `keywords` varchar(255) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `publish_date` int(11) NOT NULL DEFAULT '0',
  `author` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `eas_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL DEFAULT '0',
  `page_id` int(11) NOT NULL DEFAULT '0',
  `type` set('text','html','code','image','youtube','gallery','poll','graph','map','form','mp3','comments') NOT NULL,
  `position` tinyint(4) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `eas_texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL DEFAULT '0',
  `content` text COLLATE utf8_czech_ci NOT NULL,
  FOREIGN KEY (`content_id`) REFERENCES eas_contents(`id`) ON DELETE CASCADE,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `eas_sysconfig` (
  `variable` varchar(64) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8_czech_ci NOT NULL,
  UNIQUE KEY `variable` (`variable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `eas_sysconfig` (`variable`, `value`) VALUES
('site_title', 'Easy Admin System 3'),
('site_description', 'Easy Admin System - redakční systém pro snadnou správu webových stránek'),
('site_keywords', 'easy, admin, system, redakční, systém'),
('site_root_url', 'http://easyadmin.dev'),
('system_lock', '0'),
('login_timeout', '30'),
('updates_check', '0'),
('updates_check_interval', '14');
