<p>Select an article below to edit it or [<a href="<?php echo $inst_base; ?>newarticle/<?php echo $category; ?>">Post New Article</a>]</p>
<ol>
<?php

	$cat = '';
	foreach($posts as $post):
		if($cat != $post['category'])
		{
			$cat = $post['category'];
			echo '<h4>'.$cat.'</h4>';
		}
	
	?><li>
		[<a onclick="return confirm(\'Do you really want to delete this?\');"  href="<?=$inst_base;?>rm/<?=$post['id'];?>/">x</a>] 
		<a href="<?=$inst_base;?>edit/<?=$post['id'];?>/"><?=$post['title'];?></a>
	</li>
	<?php endforeach; ?>
</ol>