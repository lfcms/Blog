<?php

class blog extends app
{
	public function init($args)
	{
		$ini = $this->ini;
		
		if($ini != '' && preg_match_all('/([^=]+)=([^&]+)(?:\&)?/', $ini, $match))
			$this->ini = array_combine($match[1], $match[2]);
	}
	
	//default
	public function main($vars)
	{
		/*if(isset($this->ini['format']) && $this->ini['format'] == 'grid')
			$this->grid();
		else*/ // This should be a plugin or setting in MySQL
			$this->paginate();
	}
	
	public function cat($args)
	{
		$args[1] = urldecode($args[1]);
		$this->paginate(0, 3, $args[1]);
	}
	
	public function p($vars)
	{
		if($vars[1] < 0) $vars[1] = 0;
		$this->paginate($vars[1]);
	}
	
	private function paginate($start = 0, $length = 3, $category = NULL)
	{
		/*$where = '';
		if(isset($this->ini['cat'])) 
		{
			$category = $this->ini['cat'];
			$where[] = "t.category = '".$this->ini['cat']."'";
		}
		
		if(!isset($where)) $where = '';
		else {
			$where = 'WHERE '.implode(' AND ', $where);
		}*/
		
		$where = ''; 
		if(isset($this->ini['cat']))
		{
			$category = $this->ini['cat'];
			$where = "WHERE blog_threads.category = '".$this->ini['cat']."'";
		}
		
		$start = $start*$length;
		// print blog articles
		$sql = "
			SELECT t.id, t.title, t.owner_id, t.content, t.date, t.category, u.display_name as user
			FROM blog_threads t
				LEFT JOIN lf_users u ON t.owner_id = u.id
			".$where."
			ORDER BY t.date DESC
			LIMIT ".$start.", ".$length."
		";
		
		$this->db->query($sql);
		while($row = $this->db->fetch())
			$blog[$row['id']] = $row;
		
		$categories = $this->db->fetchall('SELECT * FROM blog_threads '.$where);
		foreach($categories as $cat)
		{
			if(!isset($cat_count[$cat['category']])) $cat_count[$cat['category']] = 0;
			$cat_count[$cat['category']]++;
		}
		
		ob_start();
		include 'view/main.php';
		$out = ob_get_clean();
		$out = preg_replace(
			'/{(?:youtube|vimeo|embed):([^}]+)}/', 
			'<iframe src="$1" width="100%" height="400px" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
			$out
		);
		
		$prev = 'Newer';
		$next = 'Older';
		
		if($start > 0)
			$prev = '<a href="%appurl%p/'.(($start/$length) - 1).'">'.$prev.'</a>';
			
		$where = '';
		if(isset($this->ini['cat'])) 
			$where = "WHERE cat = '".$this->ini['cat']."'";
			
		$limit = $this->db->fetch('SELECT count(id) FROM blog_threads '.$where);
		if($start + $length < $limit['count(id)'])
			$next = '<a href="%appurl%p/'.($start/$length + 1).'">'.$next.'</a>';
			
		if($start/$length > 0)
			$page =  '/ Page '.($start/$length + 1);
		else
			$page = '';
			
		$out = str_replace(
			'<h2>Blog</h2>', 
			'<h2>
				'.$prev.' '.$next.' '.$page.'
			</h2>', 
			$out
		);
		
		echo $out;
		echo '<div style="clear:both;"></div>';
		echo $prev.' | '.$next;
	}
	
