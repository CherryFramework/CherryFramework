<?php
	/*
	 * Set Proper Parent/Child theme paths for inclusion
	 */
	@define( 'PARENT_DIR', get_template_directory() );
	@define( 'CHILD_DIR', get_stylesheet_directory() );

	@define( 'PARENT_URL', get_template_directory_uri() );
	@define( 'CHILD_URL', get_stylesheet_directory_uri() );

	@define( 'CURRENT_THEME', getCurrentTheme() );
	@define( 'CHERRY_VER', cherry_get_theme_version('CherryFramework') );
	@define( 'FILE_WRITEABLE', is_writeable(PARENT_DIR.'/style.css'));

	/**
	*
	* Variables array init
	*
	**/
	$variablesArray = array(
		'textColor'      => '#000000',
		'bodyBackground' => '#000000',
		'baseFontFamily' => '#000000',
		'baseFontSize'   => '#000000',
		'baseLineHeight' => '#000000',
		'linkColor'      => '#000000',
		'linkColorHover' => '#000000',
		'mainBackground' => '#ffffff'
		);

	/**
	*
	* JS global variables
	*
	**/
	function cherry_js_global_variables(){
		$output = "<script>";
		$output .="\n var system_folder = '".PARENT_URL."/admin/data_management/',";
		$output .= "\n\t CHILD_URL ='" .CHILD_URL."',";
		$output .= "\n\t PARENT_URL = '".PARENT_URL."', ";
		$output .= "\n\t CURRENT_THEME = '".CURRENT_THEME."'";
		$output .= "</script>";
		echo $output;
	}
	add_action('wp_head', 'cherry_js_global_variables');
	add_action('admin_head', 'cherry_js_global_variables');

	/**
	*
	* Definition current theme
	*
	**/
	function getCurrentTheme() {
		if ( function_exists('wp_get_theme') ) {
			$theme = wp_get_theme();
			if ( $theme->exists() ) {
				$theme_name = $theme->Name;
			}
		} else {
			$theme_name = get_current_theme();
		}
		$theme_name = preg_replace("/\W/", "_", strtolower($theme_name) );
		return $theme_name;
	}

	/**
	*
	* Definition theme version
	* @param string $theme_name Directory name for the theme
	*
	**/
	function cherry_get_theme_version($theme_name) {
		if ( function_exists('wp_get_theme') ) {
			$theme = wp_get_theme($theme_name);
			if ( $theme->exists() ) {
				$theme_ver = $theme->Version;
			}
		} else {
			$theme_data = get_theme_data( get_theme_root() . '/' . $theme_name . '/style.css' );
			$theme_ver  = $theme_data['Version'];
		}
		return $theme_ver;
	}

	/**
	*
	* Comment some value from variables.less
	*
	**/
	if ( CURRENT_THEME != 'cherry' )
		add_action('cherry_activation_hook', 'comment_child_var');

	function comment_child_var() {
		global $variablesArray;

		$file = CHILD_DIR .'/bootstrap/less/variables.less';

		if ( file_exists($file) ) {
			$allVariablessArray = file($file);

			foreach ($variablesArray as $key => $value) {
				foreach ($allVariablessArray as $k => $v) {
					$pos = strpos($v, $key);
					if ( $pos!==false && $pos == 1 ) {
						$allVariablessArray[$k] = '// ' . $v;
						break;
					}
				}
			}
			file_put_contents($file, $allVariablessArray);
		}
	}

	/**
	 * Helper function to return the theme option value.
	 * If no value has been saved, it returns $default.
	 * Needed because options are saved as serialized strings.
	 **/
	if ( !function_exists( 'of_get_option' ) ) {
		function of_get_option($name, $default = false) {

			$optionsframework_settings = get_option('optionsframework');

			// Gets the unique option id
			$option_name = $optionsframework_settings['id'];

			if ( get_option($option_name) ) {
				$options = get_option($option_name);
			}

			if ( isset($options[$name]) ) {
				return $options[$name];
			} else {
				return $default;
			}
		}
	}

	/**
	*
	* Unlink less cache files
	*
	**/
	add_action('cherry_activation_hook', 'clean_less_cache');

	function clean_less_cache() {
		if ( CURRENT_THEME == 'cherry' ) {
			$bootstrapInput	= PARENT_DIR .'/less/bootstrap.less';
			$themeInput		= PARENT_DIR .'/less/style.less';
		} else {
			$bootstrapInput	= CHILD_DIR .'/bootstrap/less/bootstrap.less';
			$themeInput		= CHILD_DIR .'/style.less';
		}

		$cacheFile1 = $bootstrapInput.".cache";
		$cacheFile2 = $themeInput.".cache";
		if (file_exists($cacheFile1)) unlink($cacheFile1);
		if (file_exists($cacheFile2)) unlink($cacheFile2);
	}

	if ( (is_admin() && ($pagenow == "themes.php")) && FILE_WRITEABLE ) {
		do_action('cherry_activation_hook');
	}

	if ( !function_exists('cherry_theme_setup')) {
		function cherry_theme_setup() {

			//Loading theme textdomain
			load_theme_textdomain( CURRENT_THEME, PARENT_DIR . '/languages' );

			//Localization functions
			include_once (PARENT_DIR . '/includes/locals.php');

			//Plugin Activation
			include_once (CHILD_DIR . '/includes/register-plugins.php');

			//Setup MotoPress
			include_once (PARENT_DIR . '/includes/register-motopress.php');

			//Include shop
			if ( file_exists(get_stylesheet_directory().'/shop.php') ) {
				include_once (CHILD_DIR . '/shop.php');
			}
		}
		add_action('after_setup_theme', 'cherry_theme_setup');
	}

	//WPML compatibility
	//WPML filter for correct posts IDs for the current language Solution
	if ( function_exists( 'wpml_get_language_information' )) {
		update_option('suppress_filters', 0);
	} else {
		update_option('suppress_filters', 1);
	}
	//Register text for translation
	function cherry_wpml_translate_filter( $value, $name ) {
		return icl_translate( 'cherry', $name, $value );
	}
	//Check if WPML is activated
	if ( function_exists( 'icl_translate' ) ) {
		add_filter( 'cherry_text_translate', 'cherry_wpml_translate_filter', 10, 2 );
	}

	//Loading Custom function
	include_once (CHILD_DIR . '/includes/custom-function.php');

	//Loading jQuery and Scripts
	include_once (PARENT_DIR . '/includes/theme-scripts.php');

	//Sidebar
	include_once (CHILD_DIR . '/includes/sidebar-init.php');

	//Theme initialization
	include_once (CHILD_DIR . '/includes/theme-init.php');

	//Additional function
	include_once (PARENT_DIR . '/includes/theme-function.php');

	//Aqua Resizer for image cropping and resizing on the fly
	include_once (PARENT_DIR . '/includes/aq_resizer.php');

	//Add the pagemeta
	include_once (PARENT_DIR . '/includes/theme-pagemeta.php');

	//Add the postmeta
	include_once (PARENT_DIR . '/includes/theme-postmeta.php');

	//Add the postmeta to Portfolio posts
	include_once (PARENT_DIR . '/includes/theme-portfoliometa.php');

	//Add the postmeta to Slider posts
	include_once (PARENT_DIR . '/includes/theme-slidermeta.php');

	//Add the postmeta to Testimonials
	include_once (PARENT_DIR . '/includes/theme-testimeta.php');

	//Add the postmeta to Our Team posts
	include_once (PARENT_DIR . '/includes/theme-teammeta.php');

	//Loading options.php for theme customizer
	include_once (CHILD_DIR . '/options.php');
	include_once (PARENT_DIR . '/framework_options.php');

	//Framework Data Management
	include_once (PARENT_DIR . '/admin/data_management/data_management_interface.php');

	//SEO Settings
	include_once (PARENT_DIR . '/admin/seo/seo_settings_page.php');

	//WP Pointers
	include_once (PARENT_DIR . '/includes/class.wp-help-pointers.php');

	//Embedding LESS compile
	if ( !class_exists('lessc') ) {
		include_once (PARENT_DIR .'/includes/lessc.inc.php');
	}
	include_once (PARENT_DIR .'/includes/less-compile.php');

	// Olark Live Chat.
	if ( is_child_theme() && file_exists( CHILD_DIR . '/includes/live-chat.php' ) ) {
		include_once ( CHILD_DIR . '/includes/live-chat.php' );
	} else {
		include_once ( PARENT_DIR . '/includes/live-chat.php' );
	}

	// TM Live Chat.
	if ( 'yes' == of_get_option( 'tm_live_chat', 'yes' ) ) {
		include_once ( PARENT_DIR . '/includes/tm-chat/class-cherry-tm-chat.php' );
	}

	// removes detailed login error information for security
	add_filter('login_errors',create_function('$a', "return null;"));

	/*
	 * Loads the Options Panel
	 *
	 * If you're loading from a child theme use stylesheet_directory
	 * instead of template_directory
	 */
	if ( !function_exists( 'optionsframework_init' ) ) {
		define( 'OPTIONS_FRAMEWORK_DIRECTORY', PARENT_URL . '/admin/' );
		include_once dirname( __FILE__ ) . '/admin/options-framework.php';
	}

	/*
	 * Removes Trackbacks from the comment count
	 *
	 */
	if ( !function_exists('comment_count') ) {
		add_filter('get_comments_number', 'comment_count', 0);

		function comment_count( $count ) {
			if ( ! is_admin() ) {
				global $id;
				$args = 'status=approve&post_id=' . $id;
				$comments = get_comments( $args, ARRAY_A );
				$comments_by_type = separate_comments( $comments );
				return count($comments_by_type['comment']);
			} else {
				return $count;
			}
		}
	}

	/*
	 * Post Formats
	 *
	 */
	$formats = array(
				'aside',
				'gallery',
				'link',
				'image',
				'quote',
				'audio',
				'video');
	add_theme_support( 'post-formats', $formats );
	add_post_type_support( 'post', 'post-formats' );

	/*
	 * Custom excpert length
	 *
	 */
	if(!function_exists('new_excerpt_length')) {

		function new_excerpt_length($length) {
			return 60;
		}
		add_filter('excerpt_length', 'new_excerpt_length');
	}

	add_filter( 'the_excerpt', 'do_shortcode' );
	// enable shortcodes in sidebar
	add_filter('widget_text', 'do_shortcode');

	// custom excerpt ellipses for 2.9+
	if(!function_exists('custom_excerpt_more')) {

		function custom_excerpt_more($more) {
			return theme_locals("read_more").' &raquo;';
		}
		add_filter('excerpt_more', 'custom_excerpt_more');
	}

	// no more jumping for read more link
	if(!function_exists('no_more_jumping')) {

		function no_more_jumping($post) {
			return '&nbsp;<a href="'.get_permalink().'" class="read-more">'.theme_locals("continue_reading").'</a>';
		}
		add_filter('excerpt_more', 'no_more_jumping');
	}

	// category id in body and post class
	if(!function_exists('category_id_class')) {

		function category_id_class($classes) {
			global $post;
			foreach((get_the_category()) as $category)
				$classes [] = 'cat-' . $category->cat_ID . '-id';
				return $classes;
		}
		add_filter('post_class', 'category_id_class');
		add_filter('body_class', 'category_id_class');
	}

	// Threaded Comments
	if(!function_exists('enable_threaded_comments')) {
		function enable_threaded_comments() {
			if (!is_admin()) {
				if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
					wp_enqueue_script('comment-reply');
				}
			}
		}
		add_action('get_header', 'enable_threaded_comments');
	}

	//remove auto loading rel=next post link in header
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');


	// WP Pointers
	if (!function_exists('myHelpPointers')) {
		add_action('admin_enqueue_scripts', 'myHelpPointers');

		function myHelpPointers() {
			//First we define our pointers
			$pointers = array(
				array(
					'id'       => 'xyz1', // unique id for this pointer
					'screen'   => 'themes', // this is the page hook we want our pointer to show on
					'target'   => '#toplevel_page_options-framework', // the css selector for the pointer to be tied to, best to use ID's
					'title'    => theme_locals("import_sample_data"),
					'content'  => theme_locals("import_sample_data_desc"),
					'position' => array(
										'edge'   => 'left', //top, bottom, left, right
										'align'  => 'left', //top, bottom, left, right, middle
										)
					),
				array(
					'id'       => 'xyz2', // unique id for this pointer
					'screen'   => 'toplevel_page_options-framework', // this is the page hook we want our pointer to show on
					'target'   => '#toplevel_page_cherry-plugin-page', // the css selector for the pointer to be tied to, best to use ID's
					'title'    => theme_locals("import_sample_data"),
					'content'  => theme_locals("import_sample_data_desc"),
					'position' => array(
										'edge'   => 'left', //top, bottom, left, right
										'align'  => 'left', //top, bottom, left, right, middle
										)
					)
				// more as needed
			);

			//Now we instantiate the class and pass our pointer array to the constructor
			$myPointers = new WP_Help_Pointer($pointers);
		};
	}

	/*
	 * Navigation with description
	 *
	 */
	if (! class_exists('description_walker')) {
		class description_walker extends Walker_Nav_Menu {
			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				global $wp_query;
				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

				$class_names = $value = '';

				$classes = empty( $item->classes ) ? array() : (array) $item->classes;

				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
				$class_names = ' class="'. esc_attr( $class_names ) . '"';

				$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

				// $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
				// $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
				// $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				// $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

				$atts = array();
				$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
				$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
				$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
				$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

				/**
				 * Filter the HTML attributes applied to a menu item's <a>.
				 *
				 * @since 3.6.0
				 *
				 * @see wp_nav_menu()
				 *
				 * @param array $atts {
				 *     The HTML attributes applied to the menu item's <a>, empty strings are ignored.
				 *
				 *     @type string $title  Title attribute.
				 *     @type string $target Target attribute.
				 *     @type string $rel    The rel attribute.
				 *     @type string $href   The href attribute.
				 * }
				 * @param object $item The current menu item.
				 * @param array  $args An array of wp_nav_menu() arguments.
				 */
				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}

				$description  = ! empty( $item->description ) ? '<span class="desc">'.esc_attr( $item->description ).'</span>' : '';

				if($depth != 0) {
					$description = $append = $prepend = "";
				}

				$item_output = $args->before;
				$item_output .= '<a'. $attributes .'>';
				$item_output .= $args->link_before;

				if (isset($prepend))
					$item_output .= $prepend;

				$item_output .= apply_filters( 'the_title', $item->title, $item->ID );

				if (isset($append))
					$item_output .= $append;

				$item_output .= $description.$args->link_after;
				$item_output .= '</a>';
				$item_output .= $args->after;

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
		}
	}

	/*
	 * Cherry update
	 */
	$cherryTemplates = array(
		'404.php',
		'archive.php',
		'author.php',
		'category.php',
		'index.php',
		'page.php',
		'page-archives.php',
		'page-faq.php',
		'page-fullwidth.php',
		'page-home.php',
		'page-Portfolio2Cols-filterable.php',
		'page-Portfolio3Cols-filterable.php',
		'page-Portfolio4Cols-filterable.php',
		'page-testi.php',
		'search.php',
		'single.php',
		'single-portfolio.php',
		'single-team.php',
		'single-testi.php',
		'tag.php'
	);

	$headerFooterPattern = '/(\<header.*?\>.*?\<\/header\>|\<footer.*?\>.*?\<\/footer\>)/is';

	if (is_user_logged_in() && current_user_can('update_themes')) {
		$updateErrors = array();

		$oldCherryVersion = get_option('cherry_version', '1.0');
		$currentCherryVersion = getCherryVersion($updateErrors);
		$cherryV2 = '2.0';
		$cherryForceUpdate = (bool) get_option('cherry_force_update', false);

		if (version_compare($oldCherryVersion, $cherryV2) == -1 || $cherryForceUpdate) {
			writeLog(PHP_EOL . date('Y-m-d H:i:s'));
			writeLog('Old CherryFramework version: ' . $oldCherryVersion);
			writeLog('Current CherryFramework version: ' . $currentCherryVersion);
			writeLog('Force update: ' . (int) $cherryForceUpdate);

			cherryUpdate($cherryTemplates, $headerFooterPattern, $updateErrors);
			//changeChildTheme
			changeChildTheme($updateErrors);
			//end changeChildTheme
			if (empty($updateErrors)) {
				update_option('cherry_version', $currentCherryVersion);
				update_option('cherry_force_update', 0);
			}
		}
	}

	function getCherryVersion(&$updateErrors) {
		$style = PARENT_DIR . '/style.css';
		$themeVersion = 0;

		if (function_exists('wp_get_theme')) {
			$theme = wp_get_theme(get_option('template', 'CherryFramework'));
			if (file_exists($style) && $theme->exists()) {
				$themeVersion = $theme->Version;
			} else {
				$updateErrors[] = $style . ' does not exist';
				writeLog($style . ' does not exist');
			}
		} elseif (function_exists('get_theme_data')) {
			if (file_exists($style)) {
				$theme = get_theme_data($style);
				$themeVersion = $theme['Version'];
			} else {
				$updateErrors[] = $style . ' does not exist';
				writeLog($style . ' does not exist');
			}
		} else {
			if (file_exists($style)) {
				$content = file_get_contents($style);
				if ($content) {
					$pattern = '/\s*version\s*:\s*([^\n]+)\s*/is';
					preg_match($pattern, $content, $matches);
					if (!empty($matches[1])) {
						$themeVersion = trim($matches[1]);
					}
				} else {
					$updateErrors[] = 'Failed to read ' . $style;
					writeLog('Failed to read ' . $style);
				}
			} else {
				$updateErrors[] = $style . ' does not exist';
				writeLog($style . ' does not exist');
			}
		}
		return $themeVersion;
	}

	function isCherryChildTheme($themePath, $name, &$updateErrors) {
		$style = $themePath . '/style.css';
		$themeTemplate = false;

		if (function_exists('wp_get_theme')) {
			$theme = wp_get_theme($name);
			if ($theme->exists()) {
				if ($theme->Stylesheet != $theme->Template) {
					$themeTemplate = $theme->Template;
				}
			} else {
				$updateErrors[] = 'Theme ' . $theme->Name . ' does not exist';
				writeLog('Theme ' . $theme->Name . ' does not exist');
			}
		} elseif (function_exists('get_theme_data')) {
			$theme = get_theme_data($style);
			$themeTemplate = $theme['Template'];
		} else {
			$content = file_get_contents($style);
			if ($content) {
				$pattern = '/\s*template\s*:\s*([^\n]+)\s*/is';
				preg_match($pattern, $content, $matches);
				if (!empty($matches[1])) {
					$themeTemplate = trim($matches[1]);
				}
			} else {
				$updateErrors[] = 'Failed to read ' . $style;
				writeLog('Failed to read ' . $style);
			}
		}
		return ($themeTemplate == 'CherryFramework') ? true : false;
	}

	function getHeaderFooterCode($headerFooterPattern) {
		$headerFooter = array('header', 'footer');
		$headerFooterCode = array('header' => null, 'footer' => null);

		foreach ($headerFooter as $name) {
			$filePath = PARENT_DIR . '/' . $name . '.php';
			if (file_exists($filePath)) {
				$content = file_get_contents($filePath);
				if ($content) {
					$matchesCount = preg_match($headerFooterPattern, $content, $matches);
					if ($matchesCount == 1 && !empty($matches[0])) {
						$headerFooterCode[$name] = $matches[0];
					} else {
						$updateErrors[] = 'header|footer code not found in ' . $filePath;
						writeLog('header|footer code not found in ' . $filePath);
					}
				} else {
					$updateErrors[] = 'Failed to read ' . $filePath;
					writeLog('Failed to read ' . $filePath);
				}
			} else {
				$updateErrors[] = $filePath . ' does not exist';
				writeLog($filePath . ' does not exist');
			}
		}

		return $headerFooterCode;
	}

	function backupFile($templatePath) {
		return copy($templatePath, $templatePath . '.bak');
	}

	function writeLog($message) {
		$logFile = PARENT_DIR . '/update.log';
		if (is_writable(PARENT_DIR)) {
			file_put_contents($logFile, $message . PHP_EOL, FILE_APPEND);
		}
	}

	function cherryUpdate($cherryTemplates, $headerFooterPattern, &$updateErrors) {
		$themesPath = get_theme_root();

		$skip = array('.', '..', 'CherryFramework', 'twentytwelve', 'twentyeleven', 'twentyten', 'index.php');
		$themes = array_diff(scandir($themesPath), $skip);

		if (!empty($themes)) {
			foreach ($themes as $theme) {
				$themePath = $themesPath . '/' . $theme;
				if (is_dir($themePath) && file_exists($themePath . '/style.css')) {

					$isCherryChildTheme = isCherryChildTheme($themePath, $theme, $updateErrors);

					if ($isCherryChildTheme) {
						if (is_writable($themePath)) {
							writeLog(PHP_EOL . 'Child theme: ' . $themePath);
							$files = scandir($themePath);
							$themeTemplates = array_intersect($files, $cherryTemplates);
							if (!empty($themeTemplates)) {
								foreach ($themeTemplates as $template) {
									$templatePath = $themePath . '/' . $template;
									if (is_writable($templatePath)) {
										$content = file_get_contents($templatePath);
										if ($content) {
											$headerFooterMatchesCount = preg_match_all($headerFooterPattern, $content, $headerFooterMatches);
											if ($headerFooterMatchesCount > 0) {
												$backupFile = backupFile($templatePath);
												if ($backupFile) {
													writeLog('Backup ' . $templatePath);

													$content = preg_replace($headerFooterPattern, '', $content, -1, $headerFooterReplaceCount);
													if (!is_null($content) && $headerFooterReplaceCount > 0) {
														writeLog('Replace ' . $headerFooterReplaceCount . ' header|footer');

														if (file_put_contents($templatePath, $content)) {
															writeLog('Save ' . $templatePath);
														} else {
															$updateErrors[] = 'Failed to save ' . $templatePath;
															writeLog('Failed to save ' . $templatePath);
														}
													}
												} else {
													$updateErrors[] = 'Failed to backup ' . $templatePath;
													writeLog('Failed to backup ' . $templatePath);
												}
											}
										} else {
											$updateErrors[] = 'Failed to read ' . $templatePath;
											writeLog('Failed to read ' . $templatePath);
										}
									} else {
										$updateErrors[] = $templatePath . ' is not writable';
										writeLog($templatePath . ' is not writable');
									}
								}
							}

							$headerFooter = array_intersect($files, array('header.php', 'footer.php'));
							if (!empty($headerFooter)) {
								$headerFooterCode = getHeaderFooterCode($headerFooterPattern);
								if (!is_null($headerFooterCode['header']) && !is_null($headerFooterCode['footer'])) {
									foreach ($headerFooter as $file) {
										$filePath = $themePath . '/' . $file;
										if (is_writable($filePath)) {
											$content = file_get_contents($filePath);
											if ($content) {
												$headerFooterMatchesCount = preg_match_all($headerFooterPattern, $content, $headerFooterMatches);
												if ($headerFooterMatchesCount === 0) {
													$backupFile = backupFile($filePath);
													if ($backupFile) {
														writeLog('Backup ' . $filePath);

														if ($file == 'header.php') {
															$content .= $headerFooterCode['header'];
														} elseif ($file == 'footer.php') {
															$content = $headerFooterCode['footer'] . $content;
														}

														writeLog('Add header|footer code in ' . $filePath);

														if (file_put_contents($filePath, $content)) {
															writeLog('Save ' . $filePath);
														} else {
															$updateErrors[] = 'Failed to save ' . $filePath;
															writeLog('Failed to save ' . $filePath);
														}
													} else {
														$updateErrors[] = 'Failed to backup ' . $filePath;
														writeLog('Failed to backup ' . $filePath);
													}
												}
											} else {
												$updateErrors[] = 'Failed to read ' . $filePath;
												writeLog('Failed to read ' . $filePath);
											}
										} else {
											$updateErrors[] = $filePath . ' is not writable';
											writeLog($filePath . ' is not writable');
										}
									}
								}
							}
						} else {
							$updateErrors[] = $themePath . ' is not writable';
							writeLog($themePath . ' is not writable');
						}
					}
				}
			}
		}
	}
