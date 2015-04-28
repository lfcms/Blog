<?php

class Post extends orm
{
	public $table = 'blog_threads';
	
	public function listCategories()
	{
		$this->distinct('category')
			->orderBy('category')
			->find();
		
		if(is_null($this->result))
			return array('Uncategorized');
		else
			while($cat = $this->get())
				$ret[] = $cat['category'];
		
		return $ret;
	}
}