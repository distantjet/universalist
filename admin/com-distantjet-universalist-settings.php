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

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            try {

                if(!current_user_can('manage_options')) {

                    throw new Exception('Sorry, you do not have permission to perform that action');
                }

                $nonce = $_POST['nonce'];

                if(!wp_verify_nonce($nonce, 'distantjet_universalist_settings')) {

                    throw new Exception('You do not have permission to perform that action.');
                }

                if(isset($_POST['lang_selection_primary']) && trim($_POST['lang_selection_primary']) != '') {

                    update_option('distantjet_univ_option_lang_primary', sanitize_text_field($_POST['lang_selection_primary']));
                }
                
                if(isset($_POST['lang_selection_secondary']) && trim($_POST['lang_selection_secondary']) != '') {

                    update_option('distantjet_univ_option_lang_secondary', sanitize_text_field($_POST['lang_selection_secondary']));
                }

                wp_send_json_success( array( 'message' => __( 'Settings saved', 'distantjet-universalist' ) ) );
            }
            catch(Exception $ex) {

                wp_send_json_error(array(
                    'message' => __($ex->getMessage(), 'distantjet-universalist'),
                    'type'    => 'error'
                ), 400);

            }

            echo json_encode($data);
        }

        die;
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
