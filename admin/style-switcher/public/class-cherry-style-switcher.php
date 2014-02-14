<?php
/**
 * Style Switcher class.
 *
 * @package Cherry_Style_Switcher
 * @author  CherryTeam
 */
class Cherry_Style_Switcher {

	/**
	 * Style Switcher version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 *
	 * Unique identifier.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $preffix = 'cherry-style-switcher';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the Style Switcher by loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// This hook allows to define Style Switcher (Theme Customizer) sections, settings, and controls.
		add_action( 'customize_register', array( $this, 'customize_manager_demo' ) );

		// Hook for load customizer-facing stylesheet, script and others.
		add_action( 'customize_preview_init', array( $this, 'preview_init' ) );

		// Load main stylesheet.
		add_action( 'cherry_customize_enqueue_styles', array( $this, 'enqueue_styles' ) );
		add_action( 'customize_controls_init', array( $this, 'delete_demo_css' ) );

		// Hook for update option via ajax.
		add_action( 'wp_ajax_custom_update_option', array( $this, 'custom_update_option' ) );
		add_action( 'wp_ajax_nopriv_custom_update_option', array( $this, 'custom_update_option' ) );

		// Hook for delete option.
		add_action( 'customize_controls_init', array( $this, 'custom_delete_option' ) );

		// Hook for require template part via ajax.
		add_action( 'wp_ajax_require_template_part', array( $this, 'require_template_part' ) );
		add_action( 'wp_ajax_nopriv_require_template_part', array( $this, 'require_template_part' ) );

		// Output CSS for Status Label
		add_action( 'wp_footer', array( $this, 'add_spiner' ), 999 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Function used to get the file absolute path - useful when child theme is used
	 *
	 * @since     1.0.0
	 *
	 * @param     boolean   $path
	 * @return    string    file absolute path (in the original theme or in the child theme if file exists)
	 */
	public static function file_path( $path = false ) {
		if ( is_child_theme() ) {
			if ( $path == false ) {
				return CHILD_DIR;
			} else {
				if ( is_file( CHILD_DIR . '/' . $path ) ) {
					return CHILD_DIR . '/' . $path;
				} else {
					return PARENT_DIR . '/' . $path;
				}
			}
		} else {
			if ( $path == false ) {
				return PARENT_DIR;
			} else {
				return PARENT_DIR . '/' . $path;
			}
		}
	}

