<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$lang_secondary = get_option('distantjet_univ_option_lang_secondary');
$lang_primary = get_option('distantjet_univ_option_lang_primary');

/**
 * Dynamic rendering for the Universalist Title block.
 */

// Use the existing logic for language detection
if(function_exists( 'distantjet_universalist_detect_lang' )) {

	$distantjet_universalist_heading_primary = $attributes['heading_primary'] ?? '';
    $distantjet_universalist_heading_secondary = $attributes['heading_secondary'] ?? '';

	if(distantjet_universalist_detect_lang() === $lang_secondary) {

		// Determine content based on locale
		if( !empty( $distantjet_universalist_heading_secondary )) {

			$distantjet_universalist_display_heading = $distantjet_universalist_heading_secondary;
		}
	}
	else {

		// Determine content based on locale
		if( !empty( $distantjet_universalist_heading_primary )) {

			$distantjet_universalist_display_heading = $distantjet_universalist_heading_primary;
		}
	}
}

$distantjet_universalist_tag      = $attributes['heading_style'] ?? 'h2';

// Industry standard: Use get_block_wrapper_attributes()
$distantjet_universalist_wrapper_attributes = get_block_wrapper_attributes();

printf(
    '<%1$s %2$s>%3$s</%1$s>',
    wp_kses($distantjet_universalist_tag, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6')),
    wp_kses_post($distantjet_universalist_wrapper_attributes),
    esc_html($distantjet_universalist_display_heading)
);