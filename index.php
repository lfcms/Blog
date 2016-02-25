<?php

include 'model/blog.php';
include 'controller/blog_index.php';
include /*LF.'system/lib/*/ '3rdparty/parsedown/Parsedown.php';

echo \lf\resolveAppUrl(
	(new \lf\user)->resolveIds( // resolves {user:39} into lf_users id 39's display_name
		(new \lf\cms)->mvc( (new blog_index), $_app['ini'] )
	)
);