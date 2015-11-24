<?php

include 'model/blog.php';
include /*LF.'system/lib/*/ '3rdparty/parsedown/Parsedown.php';

echo (new User)->resolveIds( // resolves {user:39} into lf_users id 39's display_name
	$this->lf->mvc('blog_index', $_app['ini'])
);