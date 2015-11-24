<?php

class blog
{
	private $threads;
	public $postsPerPage = 3;
	
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
		return $this->threads->getAll();
	}
	
	public function displayPost($filter = array())
	{
		if(is_null($this->threads) || $filter != array())
			$this->loadThreads($filter);
		
		
		
		return $this;
	}
}