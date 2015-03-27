<?php
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
} ?>

<div id="tm-chat-dialog" class="tm-chat chat_box_wrap form-order-id offline">
	<div class="chat_box_heading chat_out">
		<a href="#" class="tm-chat-control"><i class="dashicons dashicons-arrow-up-alt2"></i></a>
		<span class="tm-chat-title"><i class="dashicons dashicons-editor-help"></i><?php _e( 'Ask Your Question', CURRENT_THEME ); ?></span>
	</div>
	<div class="chat_box_heading chat_in">
		<a href="#" class="tm-chat-control"><i class="dashicons dashicons-arrow-down-alt2"></i></a>
		<span class="tm-chat-title"><i class="dashicons dashicons-editor-help"></i><?php _e( 'Ask Your Question', CURRENT_THEME ); ?></span>
	</div>

	<div class="chat_box_body">
		<div class="tm-chat-msg tm-chat-msg-success">
			<i class="dashicons dashicons-yes"></i>
			<div class="extra-wrap">
				<?php _e( 'Your message has been sent.<br> We will reply to you shortly.' , CURRENT_THEME ); ?>
			</div>
		</div>
		<div class="tm-chat-msg tm-chat-msg-error">
			<i class="dashicons dashicons-yes"></i>
			<div class="extra-wrap">
				<?php _e( 'Sorry, but your message has not been sent.' , CURRENT_THEME ); ?>
			</div>
		</div>
		<form action="" method="POST" role="form" class="tm-chat-form">
			<div class="message-after-send">
				<legend class="form-group"><?php _e( 'Please feel free to ask another question', CURRENT_THEME ); ?></legend>
			</div>
			<div class="message-before-send">
				<legend class="form-group"><?php _e( 'Thanks for contacting us!', CURRENT_THEME ); ?></legend>
				<p><?php _e( 'To better serve you, please fill out the short form.', CURRENT_THEME ); ?></p>
			</div>
			<div class="form-group">
				<input type="text" name="chat-nick" id="chat-nick" value="" placeholder="<?php _e( 'Name', CURRENT_THEME ); ?>" required tabindex="1">
			</div>
			<div class="form-group">
				<input type="text" name="chat-email" id="chat-email" value="" placeholder="<?php _e( 'Email', CURRENT_THEME ); ?>" required tabindex="2">
			</div>
			<div class="form-group form-group-preloader">
				<input type="text" name="chat-order-id" id="chat-order-id" class="LV_valid_field" value="" placeholder="<?php _e( 'Order ID', CURRENT_THEME ); ?>" tabindex="3">
				<span class="optional"><?php _e( 'Optional', CURRENT_THEME ); ?></span>
				<span class="preloader"></span>
			</div>
			<div class="form-group full-width">
				<input type="text" name="chat-subject" id="chat-subject" value="" placeholder="<?php _e( 'Subject', CURRENT_THEME ); ?>" required tabindex="4">
			</div>
			<div class="form-group full-width">
				<textarea name="chat-message" id="chat-message" placeholder="<?php _e( 'Message', CURRENT_THEME ); ?>" required tabindex="5"></textarea>
			</div>
			<div class="form-group">
				<input type="button" name="chat-start" value="<?php _e( 'Submit', CURRENT_THEME ); ?>" class="start_chat disabled" tabindex="6" disabled>
			</div>

			<div class="chat-settings-wrap">
				<a class="chat-settings" href="<?php echo add_query_arg( array( 'page' => 'options-framework#of-option-1##section-tm_live_chat' ), admin_url('admin.php') ); ?> "><i class="dashicons dashicons-admin-generic"></i><span><?php _e( 'Chat settings', CURRENT_THEME ); ?></span></a>
			</div>
			<div class="status-group">
				<i class="status-marker"></i><span class="chat_operator_status"><?php _e( 'operator is offline', CURRENT_THEME ); ?></span>
			</div>
		</form>
	</div>

</div>