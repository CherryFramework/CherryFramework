<!-- .share-buttons -->
<div class="share-buttons">
	<!-- Facebook Like Button -->
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) {return;}
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

	<!-- Google+ Button -->
	<script type="text/javascript">
	  (function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	  })();
	</script>	

	<?php
		/* get permalink */
		$permalink = get_permalink(get_the_ID());
	?>		
	<span class="twitter">
		<a href="http://twitter.com/share?url=<?php echo $permalink; ?>&text=<?php echo get_the_title().' - '.$permalink; ?>" class="twitter-share-button" data-count="horizontal"><?php echo theme_locals("tweet_this_article") ?></a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</span>
	<span class="facebook"><div id="fb-root"></div><div class="fb-like" data-href="<?php echo $permalink; ?>" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false" data-font="arial"></div></span>
	<span class="google"><div class="g-plusone" data-size="medium" data-href="<?php echo $permalink; ?>"></div></span>
	<span class="pinterest">
		<a href="javascript:void((function(){var e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)})());"><img src='http://assets.pinterest.com/images/PinExt.png'/></a>
	</span>
</div><!-- //.share-buttons -->