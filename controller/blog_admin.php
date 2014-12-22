<link href="%relbase%lf/apps/blog/css/blog_admin.styles.css" rel="stylesheet">
<?php

class blog_admin extends app
{
	public function main($vars)
	{
		$cats = $this->db->fetchall("SELECT DISTINCT category FROM blog_threads ORDER BY category");
		if(!$cats) 
			$this->cats[] = 'No categories';
		else
			foreach($cats as $cat)
				$this->cats[] = $cat['category'];
		
		if($vars[0] == '') $vars[0] = 'view';
		
		include 'view/blog_admin.home.php';
	}
	
	private function cat($vars)
	{
		$vars[1] = urldecode($vars[1]);
		$this->view($vars, $vars[1]);
	}
	
	private function view($vars, $category = '')
	{		
		$where = '';
		if($category != '') $where = "WHERE category = '".$category."'";
		
		$posts = $this->db->fetchall("SELECT id, title, category FROM blog_threads ".$where." ORDER BY category, id DESC");
		
		include 'view/blog_admin.view.php';
	}
	
	private function edit($vars)
	{		
		$id = intval($vars[1]);
		if($id <= 0) return;
		
		$msg = '';
		if(count($_POST) > 0)
		{
			if($_POST['newcat'] != '') $_POST['category'] = $_POST['newcat'];
			
			$result = $this->db->query("
				UPDATE blog_threads 
				SET 
					title 	= '".$this->db->escape($_POST['title'])."', 
					content = '".$this->db->escape($_POST['content'])."',
					category = '".$this->db->escape($_POST['category'])."'
				WHERE id = ".$id
			);
			$msg = 'Saved.';
			redirect302();
		}
		
		$result = $this->db->query("SELECT * FROM blog_threads WHERE id = ".$id);
		$row = $this->db->fetch($result);
		
		$cats = $this->cats;
		
		$cat_options = '';
		foreach($cats as $cat)
		{
			$selected = $cat == $row['category'] ? ' selected="selected"' : '';
			$cat_options .= '<option'.$selected.' value="'.$cat.'">'.$cat.'</option>';
		}
		
		include 'view/blog_admin.edit.php';
	}
	
	
	private function create($vars)
	{
		if(count($_POST) > 0)
		{
			if($_POST['newcat'] != '') $_POST['category'] = $_POST['newcat'];
			
			$result = $this->db->query("
				INSERT INTO blog_threads (`id`, `category`, `title`, `content`, `owner_id`, `date`)
				VALUES (
					NULL, 
					'".$this->db->escape($_POST['category'])."',
					'".$this->db->escape($_POST['title'])."', 
					'".$this->db->escape($_POST['content'])."', 
					".$this->request->api('getuid').", 
					NOW() 
				)
			");
			$id = $this->db->last();
		}
		
		redirect302($this->lf->appurl.'edit/'.$id);
	}
	
	private function newarticle($vars)
	{		
		$cat_options = '';
		foreach($this->cats as $cat)
		{
			$select = '';
			if(isset($vars[1]) && $vars[1] == $cat) $select = ' selected="selected"';
			
			$cat_options .= '<option'.$select.' value="'.$cat.'">'.$cat.'</option>';
		}
		
		include 'view/blog_admin.newarticle.php';
	}
	
	private function rm($vars)
	{
		$id = intval($vars[1]);
		if($id <= 0) return;

		// Remove thread and comments
		$this->db->query("DELETE FROM blog_threads AND id = ".$id);		
		if($this->db->affected() == 1)
			$this->db->query("DELETE FROM blog_messages WHERE parent_id = ".$id);
		
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
