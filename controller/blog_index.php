<?php

if(!class_exists('blog'))                                         
    include 'model/blog.php';

class blog_index extends app
{
	public $theme = 'lf';
	
	public function init($args)
	{
		$ini = $this->ini;
		
		// I was experimenting with stuff like grid=true&
		if($ini != '' && preg_match_all('/([^=]+)=([^&]+)(?:\&)?/', $ini, $match))
			$this->ini = array_combine($match[1], $match[2]);
	}
	
	private function viewPost($id)
	{
		$partial = 'blog.post';
		if(isset($_GET['api'])) //ezpz
			$partial = 'blog.json';
		
		$thread = (new BlogThreads)->getById($id);
		
		$post = $this->partial(
			$partial, 
			array(
				'post' => $thread
			) 
		);
		
		?>
		<div class="row">
			<div class="col-9">
				<?=$this->partial(
					$partial, 
					array(
						'post' => $thread
					) 
				);?>
			</div>
			<div class="col-3">
				<?php $this->printCategoryCount(); ?>
			</div>
		</div>
		<?php
	}
	
	//default
	public function main($vars)
	{
		if(preg_match('/^([0-9]+)\-(.*)/', $vars[0], $match))
			return $this->viewPost($match[1]);
		
		$p = 1;
		if(isset($_GET['p']))
			$p = $_GET['p'];
		
		$blog = (new blog)->loadThreads($_GET);
		
		
		/* NEXT && PREV */
		$prev = '<i class="fa fa-caret-left"></i> Newer';
		$next = 'Older <i class="fa fa-caret-right"></i>';

		if($p > 1)
			$prev = '<a href="?p='.($p - 1).'">'.$prev.'</a>';

		$threads = (new BlogThreads);
		if(isset($this->ini['cat']))
			$threads->byCategory($this->ini['cat']);

		$limit = $threads->rowCount();

		if($p * $blog->ppp() < $limit)
			$next = '<a href="?p='.($p + 1).'">'.$next.'</a>';
			
		$page =  '/ Page '.$p;
		/* END - NEXT && PREV */
		
		include 'view/blog.paginate.php';
	}
	
	public function printCategoryCount()
	{
		// this should be its own table. counting all rows is slow.
		foreach( (new BlogThreads)->cols('category')->getAll() as $value )
		{
			$category = $value['category'];
			
			if(!isset($catmap[$category]))
				$catmap[$category] = 0;
			$catmap[$category]++;
		}
		
		echo $this->partial('blog.categories', 
			array(
				'catmap' => $catmap, 
				'category' => null
			) 
		);
	}
	
	public function latest($vars)
	{
		return $this->partial(
			'blog.post', 
			array(
				'post' => (new BlogThreads)->order('id','DESC')->first()
			) 
		);
	}
	
	public function cat($args)
	{
		$args[1] = urldecode($args[1]);
		$_GET['category'] = $args[1];
		$this->main($args);
	}
	
	// this could be cool if we fixed it up a little. maybe move to blog model
	public function gridlist($vars)
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