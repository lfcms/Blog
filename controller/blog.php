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
		if(preg_match('/^([0-9]+)\-(.*)/', $vars[0], $match))
			return $this->view(array('lol',$match[1]));
		
		if(preg_match('/^[^0-9].*/', $vars[0], $match))
			return $this->cat(array('lol', $match[0]));
		
		return $this->paginate();
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
		if(is_null($category) && isset($this->ini['cat']) )
			$category = $this->ini['cat'];
		
		$where = ''; 
		if(!is_null($category))
			$where = "WHERE t.category = '".$category."'";
			
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
		
		
		
		foreach( (new orm)->fetchall($sql) as $row )
			$blog[$row['id']] = $row;
				
		// not 100% if joins work yet, but ^that would look like this
		/*(new BlogThreads)
			->cols('id, title, owner, content, date, category')
			->joinById( 
				(new LfUsers)
					->cols('display_name as user')
					->withFk('owner_id') 
			)
			->orderBy('date')
			->byCategory('Test')
			->limit(0, 3);*/		
		
		$categories = $this->db->fetchall('SELECT DISTINCT category FROM blog_threads t '.$where);
		foreach($categories as $cat)
		{
			if(!isset($cat_count[$cat['category']]))
				$cat_count[$cat['category']] = 0;
			$cat_count[$cat['category']]++;
		}
		
		ob_start();
		
		include 'view/blog.main.php';
		
		echo preg_replace(
			'/{(?:youtube|vimeo|embed):([^}]+)}/', 
			'<iframe src="$1" width="70%" height="400px" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
			ob_get_clean()
		);
		
		/*echo preg_replace(
			'/{youtube:https?://.*?\.youtu(?:be\..com|\.be)(?:([a-zA-Z0-9]+)|/watch?v=([a-zA-Z0-9]+))}/', 
			'<iframe src="https://www.youtube.com/embed/$1" width="70%" height="400px" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
			ob_get_clean()
		);*/ // this might work, havent tried it yet
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

		ob_start();
		include 'view/blog.view.php';
		echo preg_replace(
			'/{(?:youtube|vimeo|embed):([^}]+)}/', 
			'<iframe src="$1" width="100%" height="400px" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
			ob_get_clean()
		);
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
	}
}

?>