<?php

if (!defined('ABSPATH')) die;

class DistantJet_Universalist_Settings
{

    private static $instance = null;

    public static function get_instance()
    {

        if (null === self::$instance) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    public function save_settings() {

        $data['error'] = null;

       $request_method = filter_input( INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS );

        if('POST' === $request_method) {

            try {

                if(!current_user_can('manage_options')) {

                    throw new Exception('You do not have sufficient permissions to access this page.');
                }

                // 1. Unslash the nonce before verification
                $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

                if(!wp_verify_nonce($nonce, 'distantjet_universalist_settings')) {

                    throw new Exception('You do not have sufficient permissions to access this page.');
                }

                // 2. Unslash before sanitizing for options
                if ( isset( $_POST['lang_selection_primary'] ) ) {

                    $primary_lang = sanitize_text_field( wp_unslash( $_POST['lang_selection_primary'] ) );
                }

                if (isset($_POST['lang_selection_secondary'])) {

                    $secondary_lang = sanitize_text_field(wp_unslash($_POST['lang_selection_secondary']));
                }

                if($primary_lang === $secondary_lang) {

                    throw new Exception('Please select two different languages for your primary and secondary settings.');
                }

                if ('' !== trim($primary_lang)) {

                    update_option('distantjet_univ_option_lang_primary', $primary_lang);
                }

                if ('' !== trim($secondary_lang)) {

                    update_option('distantjet_univ_option_lang_secondary', $secondary_lang);
                }

                wp_send_json_success( array( 'message' => __( 'Settings saved', 'com-distantjet-universalist' ) ) );
            }
            catch(Exception $ex) {

                wp_send_json_error(array(
                    'message' => $ex->getMessage(),
                    'type'    => 'error'
                ), 400);
            }
        }

        wp_die();
    }

    public function get_page()
    {
        ?>

            <div class="wrap" id="distantjetUniversalistSettingsApp"></div>

        <?php
    }
}

function universalist_settings()
{

    return DistantJet_Universalist_Settings::get_instance();
}

universalist_settings();
