<?php
if ( ! class_exists( 'WP_Customize_Control' ) )
	return NULL;

/**
 * Class to create a custom skin control
 */
if ( !class_exists('Skin_Custom_Control') ) {
	class Skin_Custom_Control extends WP_Customize_Control {

		public $type = 'skin';
		/**
		 * Render the content on the theme customizer page
		 */
		public function render_content() {

			if ( is_dir( CHILD_DIR . '/css/skin/' ) ) {
				foreach ( glob(CHILD_DIR . "/css/skin/*.css") as $css ) {
					$this->choices[] = $css;
				}
			}

			if ( empty($this->choices) ) {
				foreach ( glob(PARENT_DIR . "/css/skin/*.css") as $css ) {
					$this->choices[] = $css;
				}
			}

			if ( empty($this->choices) )
				return;

			$name = '_customize-skins-' . $this->id; ?>

			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<ul class="skin-control-list">
				<?php foreach ( $this->choices as $key => $css ) {

					if ( basename($css) == 'empty.css' )
						continue;

					$css_url = $this->file_uri('css/skin/' . basename($css)); ?>

				<li class="skin-control">
					<input id="<?php echo esc_attr( $name ); ?>_<?php echo esc_attr( $key ); ?>" class="skin-radio" type="radio" value="<?php echo esc_attr( $css_url ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $css_url ); ?> />
					<label for="<?php echo esc_attr( $name ); ?>_<?php echo esc_attr( $key ); ?>">
						<span class="<?php echo $this->id; ?>"><?php echo basename($css, '.css'); ?></span>
					</label>
				</li>
			<?php } ?>
			</ul>
	<?php }

		public function enqueue() {
			wp_enqueue_style( 'custom_customizer_controls', OPTIONS_FRAMEWORK_DIRECTORY . 'style-switcher/assets/css/customizer-controls.css', false, '', 'all' );
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