//changeChildTheme
	function changeChildTheme(&$updateErrors){
		$themesPath = get_theme_root();

		$skip = array('.', '..', 'CherryFramework', 'twentytwelve', 'twentyeleven', 'twentyten', 'index.php');
		$themes = array_diff(scandir($themesPath), $skip);

		if (!empty($themes)) {
			foreach ($themes as $theme) {
				$themePath = $themesPath . '/' . $theme;
				if (is_dir($themePath) && file_exists($themePath . '/style.css')) {

					$isCherryChildTheme = isCherryChildTheme($themePath, $theme, $updateErrors);

					if ($isCherryChildTheme) {
						if (is_writable($themePath)) {
							unset($slider_in_header, $slider_in_page_home, $pahe_home_content);
							writeLog(PHP_EOL . 'Child theme: ' . $themePath);
							$files = scandir($themePath);
							$header_php = array_intersect($files, array('header.php'));
							if (!empty($header_php)) {
								$templatePath = $themePath . '/header.php';
								$content = file_get_contents($templatePath);
								if (stripos($content, '"static/static-slider"')!==false) {
									$slider_in_header = true;
								}
							}
							if(!isset($slider_in_header)){
								if (is_dir($themePath."/wrapper")){
									$nestedFiles = scandir($themePath."/wrapper");
									$header_wrapper_php = array_intersect($nestedFiles, array('wrapper-header.php'));
									if (!empty($header_wrapper_php)) {
										$header_wrapper_php_path = $themePath . '/wrapper/wrapper-header.php';
										$content = file_get_contents($header_wrapper_php_path);
										if (stripos($content, '"static/static-slider"')!==false) {
											$slider_in_header = true;
										}
									}
								}
							}
							if(!isset($slider_in_header)){
								$page_home_php = array_intersect($files, array('page-home.php'));
								if (!empty($page_home_php)) {
									$templatePath = $themePath . '/page-home.php';
									$content = file($templatePath);
									for ($i=0, $arrayCount = count($content); $i < $arrayCount ; $i++) {
										if(stripos($content[$i], '"static/static-slider"')!==false){
											$slider_in_page_home = $i;
										}
										if(stripos($content[$i], '"page-home.php"')!==false){
											$pahe_home_content = $i;
										}
									}
									if(!isset($slider_in_page_home)){
										array_splice($content, $pahe_home_content+1, 0, "\t<div class=\"row\">\n\t\t<div class=\"span12\" data-motopress-type=\"static\" data-motopress-static-file=\"static/static-slider.php\">\n\t\t\t<?php get_template_part(\"static/static-slider\"); ?>\n\t\t</div>\n\t</div>\n");
										writeLog('Change page-home.php');
										if (file_put_contents($templatePath, $content)) {
											writeLog('Save ' . $templatePath);
										} else {
											$updateErrors[] = 'Failed to save ' . $templatePath;
											writeLog('Failed to save ' . $templatePath);
										}
									}
								}
							}
							$stylesheet = array_intersect($files, array('style.css'));
							$stylePath = $themePath . '/style.css';
							if (!empty($stylesheet)) {
								$styleContent = file($stylePath);
								if ($styleContent) {
									for ($i=0, $arrayCount = count($styleContent); $i < $arrayCount ; $i++) {
										if(stripos($styleContent[$i], "/*--")!==false){
											$styleContentChange = array();
										}
										if(isset($styleContentChange)){
											$styleContentChange[$i] = $styleContent[$i];
										}
										if(stripos($styleContent[$i], "--*/")!==false){
											break;
										}
									}
									array_push($styleContentChange, "\n/* ----------------------------------------\n\tPlease, You may put custom CSS here\n---------------------------------------- */\n");
									if (file_put_contents($stylePath, $styleContentChange)) {
										writeLog('Save ' . $stylePath);
									} else {
										$updateErrors[] = 'Failed to save ' . $stylePath;
										writeLog('Failed to save ' . $stylePath);
									}
								} else {
									$updateErrors[] = 'Failed to read ' . $stylesheet;
									writeLog('Failed to read ' . $stylesheet);
								}
							}
							if (is_dir($themePath."/static")){
								$nestedFiles = scandir($themePath."/static");
								$staticSlider = array_intersect($nestedFiles, array('static-slider.php'));
								$staticSliderPath = $themePath . '/static/static-slider.php';
								unset($sliderStatic, $new_function);
								if (!empty($staticSlider)) {
									$backupFileStatic = backupFile($staticSliderPath);
									if ($backupFileStatic) {
											$staticSliderContent = file($staticSliderPath);
											if ($staticSliderContent) {
												$static_slider_dom_1 = "<?php if(of_get_option('slider_type') != 'none_slider'){ ?>\n";
												$static_slider_dom_2 = "\t<?php get_slider_template_part(); ?>\n";
												$static_slider_dom_3 = "\n<?php }else{ ?>\n\t<div class=\"slider_off\"></div>\n<?php } ?>\n";
												for ($i=0, $arrayCount = count($staticSliderContent); $i < $arrayCount ; $i++) {
													if(stripos($staticSliderContent[$i], "get_template_part('slider')")!==false){
														$sliderStatic = $i;
													}
													if(stripos($staticSliderContent[$i], "get_slider_template_part")!==false){
														$new_function = $i;
													}
												}
												if(!isset($sliderStatic) && !isset($new_function)){
													$staticSliderContent = "<?php /* Static Name: Slider */ ?>\n<?php if(of_get_option('slider_type') != 'none_slider'){ ?>\n\t<div id=\"slider-wrapper\" class=\"slider\">\n\t\t<div class=\"container\">\n\t\t\t<?php get_slider_template_part(); ?>\n\t\t</div>\n\t</div><!-- .slider -->\n<?php }else{ ?>\n\t<div class=\"slider_off\"><!--slider off--></div>\n<?php } ?>";
												}else{
													if(!isset($new_function)){
														$staticSliderContent[$sliderStatic] = $static_slider_dom_2;
														array_splice($staticSliderContent, 1, 0, $static_slider_dom_1);
														array_push($staticSliderContent, $static_slider_dom_3);
													}
												}
												if (file_put_contents($staticSliderPath, $staticSliderContent)) {
													writeLog('Save ' . $staticSliderPath);
												} else {
													$updateErrors[] = 'Failed to save ' . $staticSliderPath;
													writeLog('Failed to save ' . $staticSliderPath);
												}
											} else {
												$updateErrors[] = 'Failed to read ' . $staticSlider;
												writeLog('Failed to read ' . $staticSlider);
											}

									}else {
										$updateErrors[] = 'Failed to backup ' . $staticSliderPath;
										writeLog('Failed to backup ' . $staticSliderPath);
									}
								}
							}
						} else {
							$updateErrors[] = $themePath . ' is not writable';
							writeLog($themePath . ' is not writable');
						}
					}
				}
			}
		}
	};
