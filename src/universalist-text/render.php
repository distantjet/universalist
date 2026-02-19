<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Render callback for the Universalist Text block.
 */

// Use the existing logic for language detection
$distantjet_universalist_lang = function_exists( 'distantjet_universalist_detect_lang' ) ? distantjet_universalist_detect_lang() : 'en';

$distantjet_universalist_text_en = $attributes['text_en'] ?? '';
$distantjet_universalist_text_es = $attributes['text_es'] ?? '';

// Determine content based on locale
$distantjet_universalist_content = ( $distantjet_universalist_lang === 'es' && ! empty( $distantjet_universalist_text_es ) ) ? $distantjet_universalist_text_es : $distantjet_universalist_text_en;

// Generate wrapper attributes using get_block_wrapper_attributes()
// This is the industry standard for handling alignment, custom classes, and IDs automatically.
$distantjet_universalist_wrapper_attributes = get_block_wrapper_attributes();

printf(
	'<div %s>%s</div>',
	wp_kses_post($distantjet_universalist_wrapper_attributes),
	wp_kses_post( $distantjet_universalist_content )
);