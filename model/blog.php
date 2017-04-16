<?php

class blog
{
	private $threads = null;
	public $postsPerPage = 3;
	
	
	public function thread($id = null)
	{
		$blogThreads = (new \BlogThreads);
		$schema = $blogThreads->getSchema();
		// Handle RESTful request
		switch($_SERVER['REQUEST_METHOD'])
		{
			// If GET, deliver all blog threads
			// if $id specified, deliver just that thread
			case 'GET':
				if( is_null( $id ) )
					$threads = $blogThreads->getAll();
				else
					$threads = $blogThreads->getById($id);
				$result = [
					"threads" => $threads,
					"schema" => $schema
				];
				break;
				
			// If POST, accept array of post data
			case 'POST':
				$result = $blogThreads->insertArray($_POST);
				break;
			// If PUT, given $id, update thread content with $_POST
			case 'PUT':
				if($id == null)
					return '400'; 
				
				$payload = array();
				parse_raw_http_request($payload);
				$blogThreads->setAsNow('date');
				$result = [
					"result" => $blogThreads->updateById($id, $payload)
				];
				break;
			case 'DELETE':
				if($id == null)
					return '400';
				$result = (new \BlogThreads)->deleteById($id);
				break;
			case 'OPTIONS':
				$result = [
					"Verbs" => [
						"GET" => [
							"description" => "List blogs posts"
							, "usage" => "Lists all posts by default. Specify thread id in URI for retrieving individual posts."
						]
						, "POST" => [
							"description" => "Create a new blog post"
							, "usage" => "Specify owner_id, category, title, and content. Inserted ID is returned."
						]
						, "PUT" => [
							"description" => "Update a blog post"
							, "usage" => "Specify owner_id, category, title, or content. Specify thread id in URI."
						]
					]
					, "Schema" => [
						(new \lf\orm)->fetchall("desc blog_threads")
					]
				];
				break;
			default:
				$result = 'not implemented';
				break;
		}
		
		return $result;
	}
	
	/**
	 * Default list of Articles
	 * 
	 */
	public function printThreads($category = '')
	{
		$vars =  \lf\requestGet('Param'); //backward compat
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
		$vars =  \lf\requestGet('Param'); //backward compat
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
		$vars =  \lf\requestGet('Param'); //backward compat
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
		
		$ret = [];
		if(is_null($categories))
			return array('Uncategorized');
		else
			// one of the many ways to loop through an \lf\orm object
			while( $category = $categories->get() ) 
				$ret[] = $category['category'];
		
		return $ret;
	}
	
	public function updatePost($id)
	{
		return (new BlogThreads)
			->setArray($_POST)
			->setAsNow('date')
			->byId($id)
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
	
	public function getThreads($filter = array())
	{
		// see if its cached
		if( is_null( $this->threads ) )
			// load it if it is not
			$this->loadThreads($filter = array());
		
		// print the loaded result
		return $this->threads->getAll();
	}
	
	public function displayPost($filter = array())
	{
		if(is_null($this->threads) || $filter != array())
			$this->loadThreads($filter);
		
		
		
		return $this;
	}
}