	/**
	 * Function used to get the file URI - useful when child theme is used
	 *
	 * @since     1.0.0
	 *
	 * @param     boolean   $path
	 * @return    string    file absolute path (in the original theme or in the child theme if file exists)
	 */
	function file_uri( $path = false ) {
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
			if( $path == false ) {
				return PARENT_URL;
			} else {
				return PARENT_URL . '/' . $path;
			}
		}
	}

	/**
	 * Register and enqueue main stylesheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->preffix, $this->file_uri('admin/style-switcher/assets/css/style-switcher.css'), array(), self::VERSION );
	}

	/**
	 * Register and enqueue customizer-facing stylesheet, script and others.
	 *
	 * @since    1.0.0
	 */
	public function preview_init() {
		// Register and enqueue spin.js script.
		wp_enqueue_script( 'spin', PARENT_URL . '/admin/style-switcher/assets/js/spin.min.js', array( 'jquery', 'customize-preview' ), '1.3.3', true );
		// Register and enqueue main script.
		wp_enqueue_script( $this->preffix, $this->file_uri('admin/style-switcher/assets/js/style-switcher.js'), array( 'jquery', 'customize-preview' ), self::VERSION, true );

		// Register and enqueue others stylesheets.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_demo_style' ), 99 );
		add_action( 'wp_head', array( $this, 'loader_styles' ), 99 );
		add_action( 'wp_head', array( $this, 'enqueue_skin_style' ), 999);

		// Output JavaScript variables on the front end.
		add_action( 'wp_head', array( $this, 'add_js_var' ) );

		if ( of_get_option('main_layout') === 'fixed' ) {
			$body_class = get_body_class();

			if ( !in_array('cherry-fixed-layout', $body_class) ) {
				add_filter( 'body_class', array( $this, 'layout_class' ) );
			}
		} elseif ( of_get_option('main_layout') === 'fullwidth' ) {
			add_filter('body_class', array( $this, 'remove_body_class' ), 20, 2);
		}
	}

	/**
	 * Read variables.less, compile it's and output CSS
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function print_styles() {
		if ( !class_exists('lessc') )
			include_once ( PARENT_DIR .'/includes/lessc.inc.php' );

		$input_file  = '/style.less';
		$output_file = '/demo-style.css';

		if ( CURRENT_THEME == 'cherry' ) {
			$input_file  = '/less' . $input_file;
			$output_file = '/css' . $output_file;
		}

		global $variablesArray;
		$links_color = ( get_option( 'cherry_color_schemes' ) == '' ) ? of_get_option('links_color') : get_option( 'cherry_color_schemes' );
		$variablesArray['linkColor']      = $links_color;
		$variablesArray['linkColorHover'] = $links_color;

		$formatter = new lessc_formatter_classic;
		$formatter->indentChar = "\t";

		$less = new lessc;
		$less->setVariables($variablesArray);
		$less->setFormatter($formatter);

		try {
			$less->compileFile( CHILD_DIR . $input_file, CHILD_DIR . $output_file );
		} catch (Exception $ex) {
			echo "lessphp fatal error: ".$ex->getMessage();
		}
	}

	/**
	 * Register and enqueue demo (color schemes) stylesheet.
	 * 
	 * @since    1.0.0
	 */
	public function enqueue_demo_style() {
		$demo_file = '/demo-style.css';
		$main_file = '/main-style.css';

		if ( CURRENT_THEME == 'cherry' ) {
			$demo_file = '/css' . $demo_file;
			$main_file = '/css/style.css';
		}
		if ( file_exists( CHILD_DIR . $demo_file ) ) {
			wp_enqueue_style( $this->preffix . '-schemes', CHILD_URL . $demo_file, array(), self::VERSION );
		} else {
			wp_enqueue_style( $this->preffix . '-schemes', CHILD_URL . $main_file, array(), self::VERSION );
		}
	}

	/**
	 * Output CSS for #style-switcher-status
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function loader_styles() {
		$output = "<style type='text/css'>";
		$output .= "#style-switcher-spin {position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 0; display: none; background-color: #000;}";
		$output .= "</style>";
		echo $output;
	}

	/**
	 * Register and enqueue skin stylesheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_skin_style() {
		if ( get_option( 'cherry_color_skin' ) ) {
			wp_enqueue_style( $this->preffix . '-skin', get_option( 'cherry_color_skin' ), array(), self::VERSION );
		} else {
			wp_enqueue_style( $this->preffix . '-skin', PARENT_URL . '/css/skin/empty.css', array(), self::VERSION );
		}
	}

	/**
	 * The filter is used to filter the classes that are assigned
	 * to the body HTML element on the current page.
	 *
	 * @since    1.0.0
	 *
	 * @param  string or array $classes
	 * @return array
	 */
	public function layout_class( $classes ) {
		$classes[] = 'cherry-fixed-layout';

		return $classes;
	}

	public function remove_body_class( $wp_classes ) {
		foreach ( $wp_classes as $key => $value ) {
			if ( $value == 'cherry-fixed-layout' ) unset( $wp_classes[$key] );
		}
		return $wp_classes;
	}

	/**
	 * Customizer manager demo
	 *
	 * @since    1.0.0
	 *
	 * @param  WP_Customizer_Manager $wp_customize
	 * @return void
	 */
	public function customize_manager_demo( $wp_customize ) {
		$this->custom_sections( $wp_customize );
	}

	/**
	 * Adds a new section to use custom controls in the WordPress customiser
	 *
	 * @since    1.0.0
	 *
	 * @param  object $wp_customize - WP Manager
	 * @return void
	 */
	private function custom_sections( $wp_customize ) {
		if ( !function_exists('combined_option_array') )
			return;

		$options = combined_option_array();

		$wp_customize->add_section( CURRENT_THEME.'_style_switcher', array(
			'title'    => __('Style Switcher', CURRENT_THEME),
			'priority' => 999
		));

		/* Layout Style */
		// Custom control - Button
		if ( ( of_get_option('visible_layout_style_opt') == 'true' ) && ( isset($options['main_layout']) ) ) {
			require_once $this->file_path('admin/style-switcher/controls/button.php');
			$wp_customize->add_setting( CURRENT_THEME.'[main_layout]', array(
				'type'      => 'option',
				'transport' => 'postMessage'
			) );
			$wp_customize->add_control( new Button_Custom_Control( $wp_customize, CURRENT_THEME.'_main_layout', array(
				'label'    => __('Layout Style', CURRENT_THEME),
				'section'  => CURRENT_THEME.'_style_switcher',
				'settings' => CURRENT_THEME.'[main_layout]',
				'choices'  => $options['main_layout']['options'],
				'type'     => 'button',
				'priority' => 1
			) ) );
		}

		/* Color Skin */
		// Custom control - Skin
		if ( of_get_option('visible_color_skin_opt') == 'true' ) {
			require_once $this->file_path('admin/style-switcher/controls/skin.php');
			$wp_customize->add_setting( CURRENT_THEME.'[color_skin]', array(
				'type'      => 'option',
				'transport' => 'postMessage'
			) );
			$wp_customize->add_control( new Skin_Custom_Control( $wp_customize, CURRENT_THEME.'_color_skin', array(
				'label'    => __('Color Skin', CURRENT_THEME),
				'section'  => CURRENT_THEME.'_style_switcher',
				'settings' => CURRENT_THEME.'[color_skin]',
				'choices'  => '',
				'type'     => 'skin',
				'priority' => 2
			) ) );
		}

		/* Color Schemes */
		// Custom control - Button
		if ( ( of_get_option('visible_color_schemes_opt') == 'true' ) && ( isset($options['main_layout']) ) ) {
			require_once $this->file_path('admin/style-switcher/controls/button.php');
			$wp_customize->add_setting( CURRENT_THEME.'[links_color]', array(
				'default'   => $options['links_color']['std'],
				'type'      => 'option',
				'transport' => 'postMessage'
			) );
			$wp_customize->add_control( new Button_Custom_Control( $wp_customize, CURRENT_THEME.'_links_color', array(
				'label'    => __('Color Schemes', CURRENT_THEME),
				'section'  => CURRENT_THEME.'_style_switcher',
				'settings' => CURRENT_THEME.'[links_color]',
				'choices'  => array(
					'#e74c3c' => 'color1',
					'#e67e22' => 'color2',
					'#f1c40f' => 'color3',
					'#9b59b6' => 'color4',
					'#2980b9' => 'color5',
					'#27ae60' => 'color6',
					'#16a085' => 'color7',
					'#34495e' => 'color8'
					),
				'type'     => 'button',
				'priority' => 3
			) ) );
		}

		/* Patterns */
		// Custom control - Pattern
		if ( ( of_get_option('visible_patterns_opt') == 'true' ) && ( isset($options['body_background']) ) ) {
			require_once $this->file_path('admin/style-switcher/controls/pattern.php');
			$wp_customize->add_setting( CURRENT_THEME.'[body_background][image]', array(
				'default'   => $options['body_background']['std']['image'],
				'type'      => 'option',
				'transport' => 'postMessage'
			) );
			$wp_customize->add_control( new Pattern_Custom_Control( $wp_customize, CURRENT_THEME.'_body_background', array(
				'label'    => __('Patterns', CURRENT_THEME),
				'section'  => CURRENT_THEME.'_style_switcher',
				'settings' => CURRENT_THEME.'[body_background][image]',
				'choices'  => '',
				'type'     => 'pattern',
				'priority' => 4
			) ) );
		}

		/* Slider Type */
		// Custom control - Layout Picker
		if ( of_get_option('visible_slider_opt') == 'true' ) {
			require_once $this->file_path('admin/style-switcher/controls/layout-picker.php');
			$wp_customize->add_setting( CURRENT_THEME.'[slider_type]', array(
				'default'   => $options['slider_type']['std'],
				'type'      => 'option',
				'transport' => 'postMessage'
			) );
			$wp_customize->add_control( new Layout_Picker_Custom_Control( $wp_customize, CURRENT_THEME.'_slider_type', array(
				'label'    => __('Slider', CURRENT_THEME),
				'section'  => CURRENT_THEME.'_style_switcher',
				'settings' => CURRENT_THEME.'[slider_type]',
				'choices'  => $options['slider_type']['options'],
				'type'     => 'layout-picker',
				'priority' => 5
			) ) );
		}

		/* Blog layout */
		// Custom control - Layout Picker
		if ( of_get_option('visible_blog_layout_opt') == 'true' ) {
			require_once $this->file_path('admin/style-switcher/controls/layout-picker.php');
			$wp_customize->add_setting( CURRENT_THEME.'[blog_sidebar_pos]', array(
				'default'   => $options['blog_sidebar_pos']['std'],
				'type'      => 'option'
			) );
			$wp_customize->add_control( new Layout_Picker_Custom_Control( $wp_customize, CURRENT_THEME.'_blog_sidebar_pos', array(
				'label'    => $options['blog_sidebar_pos']['name'],
				'section'  => CURRENT_THEME.'_style_switcher',
				'settings' => CURRENT_THEME.'[blog_sidebar_pos]',
				'choices'  => $options['blog_sidebar_pos']['options'],
				'type'     => 'layout-picker',
				'priority' => 6
			) ) );
		}
	}

	/**
	 * Custom function for update_option
	 *
	 * @since    1.0.0
	 *
	 * @return   void
	 */
	public function custom_update_option() {
		if ( !empty($_POST) && array_key_exists('option_name', $_POST) ) {
			$option_name = $_POST['option_name'];
		}
		if ( !empty($_POST) && array_key_exists('option_value', $_POST) ) {
			$option_value = $_POST['option_value'];
		}
		if ( isset($option_name) && isset($option_value) ) {
			update_option( $option_name, $option_value );
		}
		if ( $option_name == 'cherry_color_schemes' ) {
			$this->print_styles();
		}
		exit;
	}

	/**
	 * Custom function for delete option
	 *
	 * @since   1.0.0
	 *
	 * @return  void
	 */
	public function custom_delete_option() {
		delete_option('cherry_color_skin');
		delete_option('cherry_color_schemes');
	}

	/**
	 * Adds the JavaScript variables to the frontend.
	 *
	 * @since   1.0.0
	 */
	public function add_js_var() {
		$output = '<script type="text/javascript">';
			$output .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '", ';
			$output .= 'curslider = "' . of_get_option( 'slider_type' ) . '"';
		$output .= '</script>';

		echo $output;
	}

	/**
	 * Require template part requested via Ajax
	 *
	 * @since   1.0.0
	 */
	public function require_template_part() {
		if ( !empty($_POST) && array_key_exists('template_part', $_POST) ) {
			$template_part = $_POST['template_part'];

			switch ( $template_part ) {
				case 'camera_slider':
					require_once $this->file_path( 'slider.php' );
					break;
				case 'accordion_slider':
					require_once $this->file_path( 'accordion.php' );
					break;
				default:
					break;
			}
		}
		exit;
	}

	/**
	 * Add tag div for spiner
	 *
	 * @since   1.0.0
	 */
	public function add_spiner() {
		$output = '<div id="style-switcher-spin"></div>';
		echo $output;
	}

	/**
	 * Delete demo stylesheet
	 * 
	 * @since   1.0.0
	 */
	public function delete_demo_css() {
		if ( FILE_WRITEABLE ) {
			$demo_file = '/demo-style.css';

			if ( CURRENT_THEME == 'cherry' ) {
				$demo_file = '/css' . $demo_file;
			}

			if ( file_exists( CHILD_DIR . $demo_file ) ) {
				unlink( CHILD_DIR . $demo_file );
			}
		}
	}
}