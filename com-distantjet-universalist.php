<?php
/**
 * Plugin Name:       Universalist
 * Plugin URI:        https://distantjet.com/universalist
 * Description:       Create content for multiple languages
 * Version:           1.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Matias Escudero
 * License:           GPL-2.0-or-later
 *
 * @package           distantjet
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main Plugin Class
 */
class DistantJet_Universalist {

    /**
     * Singleton Instance
     */
    private static $instance = null;

    /**
     * Plugin Settings Properties
     */
    public $primary_lang;
    public $secondary_lang;
    public $current_lang;

    /**
     * Get the single instance of the class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Load settings into properties to avoid repetitive get_option calls
        $this->primary_lang   = get_option( 'distantjet_univ_option_lang_primary', 'en-US' );
        $this->secondary_lang = get_option( 'distantjet_univ_option_lang_secondary', 'es-AR' );
        
        // Detect current language once per request
        $this->current_lang = $this->detect_lang();

        // Register Hooks
        add_action( 'init', [ $this, 'register_blocks' ] );
        add_action( 'init', [ $this, 'register_meta_fields' ] );
        
        add_filter( 'locale', [ $this, 'determine_locale' ] );
        add_filter( 'the_title', [ $this, 'translate_page_title' ], 10, 2 );
        add_filter( 'pre_get_document_title', [ $this, 'translate_document_title' ] );
    }

    /**
     * Detect language from cookie or browser.
     */
    public function detect_lang() {
        if ( isset( $_COOKIE['distantjet_univ_lang_cookie'] ) ) {
            return sanitize_text_field( wp_unslash( $_COOKIE['distantjet_univ_lang_cookie'] ) );
        }

        if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
            return substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ), 0, 5 );
        }

        return $this->primary_lang;
    }

    /**
     * Register blocks from metadata.
     */
    public function register_blocks() {
        $blocks = [
            'universalist-list',
            'universalist-text',
            'universalist-heading',
            'universalist-page-title'
        ];

        foreach ( $blocks as $block ) {
            $path = __DIR__ . '/build/' . $block;
            if ( file_exists( $path ) ) {
                register_block_type_from_metadata( $path );
            }
        }
    }

    /**
     * Register bilingual page title meta.
     */
    public function register_meta_fields() {
        $keys = [
            'distantjet_universalist_page_title_primary',
            'distantjet_universalist_page_title_secondary'
        ];

        foreach ( $keys as $key ) {
            register_post_meta( '', $key, [
                'show_in_rest' => true,
                'single'       => true,
                'type'         => 'string',
            ] );
        }
    }

    /**
     * Get the translated title based on post ID and current lang.
     */
    public function get_translated_title( $post_id ) {
        $title_primary   = get_post_meta( $post_id, 'distantjet_universalist_page_title_primary', true );
        $title_secondary = get_post_meta( $post_id, 'distantjet_universalist_page_title_secondary', true );

        if ( $this->current_lang === $this->secondary_lang && ! empty( $title_secondary ) ) {
            return $title_secondary;
        }

        return ! empty( $title_primary ) ? $title_primary : '';
    }

    /**
     * Filters
     */
    public function determine_locale( $locale ) {
        return $this->current_lang ?: $locale;
    }

    public function translate_page_title( $title, $post_id ) {
        if ( is_admin() ) return $title;
        $translated = $this->get_translated_title( $post_id );
        return $translated ? $translated : $title;
    }

    public function translate_document_title( $title ) {
        if ( ! is_singular() ) return $title;
        $translated = $this->get_translated_title( get_queried_object_id() );
        return $translated ? $translated : $title;
    }
}

/**
 * Initialize the plugin.
 */
function universalist() {
    return DistantJet_Universalist::get_instance();
}

// Start the plugin
universalist();