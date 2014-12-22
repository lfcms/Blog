<?php

$blogs = $this->db->fetchall('SELECT DISTINCT category FROM blog_threads');

$args = '';
if(count($blogs))
{
	$args = '<select name="ini" id="">';
	$args .= '<option value="">Show from all categories</option>';
	$args .= '<optgroup label="Categories:">';
	foreach($blogs as $blog)
		$args .= '<option value="cat='.$blog['category'].'">'.$blog['category'].'</option>';
	$args .= '</optgroup>';
	$args .= '</select>';
	
	$args = str_replace('value="'.$save['ini'].'"', 'value="'.$save['ini'].'" selected="selected"', $args);
}

?>