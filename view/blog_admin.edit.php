<form class="blog_edit_article" action="<?=$this->inst_base;?>edit/<?=$row['id'];?>/" method="post">
	<div class="save_article">
		<input type="submit" value="Publish Article" class="save_article" />
	<?=$msg;?>
	</div>
	<div class="article_title">
		<input name="title" value="<?=htmlspecialchars($row['title'], ENT_QUOTES);?>" />
	</div>
	<div class="category_selection">
		Category: <select name="category" id=""><?=$cat_options;?></select> or <input type="text" name="newcat" placeholder="New Category" />
		<a href="<?=$inst_base;?>">cancel</a>
	</div>
	<textarea id="ckeditor" name="content"><?=htmlspecialchars($row['content'], ENT_QUOTES);?></textarea>
</form>