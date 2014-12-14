CREATE TABLE IF NOT EXISTS `blog_threads` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `date` varchar(20) NOT NULL,
  `owner_id` int(5) NOT NULL,
  `instance` varchar(128) NOT NULL,
  `category` varchar(128) NOT NULL,
  `title` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `likes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- In case you are using an old blog table style
ALTER TABLE io_threads ADD category varchar(128) DEFAULT 'uncategorized';
ALTER TABLE io_threads ADD instance varchar(128) DEFAULT 'default';

CREATE TABLE IF NOT EXISTS `blog_messages` (
  `msg_id` int(5) NOT NULL AUTO_INCREMENT,
  `date` varchar(20) NOT NULL,
  `parent_id` int(5) NOT NULL,
  `sender_id` int(5) NOT NULL,
  `device` varchar(25) NOT NULL,
  `link` varchar(50) NOT NULL,
  `body` text NOT NULL,
  `likes` int(11) NOT NULL,
  `reply` int(11) NOT NULL,
  PRIMARY KEY (`msg_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

RENAME TABLE io_threads TO blog_threads, io_messages TO blog_messages;