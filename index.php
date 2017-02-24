<?php

include_once 'resource/blog.php';
include_once 'model/blog.php';
include_once 'controller/blog_index.php';
include_once '3rdparty/parsedown/Parsedown.php'; // LF.'system/lib/ is in our $PATH

pre( 
	(new \blog\threads)->put(29, [
		'date' => '2016-11-27 22:31:32' // should rather be CURRENT TIMESTAMP by default and thus gone
		, 'owner_id' => (new \lf\user)->idFromSession()
		, 'category' => 'REST'
		, 'title' => 'RESTful Title'
		, 'content' => 'blog '
	]) 
);


//pre( (new \lf\orm)->desc('blog_threads') );
pre( (new \blog\threads)->get() );


echo \lf\resolveAppUrl( // resolves legacy `%appurl%` shortcuts into `\lf\requestGet('ActionUrl')`
	(new \lf\user)->resolveIds( // resolves `{user:39}` into `lf_users`-`id`-`39`'s `display_name`
		(new \lf\cms)->mvc( 
			(new blog_index), 
			$_app['ini'] 
		)
	)
);