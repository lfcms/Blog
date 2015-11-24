<?php

class blog_admin extends app
{
	/**
	 * Route all functions through main() which loads a from at home.php
	 * based on $vars
	 */
	public function main($vars)
	{
		$this->categories = (new Post)->listCategories();
		$cats = $this->categories;
		
		if($vars[0] == '') 
			$vars[0] = 'view';
		
		$function = $vars[0];
		
		
		include 'view/blog_admin.home.php';
	}
	
	/**
	 * Limit view() to certain category
	 * 
	 */
	private function cat($vars)
	{
		$vars[1] = urldecode($vars[1]);
		$this->view($vars, $vars[1]);
	}
	
	/**
	 * Default list of Articles
	 * 
	 */
	private function view($vars, $category = '')
	{
		$posts = (new Post)->articleList();
		
		if($category != '') 
			$posts->byCategory($category);
			
		$posts->find();
		
		include 'view/blog_admin.view.php';
	}
	
	/**
	 * Edit form for given article
	 * 
	 */
	private function edit($vars)
	{
		$cats = $this->categories;
		$post = (new Post)->findById($this->lf->vars[1]);
		
		$cat_options = '';
		foreach($cats as $cat)
		{
			$selected = ( $cat == $post->category )
				? ' selected="selected"' 
				: '';
			$cat_options .= '<option'.$selected.' value="'.$cat.'">'.$cat.'</option>';
		}
	
		if(is_null($post->result))
			redirect302($this->lf->appurl);
		
		if(count($_POST) > 0)
		{
			if($_POST['newcat'] != '') $_POST['category'] = $_POST['newcat'];
			
			unset($_POST['newcat']);
			
			$post->saveFromPOST();
				
			$this->notice('<div class="notice">Page saved.</div>');
				
			redirect302();
		}
		
		include 'view/blog_admin.edit.php';
	}
	
	private function create($vars)
	{
		if( count($_POST) > 0 )
		{
			if($_POST['newcat'] != '') 
				$_POST['category'] = $_POST['newcat'];
			
			unset($_POST['newcat']);
			
			$_POST['owner_id'] = $_SESSION['login']->getId();
			
			$id = (new Post)->createFromPOST();
			$this->notice('<div class="notice">Page saved.</div>');
			
			redirect302($this->lf->appurl.'edit/'.$id);
		}
	}
	
	private function newarticle($vars)
	{
		$cat_options = '';
		foreach($this->categories as $cat)
		{
			$select = '';
			if(isset($vars[1]) && $vars[1] == $cat) 
				$select = ' selected="selected"';
			
			$cat_options .= '<option'.$select.' value="'.$cat.'">'.$cat.'</option>';
		}
		
		include 'view/blog_admin.newarticle.php';
	}
	
	private function rm($vars)
	{
		//echo $vars[1];
		
		$id = intval($vars[1]);
		if($id <= 0) return;
		
		(new Post)->deletePost($id);
		
		$this->notice('Post deleted.');
		
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
