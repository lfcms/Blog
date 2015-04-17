<h3><i class="fa fa-newspaper-o"></i> Articles</h3>

<a class="button green marbot" href="%appurl%newarticle/<?php echo $category; ?>"><i class="fa fa-plus"></i> New Article</a>
	
<ol class="efvlist rounded">
	<?php
		$cat = '';
		if($posts)
		foreach($posts as $post):
			//if($cat != $post['category'])
			//{
			//	$cat = $post['category'];
			//	echo '<h4>'.$cat.'</h4>';
			//}
	?>
	<li>
		<a href="%appurl%edit/<?=$post['id'];?>/"><?=$post['title'];?></a>
		<a <?=jsprompt();?>  href="%appurl%rm/<?=$post['id'];?>/" class="x pull-right"><i class="fa fa-trash"></i></a>
	</li>
	<?php endforeach; else echo '<li>No Posts</li>'; ?>
</ol>