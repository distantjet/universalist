<?php
/**
 * Plugin Name:       Universalist
 * Plugin URI:        https://distantjet.com/universalist
 * Description:       Create content for multiple languages
 * Version:           1.0.0
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

update_option('distantjet_univ_option_lang_primary', 'en-US');
update_option('distantjet_univ_option_lang_secondary', 'es-AR');
// update_option('distantjet_univ_option_lang_secondary', 'fr_FR');

function distantjet_com_distantjet_universalist_block_init() {
	register_block_type_from_metadata( __DIR__ . '/build/universalist-list' );
	register_block_type_from_metadata( __DIR__ . '/build/universalist-text' );
	register_block_type_from_metadata( __DIR__ . '/build/universalist-heading' );
	register_block_type_from_metadata( __DIR__ . '/build/universalist-page-title' );
}

add_action( 'init', 'distantjet_com_distantjet_universalist_block_init' );


function distantjet_universalist_determine_locale($locale) {
    // Check the Cookie. 
    // This is safe because cookies are sent by the browser, not the cache.
    if (isset($_COOKIE['distantjet_univ_lang_cookie'])) {
        return sanitize_text_field(wp_unslash($_COOKIE['distantjet_univ_lang_cookie']));
    }
    // if (isset($_COOKIE['dj_universalist_lang_cookie'])) {
    //     $lang = sanitize_text_field(wp_unslash($_COOKIE['dj_universalist_lang_cookie']));
    //     return ($lang === 'es') ? 'es_AR' : 'en_US';
    // }

    // Fallback to Browser Language
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $browser_lang = substr(sanitize_text_field(wp_unslash($_SERVER['HTTP_ACCEPT_LANGUAGE'])), 0, 5);
        return $browser_lang;
    }
    // if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    //     $accept_lang = sanitize_text_field(wp_unslash($_SERVER['HTTP_ACCEPT_LANGUAGE']));
    //     $browser_lang = substr($accept_lang, 0, 2);
    //     return ($browser_lang === 'es') ? 'es_AR' : 'en_US';
    // }

    return $locale;
}
add_filter('locale', 'distantjet_universalist_determine_locale');


// This will be used to also translate the website using i18n
// Language detection used by universalist
function distantjet_universalist_detect_lang() {
    // Check Cookie
    if ( isset( $_COOKIE['distantjet_univ_lang_cookie'] ) ) {

        return sanitize_text_field( wp_unslash( $_COOKIE['distantjet_univ_lang_cookie'] ) );
    }
    // if ( isset( $_COOKIE['dj_universalist_lang_cookie'] ) ) {

    //     $cookie_lang = sanitize_text_field( wp_unslash( $_COOKIE['dj_universalist_lang_cookie'] ) );
    //     return 'es' === $cookie_lang ? 'es' : 'en';
    // }

    // Check Browser Language
    
    if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {

        return  substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ), 0, 5 );
    }
    // if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {

    //     $accept_language = sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) );
    //     $dj_universalist_lang = substr( $accept_language, 0, 2 );
    //     return 'es' === $dj_universalist_lang ? 'es' : 'en';
    // }

    return 'en-US';
}


// Register bilingual page title meta
add_action( 'init', function () {
    register_post_meta( '', 'distantjet_universalist_page_title_primary', [
        'show_in_rest' => true,
        'single'       => true,
        'type'         => 'string',
    ] );

    register_post_meta( '', 'distantjet_universalist_page_title_secondary', [
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
    
    $title_primary = get_post_meta( $post_id, 'distantjet_universalist_page_title_primary', true );
    $title_secondary = get_post_meta( $post_id, 'distantjet_universalist_page_title_secondary', true );

    // Get the saved secondary language
    $lang_secondary = get_option('distantjet_univ_option_lang_secondary');

    if ( $lang_secondary === $lang && ! empty( $title_secondary ) ) {
        return $title_secondary;
    }
    
    if ( ! empty( $title_primary ) ) {
        return $title_primary;
    }

    return ''; // Return empty so the filter knows to use the default
}

// Translate the on-page title
add_filter( 'the_title', function ( $title, $post_id ) {
    // Keep the admin check to avoid breaking the backend list of posts
    if ( is_admin() ) {
        return $title;
    }

    $translated = distantjet_universalist_get_translated_title( $post_id );
    
    return $translated ? $translated : $title;
}, 10, 2 );

// Translate the <title> tag (SEO/Browser Tab)
add_filter( 'pre_get_document_title', function ( $title ) {
    if ( ! is_singular() ) {
        return $title;
    }

    $translated = distantjet_universalist_get_translated_title( get_queried_object_id() );
    return $translated ? $translated : $title;
} );

