<?=$this->notice();?>
<div class="row">
	<div class="col-9">
		<?php
			$this->$function($vars);
		?>
	</div>
	<div class="col-3">
		<div class="tile white">
			<div class="tile-header">
				<form class="category-title" action="%appurl%addcat/" method="post">
					<h4><i class="fa fa-tags"></i> Categories</h4>
					<input class="marbot" type="text" name="category" placeholder="New category" />
				</form>
			</div>	
			<ul class="fvlist">
				<?php if(isset($cats) && !is_null($cats)):
					//agnosticsm
					if(!is_array($cats)){
						//make it an array
						$cats = [$cats];
					}
					foreach($cats as $cat): 
						if(isset($vars[1]) && $cat == $vars[1]) 
							$selected = 'blue light_a';
						else 
							$selected = '';
					?>
						<li class="no_pad <?=$selected;?>">
							<a href="%appurl%cat/<?=urlencode($cat);?>/" class="pad block"><?=$cat;?></a>
						</li>
					<?php endforeach;
				else: ?>
					<li class="" href="#">Unknown</li>
					
				<?php endif;?>
			</ul>
		</div>
	</div>
	
</div>
