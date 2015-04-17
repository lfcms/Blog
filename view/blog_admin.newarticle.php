Back to <a href="%appurl%">post list</a>

<h3>New Post</h3>

<?=$this->notice();?>

<form action="%appurl%create" method="post">
	
	<div class="row">
		<div class="col-12">
			<input type="text" name="title" placeholder="New Title" class="title" />
		</div>
	</div>
	
	<div class="row">
		<div class="col-4">
			<select name="category" id=""><?=$cat_options;?></select>
		</div>
		<div class="col-4">
			<input type="text" name="newcat" placeholder="New Category" />
		</div>
		<div class="col-4">
			<a target="_blank" class="button" href="http://parsedown.org/demo">Parsedown</a>
		</div>
	</div>
	
	

	
	
		
	
	
	<textarea style="width: 100%; height: 400px; padding: 2px; margin-top: 10px;" id="ckeditor" placeholder="New Content" name="content"></textarea>
	 
	<input type="hidden" name="access" value="public" />
	<div class="row">
		<div class="col-6"><input type="submit" class="green submit" value="Publish Article" /></div>
		<div class="col-6"><a class="red button" href="%appurl%">Cancel</a></div>
	</div>
	
</form>
<?php /*readfile(ROOT.'system/lib/editor.js');*/ ?>