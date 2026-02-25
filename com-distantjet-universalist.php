<?php
/**
 * Plugin Name:       Universalist
 * Plugin URI:        https://distantjet.com/universalist
 * Description:       Create content for multiple languages
 * Version:           2.0.3
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


        register_activation_hook(__FILE__, 'distantjet_universalist_activation');

        function distantjet_universalist_activation() {

            add_option('distantjet_universalist_activated', plugin_basename(__FILE__));
        }

        add_action('admin_init', function() {

            if(is_admin() && get_option('distantjet_universalist_activated') == plugin_basename(__FILE__)) {

                delete_option('distantjet_universalist_activated');
                wp_redirect(get_admin_url(null, 'admin.php?page=universalist-settings'));
                exit;
            }
        });



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

        $menu_page = add_menu_page('Universalist', 'Universalist', 'manage_options', 'universalist-settings', array($this, 'settings_page'), 'data:image/svg+xml;base64,'.base64_encode('<svg id="a" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" y="0" width="20" height="20" rx="1.85" ry="1.85" fill="currentColor"/><path d="M10.74,20.31c.14.69.21,1.36.2,2.06h-1.96c0-.67.06-1.31.17-1.96.07-.38.2-.73.35-1.08l-.34-.54-1.31,1.37.81,2.2H-.64c-.05-.98-.07-2.25.53-3.07.28-.36.62-.62,1.03-.83.55-.28,1.1-.49,1.67-.72.53-.21,1.04-.42,1.56-.66,1.07-.51,1.99-1.32,2.46-2.43l.06-1.32c-.33-.59-.57-1.19-.75-1.85-1.29-.46-1.59-1.94-1.48-3.17-.75-.06-4.03-.51-4.07-1.41-.02-.39.57-.63.89-.76,1.24-.44,2.46-.64,3.77-.83l.3-1.98c.07-.44.16-.86.3-1.27.37-.99,1.19-1.4,2.22-1.4h4.24c.97,0,1.8.3,2.22,1.21.19.44.31.89.38,1.38l.31,2.06c1.35.19,2.73.41,3.99.93.2.1.39.21.53.36.18.19.16.43-.04.61-.27.25-.61.41-.97.53-.83.28-1.69.43-2.57.56.45,1.04.8,2.07.79,3.2-.03.5-.13.97-.37,1.41-.65,1.09-1.75,1.57-3.02,1.74.45,1.13,1.1,1.79,2.2,2.33s2.21.85,3.33,1.37c.35.16.66.36.96.6.95.89.86,2.18.84,3.4h-9.35s.82-2.23.82-2.23l-1.31-1.36-.39.59c.14.3.24.6.33.95ZM14.45,5.26l-.13-.79c-1.05-.21-2.09-.3-3.16-.35-1.76-.08-3.76.01-5.5.33l-.13.82c.8-.1,1.54-.16,2.32-.2,2.2-.09,4.41-.07,6.59.19ZM10.55,7.64c.23-.55.65-.9,1.19-1.03.86-.23,1.77.22,2.09,1.05.09.02.17.02.27.01l-.21-1.31c-1.24-.15-2.45-.2-3.7-.2-1.4,0-2.73.04-4.11.2l-.2,1.32c.09,0,.17.01.25,0,.16-.45.48-.78.91-.97.9-.4,1.95,0,2.34.92.39-.21.8-.21,1.17.01ZM6.64,12.37c.59,1.38,1.64,2.45,3.21,2.51.9.03,1.7-.27,2.37-.86.6-.53,1.03-1.19,1.3-1.96.39-1.11.67-2.54.67-3.73h-.24c-.03.74-.46,1.37-1.16,1.62-.74.26-1.56.05-2.05-.56-.29-.37-.39-.82-.36-1.3-.22-.17-.54-.19-.77-.03l-.09,2.39c0,.07.07.11.14.11h.81c.11,0,.18.17.18.25,0,.07-.03.25-.13.25l-1.31.02c-.16-.06-.26-.22-.25-.4l.09-1.18c-.36.32-.77.55-1.25.56-.97.02-1.74-.75-1.75-1.71-.09-.02-.17-.01-.23.01-.01,1.22.36,2.92.83,4.02ZM12.41,15.62c.46-.48.46-.95.42-1.59-.34.36-.7.64-1.12.88-.36.2-.72.32-1.13.39-1.31.22-2.62-.27-3.45-1.31-.07.74,0,1.23.53,1.75.29.26.59.49.93.7l1.42.86,1.22-.75c.43-.26.82-.56,1.17-.93ZM9.57,17.63l-.9-.56c-.46-.28-.89-.59-1.29-.95-.25-.23-.41-.48-.59-.79-.06.54,0,1.02.11,1.53.19.94.48,1.82.81,2.74l1.88-1.97ZM12.3,19.57l.43-1.3c.18-.64.34-1.26.42-1.92.04-.33.05-.64-.01-.96-.16.29-.32.53-.55.74-.71.64-1.34,1.01-2.15,1.52l1.87,1.92Z"/><path d="M6.64,12.37c-.47-1.11-.84-2.81-.83-4.02.06-.03.15-.03.23-.01.01.96.78,1.73,1.75,1.71.48-.01.89-.24,1.25-.56l-.09,1.18c-.01.18.09.34.25.4l1.31-.02c.1,0,.14-.18.13-.25,0-.08-.07-.24-.18-.24h-.81c-.07,0-.14-.04-.14-.11l.09-2.39c.22-.16.54-.15.77.03-.03.47.07.93.36,1.3.49.61,1.31.82,2.05.56.69-.24,1.13-.88,1.16-1.61h.24c0,1.19-.28,2.61-.67,3.73-.27.77-.7,1.43-1.3,1.96-.67.6-1.47.9-2.37.86-1.58-.05-2.63-1.13-3.21-2.51ZM11.25,12.03c-.51-.2-.83-.31-1.36-.16-.31-.11-.63-.1-.94,0-.19.06-.61.16-.53.41.02.05.1.12.17.12h2.58c.1,0,.19-.09.2-.17,0-.08-.04-.18-.13-.22ZM10.57,13.3c.13,0,.21-.17.21-.26s-.07-.23-.19-.23h-1.4c-.14,0-.18.22-.15.31.03.11.13.18.27.18h1.27Z" fill="currentColor"/><path d="M12.41,15.62c-.36.38-.74.67-1.17.93l-1.22.75-1.42-.86c-.34-.21-.64-.44-.93-.7-.53-.52-.59-1.01-.53-1.75.84,1.04,2.14,1.53,3.45,1.31.41-.07.77-.18,1.13-.39.42-.24.78-.52,1.12-.88.04.64.03,1.11-.42,1.59Z" fill="currentColor"/><path d="M14.45,5.26c-2.19-.26-4.39-.28-6.59-.19-.78.04-1.52.1-2.32.2l.13-.82c1.73-.32,3.74-.41,5.5-.33,1.07.05,2.1.14,3.16.35l.13.79Z" fill="currentColor"/><path d="M10.55,7.64c-.36-.22-.78-.23-1.17-.01-.38-.92-1.44-1.33-2.34-.92-.43.19-.75.52-.91.97-.08,0-.16,0-.25,0l.2-1.32c1.38-.16,2.72-.2,4.11-.2,1.25,0,2.47.05,3.7.2l.21,1.31c-.1,0-.18,0-.27-.01-.32-.83-1.23-1.28-2.09-1.05-.54.13-.97.48-1.19,1.03Z" fill="currentColor"/><path d="M9.57,17.63l-1.88,1.97c-.33-.91-.62-1.8-.81-2.74-.1-.51-.17-.99-.11-1.53.18.31.34.56.59.79.4.36.83.66,1.29.95l.9.56Z" fill="currentColor"/><path d="M12.3,19.57l-1.87-1.92c.82-.5,1.45-.88,2.15-1.52.23-.21.39-.45.55-.74.06.32.05.63.01.96-.08.66-.24,1.28-.42,1.92l-.43,1.3Z" fill="currentColor"/><path d="M11.25,12.03c.09.04.14.13.13.22s-.1.17-.2.17h-2.58c-.07,0-.16-.07-.17-.12-.08-.25.34-.35.53-.41.31-.1.62-.12.94,0,.52-.16.85-.05,1.36.16Z"/><path d="M10.57,13.3h-1.27c-.14,0-.23-.06-.27-.17-.03-.09,0-.3.15-.3h1.4c.12,0,.19.14.19.22s-.07.26-.21.26Z"/></svg>'), 100);

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

        wp_enqueue_style('universalist_settings_styles', plugin_dir_url(__FILE__).'build/style-settings.css');
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