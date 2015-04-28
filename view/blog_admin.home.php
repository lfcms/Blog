<?=$this->notice();?>
<div class="row">
	<div class="col-9">
		<?php
			$this->$function($vars);
		?>
	</div>
	<div class="col-3">
		<div class="tile rounded">
			<div class="tile-header light_gray">
				<form class="category-title" action="%appurl%addcat/" method="post">
					<h4><i class="fa fa-tags"></i> Categories</h4>
					<input type="text" name="category" placeholder="New category" />
				</form>
			</div>
			<div class="tile-content">
				<?php foreach($cats as $cat): 
						if(isset($vars[1]) && $cat == $vars[1]) 
							$selected = 'selected';
						else 
							$selected = '';
				?>
					<a class="blue button martop marbot <?=$selected;?>"
							href="%appurl%cat/<?=urlencode($cat);?>/">
						<?=$cat;?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	
</div>