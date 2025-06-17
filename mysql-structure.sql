--
-- Table structure for table `email_history`
--

CREATE TABLE `email_history` (
  `email_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniqid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `from_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `recipients` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `body_html` text COLLATE utf8_unicode_ci,
  `user_id` int(11) DEFAULT NULL,
  `site_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `for_module` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `for_id` int(11) DEFAULT NULL,
   PRIMARY KEY (`email_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
