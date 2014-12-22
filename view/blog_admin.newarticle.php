<form action="%appurl%create/" method="post" class="blog_edit_article">
	<div class="save_article">
		<input type="submit" class="submit" value="Publish Article" />
	</div>
	<div class="article_title">
		<input type="text" name="title" placeholder="New Title" class="title" />
	</div>
	<div class="category_selection">
		Category: <select name="category" id=""><?=$cat_options;?></select> or <input type="text" name="newcat" placeholder="New Category" />
		<a href="%appurl%">cancel</a>
	</div>
	<textarea id="ckeditor" name="content"></textarea>
	<input type="hidden" name="access" value="public" />
</form>