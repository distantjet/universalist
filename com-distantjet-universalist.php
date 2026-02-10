<?php
/**
 * Plugin Name:       Universalist
 * Plugin URI:        https://distantjet.com/universalist
 * Description:       Create content for multiple languages
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Matias Escudero
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       com-distantjet-universalist
 *
 * @package           distantjet
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */

function distantjet_com_distantjet_universalist_block_init() {
	register_block_type_from_metadata( __DIR__ . '/build/universalist-list' );
	register_block_type_from_metadata( __DIR__ . '/build/universalist-text' );
	register_block_type_from_metadata( __DIR__ . '/build/universalist-title' );
	register_block_type_from_metadata( __DIR__ . '/build/universalist-page-title' );
}

add_action( 'init', 'distantjet_com_distantjet_universalist_block_init' );


function distantjet_universalist_determine_locale($locale) {
    // 1. Check the Cookie. 
    // This is safe because cookies are sent by the browser, not the cache.
    if (isset($_COOKIE['dj_universalist_lang_cookie'])) {
        $lang = sanitize_text_field(wp_unslash($_COOKIE['dj_universalist_lang_cookie']));
        return ($lang === 'en') ? 'en_US' : 'es_AR';
    }

    // 2. Fallback to Browser Language
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $accept_lang = sanitize_text_field(wp_unslash($_SERVER['HTTP_ACCEPT_LANGUAGE']));
        $browser_lang = substr($accept_lang, 0, 2);
        return ($browser_lang === 'en') ? 'en_US' : 'es_AR';
    }

    return $locale;
}
add_filter('locale', 'distantjet_universalist_determine_locale');


// This will be used to also translate the website using i18n
// Language detection used by universalist
function distantjet_universalist_detect_lang() {
    // 1. Check Cookie
    if ( isset( $_COOKIE['dj_universalist_lang_cookie'] ) ) {

        $cookie_lang = sanitize_text_field( wp_unslash( $_COOKIE['dj_universalist_lang_cookie'] ) );
        return 'en' === $cookie_lang ? 'en' : 'es';
    }

    // 2. Check Browser Language
    if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {

        $accept_language = sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) );
        $dj_universalist_lang = substr( $accept_language, 0, 2 );
        return 'en' === $dj_universalist_lang ? 'en' : 'es';
    }

    return 'en';
}


// Register bilingual page title meta
add_action( 'init', function () {
    register_post_meta( '', 'dj_universalist_page_title_en', [
        'show_in_rest' => true,
        'single'       => true,
        'type'         => 'string',
    ] );

    register_post_meta( '', 'dj_universalist_page_title_es', [
        'show_in_rest' => true,
        'single'       => true,
        'type'         => 'string',
    ] );
} );


/**
 * Helper to get the translated title based on post ID
 */
function distantjet_universalist_get_translated_title( $post_id ) {
    $lang = distantjet_universalist_detect_lang();
    
    $en = get_post_meta( $post_id, 'dj_universalist_page_title_en', true );
    $es = get_post_meta( $post_id, 'dj_universalist_page_title_es', true );

    if ( 'en' === $lang && ! empty( $en ) ) {
        return $en;
    }
    
    if ( ! empty( $es ) ) {
        return $es;
    }

    return ''; // Return empty so the filter knows to use the default
}

// 1. Translate the on-page title
add_filter( 'the_title', function ( $title, $post_id ) {
    if ( is_admin() || ! is_singular() ) {
        return $title;
    }

    $translated = distantjet_universalist_get_translated_title( $post_id );
    return $translated ? $translated : $title;
}, 10, 2 );

// 2. Translate the <title> tag (SEO/Browser Tab)
add_filter( 'pre_get_document_title', function ( $title ) {
    if ( ! is_singular() ) {
        return $title;
    }

    $translated = distantjet_universalist_get_translated_title( get_queried_object_id() );
    return $translated ? $translated : $title;
} );