//end changeChildTheme
	if (has_action('after_switch_theme')) {
		add_action('after_switch_theme', 'activateCherryForceUpdate', 10, 0);
	} else {
		if (is_admin() && isset($_GET['activated']) && $pagenow == 'themes.php') {
			activateCherryForceUpdate();
		}
	}

	function activateCherryForceUpdate() {
		global $cherryTemplates;
		global $headerFooterPattern;

		$style = CHILD_DIR . '/style.css';
		$themeTemplate = false;

		if (function_exists('wp_get_theme')) {
			$theme = wp_get_theme();
			if (file_exists($style) && $theme->exists()) {
				if ($theme->Stylesheet != $theme->Template) {
					$themeTemplate = $theme->Template;
				}
			} else {
				writeLog($style . ' does not exist');
			}
		} elseif (function_exists('get_theme_data')) {
			if (file_exists($style)) {
				$theme = get_theme_data($style);
				$themeTemplate = $theme['Template'];
			} else {
				writeLog($style . ' does not exist');
			}
		} else {
			if (file_exists($style)) {
				$content = file_get_contents($style);
				if ($content) {
					$pattern = '/\s*template\s*:\s*([^\n]+)\s*/is';
					preg_match($pattern, $content, $matches);
					if (!empty($matches[1])) {
						$themeTemplate = trim($matches[1]);
					}
				} else {
					writeLog('Failed to read ' . $style);
				}
			} else {
				writeLog($style . ' does not exist');
			}
		}

		if ($themeTemplate == 'CherryFramework') {
			$themePath = get_stylesheet_directory();
			$files = scandir($themePath);
			$themeTemplates = array_intersect($files, $cherryTemplates);

			$cherryForceUpdate = false;

			if (!empty($themeTemplates)) {
				foreach ($themeTemplates as $template) {
					$templatePath = $themePath . '/' . $template;
					$content = file_get_contents($templatePath);
					if ($content) {
						$headerFooterMatchesCount = preg_match($headerFooterPattern, $content);
						if ($headerFooterMatchesCount == 1) {
							$cherryForceUpdate = true;
							break;
						}
					} else {
						writeLog('Failed to read ' . $templatePath);
					}
				}
			}

			if ($cherryForceUpdate) {
				update_option('cherry_force_update', 1);
			} else {
				update_option('cherry_force_update', 0);
			}
		}
	}
