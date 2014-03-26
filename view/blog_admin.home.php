<h3>Instance: <a href="<?=$this->inst_base;?>"><?=$this->inst;?></a></h3>

<div class="blog_admin_category_list"><h4>Categories</h4>
<form action="<?=$this->inst_base;?>addcat/" method="post"><input type="text" name="category" placeholder="New category" /></form>
<?php foreach($this->cats as $cat): ?>
	<a href="<?=$this->inst_base;?>cat/<?=urlencode($cat);?>/"><?=$cat;?></a><br />
<?php endforeach; ?>
</div>
<div class="blog_admin_content">
<?php

$function = $vars[0];
$this->$function($vars);

?>
</div>