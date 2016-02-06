<?php

class Post extends BlogThreads
{
	
	
	public function articleList()
	{
		return $this->cols('id, title, category')
			->orderBy('category, title', 'DESC');
	}
	
	public function saveFromPOST()
	{
		return $this->qFromResult()
				->setArray($_POST)
				->setAsNow('date')
				->save();
	}
	
	public function createFromPOST()
	{
		return $this
			->setAsNow('date')
			->insertArray($_POST);
	}
	
	public function deletePost($id, $andComments = true)
	{
		// delete the post
		$this->byId($id)->delete();
		
		// and its comments
		(new BlogComments)->deleteByParent_id($id);
		
		return $this;
	}
}