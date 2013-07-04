<?php 	$motopress_cam_id = uniqid();
		$sliderHeight = "389px";
 ?>

<script type="text/javascript">
		jQuery(document).ready(function() {
			var myAccordion = jQuery('#accordion<?php echo $motopress_cam_id; ?>'),
				myAccordionList = jQuery('ul', myAccordion),
				sliderImg = jQuery(".slider_img", myAccordionList),
				imagesCount = sliderImg.length,
				imagesLoaded = 0,
				startingSlide = <?php echo of_get_option('acc_starting_slide')-1; ?>,
				easing = "<?php echo (of_get_option('acc_easing')!="") ? of_get_option('acc_easing') : 'easeOutCubic'; ?>",
				speed = <?php echo (of_get_option('acc_animation_speed')!="") ? of_get_option('acc_animation_speed') : 700; ?>,
				auto = <?php echo (of_get_option('acc_slideshow')!="") ? of_get_option('acc_slideshow') : true; ?>;
			if(auto && startingSlide<0){
				startingSlide = 0;
			}

			if (!myAccordion.hasClass('motopress-accordion')) {
				myAccordion.addClass('motopress-accordion');
				function checkLoadImg(){
					if(imagesLoaded>=imagesCount){
						jQuery('.accordion_loader', myAccordion).stop(true).delay(1000).fadeOut(500);
						setTimeout(function(){
							jQuery("ul", myAccordion).css({"visibility":"visible", "display":"none"}).stop(true, true).fadeIn(1000);
							resizeWindow();
						},
						1000);
						
					}
				}
				function resizeWindow(){
					var getImgHeight = sliderImg.height();
					myAccordionList.css({"height":getImgHeight});
					jQuery("li", myAccordionList).css({"height":getImgHeight});
					myAccordion.css({"height":getImgHeight});
				}
				function mouseLeaveSlider(){
					if(!auto){
						var sliderImgWidth = 100/imagesCount;
						jQuery(">li", myAccordionList).each(function(index){
							jQuery(this).removeClass("accordion_slider-open").addClass("accordion_slider-closed").stop(true).animate({'left':index*sliderImgWidth+"%", 'cursor':'pointer'}, speed, easing);
							
						})
						myAccordionList.data('current','-1');
					}
				}
				sliderImg.each(function(){
					if(jQuery(this)[0].complete){
						imagesLoaded++;
						checkLoadImg()
					}else{
						jQuery(this).bind("load", function(){
							imagesLoaded++;
							checkLoadImg()
							jQuery(this).unbind("load");
						})
					}
				})
				myAccordionList.zAccordion({
					timeout: <?php echo (of_get_option('acc_pausetime')!="") ? of_get_option('acc_pausetime') : 7000 ; ?>,
					height: "<?php echo $sliderHeight; ?>",
					width: "100%",
					slideWidth: "70%",
					tabWidth: null,
					startingSlide: startingSlide,
					trigger: "<?php echo (of_get_option('acc_trigger')!="") ? of_get_option('acc_trigger') : 'click' ; ?>",
					speed: speed,
					easing: easing,
					auto: auto,
					pause: <?php echo (of_get_option('acc_hover_pause')!="") ? of_get_option('acc_hover_pause') : true; ?>,
					slideClass: "accordion_slider",
					buildComplete: function () {
						if(startingSlide<0){
							mouseLeaveSlider();
						}
					}
				});

				$(window).resize(resizeWindow);
				myAccordionList.mouseleave(mouseLeaveSlider);
			}
		});
</script>

<div id="accordion<?php echo $motopress_cam_id; ?>" class="accordion_wrap accordion" style=" height: <?php echo $sliderHeight; ?>; ">
	<ul>
		<?php
			$post_array = of_get_option('acc_show_post');
			query_posts("post_type=slider&posts_per_page=-1&post_status=publish&orderby=name&order=ASC");
			while ( have_posts() ) : the_post();
				if(isset($post_array) && in_array("1", $post_array)){
					if(current($post_array) == 1){
						addItem($post);
					}
				}else{
					addItem($post);
				}
				next($post_array);
			endwhile;
			wp_reset_query(); 

			function addItem($post){
				$custom = get_post_custom($post->ID);
				$url = get_post_custom_values("my_slider_url");
				$caption = get_post_custom_values("my_slider_caption");
				$sl_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'slider-post-thumbnail');
				$img_class = "";

				if($sl_image_url[0]==""){
					$sl_image_url[0] = PARENT_URL."/images/blank.gif";
					$img_class = 'max_height';
				}
				if($url[0]!=""){
					$url[0] = '<a href="'.$url[0].'" title="'.theme_locals('read_more').'" class="btn btn-primary" >'.theme_locals('read_more').'</a>';
				}
				if ($caption[0]!="") {
					$caption[0] = '<p>'.stripslashes(htmlspecialchars_decode($caption[0])).'</p>';
				}
				echo '<li>';
				echo '<img src="'.$sl_image_url[0].'" width="100%" height="auto" class="slider_img '.$img_class.'" alt="">';
				if($caption[0]!="" || $url[0]!=""){
					echo '<div class="accordion_caption">'.$caption[0].$url[0].'</div>';
				}
				echo '</li>';
			}
		?>
	</ul>
	<div class="accordion_loader"></div>
</div>