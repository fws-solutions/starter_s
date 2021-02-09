<?php
require( '../../../wp-load.php' );

//$pas = 'To je Pas za Nas!!!';
//$test = $_POST['test'];
//$test2 = $_POST['test2'];

$posts = get_posts();

echo $posts[0]->post_title;


