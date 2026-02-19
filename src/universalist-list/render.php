<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$distantjet_universalist_lang = function_exists( 'distantjet_universalist_detect_lang' ) ? distantjet_universalist_detect_lang() : 'en';

// Determine the target class based on language
$distantjet_universalist_targetClass = ($distantjet_universalist_lang === 'es') ? 'universalist-list-es' : 'universalist-list-en';

echo '<div class="universalist-container">';

/**
 * Strategy: 
 * 1. Try to find the <ul> with the specific language class.
 * 2. If not found, fall back to the first <ul> in the content.
 */

// Regex for the specific class: looks for <ul ... class="...targetClass..." ...>...</ul>
$specific_regex = '/<ul[^>]*class=[^>]*' . preg_quote($distantjet_universalist_targetClass, '/') . '[^>]*>.*?<\/ul>/is';

if ( preg_match( $specific_regex, $content, $matches ) ) {
    // Found the specific list
    echo wp_kses_post( $matches[0] );
} else {
    // Fallback: Find the first <ul> regardless of class
    $fallback_regex = '/<ul[^>]*>.*?<\/ul>/is';
    if ( preg_match( $fallback_regex, $content, $matches ) ) {
        echo wp_kses_post( $matches[0] );
    }
}

echo '</div>';