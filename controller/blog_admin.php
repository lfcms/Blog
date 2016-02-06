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
			
			(new blog)->updatePost();
				
			notice('<div class="notice">Page saved.</div>');
				
			redirect302();
		}
		
		include 'view/blog_admin.edit.php';
	}
	
	private function create()
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
			
			redirect302($this->appurl.'edit/'.$id);
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
	
	private function rm()
	{
		$vars = \lf\www('Param'); //backward compat
		//echo $vars[1];
		
		$id = intval($vars[1]);
		if($id <= 0) return;
		
		(new Post)->deletePost($id);
		
		notice('Post deleted.');
		
		redirect302();
	}
	
	public function addcat()
	{
		if(count($_POST) > 0)
			$result = $this->db->query("
				INSERT INTO blog_threads (`id`, `category`, `title`, `content`, `owner_id`, `date`)
				VALUES (
					NULL, '".$this->db->escape($_POST['category'])."',
					'New ".$this->db->escape($_POST['category'])." article', 
					'New Content',
					".$this->request->api('getuid').",
					NOW() 
				)
			");
		
		redirect302();
	}
}

?>
