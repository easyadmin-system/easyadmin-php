INSERT INTO `eas_users` (`id`, `username`, `pass`, `security_level`, `authority`, `allowed`, `registration`, `last_login`, `last_ip`, `login_count`, `sex`, `star`, `email`, `notes`, `avatar`, `editor`, `loginscreen`) VALUES
(1, 'jan.elznic', 'fbb57a2d056c54e2d9ecf0054cf9f0da', 2, 5, 1, 0, 0, '127.0.0.1', 0, 0, 0, 'jan@elznic.com', '', 'jan_elznic.jpg', 1, 1),
(2, 'Admin', 'e10adc3949ba59abbe56e057f20f883e', 1, 3, 1, 0, 0, '127.0.0.1', 0, 2, 0, '', '', 'admin.png', 1, 1);

INSERT INTO `eas_sysconfig` (`variable`, `value`) VALUES
('site_title', 'Easy Admin System 3'),
('site_description', 'Easy Admin System - redakční systém pro snadnou správu webových stránek'),
('site_keywords', 'easy, admin, system, redakční, systém'),
('site_root_url', 'http://__DOMAIN__'),
('system_lock', '0'),
('login_timeout', '30'),
('updates_check', '0'),
('updates_check_interval', '30');

INSERT INTO `eas_languages` (`code`) VALUES ('cs');
