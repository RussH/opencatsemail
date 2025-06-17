--
-- Table structure for table `email_history`
--

CREATE TABLE `email_history` (
  `email_history_id` int(11) NOT NULL,
  `from_address` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `recipients` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `user_id` int(11) DEFAULT NULL,
  `site_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `for_module` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `for_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `email_history`
--

INSERT INTO `email_history` (`email_history_id`, `from_address`, `recipients`, `text`, `user_id`, `site_id`, `date`, `for_module`, `for_id`) VALUES
(1056, 'test@test.co.uk', 'test@test.co.uk', 'Subject: Test\n\nMessage:\nsda', 1253, 180, '2018-11-13 15:00:20', 'candidates', 2);
(1056, 'test@test.co.uk', 'test@test.co.uk', 'Subject: Test\n\nMessage:\nsda', 1253, 180, '2018-11-13 15:00:20', 'contacts', 2);