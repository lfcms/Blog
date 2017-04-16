<h4 class="no_martop">Quick Post</h4>

<form method="post" action="<?=\lf\requestGet('AdminUrl');?>apps/blog/create">
	<ul class="vlist">
		<li>
			<input type="text" name="title" placeholder="Post Title"/>
		</li>
		<li>
			<input type="text" name="newcat" placeholder="Category"/>
		</li>
		<li>
			<textarea  name="content" placeholder="Write a post..." id="" cols="30" rows="10"></textarea>
		</li>
		<li>
			<input class="green button" type="submit" />
		</li>
	</ul>
</form>

<h4>Recent Posts</h4>

<ul>
<?php

include __DIR__.'/model/blog.php';

foreach( (new \BlogThreads)->order('id', 'DESC')->limit(5)->getAll() as $thread ): ?>
	<li><a href="<?=\lf\requestGet('AdminUrl');?>apps/blog/edit/<?=$thread['id'];?>"><?=$thread['title'];?></a></li>
<?php endforeach; ?>
</ul>