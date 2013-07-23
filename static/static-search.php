<?php /* Static Name: Search */ ?>
<!-- BEGIN SEARCH FORM -->
<?php if ( of_get_option('g_search_box_id') == 'yes') { ?>
	<div class="search-form search-form__h hidden-phone clearfix">
		<form id="search-header" class="navbar-form pull-right" method="get" action="<?php echo home_url(); ?>/" accept-charset="utf-8">
			<input type="text" name="s" placeholder="<?php echo theme_locals('search'); ?>" class="search-form_it">
			<input type="submit" value="<?php echo theme_locals('go'); ?>" id="search-form_is" class="search-form_is btn btn-primary">
		</form>
	</div>
<?php } ?>
<!-- END SEARCH FORM -->