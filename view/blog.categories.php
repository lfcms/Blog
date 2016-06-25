<h3>Categories</h3>
<ul class="efvlist rounded">
	<li<?=is_null($category)?' class="active"':'';?>><a href="%appurl%">All Categories</a></li>
	<?php if(isset($catmap)) 
	{
		ob_start();
		foreach($catmap as $cat => $count):
			$urlcat = urlencode($cat);
		?>
			<li><a href="%appurl%cat/<?=$urlcat;?>"><?=$cat;?></a> (<?=$count;?>)</li>
		<?php endforeach;
		if($category != NULL)
			echo str_replace('<li><a href="%appurl%cat/'.$category.'">', '<li class="active"><a href="%appurl%cat/'.$category.'">', ob_get_clean());
		
		else echo ob_get_clean();
		
	} else { ?>
	<li>No Categories</li>
	<?php } ?>
</ul>