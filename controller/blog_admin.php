<link href="%relbase%lf/apps/blog/css/blog_styles.css" rel="stylesheet">
<?php
 
class blog_admin extends app
{
	public function main($vars)
	{
		$result = $this->db->fetchall('SELECT DISTINCT instance FROM io_threads ORDER BY instance');
		
		include 'view/blog_admin.main.php';
	}
	
	public function inst($vars) // needs to be converted to _router(), home()
	{
		if(!isset($vars[1])) return $this->main();
		
		$inst = urldecode($vars[1]);
		$vars = array_slice($vars, 2);
		$this->inst = $inst;
		$this->inst_base = $this->request->base.'apps/manage/blog/inst/'.urlencode($inst).'/';
		
		$cats = $this->db->fetchall("SELECT DISTINCT category FROM io_threads WHERE instance = '".$inst."' ORDER BY category");
		if(!$cats) 
			redirect302($this->request->base.'apps/manage/blog/');
		else
			foreach($cats as $cat)
				$this->cats[] = $cat['category'];
		
		if(!isset($vars[0])) $vars[0] = 'view';
		
		include 'view/blog_admin.home.php';
	}
	
	private function cat($vars)
	{
		$vars[1] = urldecode($vars[1]);
		$this->view($vars, $vars[1]);
	}
	
	private function view($vars, $category = '')
	{
		$inst = $this->inst;
		$inst_base = $this->inst_base;
		
		$where = '';
		if($category != '') $where = " category = '".$category."' AND ";
		
		$posts = $this->db->fetchall("SELECT id, title, category FROM io_threads WHERE".$where." instance = '".$this->db->escape($inst)."' ORDER BY category, id DESC");
		
		include 'view/blog_admin.view.php';
	}
	
	private function edit($vars)
	{
		$inst = $this->inst;
		$inst_base = $this->inst_base;
		
		$id = intval($vars[1]);
		if($id <= 0) return;
		
		$msg = '';
		if(count($_POST) > 0)
		{
			
			if($_POST['newcat'] != '') $_POST['category'] = $_POST['newcat'];
			
			$result = $this->db->query("
				UPDATE io_threads 
				SET 
					title 	= '".$this->db->escape($_POST['title'])."', 
					content = '".$this->db->escape($_POST['content'])."',
					category = '".$this->db->escape($_POST['category'])."'
				WHERE id = ".$id
			);
			$msg = 'Saved.';
			redirect302();
		}
		
		$result = $this->db->query("SELECT * FROM io_threads WHERE id = ".$id);
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
				INSERT INTO io_threads (`id`, `instance`, `category`, `title`, `content`, `owner_id`, `likes`, `date`)
				VALUES (
					NULL, 
					'".$this->db->escape($this->inst)."', 
					'".$this->db->escape($_POST['category'])."',
					'".$this->db->escape($_POST['title'])."', 
					'".$this->db->escape($_POST['content'])."', 
					".$this->request->api('getuid').", 
					0, NOW() 
				)
			");
			$msg = 'Page Created.';
		}
		
		redirect302($this->inst_base);
	}
	
	private function newarticle($vars)
	{
		// else { didnt post }
		$inst_base = $this->inst_base;
		$inst = $this->inst;
		
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
		$this->db->query("DELETE FROM io_threads WHERE instance = '".$this->inst."' AND id = ".$id);		
		if($this->db->affected() == 1)
			$this->db->query("DELETE FROM io_messages WHERE parent_id = ".$id);
		
		redirect302();
	}
	
	public function addcat()
	{
		if(count($_POST) > 0)
			$result = $this->db->query("
				INSERT INTO io_threads (`id`, `instance`, `category`, `title`, `content`, `owner_id`, `likes`, `date`)
				VALUES (
					NULL, '".$this->inst."', '".$this->db->escape($_POST['category'])."',
					'New Article', 
					'New Content',
					".$this->request->api('getuid').",
					0,
					NOW() 
				)
			");
		
		redirect302();
	}
	
	public function rminst($vars)
	{
		echo 'Instance delete isnt implemented yet. To remove an instance, delete all posts inside it.';
		//$this->main();
		//redirect302();
		/*
		if(isset($vars[1]))
			$result = $this->db->query("
				 io_threads (`id`, `instance`, `category`, `title`, `content`, `owner_id`, `likes`, `date`)
				VALUES (
					NULL, '".$this->db->escape($_POST['instance'])."', 'uncategorized',
					'New Article', 
					'New Content',
					".$this->request->api('getuid').",
					0,
					NOW() 
				)
			");
		
		redirect302();*/
	}
	
	public function addinst($vars)
	{
		if(count($_POST) > 0)
			$result = $this->db->query("
				INSERT INTO io_threads (`id`, `instance`, `category`, `title`, `content`, `owner_id`, `likes`, `date`)
				VALUES (
					NULL, '".$this->db->escape($_POST['instance'])."', 'uncategorized',
					'New Article', 
					'New Content',
					".$this->request->api('getuid').",
					0,
					NOW() 
				)
			");
		
		redirect302();
	}
}

?>
