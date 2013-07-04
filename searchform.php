<div class="search-form">
	<form id="searchform" method="get" action="<?php echo home_url(); ?>" accept-charset="utf-8">
		<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" class="search-form_it">
		<input type="submit" value="<?php echo theme_locals("search") ?>" id="search-submit" class="search-form_is btn btn-primary">
	</form>
</div>