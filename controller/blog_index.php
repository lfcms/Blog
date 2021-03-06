<?php

class blog_index
{
	private $ini = '';
	public $theme = 'lf';
	public $chosenCategory = null;
	
	public function __construct()
	{
		(new \lf\template)->setTitle('Blog') ;
		
		$ini = $this->ini;
		
		// I was experimenting with stuff like grid=true&
		if($ini != '' && preg_match_all('/([^=]+)=([^&]+)(?:\&)?/', $ini, $match))
			$this->ini = array_combine($match[1], $match[2]);
	}
	
	// # Display the latest blog posts
	public function main()
	{
		$param = \lf\requestGet('Param');
		
		// if the URL matches format "###-some-title",
		if(isset($param[0]) && preg_match('/^([0-9]+)\-(.*)/', $param[0], $match))
			// rewrite to have post rendered
			return $this->viewPost($match[1]);
		else
			return $this->viewPostList();
	}
	
	private function viewPostList()
	{
		$filter = [];
		if(isset($this->ini['cat']))
			$filter['cat'] = $this->ini['cat'];
		
		$blog = (new blog)->loadThreads($filter);
		//$threads = $blog->getThreads()->getAll();
		//$limit = $blog->getThreads()->rowCount();
		
		$p = 1;
		if(isset($_GET['p']))
			$p = $_GET['p'];
		
		/* NEXT && PREV */
		$prev = '<i class="fa fa-caret-left"></i> Newer';
		$next = 'Older <i class="fa fa-caret-right"></i>';

		if($p > 1)
			$prev = '<a href="?p='.($p - 1).'">'.$prev.'</a>';

		if($p * $blog->ppp() < $blog->threadCount())
			$next = '<a href="?p='.($p + 1).'">'.$next.'</a>';
			
		$page =  '/ Page '.$p;
		/* END - NEXT && PREV */
		
		(new \lf\template)->appendTitle('Home');
		include 'view/blog.paginate.php';
	}
	
	
	private function viewPost($id)
	{
		
		$partial = 'blog.post';
		if(isset($_GET['api'])) //ezpz
			$partial = 'blog.json';
		
		$thread = (new BlogThreads)->getById($id);
		
		$post = (new \lf\cms)->partial(
			$partial, 
			array(
				'post' => $thread
			) 
		);
		
		(new \lf\template)->appendTitle($thread['title']); // SEO!
		
		?>
		<div class="row">
			<div class="col-9">
				<?=(new \lf\cms)->partial(
						$partial, 
						array(
							'post' => $thread
						));?>
			</div>
			<div class="col-3">
				<?php $this->printCategoryCount($this->chosenCategory); ?>
			</div>
		</div>
		<?php
	}
	
	public function printCategoryCount($chosenCategory = null)
	{
		// this should be its own table. counting all rows is slow.
		$catmap = [];
		foreach( (new BlogThreads)->cols('category')->getAll() as $value )
		{
			$category = $value['category'];
			
			if(!isset($catmap[$category]))
				$catmap[$category] = 0;
			$catmap[$category]++;
		}

		echo (new \lf\cms)->partial('blog.categories', 
			array(
				'catmap' => $catmap, 
				'category' => $chosenCategory
			) 
		);
	}
	
	public function latest()
	{
		return $this->partial(
			'blog.post', 
			array(
				'post' => (new BlogThreads)->order('id','DESC')->first()
			) 
		);
	}
	
	public function cat()
	{
		$args = \lf\requestGet('Param');
		
		$args[1] = urldecode($args[1]);
		$_GET['category'] = $args[1];
		$this->chosenCategory = $args[1];
		$this->main($args);
	}
	
	// this could be cool if we fixed it up a little. maybe move to blog model
	public function gridlist()
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