<?php

class blog_admin
{
	public function main()
	{
		if( \lf\www('Param') == array() )
			$function = 'view';
		else
			$function = \lf\www('Param')[0];
		
		notice();
		
		echo \lf\row('no_martop',
			\lf\col(9,
				(new blog)->printThreads()
			).
			\lf\col(3,
				(new blog)->printCategories()
			)
		);
	}
	
	public function newArticle()
	{
		if( \lf\www('Param') == array() )
			$function = 'view';
		else
			$function = \lf\www('Param')[0];
		
		notice();
		
		echo (new blog)->newArticleForm();
	}
	
	/**
	 * Limit view() to certain category
	 * 
	 */
	private function cat()
	{
		$vars = \lf\www('Param'); //backward compat
		$vars[1] = urldecode($vars[1]);
		$this->view($vars, $vars[1]);
	}
	
	/**
	 * Edit form for given article
	 * 
	 */
	public function edit()
	{
		$vars = \lf\www('Param'); //backward compat
		
		$post = (new BlogThreads)->findById($vars[1]);
		
		$cat_options = '';
		foreach( (new blog)->listCategories() as $cat )
		{
			$selected = ( $cat == $post->category )
				? ' selected="selected"' 
				: '';
			$cat_options .= '<option'.$selected.' value="'.$cat.'">'.$cat.'</option>';
		}
	
		if(is_null($post->result))
			redirect302();
		
		if(count($_POST) > 0)
		{
			if($_POST['newcat'] != '') $_POST['category'] = $_POST['newcat'];
			
			unset($_POST['newcat']);
			
			(new blog)->updatePost($vars[1]);
				
			notice('<div class="notice">Page saved.</div>');
				
			redirect302();
		}
		
		include 'view/blog_admin.edit.php';
	}
	
	public function create()
	{
		$vars = \lf\www('Param'); //backward compat
		if( count($_POST) > 0 )
		{
			if($_POST['newcat'] != '') 
				$_POST['category'] = $_POST['newcat'];
			
			unset($_POST['newcat']);
			
			$_POST['owner_id'] = $_SESSION['login']->getId();
			
			$id = (new Post)->createFromPOST();
			notice('<div class="notice">Page saved.</div>');
			
			redirect302(\lf\wwwAppUrl().'edit/'.$id);
		}
	}
	
	/*public function newarticle()
	{
		$vars = \lf\www('Param'); //backward compat
		$cat_options = '';
		foreach($this->categories as $cat)
		{
			$select = '';
			if(isset($vars[1]) && $vars[1] == $cat) 
				$select = ' selected="selected"';
			
			$cat_options .= '<option'.$select.' value="'.$cat.'">'.$cat.'</option>';
		}
		
		include 'view/blog_admin.newarticle.php';
	}*/
	
	public function rm()
	{
		$vars = \lf\www('Param'); //backward compat
		
		$id = intval($vars[1]);
		if($id <= 0) return;
		
		(new BlogThreads)->debug()->deleteById($id);
		
		notice('Post deleted.');
		redirect302( \lf\wwwAppUrl() );
	}
	
	public function addcat()
	{
		if(count($_POST) > 0)
			$result = (new \lf\orm)->query("
				INSERT INTO blog_threads (`id`, `category`, `title`, `content`, `owner_id`, `date`)
				VALUES (
					NULL, '".(new \lf\orm)->escape($_POST['category'])."',
					'New ".(new \lf\orm)->escape($_POST['category'])." article', 
					'New Content',
					".(new \lf\user)->fromSession()->getId().",
					NOW() 
				)
			");
		
		redirect302();
	}
}

?>
