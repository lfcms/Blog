CREATE TABLE `blog_threads` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `date` varchar(20) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `category` varchar(128) NOT NULL,
  `title` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `likes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- In case you are using an old blog table style
ALTER TABLE io_threads ADD category varchar(128) DEFAULT 'uncategorized';
anALTER TABLE io_threads ADD instance varchar(128) DEFAULT 'default';

CREATE TABLE `blog_comments` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(20) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `device` varchar(128) NOT NULL,
  `link` varchar(128) NOT NULL,
  `body` text NOT NULL,
  `likes` int(11) NOT NULL,
  `reply` int(11) NOT NULL,
  PRIMARY KEY (`msg_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

CREATE TABLE `blog_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `scope` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

