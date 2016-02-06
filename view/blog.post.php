<div id="thread_<?php echo $post['id']; ?>" class="thread" >
	<div class="t_head">
		<?php $url_title = preg_replace('/[^a-z0-9]/','-',strtolower($post['title']) ); ?>
		<h2>
			<a href="%appurl%<?php echo $post['id'].'-'.$url_title; ?>">
				<?php echo $post['title'] ?>
			</a>
		</h2>
		
		<span>Posted by {user:<?php echo $post['owner_id'] ?>} <?=since(strtotime($post['date']));?></span>
		<p><?php
			$Parsedown = new Parsedown();
			echo $Parsedown->text($post['content']);
		?></p>
		<br style="clear:both;" />
		<ul class="hlist hspaced"> 
			<li>
				<a href="%appurl%<?php echo $id.'-'.$url_title; ?>">
					<i class="fa fa-link"></i> Permalink
				</a>
			</li>
			<li>
				<a href="%appurl%cat/<?php echo $post['category'] ?>">
					<i class="fa fa-tag"></i> <?php echo $post['category'] ?>
				</a>
			</li>
		</ul>			
	</div>
</div>