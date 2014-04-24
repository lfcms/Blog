<div class="blog-wrapper">
	<form class="instance-title" action="%appurl%addinst/" method="post">
		<input type="text" name="instance" placeholder="Create a new blog." />
	</form>
	<ul class="instance-list">
		<?php if($result):
			foreach($result as $instance): 
				$instance = $instance['instance']; 
				?><li>
					<a href="%appurl%inst/<?=urlencode($instance);?>/" class="blog-instance"><?=$instance;?></a>
					<a href="%appurl%rminst/<?=urlencode($instance);?>/" class="delete_item">x</a>
				</li>
			<?php endforeach; ?>
		<?php else: ?>
		<li>No blog instance found</li>
	<?php endif; ?>
	</ul>
</div>