<?php

$prev = 'Newer';
		$next = 'Older';
		
		if($start > 0)
			$prev = '<a href="%appurl%p/'.(($start/$length) - 1).'">'.$prev.'</a>';
			
		$where = '';
		if(isset($this->ini['cat'])) 
			$where = "WHERE cat = '".$this->ini['cat']."'";
			
		$limit = $this->db->fetch('SELECT count(id) FROM blog_threads '.$where);
		if($start + $length < $limit['count(id)'])
			$next = '<a href="%appurl%p/'.($start/$length + 1).'">'.$next.'</a>';
			
		if($start/$length > 0)
			$page =  '/ Page '.($start/$length + 1);
		else
			$page = '';
			
		/*$out = str_replace(
			'<h2>Blog</h2>', 
			'<h2>
				'.$prev.' '.$next.' '.$page.'
			</h2>', 
			$out
		);*/
		
		//echo $out;
		
		
		

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

<div class="row">
	<div class="col-9">
		<h2>
			<a href="%appurl%"><?php echo isset($this->ini['inst']) ? $this->ini['inst'] : 'Blog'; ?></a>
		<?php echo isset($category) ? ' > <a href="%appurl%'.$category.'/">'.$category.'</a>' : ''; ?>
		</h2>

		<div id="blog_posts">
			<?php if(isset($blog) && count($blog)): ?>
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
						<h4>
							<a href="%appurl%<?php echo $post['category'] ?>">
								<?php echo $post['category'] ?>
							</a> / 
							<a href="%appurl%<?php echo $id.'-'.$url_title; ?>">
								<?php echo $post['title'] ?>
							</a>
						</h4>
						<p><?php			
							$Parsedown = new Parsedown();
							echo $Parsedown->text($post['content']);
						?></p>
						<br style="clear:both;" />
						<span class="date">
							<a href="%appurl%<?php echo $id.'-'.$url_title; ?>">Permalink</a> | 
							Posted by <?php echo $post['user'] ?> <?=since(strtotime($post['date']));?>
						</span>			
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php else: ?>
			<p>No threads to show</p>
			<?php endif; ?>
		</div>
	</div>
	<div class="col-3">
		<div id="blog_categories" class="sidebar">
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
					else echo ob_get_clean();
				} else { ?>
				<li>No categories</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>

<?php

echo '<div style="clear:both;"></div>';
echo $prev.' | '.$next;