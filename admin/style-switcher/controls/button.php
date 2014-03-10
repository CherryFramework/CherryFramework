<?php
if ( ! class_exists( 'WP_Customize_Control' ) )
	return NULL;

/**
 * Class to create a custom button control
 */
if ( !class_exists('Button_Custom_Control') ) {
	class Button_Custom_Control extends WP_Customize_Control {

		public $type = 'button';
		/**
		 * Render the content on the theme customizer page
		 */
		public function render_content() {

			global $cherry_locals_arr;

			if ( empty( $this->choices ) )
				return;

			$name = '_customize-buttons-' . $this->id; ?>

			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<ul class="button-control-list">
				<?php foreach ( $this->choices as $value => $label ) { ?>
				<li class="button-control">
					<input id="<?php echo esc_attr( $name ); ?>_<?php echo esc_attr( $value ); ?>" class="button-radio" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); ?> />
					<label for="<?php echo esc_attr( $name ); ?>_<?php echo esc_attr( $value ); ?>">
						<span class="<?php echo $this->id; ?>" style="background:<?php echo sanitize_hex_color($value); ?>">
							<?php if ( isset($cherry_locals_arr[esc_attr( $value )]) ) {
								echo $cherry_locals_arr[esc_attr( $value )];
							} ?>
						</span>
					</label>
				</li>
			<?php } ?>
			</ul>
	<?php }

		public function enqueue() {
			wp_enqueue_style( 'custom_customizer_controls', $this->file_uri('admin/style-switcher/assets/css/customizer-controls.css'), false, '', 'all' );
		}

		/**
		 * Function used to get the file URI - useful when child theme is used
		 */
		public function file_uri( $path = false ) {
			if ( CURRENT_THEME != 'cherry' ) {
				if ( $path == false ) {
					return CHILD_URL;
				} else {
					if ( is_file( CHILD_DIR . '/' . $path ) ) {
						return CHILD_URL . '/' . $path;
					} else {
						return PARENT_URL . '/' . $path;
					}
				}
			} else {
				if ( $path == false ) {
					return PARENT_URL;
				} else {
					return PARENT_URL . '/' . $path;
				}
			}
		}
	}
} ?>