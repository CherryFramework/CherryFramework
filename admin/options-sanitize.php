<?php
	$font_array = array();
	$char_array = array();

/* Text */

add_filter( 'of_sanitize_text', 'sanitize_text_field' );

/* Textarea */

function of_sanitize_textarea($input) {
	global $allowedposttags;
	$output = wp_kses( $input, $allowedposttags);
	return $output;
}

add_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );

/* Select */

add_filter( 'of_sanitize_select', 'of_sanitize_enum', 10, 2);

/* Radio */

add_filter( 'of_sanitize_radio', 'of_sanitize_enum', 10, 2);

/* Images */

add_filter( 'of_sanitize_images', 'of_sanitize_enum', 10, 2);

/* Checkbox */

function of_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = '1';
	} else {
		$output = false;
	}
	return $output;
}
add_filter( 'of_sanitize_checkbox', 'of_sanitize_checkbox' );

/* Multicheck */

function of_sanitize_multicheck( $input, $option ) {
	$output = '';
	if ( is_array( $input ) ) {
		foreach( $option['options'] as $key => $value ) {
			$output[$key] = "0";
		}
		foreach( $input as $key => $value ) {
			if ( array_key_exists( $key, $option['options'] ) && $value ) {
				$output[$key] = "1";
			}
		}
	}
	return $output;
}
add_filter( 'of_sanitize_multicheck', 'of_sanitize_multicheck', 10, 2 );

/* Color Picker */

add_filter( 'of_sanitize_color', 'of_sanitize_hex' );

/* Uploader */

function of_sanitize_upload( $input ) {
	$output = '';
	$filetype = wp_check_filetype($input);
	if ( $filetype["ext"] ) {
		$output = $input;
	}
	return $output;
}
add_filter( 'of_sanitize_upload', 'of_sanitize_upload' );

/* Editor */

function of_sanitize_editor($input) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		$output = $input;
	}
	else {
		global $allowedtags;
		$output = wpautop(wp_kses( $input, $allowedtags));
	}
	return $output;
}
add_filter( 'of_sanitize_editor', 'of_sanitize_editor' );

/* Allowed Tags */

function of_sanitize_allowedtags($input) {
	global $allowedtags;
	$output = wpautop(wp_kses( $input, $allowedtags));
	return $output;
}

/* Allowed Post Tags */

function of_sanitize_allowedposttags($input) {
	global $allowedposttags;
	$output = wpautop(wp_kses( $input, $allowedposttags));
	return $output;
}

add_filter( 'of_sanitize_info', 'of_sanitize_allowedposttags' );


/* Check that the key value sent is valid */

function of_sanitize_enum( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['options'] ) ) {
		$output = $input;
	}
	return $output;
}

/* Background */

function of_sanitize_background( $input ) {
	$output = wp_parse_args( $input, array(
		'color' => '',
		'image'  => '',
		'repeat'  => 'repeat',
		'position' => 'top center',
		'attachment' => 'scroll'
	) );

	$output['color'] = apply_filters( 'of_sanitize_hex', $input['color'] );
	$output['image'] = apply_filters( 'of_sanitize_upload', $input['image'] );
	$output['repeat'] = apply_filters( 'of_background_repeat', $input['repeat'] );
	$output['position'] = apply_filters( 'of_background_position', $input['position'] );
	$output['attachment'] = apply_filters( 'of_background_attachment', $input['attachment'] );

	return $output;
}
add_filter( 'of_sanitize_background', 'of_sanitize_background' );

function of_sanitize_background_repeat( $value ) {
	$recognized = of_recognized_background_repeat();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_background_repeat', current( $recognized ) );
}
add_filter( 'of_background_repeat', 'of_sanitize_background_repeat' );

function of_sanitize_background_position( $value ) {
	$recognized = of_recognized_background_position();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_background_position', current( $recognized ) );
}
add_filter( 'of_background_position', 'of_sanitize_background_position' );

function of_sanitize_background_attachment( $value ) {
	$recognized = of_recognized_background_attachment();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_background_attachment', current( $recognized ) );
}
add_filter( 'of_background_attachment', 'of_sanitize_background_attachment' );


/* Typography */

function of_sanitize_typography( $input, $option ) {

	$output = wp_parse_args( $input, array(
		'size'  => '',
		'face'  => '',
		'character'  => '',
		'style' => '',
		'lineheight' => '',
		'color' => ''
	) );

	if ( isset( $option['options']['faces'] ) && isset( $input['face'] ) ) {
		if ( !( array_key_exists( $input['face'], $option['options']['faces'] ) ) ) {
			$output['face'] = '';
		}
	}
	else {
		$output['face']  = apply_filters( 'of_font_face', $output['face'] );
	}

	$output['size']  = apply_filters( 'of_font_size', $output['size'] );
	$output['style'] = apply_filters( 'of_font_style', $output['style'] );
	$output['character'] = apply_filters( 'of_font_character', $output['character'] );
	$output['color'] = apply_filters( 'of_sanitize_color', $output['color'] );
	$output['lineheight'] = apply_filters( 'of_sanitize_lineheight', $output['lineheight'] );
	return $output;
}
add_filter( 'of_sanitize_typography', 'of_sanitize_typography', 10, 2 );