	public function view($vars)
	{	
		
		//Thread
		$thread = $this->db->fetch("
			SELECT t.id, t.title, t.category, t.owner_id, t.content, t.date, u.display_name as user
			FROM blog_threads t
			LEFT JOIN lf_users u ON t.owner_id = u.id
			WHERE t.id = ".intval($vars[1])."
		");
		
		if(!$thread) return '404 Post not found.';
		
		// get comments
		$options = '';
		$posts = array();
		$sql = "
			SELECT p.msg_id as id, p.sender_id as owner, p.reply, p.body as content, p.date, u.user
			FROM blog_comments p 
			LEFT JOIN lf_users u ON p.sender_id = u.id 
			WHERE p.parent_id = '".intval($vars[1])."'
		";
		
		//$this->db->query($sql);
		//while($row = $this->db->fetch())
		$data = $this->db->fetchall($sql);
		foreach($data as $row)
		{
			if($row['owner'] == 0) $row['user'] = '[deleted]';
			$posts[$row['reply']][] = $row;
					
			$options .= ' 
				<option value="'.$row['id'].'">
					Reply to '.$row['user'].' - '.$row['content'].'
				</options>
			';
		}
		
		/*ob_start();
		include('view/comments.php');
		$comments = ob_get_clean();*/

		ob_start();
		if(is_dir('../comments'))
		{
			$cwd = getcwd();
			chdir('../comments');
			$comments = $this->request->apploader('comments', 'blog/'.intval($vars[1]));
			$comments = str_replace('%appurl%', '%appurl%comment/', $comments);
			chdir($cwd);
			
			include 'view/thread.php';
			$out = ob_get_clean();
			$out = preg_replace(
				'/{(?:youtube|vimeo|embed):([^}]+)}/', 
				'<iframe src="$1" width="100%" height="400px" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
				$out
			);
		} else {
		 
			
			include 'view/thread.php';
			
			//include 'view/comments.php';
			$out = ob_get_clean();
			$out = preg_replace(
				'/{(?:youtube|vimeo|embed):([^}]+)}/', 
				'<iframe src="$1" width="100%" height="400px" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
				$out
			);
		}
		echo $out;
	}
	
	public function comment($vars)
	{
		
		$vars = array_slice($vars, 1);
		print_r($vars);
		print_r($_POST);
		
		$cwd = getcwd();
		chdir('../comments');
		$comments = $this->request->apploader('comments', 'blog/'.intval($_POST['cat']), $vars);
		$comments = str_replace('%appurl%', '%appurl%comment/', $comments);
		chdir($cwd);
		
		echo $comments;
	}
	
	public function mkpost($vars)
	{
		// Authenticated users only
		if($this->request->api('me') == 'anonymous') exit();
		
		
		$sql = "
			INSERT INTO blog_comments (`msg_id`, `date`,`parent_id`,`sender_id`,`device`,`link`,`body`,`reply`)
			VALUES (
				NULL, 
				NOW(), 
				".intval($vars[1]).",
				".$this->request->api('getuid').", 
				'desktop',
				'".$this->db->escape(htmlentities($_POST['msg'], ENT_QUOTES))."', 
				0, 
				".intval($_POST['reply'])."
			)
		";
		
		$this->db->query($sql);
		
		header('HTTP/1.1 302 Moved Temporarily');
		header('Location: '. $_SERVER['HTTP_REFERER']);
		exit();
	}
	
	public function sidebar($limit = 4)
	{
		$where = '';
		if(isset($this->ini['cat'])) 
		{
			$where .= " WHERE cat = '".$this->ini['cat']."'";
			if(isset($this->ini['cat'])) 
				$where .= " AND category = '".$this->ini['cat']."'";
		}
		
		$posts = $this->db->fetchall('SELECT t.*, u.display_name FROM blog_threads t LEFT JOIN lf_users u ON t.owner_id = u.id'.$where.' ORDER BY t.date DESC LIMIT 3');
		
		foreach($posts as $post)
		{
			$date = date_parse($post['date']);
			$day = $date['day'];
			$month = date("M", mktime(0, 0, 0, $date['month'], 10));
			?>
			<div>
				<p class="date"><span><?php echo $day; ?></span><?php echo strtoupper($month); ?></p>
				<p><?php echo substr($post['title'],0,20); ?></p>
				<p class="posted-by">posted by <span><?php echo $post['display_name']; ?></span> | <a href="%baseurl%blog/view/<?php echo $post['id']; ?>/">View comments</a></p>
			</div>
			<?php
		}
		?>
		<a href="%baseurl%blog/">Read More</a>
		<?php
	}
	
	/*
	public function grid($limit = 4)
	{
		$where = '';
		if(isset($this->ini['cat'])) 
		{
			$where .= " WHERE cat = '".$this->ini['cat']."'";
			if(isset($this->ini['cat'])) 
				$where .= " AND category = '".$this->ini['cat']."'";
		}
		
		$sql = "SELECT * FROM blog_threads".$where." LIMIT 8";
		$this->db->query($sql);
		$blog = $this->db->fetchall();

		?>
		<h2><?php echo $this->ini['cat']; ?></h2>
		<style type="text/css">
			.overlay:hover p { display: block !important; }
			.overlay:hover div span { display: none !important; }
		</style>
		<?php

		// Print blog posts
		foreach($blog as $post)
		{
			preg_match('/"([^"]+.(?:jpg|png|gif|JPG|PNG|GIF))"/', $post['content'], $match);
			
			$bg = 'http://placehold.it/200x200'; //default
			if(isset($match[1]))
				$bg = $match[1];

			?>
			<div style="width: 200px; background: #000; overflow: hidden; margin-bottom: 10px; float: left; margin-right: 10px;" class="overlay" >
				<a href="%appurl%view/<?=$post['id'];?>/">
					<p style="z-index: 100; font-weight: bold; position: absolute; float: left; color: white; width: 130px; font-size: 15px; display: none; background: url(%relbase%lf/media/transparent.png); width: 200px; height: 200px;"><span style="display: block; padding: 20px;"><?php echo date('M d, Y',strtotime($post['date'])); ?></span></p>
				</a>
				<div style="height: 200px">
					<span style="background: url(%relbase%lf/media/transparent.png); display: block; position: absolute; z-index: 99; float: left; color: white; padding: 20px; width: 160px; font-size: 16px;">
						<?=$post['title'];?>
					</span>
					<img height="200px" src="<?=$bg;?>" alt="" />
				</div>
			</div>
			<?php
		}
		?><div style="clear: both"></div><?php
	}*/
	
	public function latest($vars)
	{
		/*$sql = "SELECT * FROM blog_threads";
		$this->db->query($sql);
		$blog = $this->db->fetchall();*/

		$where = '';
		if(isset($this->ini['cat']))
		{
			$where = " WHERE cat = '".$this->ini['cat']."'";
			if(isset($this->ini['cat']))
				$where .= " AND category = '".$this->ini['cat']."'";
		}
		
		$latest = $this->db->fetch("SELECT * FROM blog_threads".$where." ORDER BY id DESC LIMIT 1");
		
		preg_match('/"([^"]+.(?:jpg|png|gif|JPG|PNG|GIF))"/', $latest['content'], $match);
		
		$bg = 'http://placehold.it/200x200'; //default
		if(isset($match[1]))
			$bg = $match[1];
		
		?>
		<article>
			<header><?php echo $this->ini['cat']; ?></header>
			<img width="200px" height="200px" src="<?php echo $bg; ?>" alt="#" title="#" />
			<!-- <script type="text/javascript" src="http://www.freshcontent.net/music_news_feed.php"></script> -->
			<a href="%baseurl%latest/view/<?php echo $latest['id']; ?>/"><?php echo $latest['title']; ?></a>
		</article>
		<?php
		
		/*
		?>
		<h2>Blog Posts</h2>
		<style type="text/css">
			.overlay:hover p { display: block !important; }
			.overlay:hover div span { display: none !important; }
		</style>
		<?php

		// Print blog posts
		foreach($blog as $post)
		{
			preg_match('/"([^"]+.jpg)"/', $post['content'], $match);
			
			$bg = 'http://placehold.it/200x200'; //default
			if(isset($match[1]))
				$bg = $match[1];

			?>
			<div style="width: 200px; background: #000; overflow: hidden; margin-bottom: 10px; float: left; margin-right: 10px;" class="overlay" >
				<a href="%appurl%view/<?=$post['id'];?>/">
					<p style="z-index: 100; font-weight: bold; position: absolute; float: left; color: white; width: 130px; font-size: 15px; display: none; background: url(%relbase%lf/media/transparent.png); width: 200px; height: 200px;"><span style="display: block; padding: 20px;"><?php echo date('M d, Y',strtotime($post['date'])); ?></span></p>
				</a>
				<div style="height: 200px">
					<span style="background: url(%relbase%lf/media/transparent.png); display: block; position: absolute; z-index: 99; float: left; color: white; padding: 20px; width: 160px; font-size: 16px;">
						<?=$post['title'];?>
					</span>
					<img height="200px" src="<?=$bg;?>" alt="" />
				</div>
			</div>
			<?php
		}
		?><div style="clear: both"></div><?php*/
	}
}

?>