//------------------------------------------------------
//  slider function
//------------------------------------------------------
	if (!function_exists("my_post_type_slider")) {
		function my_post_type_slider() {
			register_post_type( 'slider',
				array(
					'label'               => theme_locals("slides"),
					'singular_label'      => theme_locals("slides"),
					'_builtin'            => false,
					'exclude_from_search' => true, // Exclude from Search Results
					'capability_type'     => 'page',
					'public'              => true,
					'show_ui'             => true,
					'show_in_nav_menus'   => false,
					'rewrite' => array(
								'slug'       => 'slide-view',
								'with_front' => FALSE,
					),
					'query_var' => "slide", // This goes to the WP_Query schema
					'menu_icon' => PARENT_URL . '/includes/images/icon_slides.png',
					'supports'  => array(
									'title',
									// 'custom-fields',
									'thumbnail'
					)
				)
			);
		}
		add_action('init', 'my_post_type_slider');
	}
	if (!function_exists("get_slider_template_part")) {
		function get_slider_template_part() {
			switch (of_get_option('slider_type')) {
				case "accordion_slider":
					$slider_type = "accordion";
				break;
				default:
					$slider_type = "slider";
			}
			return get_template_part($slider_type);
		}
	}
//------------------------------------------------------
//  Warning notice
//------------------------------------------------------
	add_action( 'admin_notices', 'warning_notice' );
	function warning_notice() {
		global $pagenow;
		$pageHidden = array('admin.php');
		if (!get_user_meta(get_current_user_id(), '_wp_hide_notice', true) && is_admin() && !FILE_WRITEABLE && !in_array($pagenow, $pageHidden)) {
			printf('<div class="updated"><strong><p>'.theme_locals('warning_notice_2').'</p><p>'.theme_locals('warning_notice_3').'</p><p><a href="'.esc_url(add_query_arg( 'wp_nag', wp_create_nonce( 'wp_nag' ))).'">'.theme_locals('dismiss_notice').'</a></p></strong></div>');
		}
	}
