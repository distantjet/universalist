<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Dynamic rendering for the Universalist Title block.
 */

$distantjet_universalist_lang = function_exists('distantjet_universalist_detect_lang') ? distantjet_universalist_detect_lang() : 'en';

$distantjet_universalist_title_en = $attributes['title_en'] ?? '';
$distantjet_universalist_title_es = $attributes['title_es'] ?? '';
$distantjet_universalist_tag      = $attributes['title_style'] ?? 'h2';

$distantjet_universalist_display_title = ($distantjet_universalist_lang === 'es' && !empty($distantjet_universalist_title_es)) ? $distantjet_universalist_title_es : $distantjet_universalist_title_en;

// Industry standard: Use get_block_wrapper_attributes()
$distantjet_universalist_wrapper_attributes = get_block_wrapper_attributes();

printf(
    '<%1$s %2$s>%3$s</%1$s>',
    wp_kses($distantjet_universalist_tag, array('h1', 'h2', 'h3', 'h4', 'h5', 'h6')),
    wp_kses_post($distantjet_universalist_wrapper_attributes),
    esc_html($distantjet_universalist_display_title)
);