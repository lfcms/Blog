<div class="row">
	<div class="col-9">
		<?php if( (new User)->fromSession()->hasAccess('admin') ): ?>
		<a class="transparent button dark" href="%baseurl%admin/apps/blog/newarticle/">Publish new Post (logged in as <?=(new User)->fromSession()->getDisplay_name();?>)</a>
		<?php endif; ?>
		
		<div id="blog_posts">
		<?php if($blog->threadCount()): $like = array(); ?>
			<div id="threads">
				
			<?php //loop through blog posts
			foreach($blog->getThreads() as $index => $post): ?>
				
				<?=$this->partial('blog.post', array('post' => $post) );?>
			
				<hr />
			<?php endforeach; ?>
		<?php else: ?>
			<p>No threads to show</p>
		<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<?php echo $prev; ?>
			</div>
			<div class="col-6">
				<span class="pull-right">
					<?php echo $next; ?>
				</span>
			</div>
		</div>
	</div>
	<div class="col-3">
		<div id="blog_categories" class="sidebar">
			<?php $this->printCategoryCount($this->chosenCategory); ?>
		</div>
	</div>
</div>