//------------------------------------------------------
//  Post Meta
//------------------------------------------------------
	$global_meta_elements = array();
	function get_post_metadata( $args = array() ) {
		global $global_meta_elements;
		if(array_key_exists('meta_elements', $args)){
			$global_meta_elements = array_unique(array_merge($global_meta_elements, $args['meta_elements']));
		}

		$meta_elements_empty  = isset($args['meta_elements']) ? false : true ;
		$defaults = array(
						'meta_elements' =>  array('start_unite', 'date', 'author', 'permalink', 'end_unite', 'start_unite', 'categories', 'tags', 'end_unite', 'start_unite', 'comment', 'views', 'like', 'dislike', 'end_unite'),
						'meta_class' => 'post_meta',
						'meta_before' => '',
						'meta_after'  => '',
						'display_meta_data' => true
					);
		$args = wp_parse_args( $args, $defaults );
		$post_meta_type = (of_get_option('post_meta') == 'true' || of_get_option('post_meta') == '') ? 'line' : of_get_option('post_meta');
		if($meta_elements_empty){
			foreach ($global_meta_elements as $key) {
				if($key != 'end_unite || start_unite'){
				unset($args['meta_elements'][array_search($key, $args['meta_elements'])]);
				}
			}
		}

		if($post_meta_type!='false' && $args['display_meta_data']){
			$post_ID = get_the_ID();
			$post_type = get_post_type($post_ID);
			$icon_tips_before = ($post_meta_type == 'icon') ? '<div class="tips">' : '';
			$icon_tips_after = ($post_meta_type == 'icon') ? '</div>' : '';

			$user_login = is_user_logged_in() ? true : false;
			$user_id = $user_login ? get_current_user_id() : "";
			$voting_class = $user_login ? 'ajax_voting ' : 'not_voting ';
			$voting_url = PARENT_URL.'/includes/voting.php?post_ID='.$post_ID.'&amp;get_user_ID='.$user_id;
			$get_voting_array = cherry_getPostVoting($post_ID, $user_id);
			$user_voting = $get_voting_array['user_voting'];

			echo $args['meta_before'].'<div class="'.$args['meta_class'].' meta_type_'.$post_meta_type.'">';
				foreach ($args['meta_elements'] as $value) {
					switch ($value) {
						case 'date':
							if(of_get_option('post_date') != 'no'){ ?>
								<div class="post_date">
									<i class="icon-calendar"></i>
									<?php echo $icon_tips_before . '<time datetime="' . get_the_time('Y-m-d\TH:i:s') . '">' . get_the_date() . '</time>' . $icon_tips_after; ?>
								</div>
								<?php
							}
							break;
						case 'author':
							if(of_get_option('post_author') != 'no'){ ?>
								<div class="post_author">
									<i class="icon-user"></i>
									<?php
									echo $icon_tips_before;
									the_author_posts_link();
									echo $icon_tips_after;
									?>
								</div>
								<?php
							}
							break;
						case 'permalink':
							if(of_get_option('post_permalink') != 'no' && !is_singular()){ ?>
								<div class="post_permalink">
									<i class="icon-link"></i>
									<?php echo $icon_tips_before.'<a href="'.get_permalink().'" title="'.get_the_title().'">'.theme_locals('permalink_to').'</a>'.$icon_tips_after; ?>
								</div>
								<?php
							}
							break;
						case 'categories':
							if(of_get_option('post_category') != 'no'){ ?>
								<div class="post_category">
									<i class="icon-bookmark"></i>
									<?php
										echo $icon_tips_before;
										if($post_type != 'post'){
											$custom_category = !is_wp_error(get_the_term_list($post_ID, $post_type.'_category','',', ')) ? get_the_term_list($post_ID, $post_type.'_category','',', ') : theme_locals('has_not_category');
											echo $custom_category;
										}else{
											the_category(', ');
										}
										echo $icon_tips_after;
									?>
								</div>
								<?php
							}
							break;
						case 'tags':
							if(of_get_option('post_tag') != 'no'){ ?>
								<div class="post_tag">
									<i class="icon-tag"></i>
									<?php
										echo $icon_tips_before;
										if(get_the_tags() || has_term('', $post_type.'_tag', $post_ID)){
											echo ($post_type != 'post') ? the_terms($post_ID, $post_type.'_tag','',', ') : the_tags('', ', ');
										} else {
											echo theme_locals('has_not_tags');
										}
										echo $icon_tips_after;
									 ?>
								</div>
								<?php
							}
							break;
						case 'comment':
							if(of_get_option('post_comment') != 'no'){ ?>
								<div class="post_comment">
									<i class="icon-comments"></i>
									<?php
										echo $icon_tips_before;
										comments_popup_link(theme_locals('no_comments'), theme_locals('comment'), '% '.theme_locals('comments'), theme_locals('comments_link'), theme_locals('comments_closed'));
										echo $icon_tips_after;
									 ?>
								</div>
								<?php
							}
							break;
						case 'views':
							if(of_get_option('post_views') != 'no'){ ?>
								<div class="post_views" title="<?php echo theme_locals('number_views'); ?>">
									<i class="icon-eye-open"></i>
									<?php echo $icon_tips_before.cherry_getPostViews($post_ID).$icon_tips_after; ?>
								</div>
								<?php
							}
							break;
						case 'dislike':
							if(of_get_option('post_dislike') != 'no'){
								$dislike_url = ($user_login && $user_voting=='none') ? 'href="'.$voting_url.'&amp;voting=dislike"' : '';
								$dislike_count = $get_voting_array['dislike_count'];
								$dislike_title = $user_login ? theme_locals('dislike') : theme_locals('not_voting');
								$dislike_class = ($user_voting == 'dislike') ? 'user_dislike ' : '';
								if($user_voting!='none'){
									$voting_class = "user_voting ";
								}
							?>
								<div class="post_dislike">
									<a <?php echo $dislike_url; ?> class="<?php echo $voting_class.$dislike_class; ?>" title="<?php echo $dislike_title; ?>" date-type="dislike" >
										<i class="icon-thumbs-down"></i>
										<?php echo $icon_tips_before.'<span class="voting_count">'.$dislike_count.'</span>'.$icon_tips_after; ?>
									</a>
								</div>
								<?php
							}
							break;
						case 'like':
							if(of_get_option('post_like') != 'no'){
								$like_url = ($user_login && $user_voting=='none') ? 'href="'.$voting_url.'&amp;voting=like"' : '';
								$like_count = $get_voting_array['like_count'];
								$like_title = $user_login ? theme_locals('like') : theme_locals('not_voting');
								$like_class = ($user_voting == 'like') ? 'user_like ' : '';
								if($user_voting!='none'){
									$voting_class = "user_voting ";
								}
							?>
								<div class="post_like">
									<a <?php echo $like_url; ?> class="<?php echo $voting_class.$like_class; ?>" title="<?php echo $like_title; ?>" date-type="like" >
										<i class="icon-thumbs-up"></i>
										<?php echo $icon_tips_before.'<span class="voting_count">'.$like_count.'</span>'.$icon_tips_after; ?>
									</a>
								</div>
								<?php
							}
						break;
						case 'start_unite':
							echo '<div class="post_meta_unite clearfix">';
						break;
						case 'end_unite':
							echo '</div>';
						break;
						case 'start_group':
							echo '<div class="meta_group clearfix">';
						break;
						case 'end_group':
							echo '</div>';
						break;
					}
				}
			echo '</div>'.$args['meta_after'];
		}
	}
