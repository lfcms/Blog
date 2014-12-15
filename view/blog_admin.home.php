<div class="blog-wrapper">
	<div class="blog_admin_content">
	<?php
		$function = $vars[0];
		$this->$function($vars);
	?>
	</div>
</div>
<div class="blog-category-wrapper">
	
	<!-- this has to be outside the form so the "new category" box does not show when making a new article -->
	<h4>Categories</h4>
	
	<?php if($vars[0] != 'newarticle') { ?>
	<form class="category-title" action="%appurl%addcat/" method="post">
		<input type="text" name="category" placeholder="New category" />
	</form>
	<?php } ?>
	
	<ul id="blog_category_list">
	<?php foreach($this->cats as $cat): 
		if(isset($vars[1]) && $cat == $vars[1]) $selected = ' class="selected"';
		else $selected = '';
	?>
		<li>
			<a<?=$selected;?> href="%appurl%cat/<?=urlencode($cat);?>/">
				<?=$cat;?>
			</a> 
			<a href="%appurl%editcat/<?=urlencode($cat);?>/">[edit]</a>
		</li>
	<?php endforeach; ?>
	</ul>
</div>
