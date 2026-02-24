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

require_once plugin_dir_path( __FILE__ ) . 'admin/com-distantjet-universalist-settings.php';

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
        $this->primary_lang   = get_option( 'distantjet_univ_option_lang_primary', 'en' );
        $this->secondary_lang = get_option( 'distantjet_univ_option_lang_secondary', 'es' );
        
        // Detect current language once per request
        $this->current_lang = $this->detect_lang();

        // Register Hooks
        add_action( 'init', [ $this, 'register_blocks' ] );
        add_action( 'init', [ $this, 'register_meta_fields' ] );
        
        add_filter( 'locale', [ $this, 'determine_locale' ] );
        add_filter( 'the_title', [ $this, 'translate_page_title' ], 10, 2 );
        add_filter( 'pre_get_document_title', [ $this, 'translate_document_title' ] );

        // Add menu pages and load their scripts
        add_action('admin_menu', array($this, 'settings'));

        $univ_settings = universalist_settings();

        add_action('wp_ajax_save_settings', array($univ_settings, 'save_settings'));
        add_action('wp_ajax_nopriv_save_settings', array($univ_settings, 'save_settings'));
    }

    /**
     * Detect language from cookie or browser.
     */
    public function detect_lang() {
        if ( isset( $_COOKIE['distantjet_univ_lang_cookie'] ) ) {
            return sanitize_text_field( wp_unslash( $_COOKIE['distantjet_univ_lang_cookie'] ) );
        }

        if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
            return substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ), 0, 2 );
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

    public function settings() {

        $menu_page = add_menu_page('Universalist', 'Universalist', 'manage_options', 'universalist-settings', array($this, 'settings_page'), 'data:image/svg+xml;base64,'.base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="256" height="256" viewBox="0 0 256 256"><g transform="translate(-13337 -1157)"><rect width="256" height="256" transform="translate(13337 1157)" fill="#74b3e4"/><path d="M95.5,164.369a81.893,81.893,0,0,1,1.665,17.264l-16.406-.016A96.7,96.7,0,0,1,82.2,165.258a39.675,39.675,0,0,1,2.965-9.02L82.325,151.7,71.394,163.155l6.756,18.463-77.986.016c-.4-8.186-.568-18.815,4.43-25.728A25.012,25.012,0,0,1,13.19,149c4.567-2.326,9.216-4.1,14.009-6,4.477-1.778,8.722-3.482,13.054-5.554,8.992-4.3,16.661-11.033,20.562-20.358l.466-11.049a67.871,67.871,0,0,1-6.29-15.466C44.158,86.681,41.714,74.312,42.584,64.055c-6.321-.478-33.772-4.3-34.113-11.781-.149-3.3,4.813-5.244,7.453-6.376,10.394-3.693,20.628-5.326,31.6-6.956L50,22.36a60.778,60.778,0,0,1,2.51-10.637c3.074-8.311,10-11.695,18.607-11.7L106.6,0c8.1,0,15.083,2.495,18.6,10.183A44.739,44.739,0,0,1,128.36,21.7l2.573,17.217c11.331,1.574,22.822,3.4,33.373,7.759a15.292,15.292,0,0,1,4.43,3.027,3.407,3.407,0,0,1-.294,5.1,22.236,22.236,0,0,1-8.139,4.437,130.118,130.118,0,0,1-21.5,4.7c3.8,8.687,6.74,17.358,6.607,26.766a26.806,26.806,0,0,1-3.118,11.812c-5.483,9.161-14.671,13.16-25.273,14.6,3.756,9.5,9.212,15.028,18.459,19.489,8.988,4.336,18.494,7.1,27.878,11.5a37.334,37.334,0,0,1,8.064,5.06c7.939,7.441,7.159,18.231,7.038,28.458h-78.3l6.862-18.654L96.048,151.543l-3.278,4.9a43.88,43.88,0,0,1,2.722,7.923ZM126.5,38.382l-1.069-6.615A169.044,169.044,0,0,0,98.99,28.849c-14.71-.627-31.524.1-46.035,2.769L51.866,38.5c6.74-.846,12.878-1.347,19.434-1.688a348.286,348.286,0,0,1,55.2,1.571ZM93.89,58.29a14.245,14.245,0,0,1,10-8.636,14.722,14.722,0,0,1,17.515,8.808,7.724,7.724,0,0,0,2.229.125l-1.719-10.978a255.1,255.1,0,0,0-31-1.712A290.607,290.607,0,0,0,56.5,47.574l-1.68,11.017a10.513,10.513,0,0,0,2.1.016,13.787,13.787,0,0,1,7.641-8.142,14.64,14.64,0,0,1,19.567,7.723A9.352,9.352,0,0,1,93.89,58.29Zm-32.75,39.6c4.9,11.558,13.7,20.538,26.911,20.985a27.687,27.687,0,0,0,19.857-7.242,38.857,38.857,0,0,0,10.876-16.418c3.259-9.329,5.648-21.247,5.577-31.227l-2.041.02a14.564,14.564,0,0,1-9.678,13.5,15.265,15.265,0,0,1-17.127-4.723,15.622,15.622,0,0,1-3.027-10.868,5.389,5.389,0,0,0-6.415-.227l-.74,20.041c-.023.587.576.889,1.167.9l6.744.063c.96.008,1.492,1.332,1.547,2.005.047.6-.258,2.1-1.124,2.111l-10.978.125a3.32,3.32,0,0,1-2.1-3.388l.725-9.885c-3.051,2.663-6.451,4.61-10.5,4.708a14.351,14.351,0,0,1-14.656-14.3,3.334,3.334,0,0,0-1.935.121c-.11,10.2,2.988,24.447,6.909,33.7Zm48.291,27.243c3.838-4.038,3.862-7.982,3.552-13.344a39.9,39.9,0,0,1-9.361,7.39,27.505,27.505,0,0,1-9.423,3.235,30.323,30.323,0,0,1-28.916-10.931c-.564,6.235.008,10.332,4.414,14.671a52.613,52.613,0,0,0,7.782,5.847l11.891,7.238,10.246-6.278a47.37,47.37,0,0,0,9.819-7.829ZM85.7,141.947l-7.567-4.676A73.4,73.4,0,0,1,67.3,129.328a25.887,25.887,0,0,1-4.978-6.611,40.482,40.482,0,0,0,.9,12.772,161.766,161.766,0,0,0,6.764,22.927L85.7,141.943Zm22.845,16.207,3.584-10.888a104.16,104.16,0,0,0,3.5-16.058,27.874,27.874,0,0,0-.094-8.037,23.432,23.432,0,0,1-4.614,6.169c-5.906,5.346-11.19,8.491-18.016,12.7l15.643,16.113Z" transform="translate(13376.014 1194)" fill="#0f172b"/><path d="M217.734,239.565a1.809,1.809,0,0,1,1.081,1.809,1.717,1.717,0,0,1-1.649,1.406l-21.619.023a1.841,1.841,0,0,1-1.457-1.042c-.674-2.1,2.879-2.945,4.441-3.466a11.759,11.759,0,0,1,7.841-.047c4.379-1.316,7.093-.388,11.362,1.32Z" transform="translate(13257.983 1049.458)" fill="#0f172b"/><path d="M220.1,263.544l-10.618.071a2.176,2.176,0,0,1-2.236-1.531c-.223-.76.074-2.53,1.257-2.534l11.761-.039c.971,0,1.578,1.183,1.571,1.884s-.615,2.138-1.739,2.146Z" transform="translate(13249.972 1036.128)" fill="#0f172b"/></g></svg>'), 100);

        $submenu_page = add_submenu_page('universalist-settings', 'Universalist Settings',  'Universalist Settings', 'manage_options', 'universalist-settings', array($this, 'settings_page'));

        add_action("load-{$menu_page}", array($this, 'load_assets'));

    }

    public function load_assets()
    {

        $asset_file = include(plugin_dir_path(__FILE__) . 'build/settings.asset.php');

        wp_enqueue_script(
            'universalist_settings_scripts',
            plugin_dir_url(__FILE__) . 'build/settings.js',
            $asset_file['dependencies'], // Automatically includes wp-element, wp-dom, etc.
            $asset_file['version'],
            true
        );

        wp_localize_script('universalist_settings_scripts', 'distantjetUniversalistSettingsObj', array(
            'siteUrl' => site_url(),
            'ajaxurl'   => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('distantjet_universalist_settings'),
            'selected_language_primary' => $this->primary_lang,
            'selected_language_secondary' => $this->secondary_lang,
          
        ));


        // wp_enqueue_script(
        //     'universalist_settings_scripts',
        //     plugin_dir_url(__FILE__) . 'build/settings.js',
        //     array('wp-element'), // <--- ADD THIS DEPENDENCY
        //     '1.1.0',
        //     true
        // );
        // wp_enqueue_style('universalist_settings_styles', plugin_dir_url(__FILE__) . 'build/style-settings.css');
    }

    public function settings_page() {

        $univ_settings = universalist_settings();

        $univ_settings->get_page();
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