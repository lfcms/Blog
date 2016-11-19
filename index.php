<?php

include_once 'resource/blog.php';
include_once 'model/blog.php';
include_once 'controller/blog_index.php';
include_once '3rdparty/parsedown/Parsedown.php'; // LF.'system/lib/ is in our $PATH

echo \lf\resolveAppUrl( // resolves legacy %appurl% into \lf\requestGet('ActionUrl')
	(new \lf\user)->resolveIds( // resolves {user:39} into lf_users id 39's display_name
		(new \lf\cms)->mvc( (new blog_index), $_app['ini'] )
	)
);