//------------------------------------------------------
//  Post Views
//------------------------------------------------------
	function cherry_getPostViews($postID){
		return (get_post_meta($postID, 'post_views_count', true) == '') ? "0" : get_post_meta($postID, 'post_views_count', true);
	}
	function cherry_setPostViews($postID){
		$count_key = 'post_views_count';
		$count = get_post_meta($postID, $count_key, true);
		if($count==''){
			$count = 1;
		}else{
			$count++;
		}
		update_post_meta($postID, $count_key, $count);
	}
//------------------------------------------------------
//  Post voting
//------------------------------------------------------
	function cherry_getPostVoting($postID, $user_id){
		$like_count = (get_post_meta($postID, 'post_like', true) == false) ? "0" : get_post_meta($postID, 'post_like', true);
		$dislike_count = (get_post_meta($postID, 'post_dislike', true) == false) ? "0" : get_post_meta($postID, 'post_dislike', true);
		$user_like_array = get_post_meta($postID, 'user_like');
		$user_dislike_array = get_post_meta($postID, 'user_dislike');
		$user_voting = 'none';
		if(in_array($user_id, $user_like_array)){
			$user_voting = 'like';
		}else if(in_array($user_id, $user_dislike_array)){
			$user_voting = 'dislike';
		}
		return array('like_count' => $like_count, 'dislike_count' => $dislike_count, 'user_voting' => $user_voting);
	}
