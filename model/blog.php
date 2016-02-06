<?php

class blog
{
	private $threads = null;
	public $postsPerPage = 3;
	
	
	
	/**
	 * Default list of Articles
	 * 
	 */
	public function printThreads($category = '')
	{
		$vars = \lf\www('Param'); //backward compat
		//$posts = (new Post)->articleList();
		
		$posts = (new BlogThreads);
		
		if($category != '') 
			$posts->byCategory($category);
			
		$posts->find();
		
		ob_start();
		include 'view/blog_admin.view.php';
		return ob_get_clean();
	}
	
	public function newArticleForm()
	{
		$vars = \lf\www('Param'); //backward compat
		$cat_options = '';
		foreach($this->listCategories() as $cat)
		{
			$select = '';
			if(isset($vars[1]) && $vars[1] == $cat) 
				$select = ' selected="selected"';
			
			$cat_options .= '<option'.$select.' value="'.$cat.'">'.$cat.'</option>';
		}
		
		ob_start();
		include 'view/blog_admin.newarticle.php';
		return ob_get_clean();
	}
	
	public function printCategories()
	{
		$vars = \lf\www('Param'); //backward compat
		$this->categories = $this->listCategories();
		$cats = $this->categories;
		
		
		ob_start();
		include 'view/blog.admin.categories.php';
		return ob_get_clean();
	}
	
	public function listCategories()
	{
		$categories = (new BlogThreads)
						->distinct('category')
						->orderBy('category')
						->find();
		
		if(is_null($categories))
			return array('Uncategorized');
		else
			// one of the many ways to loop through an \lf\orm object
			while( $category = $categories->get() ) 
				$ret[] = $category['category'];
		
		return $ret;
	}
	
	public function updatePost()
	{
		return (new BlogThreads)
			->setArray($_POST)
			->setAsNow('date')
			->save();
	}
	
	public function setPostsPerPage($ppp)
	{
		$this->postsPerPage = $ppp;
	}
	
	public function ppp()
	{
		return $this->postsPerPage;
	}
	
	// Egyptian cotton
	public function threadCount()
	{
		//pre($this->threads);
		if($this->threads == null)
			return 0;
		else
			return $this->threads->resultCount();
	}
	
	// filter blog threads by column value, store for later use
	public function loadThreads($filter = array())
	{
		$threads = (new BlogThreads);
		
		if(!isset($filter['p']))					// If ?p is not set in $_GET,
			$filter['limit'] = $this->postsPerPage;	// set the return count limit to ppp
		else if(is_int($filter['p'] + 0))			// Else, if p is an integer,
			$filter['limit'] = (($filter['p'] - 1)*$this->postsPerPage).
				','.$this->postsPerPage;			// p = 1. (p - 1)*3,ppp = LIMIT 0, 3
													// p = 2. (p - 1)*ppp = LIMIT 3, 3
													// p = 3. (p - 1)*ppp = LIMIT 6, 3
		
		
		unset($filter['p']);
		
		foreach($filter as $index => $value)
		{
			if($index == 'limit')
			{
				$threads->limit($value);
				continue;
			}
			
			$byIndex = "by$index"; //  because you can't do `->by$index` in PHP
			$threads->$byIndex($value);
		}
		
		$this->threads = $threads->order('date','DESc')->find();
		
		//pre($this->threads);
		return $this;
	}
	
	public function getThreads()
	{
		if( is_null( $this->threads ) )
			$this->loadThreads();
		
		return $this->threads->getAll();
	}
	
	public function displayPost($filter = array())
	{
		if(is_null($this->threads) || $filter != array())
			$this->loadThreads($filter);
		
		
		
		return $this;
	}
}