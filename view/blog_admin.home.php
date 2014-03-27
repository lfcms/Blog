<div class="blog-wrapper">
	<h3>Instance: <a href="<?=$this->inst_base;?>"><?=$this->inst;?></a></h3>
	<div class="blog_admin_content">
	<?php
		$function = $vars[0];
		$this->$function($vars);
	?>
	</div>
</div>
<div class="blog-category-wrapper">
	<form class="category-title" action="<?=$this->inst_base;?>addcat/" method="post">
		<h4>Categories</h4>
		<input type="text" name="category" placeholder="New category" />
	</form>
	<?php foreach($this->cats as $cat): 
		if($cat == $vars[1]) $selected = ' class="selected"';
		else $selected = '';
	?>
		<a<?=$selected;?> href="<?=$this->inst_base;?>cat/<?=urlencode($cat);?>/"><?=$cat;?></a>
	<?php endforeach; ?>
</div>
