CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` longtext NOT NULL,
  `cat_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
