<?php /* Loop Name: Portfolio 3 */ ?>
<?php // Theme Options vars
$items_count = of_get_option('items_count3');
$cols = '3cols';

if(file_exists(CHILD_DIR . '/portfolio-loop.php')){
	require_once CHILD_DIR . '/portfolio-loop.php';
}else{
	require_once PARENT_DIR . '/portfolio-loop.php';
}