//------------------------------------------------------
//  Get team social networks
//------------------------------------------------------
function cherry_get_post_networks( $args = array() ) {
	global $post;
	extract( wp_parse_args( $args, apply_filters( 'cherry_get_post_networks_args', array(
				'post_id'       => get_the_ID(),
				'class'         => 'post_networks',
				'before_title'  => '<h4>',
				'after_title'   => '</h4>',
				'display_title' => true,
				'output_type'   => 'echo',
			) )
		)
	);

	$output         = '';
	$fields_id      = get_post_meta( $post_id, 'fields_id', true );
	$networks_title = get_post_meta( $post_id, 'networks_title', true );
	$network_icons  = get_post_meta( $post_id, 'network_icon', true );
	$network_titles = get_post_meta( $post_id, 'network_title', true );
	$network_urls   = get_post_meta( $post_id, 'network_url', true );

	if ( empty( $fields_id ) || !is_array( $fields_id ) ) {
		return $output;
	}

	$output .= '<div class="'.$class.'">';
	$output .= $networks_title && $display_title ? $before_title . $networks_title . $after_title : '';
	$output .= '<ul class="clearfix unstyled">';

	foreach ( $fields_id as $key => $value ) {

		$icon  = ( isset( $network_icons[ $value ] ) ) ? $network_icons[ $value ] : '';
		$title = ( isset( $network_titles[ $value ] ) ) ? $network_titles[ $value ] : '';
		$url   = ( isset( $network_urls[ $value ] ) ) ? $network_urls[ $value ] : '';

		$output .= '<li class="network_'.$key.'">';
			$output .= $url ? '<a href="'.esc_url( $url ).'" title="'.esc_attr( $title ).'">' : '' ;
			$output .= $icon ? '<span class="'.esc_attr( $icon ).'"></span>' :'';
			$output .= $title ? '<span class="network_title">'.$title.'</span>' : '' ;
			$output .= $url ? '</a>' : '' ;
		$output .= '</li>';
	}

	$output .= '</ul></div>';

	$output = apply_filters( 'cherry_get_post_networks_html', $output );

	if ( $output_type == 'echo' ) {
		echo $output;
	} else {
		return $output;
	}
}
//------------------------------------------------------
//  Related Posts
//------------------------------------------------------
	if(!function_exists('cherry_related_posts')){
		function cherry_related_posts($args = array()){
			global $post;
			$default = array(
				'post_type' => get_post_type($post),
				'class' => 'related-posts',
				'class_list' => 'related-posts_list',
				'class_list_item' => 'related-posts_item',
				'display_title' => true,
				'display_link' => true,
				'display_thumbnail' => true,
				'width_thumbnail' => 250,
				'height_thumbnail' => 150,
				'before_title' => '<h3 class="related-posts_h">',
				'after_title' => '</h3>',
				'posts_count' => 4
			);
			extract(array_merge($default, $args));

			$post_tags = wp_get_post_terms($post->ID, $post_type.'_tag', array("fields" => "slugs"));
			$tags_type = $post_type=='post' ? 'tag' : $post_type.'_tag' ;
			$suppress_filters = get_option('suppress_filters');// WPML filter
			$blog_related = apply_filters( 'cherry_text_translate', of_get_option('blog_related'), 'blog_related' );
			if ($post_tags && !is_wp_error($post_tags)) {
				$args = array(
					"$tags_type" => implode(',', $post_tags),
					'post_status' => 'publish',
					'posts_per_page' => $posts_count,
					'ignore_sticky_posts' => 1,
					'post__not_in' => array($post->ID),
					'post_type' => $post_type,
					'suppress_filters' => $suppress_filters
					);
				query_posts($args);
				if ( have_posts() ) {
					$output = '<div class="'.$class.'">';
					$output .= $display_title ? $before_title.$blog_related.$after_title : '' ;
					$output .= '<ul class="'.$class_list.' clearfix">';
					while( have_posts() ) {
						the_post();
						$thumb   = has_post_thumbnail() ? get_post_thumbnail_id() : PARENT_URL.'/images/empty_thumb.gif';
						$blank_img = stripos($thumb, 'empty_thumb.gif');
						$img_url = $blank_img ? $thumb : wp_get_attachment_url( $thumb,'full');
						$image   = $blank_img ? $thumb : aq_resize($img_url, $width_thumbnail, $height_thumbnail, true) or $img_url;

						$output .= '<li class="'.$class_list_item.'">';
						$output .= $display_thumbnail ? '<figure class="thumbnail featured-thumbnail"><a href="'.get_permalink().'" title="'.get_the_title().'"><img data-src="'.$image.'" alt="'.get_the_title().'" /></a></figure>': '' ;
						$output .= $display_link ? '<a href="'.get_permalink().'" >'.get_the_title().'</a>': '' ;
						$output .= '</li>';
					}
					$output .= '</ul></div>';
					echo $output;
				}
				wp_reset_query();
			}
		}
	}

//------------------------------------------------------
//  Main Layout option
//------------------------------------------------------
	if (of_get_option('main_layout') == 'fixed') {
		add_filter('body_class','cherry_layout_class');
		function cherry_layout_class($classes) {
			$classes[] = 'cherry-fixed-layout';

			return $classes;
		}
	}

