<h3 class="no_martop"><i class="fa fa-newspaper-o"></i> Articles</h3>

<a class="button green marbot" href="<?= \lf\requestGet('ActionUrl');?>newarticle/<?php echo $category; ?>"><i class="fa fa-plus"></i> New Article</a>
	
<ul class="efvlist white">
<?php
	$cat = '';
	if($posts)
	foreach($posts->getAll() as $post):
		//if($cat != $post['category'])
		//{
		//	$cat = $post['category'];
		//	echo '<h4>'.$cat.'</h4>';
		//}
?>
	<li>
		<div class="row no_martop no_marbot">
			<div class="col-9">
				<a href="<?= \lf\requestGet('ActionUrl');?>edit/<?=$post['id'];?>/" class="block"><?=$post['title'];?></a>
			</div>
			<div class="col-2">
				<span class="light_gray_fg"><?=$post['category'];?></span>
			</div>
			<div class="col-1">
				<a <?=jsprompt();?>  href="<?= \lf\requestGet('ActionUrl');?>rm/<?=$post['id'];?>/" class="x pull-right" title="Delete Article"><i class="fa fa-trash"></i></a>
			</div>
		</div>
	</li>
<?php endforeach; else echo '<li>No Posts</li>'; ?>
</ul>