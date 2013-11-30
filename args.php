<?php

$blogs = $this->db->fetchall('SELECT DISTINCT instance FROM io_threads');

$args = '';
if(count($blogs))
{
	$args = '<select name="ini" id="">';
	$args .= '<option value="">Show all blogs</option>';
	$args .= '<optgroup label="Blogs:">';
	foreach($blogs as $blog)
		$args .= '<option value="inst='.$blog['instance'].'">'.$blog['instance'].'</option>';
	$args .= '</optgroup>';
	$args .= '</select>';
	
	$args = str_replace('value="'.$save['ini'].'"', 'value="'.$save['ini'].'" selected="selected"', $args);
}

?>