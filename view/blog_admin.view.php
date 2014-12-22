<div class="new-article-button">
	<a href="%appurl%newarticle/<?php echo $category; ?>">Post New Article</a>
</div>
<ol class="article-list">
	<?php
		$cat = '';
		foreach($posts as $post):
			//if($cat != $post['category'])
			//{
			//	$cat = $post['category'];
			//	echo '<h4>'.$cat.'</h4>';
			//}
	?>
	<li>
		<a href="%appurl%edit/<?=$post['id'];?>/"><?=$post['title'];?></a>
		<a onclick="return confirm(\'Do you really want to delete this?\');"  href="%appurl%rm/<?=$post['id'];?>/" class="delete_item">x</a>
	</li>
	<?php endforeach; ?>
</ol>