<?php

function since($timestamp)
{
	$timestamp = time() - $timestamp;
	$ret = '';
	
	if($timestamp > 86400*30)
		$ret .= (int)($timestamp / (86400*30)) . " months";
	else if($timestamp > 86400)
		$ret .= (int)($timestamp / 86400) . " days";
	else if($timestamp > 3600)
		$ret .= (int)($timestamp / 3600) . " hours";
	else if($timestamp > 60)
		$ret .= (int)($timestamp / 60) . " minutes";
	else
		$ret .= $timestamp . " seconds";
	
	$ret .= " ago";
	
	return $ret;
}

?>
<style type="text/css">
#blog_categories { float: right; width: 200px; }
#blog_posts { margin-right: 210px; }

.app-blog .thread{ border-bottom: 1px solid #DDD; margin-bottom: 10px; padding: 0 0 10px;  }
</style>

<h2>

<a href="%appurl%"><?php echo isset($this->ini['inst']) ? $this->ini['inst'] : 'Blog'; ?></a>
<?php echo isset($category) ? ' / <a href="%appurl%cat/'.$category.'/">'.$category.'</a>' : ''; ?>
</h2>

<div id="blog_categories">
	<h3>Categories</h3>
	<ul>
		<?php if(isset($cat_count)) 
		{
			ob_start();
			foreach($cat_count as $cat => $count):
				$urlcat = urlencode($cat);
			?>
				<li><a href="%appurl%cat/<?=$urlcat;?>"><?=$cat;?></a> (<?=$count;?>)</li>
<?php 		endforeach;
		
			if($category != NULL)
			{
				echo str_replace('<li><a href="%appurl%cat/'.$category.'">', '<li class="active"><a href="%appurl%cat/'.$category.'">', ob_get_clean());
			}
			
		} else { ?>
		<li>No categories</li>
		<?php } ?>
	</ul>
</div>
<div id="blog_posts">
	<?php if($this->request->api('me') != 'anonymous' && false): ?>
	<form action="%post%" method="post" class="add_thread">
		<textarea name="input"></textarea>
		<input type="hidden" name="access" value="public" />
		<input type="submit" class="submit" value="Create Thread" />
	</form>
	<?php endif; if(!count($blog)): ?>
	<p>No threads to show</p>
	<?php else: ?>
	<div id="threads">
		<?php $like = array(); foreach($blog as $id => $post): /* loop through blog posts */ ?>
		<div id="thread_<?php echo $id; ?>" class="thread" >
			<div class="t_head">
				<?php
					$like_disp = ''; // Display 'like' button if logged in
					if($this->request->api('me') != 'anonymous')
					{
						$like_disp = '%t_like'.$id.'%';
						$like[] = 't_like'.$id;
					}
					$url_title = preg_replace('/[^a-z0-9]/','-',strtolower($post['title']) );
				?>
				<h4><a href="%appurl%cat/<?php echo $post['category'] ?>"><?php echo $post['category'] ?></a> / <a href="%appurl%view/<?php echo $id.'/'.$url_title; ?>"><?php echo $post['title'] ?></a></h4>
				<p><?=$post['content'];?></p>
				<br style="clear:both;" />
				<span class="date">
					<a href="%appurl%view/<?php echo $id; ?>/">Permalink</a> | 
					Posted by <?php echo $post['user'] ?> <?=since(strtotime($post['date']));?>
				</span>			
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	
</div>