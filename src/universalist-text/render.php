<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$lang_secondary = get_option('distantjet_univ_option_lang_secondary');
$lang_primary = get_option('distantjet_univ_option_lang_primary');

// Use the existing logic for language detection
if(function_exists( 'distantjet_universalist_detect_lang' )) {

	$distantjet_universalist_text_primary = $attributes['text_primary'] ?? '';
	$distantjet_universalist_text_secondary = $attributes['text_secondary'] ?? '';

	if(distantjet_universalist_detect_lang() === $lang_secondary) {

		// Determine content based on locale
		if( !empty( $distantjet_universalist_text_secondary )) {

			$distantjet_universalist_content = $distantjet_universalist_text_secondary;
		}
	}
	else {

		// Determine content based on locale
		if( !empty( $distantjet_universalist_text_primary )) {

			$distantjet_universalist_content = $distantjet_universalist_text_primary;
		}
	}
}

// Generate wrapper attributes using get_block_wrapper_attributes()
// This is the industry standard for handling alignment, custom classes, and IDs automatically.
$distantjet_universalist_wrapper_attributes = get_block_wrapper_attributes();

printf(
	'<div %s>%s</div>',
	wp_kses_post($distantjet_universalist_wrapper_attributes),
	wp_kses_post( $distantjet_universalist_content )
);