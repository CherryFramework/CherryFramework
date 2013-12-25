<?php
/**
 * Bootstrap I/O files
 */
if ( !FILE_WRITEABLE ) return;

if ( CURRENT_THEME == 'cherry' ) {
	$bootstrapInput = PARENT_DIR .'/less/bootstrap.less';
} else {
	$bootstrapInput = CHILD_DIR .'/bootstrap/less/bootstrap.less';
}
$bootstrapOutput = CHILD_DIR .'/bootstrap/css/bootstrap.css';

/**
 * Current theme I/O files
 */
if ( CURRENT_THEME == 'cherry' ) {
	$themeInput  = PARENT_DIR .'/less/style.less';
	$themeOutput = PARENT_DIR .'/css/style.css';
} else {
	$themeInput  = CHILD_DIR .'/style.less';
	$themeOutput = CHILD_DIR .'/main-style.css';
}

/**
 * Get variables from Cherry Options
 */
function cherryVariables() {
	global $variablesArray;

	// textColor
	$body = of_get_option('google_mixed_3');
	if ( $body['color'] ) {
		$variablesArray['textColor'] = $body['color'];
	} else {
		$variablesArray['textColor'] = bootstrapVariables('textColor');
	}

	// bodyBackground
	$body_background = of_get_option('body_background');
	if ( $body_background['color'] ) {
		$variablesArray['bodyBackground'] = $body_background['color'];
	} else {
		$variablesArray['bodyBackground'] = bootstrapVariables('bodyBackground');
	}

	// mainBackground
	if ( of_get_option('main_background') ) {
		$variablesArray['mainBackground'] = of_get_option('main_background');
	}

	// baseFontFamily
	if ( $body['face'] ) {
		$variablesArray['baseFontFamily'] = $body['face'];
	} else {
		$variablesArray['baseFontFamily'] = bootstrapVariables('baseFontFamily');
	}

	// baseFontSize
	if ( $body['size'] ) {
		$variablesArray['baseFontSize'] = $body['size'];
	} else {
		$variablesArray['baseFontSize'] = bootstrapVariables('baseFontFamily');
	}

	// baseLineHeight
	if ( $body['lineheight'] ) {
		$variablesArray['baseLineHeight'] = $body['lineheight'];
	} else {
		$variablesArray['baseLineHeight'] = bootstrapVariables('baseFontFamily');
	}

	// linkColor
	if ( of_get_option('links_color') ) {
		$variablesArray['linkColor'] = of_get_option('links_color');
	} else {
		$variablesArray['linkColor'] = bootstrapVariables('linkColor');
	}

	// linkColorHover
	if ( of_get_option('links_color_hover') ) {
		$variablesArray['linkColorHover'] = of_get_option('links_color_hover');
	} else {
		$variablesArray['linkColorHover'] = bootstrapVariables('linkColorHover');
	}
}

/**
 * Get variables from botstrap
 * @param  string $must Bootstrap variable's name
 * @return string       Bootstrap variable's value
 */
function bootstrapVariables( $must ) {

	$val = '';
	$file = CHILD_DIR .'/bootstrap/less/variables.less';

	if ( file_exists($file) ) {
		$allVariablessArray = file( $file );

		foreach ( $allVariablessArray as $v ) {
			$pos = strpos( $v, $must );
			if ( $pos ) {
				$start  = strpos( $v, ':' ) + 1;
				$finish = strpos( $v, ';' );
				$val    = trim( substr( $v, $start, ( $finish - $start ) ) );
				break;
			}
		}
	}
	return $val;
}

// Hook for clean less cache after save Cherry Options
add_action('optionsframework_after_validate', 'clean_less_cache');

/**
 * Auto Compiling LESS files (cache)
 * @param  string $inputFile  path to the less (input) file
 * @param  string $outputFile path to the css (output) file
 */
function auto_less_compile( $inputFile, $outputFile ) {
	global $variablesArray;

	cherryVariables();
	if ( empty( $variablesArray ) ) return;

	// load the cache
	$cacheFile = $inputFile.".cache";

	if ( file_exists( $cacheFile ) ) {
		$cache = unserialize( file_get_contents( $cacheFile ) );
	} else {
		$cache = $inputFile;
	}

	// custom formatter
	$formatter = new lessc_formatter_classic;
	$formatter->indentChar = "\t";

	$less = new lessc;
	$less->setVariables($variablesArray);
	$less->setFormatter($formatter);

	try {
		// create a new cache object, and compile
		$newCache = $less->cachedCompile($cache);

		// the next time we run, write only if it has updated
		if ( !is_array($cache) || $newCache["updated"] > $cache["updated"] ) {
			file_put_contents($cacheFile, serialize($newCache));
			file_put_contents($outputFile, $newCache['compiled']);
		}
	} catch (Exception $ex) {
		echo "lessphp fatal error: ".$ex->getMessage();
	}
}
auto_less_compile( $bootstrapInput, $bootstrapOutput );
auto_less_compile( $themeInput, $themeOutput );

/**
 * Simple Compiling LESS files
 * @param  string $inputFile  path to the less (input) file
 * @param  string $outputFile path to the css (output) file
 */
function simple_less_compile( $inputFile, $outputFile ) {
	global $variablesArray;

	cherryVariables();
	if ( empty($variablesArray) )
		return;

	// custom formatter
	$formatter = new lessc_formatter_classic;
	$formatter->indentChar = "\t";

	$less = new lessc;
	$less->setVariables($variablesArray);
	$less->setFormatter($formatter);

	try {
		$less->compileFile($inputFile, $outputFile);
	} catch (Exception $ex) {
		echo "lessphp fatal error: ".$ex->getMessage();
	}
}
// TEMP: Enable compiling bootstrap.less and style.less on every refresh. Normally you don't need this! This is for developing only!
// simple_less_compile( $bootstrapInput, $bootstrapOutput );
// simple_less_compile( $themeInput, $themeOutput );
?>