<h3 class="no_martop"><i class="fa fa-pencil-square-o"></i> Edit Post</h3>

<?=$this->notice();?>

Last Edited: <?=$post->date;?>

<form class="martop" action="%appurl%edit/<?=$post->id;?>/" method="post">
	<div class="row">
		<div class="col-12">
			<input type="text" name="title" value="<?=htmlspecialchars($post->title, ENT_QUOTES);?>" placeholder="Post Title" class="title" />
		</div>
	</div>
	<div class="row">
		<div class="col-5">
			<select name="category" id=""><?=$cat_options;?></select>
		</div>
		<div class="col-5">
			<input type="text" name="newcat" placeholder="New Category" />
		</div>
		<div class="col-2">
			<a class="button light_gray gray_fg" target="_blank" title="Markdown is built in. Give it a try!" href="http://parsedown.org/demo">
				Markdown
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<textarea id="ckeditor" placeholder="Write something..." name="content" class="h400"><?=htmlspecialchars($post->content, ENT_QUOTES);?></textarea>
		</div>
	</div>
	<!-- <input type="hidden" name="access" value="public" /> -->
	<div class="row">
		<div class="col-6">
			<button class="green"><i class="fa fa-arrow-circle-up"></i> Publish</button>
		</div>
		<div class="col-6"><a class="red button" href="%appurl%"><i class="fa fa-ban"></i> Cancel</a></div>
	</div>
</form>