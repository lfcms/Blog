<?php

include_once 'model/blog.php';
include_once 'controller/blog_index.php';
include_once /*LF.'system/lib/*/ '3rdparty/parsedown/Parsedown.php';
		
echo \lf\resolveAppUrl(
	(new \lf\user)->resolveIds( // resolves {user:39} into lf_users id 39's display_name
		(new \lf\cms)->mvc( (new blog_index), $_app['ini'] )
	)
);