<?php /* Loop Name: Portfolio 4 */ ?>
<?php // Theme Options vars
$items_count = of_get_option('items_count4');
$cols = '4cols';
if(file_exists(CHILD_DIR . '/portfolio-loop.php')){
	require_once CHILD_DIR . '/portfolio-loop.php';
}else{
	require_once PARENT_DIR . '/portfolio-loop.php';
}
