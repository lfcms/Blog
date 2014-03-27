<style type="text/css">
	.add_thread .title { margin-bottom: 10px; padding: 5px; width: 100%; font-size:20px; }
</style>
<form action="<?=$inst_base;?>create/" method="post" class="add_thread">
	<input type="submit" class="submit" value="Post" />
	<input type="text" name="title" value="New Title" class="title" />
	Category: <select name="category" id=""><?=$cat_options;?></select>
	or <input type="text" name="newcat" placeholder="New Category" />
	<textarea id="ckeditor" name="content"></textarea>
	<input type="hidden" name="access" value="public" />
</form>