<?php
if ( ! class_exists( 'WP_Customize_Control' ) )
	return NULL;

/**
 * Class to create a custom pattern control
 */
if ( !class_exists('Pattern_Custom_Control') ) {
	class Pattern_Custom_Control extends WP_Customize_Control {

		public $type = 'pattern';
		/**
		 * Render the content on the theme customizer page
		 */
		public function render_content() {

			if ( is_dir( CHILD_DIR . '/images/patterns/' ) ) {
				foreach ( glob(CHILD_DIR . "/images/patterns/*.jpg") as $img ) {
					$this->choices[] = $img;
				}
			}

			if ( empty($this->choices) ) {
				foreach ( glob(PARENT_DIR . "/images/patterns/*.jpg") as $img ) {
					$this->choices[] = $img;
				}
			}

			if ( empty($this->choices) )
				return;

			$name = '_customize-patterns-' . $this->id; ?>

			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<ul class="pattern-control-list">
				<?php foreach ( $this->choices as $key => $pattern ) {

					$pattern_url = $this->file_uri('images/patterns/' . basename($pattern)); ?>

				<li class="pattern-control">
					<input id="<?php echo esc_attr( $name ); ?>_<?php echo esc_attr( $key ); ?>" class="pattern-radio" type="radio" value="<?php echo esc_url( $pattern_url ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $pattern ); ?> />
					<label for="<?php echo esc_attr( $name ); ?>_<?php echo esc_attr( $key ); ?>">
						<span></span>
						<img src="<?php echo esc_url( $pattern_url ); ?>" alt="" />
					</label>
				</li>
			<?php } ?>
			</ul>
	<?php }

		public function enqueue() {
			// wp_enqueue_style( 'custom_customizer_controls', OPTIONS_FRAMEWORK_DIRECTORY . 'style-switcher/assets/css/customizer-controls.css', false, '', 'all' );
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