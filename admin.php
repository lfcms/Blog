<?php

include 'model/blog.php';
include 'model/post.php';
include 'controller/blog_admin.php';
echo (new \lf\cms)->mvc( (new blog_admin) );