//------------------------------------------------------
//  General option
//------------------------------------------------------
	if ( (of_get_option('header_background') != '')
		|| (of_get_option('header_color') !='')
		|| (of_get_option('body_background') !='')
		|| (of_get_option('custom_css') !='') ) {

		add_action('wp_head', 'cherry_general_opt');
		function cherry_general_opt(){
			$output = "\n<style type='text/css'>";

			// body bg option
			if (of_get_option('body_background') !='') {
				$background = of_get_option('body_background');
				if ($background != '') {
					if ($background['image'] != '') {
						$output .= "\nbody { background-image:url(".$background['image']. "); background-repeat:".$background['repeat']."; background-position:".$background['position']."; background-attachment:".$background['attachment']."; }";
					}
					if($background['color'] != '') {
						$output .= "\nbody { background-color:".$background['color']." }";
					}
				}
			}

			// header bg option
			if (of_get_option('header_color') !='') {
				$header_styling = of_get_option('header_color');
				update_option('child_header_color', $header_styling);
				$output .= "\n.header { background-color:".$header_styling." }";
			} else {
				$header_styling = of_get_option('header_background');

				if ($header_styling['image'] != '') {
					$output .= "\n.header { background-image:url(".$header_styling['image']. "); background-repeat:".$header_styling['repeat']."; background-position:".$header_styling['position']."; background-attachment:".$header_styling['attachment']."; }";
				}
				if ($header_styling['color'] != '') {
					$output .= "\n.header { background-color:".$header_styling['color']." }";
				} else {
					if (get_option('child_header_color') && !is_array(get_option('child_header_color')) ) {
						$output .= "\n.header { background-color:".get_option('child_header_color')." }";
					}
				}
			}

			// custom CSS
			$output .= "\n".htmlspecialchars_decode(of_get_option('custom_css'));

			$output .= "\n</style>";
			echo $output;
		}
	}

	/**
	*
	* Register hook in update.php page
	*
	**/
	add_action('load-update.php', 'cherry_register_update_page_hook');
	function cherry_register_update_page_hook(){
		if ( isset($_GET['action']) ) {
			$plugin = isset($_REQUEST['plugin']) ? trim($_REQUEST['plugin']) : '';
			$theme  = isset($_REQUEST['theme']) ? urldecode($_REQUEST['theme']) : '';
			$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

			if ( 'CherryFramework' == $theme ) {

				if ( 'upgrade-theme' == $action ) {
					add_action('upgrader_process_complete', 'after_cherry_theme_upgrade_call');
				} elseif ( 'upload-theme' == $action ) {
					add_action('upgrader_process_complete', 'after_cherry_theme_upgrade_call');
				}
			}

			if ( 'cherry-plugin' == $plugin ) {

				if ( 'upgrade-plugin' == $action ) {
					add_action('upgrader_process_complete', 'after_cherry_plugin_upgrade_call');
				}
			}
		}
	}

	/**
	*
	* Register hook in update-core.php page
	*
	**/
	add_action('load-update-core.php', 'cherry_register_update_core_page_hook');
	function cherry_register_update_core_page_hook(){
		if ( isset( $_GET['themes'] ) )
			$themes = explode( ',', stripslashes($_GET['themes']) );
		elseif ( isset( $_POST['checked'] ) )
			$themes = (array) $_POST['checked'];

		if ( !isset($themes) )
			return;

		if ( array_search('CherryFramework', $themes) === FALSE )
			return;

		if ( isset($_GET['action']) ) {
			$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

			if ( 'do-theme-upgrade' == $action ) {
				add_action('upgrader_process_complete', 'after_cherry_theme_upgrade_call');
			}
		}
	}

	/**
	*
	* Register hook after CherryFramework upgrade
	*
	**/
	function after_cherry_theme_upgrade_call(){
		do_action('after_cherry_theme_upgrade');
	}

	/**
	*
	* Register hook after Cherry Plugin upgrade
	*
	**/
	function after_cherry_plugin_upgrade_call(){
		do_action('after_cherry_plugin_upgrade');
	}

	/**
	*
	* Unpack Cherry Plugin package
	*
	**/
	add_action('after_cherry_theme_upgrade', 'cherry_plugin_unpack_package');
	function cherry_plugin_unpack_package(){
		$plugin = 'cherry-plugin/cherry-plugin.php';

		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
			return;
		}

		if ( !function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active( $plugin ) )
			return;

		$file   = PARENT_DIR . '/includes/plugins/cherry-plugin.zip';
		$to     = WP_PLUGIN_DIR . '/cherry-plugin/';
		$result = false;

		if ( !file_exists($file) )
			return $result;

		if ( !function_exists('WP_Filesystem') ) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		}
		WP_Filesystem();
		global $wp_filesystem;

		// Clean up plugin directory
		if ( $wp_filesystem->is_dir($to) )
			$wp_filesystem->delete($to, true);

		$result = unzip_file( $file, $to );
		if ( is_wp_error($result) ) {
			if ( 'incompatible_archive' == $result->get_error_code() ) {
				return new WP_Error( 'incompatible_archive', __('The package could not be installed.', CURRENT_THEME), $result->get_error_data() );
			}
		}
		return $result;
	}

	/**
	*
	* Set Up Cherry Plugin
	*
	**/
	add_action('after_setup_theme', 'cherry_plugin_setup');
	function cherry_plugin_setup(){
		$plugin = 'cherry-plugin/cherry-plugin.php';

		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
			return;
		}

		if ( !function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if ( is_plugin_active( $plugin ) )
			return;

		$file   = PARENT_DIR . '/includes/plugins/cherry-plugin.zip';
		$to     = WP_PLUGIN_DIR . '/cherry-plugin/';
		$result = false;

		if ( !file_exists($file) )
			return $result;

		if ( !function_exists('WP_Filesystem') ) {
			require_once(ABSPATH . 'wp-admin/includes/file.php');
		}
		WP_Filesystem();
		global $wp_filesystem;

		$result = unzip_file( $file, $to );
		if ( is_wp_error($result) ) {
			if ( 'incompatible_archive' == $result->get_error_code() ) {
				return new WP_Error( 'incompatible_archive', __('The package could not be installed.', CURRENT_THEME), $result->get_error_data() );
			}
		}
		return $result;
	}

	/**
	*
	* Layout class
	*
	**/
	if ( !function_exists('cherry_get_layout_class') ) {
		function cherry_get_layout_class($layout) {
			switch ($layout) {

				case 'full_width_content':
					$layout_class = apply_filters( "cherry_layout_wrapper", "span12" );
					break;

				case 'content':
					$layout_class = apply_filters( "cherry_layout_content_column", "span8" );
					$layout_class .= ' '.of_get_option('blog_sidebar_pos');
					break;

				case 'sidebar':
					$layout_class = apply_filters( "cherry_layout_sidebar_column", "span4" );
					break;

				case 'left_block':
					$layout_class = apply_filters( "cherry_layout_left_block_column", "span7" );
					break;

				case 'right_block':
					$layout_class = apply_filters( "cherry_layout_right_block_column", "span5" );
					break;
			}

			return $layout_class;
		}
	}

	/**
	 * Cookie Banner option.
	 */
	add_action( 'wp_footer', 'cherry_cookie_banner', 999 );
	function cherry_cookie_banner() {
		$is_banner_visibility = of_get_option( 'cookie_banner', false );
		$banner_text          = trim( of_get_option( 'cookie_banner_text', '' ) );
		$banner_dismiss       = false;

		if ( 'yes' != $is_banner_visibility ) { ?>
			<script type="text/javascript">
				deleteCookie('cf-cookie-banner');
			</script>
			<?php return;
		}

		if ( empty( $banner_text ) ) {
			return;
		}

		if ( isset( $_COOKIE['cf-cookie-banner'] ) && '1' == $_COOKIE['cf-cookie-banner'] ) {
			return;
		}

		ob_start(); ?>

		<div id="cf-cookie-banner" class="cf-cookie-banner-wrap alert fade in">
			<div class="container">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<?php echo htmlspecialchars_decode( $banner_text ); ?>
			</div>
		</div>

		<?php $output = ob_get_contents();
		ob_end_clean();

		$output = apply_filters( 'cherry_cookie_banner', $output );

		printf( '%s', $output );
	}
?>