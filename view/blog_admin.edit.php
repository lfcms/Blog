<h3><i class="fa fa-pencil-square-o"></i> Edit Post</h3>

<?=$this->notice();?>

<form class="martop" action="%appurl%edit/<?=$row['id'];?>/" method="post">
	
	<div class="row">
		<div class="col-12">
			<input type="text" name="title" value="<?=htmlspecialchars($row['title'], ENT_QUOTES);?>" placeholder="Post Title" class="title" />
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
			<a target="_blank" class="button" href="http://parsedown.org/demo">M <i class="fa fa-long-arrow-down"></i></a>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<textarea id="ckeditor" placeholder="Write something..." name="content" class="h400"><?=htmlspecialchars($row['content'], ENT_QUOTES);?></textarea>
		</div>
	</div>
	<input type="hidden" name="access" value="public" />
	<div class="row">
		<div class="col-6"><button class="green"><i class="fa fa-arrow-circle-up"></i> Publish</button></div>
		<div class="col-6"><a class="red button" href="%appurl%"><i class="fa fa-ban"></i> Cancel</a></div>
	</div>
	
</form>
<?php /*readfile(ROOT.'system/lib/editor.js');*/ ?>