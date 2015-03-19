<?php
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
} ?>

<div id="tm-chat-dialog" class="tm-chat chat_box_wrap online">

	<div class="chat_box_heading chat_out">
		<a href="#" class="tm-chat-control"><i class="dashicons dashicons-arrow-up-alt2"></i></a>
		<span class="tm-chat-title"><i class="dashicons dashicons-format-chat"></i><?php _e( 'Live Chat', CURRENT_THEME ); ?></span>
	</div>
	<div class="chat_box_heading chat_in">
		<a href="#" class="tm-chat-control"><i class="dashicons dashicons-arrow-down-alt2"></i></a>
		<span class="tm-chat-title"><i class="dashicons dashicons-format-chat"></i><?php _e( 'Live Chat', CURRENT_THEME ); ?></span>
	</div>

	<div class="chat_box_body">

		<div class="tm-chat-accordion">
			<div class="tm-chat-accordion-item first-item">
				<div>
					<form action="" method="POST" role="form" class="tm-chat-form">
						<legend class="form-group-alt"><?php _e( 'Please feel free to ask another question', CURRENT_THEME ); ?></legend>
						<div class="legend">
							<legend><?php _e( 'Thanks for contacting us!', CURRENT_THEME ); ?></legend>
							<p><?php _e( 'To better serve you, please provide your order id.', CURRENT_THEME ); ?></p>
						</div>
						<div class="form-group form-group-preloader">
							<input type="text" name="chat-order-id" id="chat-order-id" class="required-field" placeholder="<?php _e( 'Enter Your Order ID', CURRENT_THEME ); ?>" tabindex="1">
							<span class="preloader"></span>
						</div>
						<div id="pr-type-group" class="form-group hidden"></div>
						<div class="form-group">
							<input type="button" name="chat-start" value="<?php _e( 'Start Chat', CURRENT_THEME ); ?>" class="start_chat disabled" tabindex="3" disabled>
							<input type="button" name="chat-start-order" value="<?php _e( 'Start Chat', CURRENT_THEME ); ?>" class="start_chat_order disabled" tabindex="3" disabled>
						</div>
					</form>
				</div>
			</div>

			<div class="order-id-switch-wrap">
				<strong><?php _e( "Have an order ID", CURRENT_THEME ); ?></strong> <a href="#" class="order-id-switch"><?php _e( 'Click here', CURRENT_THEME ); ?></a>
			</div>
			<strong><?php _e( "New client or can't find your order ID?", CURRENT_THEME ); ?> <a href="#" class="order-id-switch order-id-switch-alt"><?php _e( 'Click here', CURRENT_THEME ); ?></a></strong>

			<div class="tm-chat-accordion-item second-item">
				<div>
					<form action="" method="POST" role="form" class="tm-chat-form">
						<div class="legend">
							<p><?php _e( 'To better serve you, please fill out the short form.', CURRENT_THEME ); ?></p>
						</div>
						<div class="form-group">
							<input type="text" name="chat-nick" id="chat-nick" placeholder="<?php _e( 'Name', CURRENT_THEME ); ?>" required tabindex="1">
						</div>
						<div class="form-group">
							<input type="text" name="chat-email" id="chat-email" placeholder="<?php _e( 'Email', CURRENT_THEME ); ?>" required tabindex="2">
						</div>
						<div class="form-group">
							<input type="button" name="chat-start" value="<?php _e( 'Start Chat', CURRENT_THEME ); ?>" class="start_chat disabled" tabindex="3" disabled>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="status-group">
			<i class="status-marker"></i><span class="chat_operator_status"><?php _e( 'operator is online', CURRENT_THEME ); ?></span>
		</div>
	</div>

</div>