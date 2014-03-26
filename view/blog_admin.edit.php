<form id="blog_admin_edit_form" action="<?=$this->inst_base;?>edit/<?=$row['id'];?>/" method="post">
	<input type="submit" value="Save" /> <?=$msg;?> [<a href="<?=$inst_base;?>">deselect post</a>]
	<br /><br />
	<input style="font-size: 22px; padding: 5px; width:100%" name="title" value="<?=htmlspecialchars($row['title'], ENT_QUOTES);?>" />
	<br /><br />		
	Category: <select name="category" id=""><?=$cat_options;?></select> or <input type="text" name="newcat" placeholder="New Category" />
	<br /><br />		
	<textarea id="ckeditor" name="content"><?=htmlspecialchars($row['content'], ENT_QUOTES);?></textarea>
	<br />
	<input type="submit" value="Save" /> <?=$msg;?>
</form>