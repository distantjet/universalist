<?php

if(!defined('ABSPATH')) die;

class DistantJet_Universalist_Settings {

    private static $instance = null;

    public static function get_instance() {

        if(null === self::$instance) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    function get_page() {

        ?>

            <div class="wrap">
                
            </div>
        
        <?php
    }
}

function universalist_settings() {

    return DistantJet_Universalist_Settings::get_instance();
}

universalist_settings();