<?=$this->notice();?>
<div class="row">
	<div class="col-9">
		<?php
			$function = $vars[0];
			$this->$function($vars);
		?>
	</div>
	<div class="col-3">
		<form class="category-title" action="%appurl%addcat/" method="post">
			<h4>Categories</h4>
			<input type="text" name="category" placeholder="New category" />
		</form>
		<?php foreach($this->cats as $cat): 
			if(isset($vars[1]) && $cat == $vars[1]) $selected = 'selected';
			else $selected = '';
		?>
			<a class="blue button marbot <?=$selected;?>" href="%appurl%cat/<?=urlencode($cat);?>/"><?=$cat;?></a>
		<?php endforeach; ?>
	</div>
	
</div>