function of_sanitize_font_size( $value ) {
	$recognized = of_recognized_font_sizes();
	$value_check = preg_replace('/px/','', $value);
	if ( in_array( (int) $value_check, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_font_size', $recognized );
}
add_filter( 'of_font_size', 'of_sanitize_font_size' );


function of_sanitize_font_style( $value ) {
	$recognized = of_recognized_font_styles();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_font_style', current( $recognized ) );
}
add_filter( 'of_font_style', 'of_sanitize_font_style' );


function of_sanitize_font_lineheight( $value ) {
	$recognized = of_recognized_font_lineheight();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_font_lineheight', current( $recognized ) );
}
add_filter( 'of_font_lineheight', 'of_sanitize_font_lineheight' );


function of_sanitize_font_face( $value ) {
	$recognized = of_recognized_font_faces();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_font_face', current( $recognized ) );
}
add_filter( 'of_font_face', 'of_sanitize_font_face' );

function of_sanitize_font_character( $value ) {
	$recognized = of_recognized_font_characters();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_font_character', current( $recognized ) );
}
add_filter( 'of_font_character', 'of_sanitize_font_character' );

/**
 * Get recognized background repeat settings
 *
 * @return   array
 *
 */
function of_recognized_background_repeat() {
	$default = array(
		'no-repeat' => __('No Repeat', 'options_framework_theme'),
		'repeat-x'  => __('Repeat Horizontally', 'options_framework_theme'),
		'repeat-y'  => __('Repeat Vertically', 'options_framework_theme'),
		'repeat'    => __('Repeat All', 'options_framework_theme'),
		);
	return apply_filters( 'of_recognized_background_repeat', $default );
}

/**
 * Get recognized background positions
 *
 * @return   array
 *
 */
function of_recognized_background_position() {
	$default = array(
		'top left'      => __('Top Left', 'options_framework_theme'),
		'top center'    => __('Top Center', 'options_framework_theme'),
		'top right'     => __('Top Right', 'options_framework_theme'),
		'center left'   => __('Middle Left', 'options_framework_theme'),
		'center center' => __('Middle Center', 'options_framework_theme'),
		'center right'  => __('Middle Right', 'options_framework_theme'),
		'bottom left'   => __('Bottom Left', 'options_framework_theme'),
		'bottom center' => __('Bottom Center', 'options_framework_theme'),
		'bottom right'  => __('Bottom Right', 'options_framework_theme')
		);
	return apply_filters( 'of_recognized_background_position', $default );
}

/**
 * Get recognized background attachment
 *
 * @return   array
 *
 */
function of_recognized_background_attachment() {
	$default = array(
		'scroll' => __('Scroll Normally', 'options_framework_theme'),
		'fixed'  => __('Fixed in Place', 'options_framework_theme')
		);
	return apply_filters( 'of_recognized_background_attachment', $default );
}

/**
 * Sanitize a color represented in hexidecimal notation.
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @param    string    The value that this function should return if it cannot be recognized as a color.
 * @return   string
 *
 */

function of_sanitize_hex( $hex, $default = '' ) {
	if ( of_validate_hex( $hex ) ) {
		return $hex;
	}
	return $default;
}

/**
 * Get recognized font sizes.
 *
 * Returns an indexed array of all recognized font sizes.
 * Values are integers and represent a range of sizes from
 * smallest to largest.
 *
 * @return   array
 */

function of_recognized_font_sizes() {
	$sizes = range( 9, 100 );
	$sizes = apply_filters( 'of_recognized_font_sizes', $sizes );
	$sizes = array_map( 'absint', $sizes );
	return $sizes;
}


/**
 * Get lineheights.
 *
 * Returns an array of all recognized font styles.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function of_recognized_font_lineheight() {
	$default = array(
		'1'        => __('1', 'options_framework_theme'),
		'1.2'        => __('1.2', 'options_framework_theme'),
		'1.5'        => __('1.5', 'options_framework_theme'),
		'2' => __('2', 'options_framework_theme')
		);
	return apply_filters( 'of_recognized_font_lineheight', $default );
}


/**
 * Get recognized font faces.
 *
 * Returns an array of all recognized font faces.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function of_recognized_font_faces() {
	$default = array(
		'arial'     => 'Arial',
		'verdana'   => 'Verdana, Geneva',
		'trebuchet' => 'Trebuchet',
		'georgia'   => 'Georgia',
		'times'     => 'Times New Roman',
		'tahoma'    => 'Tahoma, Geneva',
		'palatino'  => 'Palatino',
		'helvetica' => 'Helvetica'
		);
	return apply_filters( 'of_recognized_font_faces', $default );
}

/**
 * Get recognized font character sets.
 *
 * Returns an array of all recognized font character sets.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function of_recognized_font_characters() {
	$default = array(
		'latin'			=> __('Latin', 'options_framework_theme'),
		'latin-ext'		=> __('Latin Extended', 'options_framework_theme'),
		'greek'			=> __('Greek', 'options_framework_theme'),
		'greek-ext'		=> __('Greek Extended', 'options_framework_theme'),
		'cyrillic'		=> __('Cyrillic', 'options_framework_theme'),
		'cyrillic-ext'	=> __('Cyrillic Extended', 'options_framework_theme'),
		'vietnamese'	=> __('Vietnamese', 'options_framework_theme')
		);
	return apply_filters( 'of_recognized_font_characters', $default );
}

/**
 * Get recognized font styles.
 *
 * Returns an array of all recognized font styles.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function of_recognized_font_styles() {
	$default = array(
		'normal'      => __('Normal', 'options_framework_theme'),
		'italic'      => __('Italic', 'options_framework_theme'),
		'bold'        => __('Bold', 'options_framework_theme'),
		'bold italic' => __('Bold Italic', 'options_framework_theme')
		);
	return apply_filters( 'of_recognized_font_styles', $default );
}



/**
 * Returns an array of system fonts
 * Feel free to edit this, update the font fallbacks, etc.
 */
function options_typography_get_os_fonts() {
	// OS Font Defaults
	$os_faces = array(
		'Arial, Helvetica, sans-serif'                         => 'Arial',
		'Verdana, Geneva, sans-serif'                          => 'Verdana',
		'"Trebuchet MS", Arial, Helvetica, sans-serif'         => 'Trebuchet MS',
		'Georgia, "Times New Roman", Times, serif'             => 'Georgia',
		'"Times New Roman", Times, serif'                      => 'Times New Roman',
		'Tahoma, Geneva, sans-serif'                           => 'Tahoma',
		'"Palatino Linotype", "Book Antiqua", Palatino, serif' => 'Palatino',
		'Helvetica'                         => 'Helvetica'
	);
	return $os_faces;
}


/**
 * Returns a select list of Google fonts
 * Feel free to edit this, update the fallbacks, etc.
 */
function options_typography_get_google_fonts() {
	// Google Font Defaults
	$google_faces = array(
			"Abel, sans-serif" => "Abel *",
			"Abril Fatface" => "Abril Fatface *",
			"Aclonica" => "Aclonica *",
			"Acme" => "Acme *",
			"Actor" => "Actor *",
			"Adamina" => "Adamina *",
			"Advent Pro" => "Advent Pro *",
			"Aguafina Script" => "Aguafina Script *",
			"Aladin" => "Aladin *",
			"Aldrich" => "Aldrich *",
			"Alegreya" => "Alegreya *",
			"Alegreya SC" => "Alegreya SC *",
			"Alex Brush" => "Alex Brush *",
			"Alfa Slab One" => "Alfa Slab One *",
			"Alice" => "Alice *",
			"Alike" => "Alike *",
			"Alike Angular" => "Alike Angular *",
			"Allan" => "Allan *",
			"Allerta" => "Allerta *",
			"Allerta Stencil" => "Allerta Stencil *",
			"Allura" => "Allura *",
			"Almendra" => "Almendra *",
			"Almendra SC" => "Almendra SC *",
			"Amaranth" => "Amaranth *",
			"Amatic SC" => "Amatic SC *",
			"Amethysta" => "Amethysta *",
			"Andada" => "Andada *",
			"Andika" => "Andika *",
			"Angkor" => "Angkor *",
			"Annie Use Your Telescope" => "Annie Use Your Telescope *",
			"Anonymous Pro" => "Anonymous Pro *",
			"Antic" => "Antic *",
			"Antic Didone" => "Antic Didone *",
			"Antic Slab" => "Antic Slab *",
			"Anton" => "Anton *",
			"Arapey" => "Arapey *",
			"Arbutus" => "Arbutus *",
			"Architects Daughter" => "Architects Daughter *",
			"Archivo Narrow" => "Archivo Narrow *",
			"Arimo" => "Arimo *",
			"Arizonia" => "Arizonia *",
			"Armata" => "Armata *",
			"Artifika" => "Artifika *",
			"Arvo" => "Arvo *",
			"Asap" => "Asap *",
			"Asset" => "Asset *",
			"Astloch" => "Astloch *",
			"Asul" => "Asul *",
			"Atomic Age" => "Atomic Age *",
			"Aubrey" => "Aubrey *",
			"Audiowide" => "Audiowide *",
			"Average" => "Average *",
			"Averia Gruesa Libre" => "Averia Gruesa Libre *",
			"Averia Libre" => "Averia Libre *",
			"Averia Sans Libre" => "Averia Sans Libre *",
			"Averia Serif Libre" => "Averia Serif Libre *",
			"Bad Script" => "Bad Script *",
			"Balthazar" => "Balthazar *",
			"Bangers" => "Bangers *",
			"Basic" => "Basic *",
			"Battambang" => "Battambang *",
			"Baumans" => "Baumans *",
			"Bayon" => "Bayon *",
			"Belgrano" => "Belgrano *",
			"Belleza" => "Belleza *",
			"BenchNine" => "BenchNine *",
			"Bentham" => "Bentham *",
			"Berkshire Swash" => "Berkshire Swash *",
			"Bevan" => "Bevan *",
			"Bigshot One" => "Bigshot One *",
			"Bilbo" => "Bilbo *",
			"Bilbo Swash Caps" => "Bilbo Swash Caps *",
			"Bitter" => "Bitter *",
			"Black Ops One" => "Black Ops One *",
			"Bokor" => "Bokor *",
			"Bonbon" => "Bonbon *",
			"Boogaloo" => "Boogaloo *",
			"Bowlby One" => "Bowlby One *",
			"Bowlby One SC" => "Bowlby One SC *",
			"Brawler" => "Brawler *",
			"Bree Serif" => "Bree Serif *",
			"Bubblegum Sans" => "Bubblegum Sans *",
			"Buda" => "Buda *",
			"Buenard" => "Buenard *",
			"Butcherman" => "Butcherman *",
			"Butterfly Kids" => "Butterfly Kids *",
			"Cabin" => "Cabin *",
			"Cabin Condensed" => "Cabin Condensed *",
			"Cabin Sketch" => "Cabin Sketch *",
			"Caesar Dressing" => "Caesar Dressing *",
			"Cagliostro" => "Cagliostro *",
			"Calligraffitti" => "Calligraffitti *",
			"Cambo" => "Cambo *",
			"Candal" => "Candal *",
			"Cantarell" => "Cantarell *",
			"Cantata One" => "Cantata One *",
			"Cardo" => "Cardo *",
			"Carme" => "Carme *",
			"Carter One" => "Carter One *",
			"Caudex" => "Caudex *",
			"Cedarville Cursive" => "Cedarville Cursive *",
			"Ceviche One" => "Ceviche One *",
			"Changa One" => "Changa One *",
			"Chango" => "Chango *",
			"Chau Philomene One" => "Chau Philomene One *",
			"Chelsea Market" => "Chelsea Market *",
			"Chenla" => "Chenla *",
			"Cherry Cream Soda" => "Cherry Cream Soda *",
			"Chewy" => "Chewy *",
			"Chicle" => "Chicle *",
			"Chivo" => "Chivo *",
			"Cinzel" => "Cinzel *",
			"Coda" => "Coda *",
			"Coda Caption" => "Coda Caption *",
			"Codystar" => "Codystar *",
			"Comfortaa" => "Comfortaa *",
			"Coming Soon" => "Coming Soon *",
			"Concert One" => "Concert One *",
			"Condiment" => "Condiment *",
			"Content" => "Content *",
			"Contrail One" => "Contrail One *",
			"Convergence" => "Convergence *",
			"Cookie" => "Cookie *",
			"Copse" => "Copse *",
			"Corben" => "Corben *",
			"Cousine" => "Cousine *",
			"Coustard" => "Coustard *",
			"Courgette" => "Courgette *",
			"Covered By Your Grace" => "Covered By Your Grace *",
			"Crafty Girls" => "Crafty Girls *",
			"Creepster" => "Creepster *",
			"Crete Round" => "Crete Round *",
			"Crimson Text" => "Crimson Text *",
			"Crushed" => "Crushed *",
			"Cuprum" => "Cuprum *",
			"Cutive" => "Cutive *",
			"Damion" => "Damion *",
			"Dancing Script" => "Dancing Script *",
			"Dangrek" => "Dangrek *",
			"Dawning of a New Day" => "Dawning of a New Day *",
			"Days One" => "Days One *",
			"Delius" => "Delius *",
			"Delius Swash Caps" => "Delius Swash Caps *",
			"Delius Unicase" => "Delius Unicase *",
			"Della Respira" => "Della Respira *",
			"Devonshire" => "Devonshire *",
			"Didact Gothic" => "Didact Gothic *",
			"Diplomata" => "Diplomata *",
			"Diplomata SC" => "Diplomata SC *",
			"Doppio One" => "Doppio One *",
			"Dorsa" => "Dorsa *",
			"Dosis" => "Dosis *",
			"Dr Sugiyama" => "Dr Sugiyama *",
			"Droid Sans" => "Droid Sans *",
			"Droid Sans Mono" => "Droid Sans Mono *",
			"Droid Serif" => "Droid Serif *",
			"Duru Sans" => "Duru Sans *",
			"Dynalight" => "Dynalight *",
			"EB Garamond" => "EB Garamond *",
			"Eater" => "Eater *",
			"Economica" => "Economica *",
			"Electrolize" => "Electrolize *",
			"Emblema One" => "Emblema One *",
			"Emilys Candy" => "Emilys Candy *",
			"Engagement" => "Engagement *",
			"Enriqueta" => "Enriqueta *",
			"Erica One" => "Erica One *",
			"Esteban" => "Esteban *",
			"Euphoria Script" => "Euphoria Script *",
			"Ewert" => "Ewert *",
			"Exo, sans-serif" => "Exo *",
			"Expletus Sans" => "Expletus Sans *",
			"Fanwood Text" => "Fanwood Text *",
			"Fascinate" => "Fascinate *",
			"Fascinate Inline" => "Fascinate Inline *",
			"Federant" => "Federant *",
			"Federo" => "Federo *",
			"Felipa" => "Felipa *",
			"Fjord One" => "Fjord One *",
			"Fjalla One" => "Fjalla One *",
			"Flamenco" => "Flamenco *",
			"Flavors" => "Flavors *",
			"Fondamento" => "Fondamento *",
			"Fontdiner Swanky" => "Fontdiner Swanky *",
			"Forum" => "Forum *",
			"Francois One" => "Francois One *",
			"Fredericka the Great" => "Fredericka the Great *",
			"Fredoka One" => "Fredoka One *",
			"Freehand" => "Freehand *",
			"Fresca" => "Fresca *",
			"Frijole" => "Frijole *",
			"Fugaz One" => "Fugaz One *",
			"GFS Didot" => "GFS Didot *",
			"GFS Neohellenic" => "GFS Neohellenic *",
			"Galdeano" => "Galdeano *",
			"Gentium Basic" => "Gentium Basic *",
			"Gentium Book Basic" => "Gentium Book Basic *",
			"Geo" => "Geo *",
			"Geostar" => "Geostar *",
			"Geostar Fill" => "Geostar Fill *",
			"Germania One" => "Germania One *",
			"Give You Glory" => "Give You Glory *",
			"Glass Antiqua" => "Glass Antiqua *",
			"Glegoo" => "Glegoo *",
			"Gloria Hallelujah" => "Gloria Hallelujah *",
			"Goblin One" => "Goblin One *",
			"Gochi Hand" => "Gochi Hand *",
			"Gorditas" => "Gorditas *",
			"Goudy Bookletter 1911" => "Goudy Bookletter 1911 *",
			"Graduate" => "Graduate *",
			"Gravitas One" => "Gravitas One *",
			"Great Vibes" => "Great Vibes *",
			"Gruppo" => "Gruppo *",
			"Gudea" => "Gudea *",
			"Habibi" => "Habibi *",
			"Hammersmith One" => "Hammersmith One *",
			"Handlee" => "Handlee *",
			"Hanuman" => "Hanuman *",
			"Happy Monkey" => "Happy Monkey *",
			"Henny Penny" => "Henny Penny *",
			"Herr Von Muellerhoff" => "Herr Von Muellerhoff *",
			"Holtwood One SC" => "Holtwood One SC *",
			"Homemade Apple" => "Homemade Apple *",
			"Homenaje" => "Homenaje *",
			"IM Fell DW Pica" => "IM Fell DW Pica *",
			"IM Fell DW Pica SC" => "IM Fell DW Pica SC *",
			"IM Fell Double Pica" => "IM Fell Double Pica *",
			"IM Fell Double Pica SC" => "IM Fell Double Pica SC *",
			"IM Fell English" => "IM Fell English *",
			"IM Fell English SC" => "IM Fell English SC *",
			"IM Fell French Canon" => "IM Fell French Canon *",
			"IM Fell French Canon SC" => "IM Fell French Canon SC *",
			"IM Fell Great Primer" => "IM Fell Great Primer *",
			"IM Fell Great Primer SC" => "IM Fell Great Primer SC *",
			"Iceberg" => "Iceberg *",
			"Iceland" => "Iceland *",
			"Imprima" => "Imprima *",
			"Inconsolata" => "Inconsolata *",
			"Inder" => "Inder *",
			"Indie Flower" => "Indie Flower *",
			"Inika" => "Inika *",
			"Irish Grover" => "Irish Grover *",
			"Istok Web" => "Istok Web *",
			"Italiana" => "Italiana *",
			"Italianno" => "Italianno *",
			"Jim Nightshade" => "Jim Nightshade *",
			"Jockey One" => "Jockey One *",
			"Jolly Lodger" => "Jolly Lodger *",
			"Josefin Sans" => "Josefin Sans *",
			"Josefin Slab" => "Josefin Slab *",
			"Judson" => "Judson *",
			"Julee" => "Julee *",
			"Julius Sans One" => "Julius Sans One *",
			"Junge" => "Junge *",
			"Jura" => "Jura *",
			"Just Another Hand" => "Just Another Hand *",
			"Just Me Again Down Here" => "Just Me Again Down Here *",
			"Kameron" => "Kameron *",
			"Karla" => "Karla *",
			"Kaushan Script" => "Kaushan Script *",
			"Kelly Slab" => "Kelly Slab *",
			"Kenia" => "Kenia *",
			"Khmer" => "Khmer *",
			"Knewave" => "Knewave *",
			"Kotta One" => "Kotta One *",
			"Koulen" => "Koulen *",
			"Kranky" => "Kranky *",
			"Kreon" => "Kreon *",
			"Kristi" => "Kristi *",
			"Krona One" => "Krona One *",
			"La Belle Aurore" => "La Belle Aurore *",
			"Lancelot" => "Lancelot *",
			"Lato" => "Lato *",
			"League Script" => "League Script *",
			"Leckerli One" => "Leckerli One *",
			"Ledger" => "Ledger *",
			"Lekton" => "Lekton *",
			"Lemon" => "Lemon *",
			"Lilita One" => "Lilita One *",
			"Limelight" => "Limelight *",
			"Linden Hill" => "Linden Hill *",
			"Lobster" => "Lobster *",
			"Lobster Two" => "Lobster Two *",
			"Londrina Outline" => "Londrina Outline *",
			"Londrina Shadow" => "Londrina Shadow *",
			"Londrina Sketch" => "Londrina Sketch *",
			"Londrina Solid" => "Londrina Solid *",
			"Lora" => "Lora *",
			"Love Ya Like A Sister" => "Love Ya Like A Sister *",
			"Loved by the King" => "Loved by the King *",
			"Lovers Quarrel" => "Lovers Quarrel *",
			"Luckiest Guy" => "Luckiest Guy *",
			"Lusitana" => "Lusitana *",
			"Lustria" => "Lustria *",
			"Macondo" => "Macondo *",
			"Macondo Swash Caps" => "Macondo Swash Caps *",
			"Magra" => "Magra *",
			"Maiden Orange" => "Maiden Orange *",
			"Mako" => "Mako *",
			"Marck Script" => "Marck Script *",
			"Marko One" => "Marko One *",
			"Marmelad" => "Marmelad *",
			"Marvel" => "Marvel *",
			"Mate" => "Mate *",
			"Mate SC" => "Mate SC *",
			"Maven Pro" => "Maven Pro *",
			"Meddon" => "Meddon *",
			"MedievalSharp" => "MedievalSharp *",
			"Medula One" => "Medula One *",
			"Megrim" => "Megrim *",
			"Merienda One" => "Merienda One *",
			"Merriweather" => "Merriweather *",
			"Metal" => "Metal *",
			"Metamorphous" => "Metamorphous *",
			"Metrophobic" => "Metrophobic *",
			"Michroma" => "Michroma *",
			"Miltonian" => "Miltonian *",
			"Miltonian Tattoo" => "Miltonian Tattoo *",
			"Miniver" => "Miniver *",
			"Miss Fajardose" => "Miss Fajardose *",
			"Modern Antiqua" => "Modern Antiqua *",
			"Molengo" => "Molengo *",
			"Monofett" => "Monofett *",
			"Monoton" => "Monoton *",
			"Monsieur La Doulaise" => "Monsieur La Doulaise *",
			"Montaga" => "Montaga *",
			"Montez" => "Montez *",
			"Montserrat" => "Montserrat *",
			"Moul" => "Moul *",
			"Moulpali" => "Moulpali *",
			"Mountains of Christmas" => "Mountains of Christmas *",
			"Mr Bedfort" => "Mr Bedfort *",
			"Mr Dafoe" => "Mr Dafoe *",
			"Mr De Haviland" => "Mr De Haviland *",
			"Mrs Saint Delafield" => "Mrs Saint Delafield *",
			"Mrs Sheppards" => "Mrs Sheppards *",
			"Muli" => "Muli *",
			"Mystery Quest" => "Mystery Quest *",
			"Neucha" => "Neucha *",
			"Neuton" => "Neuton *",
			"News Cycle" => "News Cycle *",
			"Niconne" => "Niconne *",
			"Nixie One" => "Nixie One *",
			"Nobile" => "Nobile *",
			"Nokora" => "Nokora *",
			"Norican" => "Norican *",
			"Nosifer" => "Nosifer *",
			"Nothing You Could Do" => "Nothing You Could Do *",
			"Noticia Text" => "Noticia Text *",
			"Nova Cut" => "Nova Cut *",
			"Nova Flat" => "Nova Flat *",
			"Nova Mono" => "Nova Mono *",
			"Nova Oval" => "Nova Oval *",
			"Nova Round" => "Nova Round *",
			"Nova Script" => "Nova Script *",
			"Nova Slim" => "Nova Slim *",
			"Nova Square" => "Nova Square *",
			"Numans" => "Numans *",
			"Nunito" => "Nunito *",
			"Odor Mean Chey" => "Odor Mean Chey *",
			"Old Standard TT" => "Old Standard TT *",
			"Oldenburg" => "Oldenburg *",
			"Oleo Script" => "Oleo Script *",
			"Open Sans" => "Open Sans *",
			"Open Sans Condensed" => "Open Sans Condensed *",
			"Orbitron" => "Orbitron *",
			"Original Surfer" => "Original Surfer *",
			"Oswald" => "Oswald *",
			"Over the Rainbow" => "Over the Rainbow *",
			"Overlock" => "Overlock *",
			"Overlock SC" => "Overlock SC *",
			"Ovo" => "Ovo *",
			"Oxygen" => "Oxygen *",
			"PT Mono" => "PT Mono *",
			"PT Sans, sans-serif" => "PT Sans *",
			"PT Sans Caption, sans-serif" => "PT Sans Caption *",
			"PT Sans Narrow, sans-serif" => "PT Sans Narrow *",
			"PT Serif" => "PT Serif *",
			"PT Serif Caption" => "PT Serif Caption *",
			"Pacifico" => "Pacifico *",
			"Parisienne" => "Parisienne *",
			"Passero One" => "Passero One *",
			"Passion One" => "Passion One *",
			"Patrick Hand" => "Patrick Hand *",
			"Patua One" => "Patua One *",
			"Paytone One" => "Paytone One *",
			"Permanent Marker" => "Permanent Marker *",
			"Petrona" => "Petrona *",
			"Philosopher" => "Philosopher *",
			"Piedra" => "Piedra *",
			"Pinyon Script" => "Pinyon Script *",
			"Plaster" => "Plaster *",
			"Play" => "Play *",
			"Playball" => "Playball *",
			"Playfair Display" => "Playfair Display *",
			"Podkova" => "Podkova *",
			"Poiret One" => "Poiret One *",
			"Poller One" => "Poller One *",
			"Poly" => "Poly *",
			"Pompiere" => "Pompiere *",
			"Pontano Sans" => "Pontano Sans *",
			"Port Lligat Sans" => "Port Lligat Sans *",
			"Port Lligat Slab" => "Port Lligat Slab *",
			"Prata" => "Prata *",
			"Preahvihear" => "Preahvihear *",
			"Press Start 2P" => "Press Start 2P *",
			"Princess Sofia" => "Princess Sofia *",
			"Prociono" => "Prociono *",
			"Prosto One" => "Prosto One *",
			"Puritan" => "Puritan *",
			"Quantico" => "Quantico *",
			"Quattrocento" => "Quattrocento *",
			"Quattrocento Sans" => "Quattrocento Sans *",
			"Questrial" => "Questrial *",
			"Quicksand" => "Quicksand *",
			"Qwigley" => "Qwigley *",
			"Radley" => "Radley *",
			"Raleway" => "Raleway *",
			"Rammetto One" => "Rammetto One *",
			"Rancho" => "Rancho *",
			"Rationale" => "Rationale *",
			"Redressed" => "Redressed *",
			"Reenie Beanie" => "Reenie Beanie *",
			"Revalia" => "Revalia *",
			"Ribeye" => "Ribeye *",
			"Ribeye Marrow" => "Ribeye Marrow *",
			"Righteous" => "Righteous *",
			"Roboto Condensed" => "Roboto Condensed *",
			"Rochester" => "Rochester *",
			"Rock Salt" => "Rock Salt *",
			"Rokkitt" => "Rokkitt *",
			"Ropa Sans" => "Ropa Sans *",
			"Rosario" => "Rosario *",
			"Rosarivo" => "Rosarivo *",
			"Rouge Script" => "Rouge Script *",
			"Ruda" => "Ruda *",
			"Ruge Boogie" => "Ruge Boogie *",
			"Ruluko" => "Ruluko *",
			"Ruslan Display" => "Ruslan Display *",
			"Russo One" => "Russo One *",
			"Ruthie" => "Ruthie *",
			"Sail" => "Sail *",
			"Salsa" => "Salsa *",
			"Sancreek" => "Sancreek *",
			"Sansita One" => "Sansita One *",
			"Sarina" => "Sarina *",
			"Satisfy" => "Satisfy *",
			"Schoolbell" => "Schoolbell *",
			"Seaweed Script" => "Seaweed Script *",
			"Sevillana" => "Sevillana *",
			"Shadows Into Light" => "Shadows Into Light *",
			"Shadows Into Light Two" => "Shadows Into Light Two *",
			"Shanti" => "Shanti *",
			"Share" => "Share *",
			"Shojumaru" => "Shojumaru *",
			"Short Stack" => "Short Stack *",
			"Siemreap" => "Siemreap *",
			"Sigmar One" => "Sigmar One *",
			"Signika" => "Signika *",
			"Signika Negative" => "Signika Negative *",
			"Simonetta" => "Simonetta *",
			"Sirin Stencil" => "Sirin Stencil *",
			"Six Caps" => "Six Caps *",
			"Slackey" => "Slackey *",
			"Smokum" => "Smokum *",
			"Smythe" => "Smythe *",
			"Sniglet" => "Sniglet *",
			"Snippet" => "Snippet *",
			"Sofia" => "Sofia *",
			"Sonsie One" => "Sonsie One *",
			"Sorts Mill Goudy" => "Sorts Mill Goudy *",
			"Special Elite" => "Special Elite *",
			"Spicy Rice" => "Spicy Rice *",
			"Spinnaker" => "Spinnaker *",
			"Spirax" => "Spirax *",
			"Squada One" => "Squada One *",
			"Stardos Stencil" => "Stardos Stencil *",
			"Stint Ultra Condensed" => "Stint Ultra Condensed *",
			"Stint Ultra Expanded" => "Stint Ultra Expanded *",
			"Stoke" => "Stoke *",
			"Sue Ellen Francisco" => "Sue Ellen Francisco *",
			"Sunshiney" => "Sunshiney *",
			"Supermercado One" => "Supermercado One *",
			"Suwannaphum" => "Suwannaphum *",
			"Swanky and Moo Moo" => "Swanky and Moo Moo *",
			"Syncopate" => "Syncopate *",
			"Tangerine" => "Tangerine *",
			"Taprom" => "Taprom *",
			"Telex" => "Telex *",
			"Tenor Sans" => "Tenor Sans *",
			"The Girl Next Door" => "The Girl Next Door *",
			"Tienne" => "Tienne *",
			"Tinos" => "Tinos *",
			"Titan One" => "Titan One *",
			"Trade Winds" => "Trade Winds *",
			"Trocchi" => "Trocchi *",
			"Trochut" => "Trochut *",
			"Trykker" => "Trykker *",
			"Tulpen One" => "Tulpen One *",
			"Ubuntu" => "Ubuntu *",
			"Ubuntu Condensed" => "Ubuntu Condensed *",
			"Ubuntu Mono" => "Ubuntu Mono *",
			"Ultra" => "Ultra *",
			"Uncial Antiqua" => "Uncial Antiqua *",
			"UnifrakturCook" => "UnifrakturCook *",
			"UnifrakturMaguntia" => "UnifrakturMaguntia *",
			"Unkempt" => "Unkempt *",
			"Unlock" => "Unlock *",
			"Unna" => "Unna *",
			"VT323" => "VT323 *",
			"Varela" => "Varela *",
			"Varela Round" => "Varela Round *",
			"Vast Shadow" => "Vast Shadow *",
			"Vibur" => "Vibur *",
			"Vidaloka" => "Vidaloka *",
			"Viga" => "Viga *",
			"Voces" => "Voces *",
			"Volkhov" => "Volkhov *",
			"Vollkorn" => "Vollkorn *",
			"Voltaire" => "Voltaire *",
			"Waiting for the Sunrise" => "Waiting for the Sunrise *",
			"Wallpoet" => "Wallpoet *",
			"Walter Turncoat" => "Walter Turncoat *",
			"Wellfleet" => "Wellfleet *",
			"Wire One" => "Wire One *",
			"Yanone Kaffeesatz" => "Yanone Kaffeesatz *",
			"Yellowtail" => "Yellowtail *",
			"Yeseva One" => "Yeseva One *",
			"Yesteryear" => "Yesteryear *",
			"Zeyada" => "Zeyad *a"
	);
	return $google_faces;
}


/**
 * Checks font options to see if a Google font is selected.
 * If so, options_typography_enqueue_google_font is called to enqueue the font.
 * Ensures that each Google font is only enqueued once.
 */
if ( !function_exists( 'options_typography_google_fonts' ) ) {
	function options_typography_google_fonts() {
		global $font_array, $char_array;
		$all_google_fonts = array_keys( options_typography_get_google_fonts() );
		$all_character_sets = array_keys( of_recognized_font_characters() );
		// Define all the options that possibly have a unique Google font
		$h1_heading = of_get_option('h1_heading', 'Arial');
		$h2_heading = of_get_option('h2_heading', 'Arial');
		$h3_heading = of_get_option('h3_heading', 'Arial');
		$h4_heading = of_get_option('h4_heading', 'Arial');
		$h5_heading = of_get_option('h5_heading', 'Arial');
		$h6_heading = of_get_option('h6_heading', 'Arial');
		$google_mixed_3 = of_get_option('google_mixed_3', 'Rokkitt, serif');
		if ( of_get_option( 'logo_typography' ) ) {
			$logo_typography = of_get_option('logo_typography');
		}
		if ( of_get_option( 'menu_typography' ) ) {
			$menu_typography = of_get_option('menu_typography', 'Arial');
		}
		if ( of_get_option( 'footer_menu_typography' ) ) {
			$footer_menu_typography = of_get_option('footer_menu_typography', 'Arial');
		}

		// Get the font face for each option and put it in an array
		if ( isset($h1_heading) && is_array($h1_heading) ) $selected_fonts['h1_heading'] = $h1_heading['face'];
		if ( isset($h2_heading) && is_array($h2_heading) ) $selected_fonts['h2_heading'] = $h2_heading['face'];
		if ( isset($h3_heading) && is_array($h3_heading) ) $selected_fonts['h3_heading'] = $h3_heading['face'];
		if ( isset($h4_heading) && is_array($h4_heading) ) $selected_fonts['h4_heading'] = $h4_heading['face'];
		if ( isset($h5_heading) && is_array($h5_heading) ) $selected_fonts['h5_heading'] = $h5_heading['face'];
		if ( isset($h6_heading) && is_array($h6_heading) ) $selected_fonts['h6_heading'] = $h6_heading['face'];
		if ( isset($google_mixed_3) && is_array($google_mixed_3) ) $selected_fonts['google_mixed_3'] = $google_mixed_3['face'];
		if ( isset($logo_typography) && is_array($logo_typography) ) $selected_fonts['logo_typography'] = $logo_typography['face'];
		if ( isset($menu_typography) && is_array($menu_typography) ) $selected_fonts['menu_typography'] = $menu_typography['face'];
		if ( isset($footer_menu_typography) && is_array($footer_menu_typography) ) $selected_fonts['footer_menu_typography'] = $footer_menu_typography['face'];

		if ( isset($h1_heading) && is_array($h1_heading) ) $selected_character['h1_heading'] = $h1_heading['character'];
		if ( isset($h2_heading) && is_array($h2_heading) ) $selected_character['h2_heading'] = $h2_heading['character'];
		if ( isset($h3_heading) && is_array($h3_heading) ) $selected_character['h3_heading'] = $h3_heading['character'];
		if ( isset($h4_heading) && is_array($h4_heading) ) $selected_character['h4_heading'] = $h4_heading['character'];
		if ( isset($h5_heading) && is_array($h5_heading) ) $selected_character['h5_heading'] = $h5_heading['character'];
		if ( isset($h6_heading) && is_array($h6_heading) ) $selected_character['h6_heading'] = $h6_heading['character'];
		if ( isset($google_mixed_3) && is_array($google_mixed_3) ) $selected_character['google_mixed_3'] = $google_mixed_3['character'];
		if ( isset($logo_typography) && is_array($logo_typography) ) $selected_character['logo_typography'] = $logo_typography['character'];
		if ( isset($menu_typography) && is_array($menu_typography) ) $selected_character['menu_typography'] = $menu_typography['character'];
		if ( isset($footer_menu_typography) && is_array($footer_menu_typography) ) $selected_character['footer_menu_typography'] = $footer_menu_typography['character'];

		if ( isset($selected_fonts) && !empty($selected_fonts) ) {
			// Remove any duplicates in the list
			$selected_fonts = array_unique($selected_fonts);

			// Check each of the unique fonts against the defined Google fonts
			// If it is a Google font, go ahead and call the function to enqueue it
			foreach ( $selected_fonts as $key => $font ) {
				if ( in_array( $font, $all_google_fonts ) ) {
					options_typography_enqueue_google_font($key, $font);
				}
			}
		}
		
		// $selected_character = array_unique($selected_character);
		if ( isset($selected_character) && !empty($selected_character) ) {
			foreach ( $selected_character as $key => $character ) {
				if ( in_array( $character, $all_character_sets ) ) {
					options_typography_enqueue_google_character_set($key, $character);
				}
			}
		}
		
		if (isset($font_array)) {
			foreach ($font_array as $key => $value) {
				switch ($font_array[$key]) {
					case 'Open+Sans+Condensed':
						$font_array[$key]= $font_array[$key].':300';
					break;
					default:
						//
					break;
				}
				options_typography_enqueue_google_fonts($font_array[$key], $char_array[$key]);
				
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'options_typography_google_fonts' );
/**
 * Enqueues the Google $font that is passed
 */
function options_typography_enqueue_google_font($key, $font) {
	global $font_array;

	$font = explode(',', $font);
	$font = $font[0];

	// Certain Google fonts need slight tweaks in order to load properly
	// Like our friend "Raleway"
	if ( $font == 'Raleway' )
		$font = 'Raleway:100';
	$font = str_replace(" ", "+", $font);
	$font_array[$key] = $font;
}

function options_typography_enqueue_google_character_set($key, $character) {
	global $char_array;

	$char_array[$key] = '&amp;subset=' . $character;
}

function options_typography_enqueue_google_fonts($f, $ch) {
	wp_enqueue_style( "options_typography_$f", "//fonts.googleapis.com/css?family=$f$ch", false, null, 'all' );
}


/*
 * Outputs the selected option panel styles inline into the <head>
 */
function options_typography_styles() {
	$output = '';
	$input  = '';

	if ( of_get_option( 'h1_heading' ) ) {
		$input = of_get_option( 'h1_heading' );
		$output .= options_typography_font_styles( of_get_option( 'h1_heading' ) , 'h1');
	}
	if ( of_get_option( 'h2_heading' ) ) {
		$input = of_get_option( 'h2_heading' );
		$output .= options_typography_font_styles( of_get_option( 'h2_heading' ) , 'h2');
	}
	if ( of_get_option( 'h3_heading' ) ) {
		$input = of_get_option( 'h3_heading' );
		$output .= options_typography_font_styles( of_get_option( 'h3_heading' ) , 'h3');
	}
	if ( of_get_option( 'h4_heading' ) ) {
		$input = of_get_option( 'h4_heading' );
		$output .= options_typography_font_styles( of_get_option( 'h4_heading' ) , 'h4');
	}
	if ( of_get_option( 'h5_heading' ) ) {
		$input = of_get_option( 'h5_heading' );
		$output .= options_typography_font_styles( of_get_option( 'h5_heading' ) , 'h5');
	}
	if ( of_get_option( 'h6_heading' ) ) {
		$input = of_get_option( 'h6_heading' );
		$output .= options_typography_font_styles( of_get_option( 'h6_heading' ) , 'h6');
	}
	if ( of_get_option( 'google_mixed_3' ) ) {
		$input = of_get_option( 'google_mixed_3' );
		$output .= options_typography_font_styles_body( $input , 'body');
	}
	if ( of_get_option( 'logo_typography' ) ) {
		$input = of_get_option( 'logo_typography' );
		$output .= options_typography_font_styles( of_get_option( 'logo_typography' ) , '.logo_h__txt, .logo_link');
	}
	if ( of_get_option( 'menu_typography' ) ) {
		$input = of_get_option( 'menu_typography' );
		$output .= options_typography_font_styles( of_get_option( 'menu_typography' ) , '.sf-menu > li > a');
	}
	if ( of_get_option( 'footer_menu_typography' ) ) {
		$input = of_get_option( 'footer_menu_typography' );
		$output .= options_typography_font_styles( of_get_option( 'footer_menu_typography' ) , '.nav.footer-nav a');
	}
	if ( $output != '' ) {
		$output = "\n<style type='text/css'>\n" . $output . "</style>\n";
		echo $output;
	}
}
add_action('wp_head', 'options_typography_styles');


/*
 * Returns a typography option in a format that can be outputted as inline CSS
 */
function options_typography_font_styles($option, $selectors) {
		$output = $selectors . ' { ';
		$output .= 'font: ' . $option['style'] . ' ' . $option['size'] . '/'.$option['lineheight'].' ' . $option['face'] . '; ';
		$output .= ' color:' . $option['color'] .'; ';
		$output .= '}';
		$output .= "\n";
		return $output;
}

/*
 * This is one the same but for body
 */
function options_typography_font_styles_body($option, $selectors) {
		$output = $selectors . ' { ';
		switch ( $option['style'] ) {
			case 'normal':
				$output .= 'font-weight: normal;';
				break;
			case 'italic':
				$output .= 'font-style: italic;';
				break;
			case 'bold':
				$output .= 'font-weight: bold;';
				break;
			case 'bold italic':
				$output .= 'font-weight: bold; font-style: italic;';
				break;
			default:
				$output .= 'font-weight: normal; font-style: normal;';
				break;
		}
		$output .= '}';
		$output .= "\n";
		return $output;
}


/**
 * Is a given string a color formatted in hexidecimal notation?
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @return   bool
 *
 */

function of_validate_hex( $hex ) {
	$hex = trim( $hex );
	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	}
	elseif ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}
	/* Regex match. */
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	}
	else {
		return true;
	}
}