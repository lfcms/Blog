<?php

namespace blog;

// gonna make it restful or something

class categories
{
	
}

class threads
{
	// list blogs endpoint
	public function get($by = null)
	{
		return (new \orm\blog_threads)->find($by);
	}
	
	// post creates new ones
	public function post($payload)
	{
		return (new \orm\blog_threads)->insertArray($payload);
	}
	
	public function put($id, $payload)
	{
		return (new \orm\blog_threads)->updateById($id, $payload);
	}
	
	public function delete($id)
	{
		return (new \orm\blog_threads)->deleteById($id);
	}
}

class messages
{
	
}
