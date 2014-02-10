<?php
if ( ! class_exists( 'WP_Customize_Control' ) )
	return NULL;

/**
 * Class to create a custom layout control
 */
if ( !class_exists('Layout_Picker_Custom_Control') ) {
	class Layout_Picker_Custom_Control extends WP_Customize_Control {

		public $type = 'layout-picker';
		/**
		 * Render the content on the theme customizer page
		 */
		public function render_content() {

			if ( empty( $this->choices ) )
				return;

			$name = '_customize-image-radios-' . $this->id; ?>

			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php foreach ( $this->choices as $value => $label ) { ?>
			<div class="layout-control">
				<input id="<?php echo esc_attr( $name ); ?>_<?php echo esc_attr( $value ); ?>" class="image-radio" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
				<label for="<?php echo esc_attr( $name ); ?>_<?php echo esc_attr( $value ); ?>">
					<img src="<?php echo esc_url( $label ); ?>" alt="<?php echo esc_attr( $value ); ?>" />
				</label>
			</div>
			<?php }
		}

		public function enqueue() {
			wp_enqueue_style( 'custom_customizer_controls', OPTIONS_FRAMEWORK_DIRECTORY . 'style-switcher/assets/css/customizer-controls.css', false, '', 'all' );
		}
	}
} ?>