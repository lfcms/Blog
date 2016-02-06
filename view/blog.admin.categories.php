<div class="tile rounded">
	<div class="tile-header light_gray">
		<form class="category-title" action="%appurl%addcat/" method="post">
			<h4><i class="fa fa-tags"></i> Categories</h4>
			<input class="marbot" type="text" name="category" placeholder="New category" />
		</form>
	</div>
	<div class="tile-content">
		<?php if(isset($cats) && !is_null($cats)):
			//agnosticsm
			if(!is_array($cats)){
				//make it an array
				$cats = [$cats];
			}
			foreach($cats as $cat): 
				if(isset($vars[1]) && $cat == $vars[1]) 
					$selected = 'selected';
				else 
					$selected = '';
			?>
				<a class="blue button martop marbot <?=$selected;?>"
						href="%appurl%cat/<?=urlencode($cat);?>/">
					<?=$cat;?>
				</a>
			<?php endforeach; 
		else: ?>
			<a class="blue button martop marbot" href="#">Unknown </a>
			
		<?php endif;?>
	</div>
</div>