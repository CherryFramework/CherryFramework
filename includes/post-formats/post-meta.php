<!-- Post Meta -->
<?php
	//if ( is_singular() ) {
	$post_meta_display = of_get_option('post_meta_display');
	
		$args = array(
			'meta_elements' => array(
				'start_unite',
					'start_group',
						'categories',
						'date',
						'author',
						'comment',
						'permalink',
					'end_group',
					'start_group',
						'views',
						'like',
						'dislike',
					'end_group',
					'start_group',
						'tags',
					'end_group',
				'end_unite'
			)
		);
		switch ($post_meta_display) {
			case 'only_blog':
					if ( !is_singular() ) {
						get_post_metadata($args);
					}
				break;
			case 'only_post':
					if ( is_singular() ) {
						get_post_metadata($args);
					}
				break;
			case 'blog_post':
					get_post_metadata($args);
				break;
			case 'hide':
					/* hide meta block */
				break;
		}

		
	//}
?>
<!--// Post Meta -->