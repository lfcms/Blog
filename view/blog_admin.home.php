<div class="blog-wrapper">
	<div class="blog_admin_content">
	<?php
		$function = $vars[0];
		$this->$function($vars);
	?>
	</div>
</div>
<div class="blog-category-wrapper">
	<form class="category-title" action="%appurl%addcat/" method="post">
		<h4>Categories</h4>
		<input type="text" name="category" placeholder="New category" />
	</form>
	<?php foreach($this->cats as $cat): 
		if(isset($vars[1]) && $cat == $vars[1]) $selected = ' class="selected"';
		else $selected = '';
	?>
		<a<?=$selected;?> href="%appurl%cat/<?=urlencode($cat);?>/"><?=$cat;?></a>
	<?php endforeach; ?>
</div>
