<?php

if (!defined('ABSPATH')) die;

class DistantJet_Universalist_Settings
{

    private static $instance = null;

    private $languages = [
        ['locale' => 'en', 'name' => 'English'],
        ['locale' => 'es', 'name' => 'Spanish'],
        ['locale' => 'fr', 'name' => 'French'],
        ['locale' => 'pt', 'name' => 'Portuguese'],
        ['locale' => 'de', 'name' => 'German'],
        ['locale' => 'it', 'name' => 'Italian'],
        ['locale' => 'nl', 'name' => 'Dutch'],
        ['locale' => 'zh', 'name' => 'Chinese'],
        ['locale' => 'ja', 'name' => 'Japanese'],
        ['locale' => 'ko', 'name' => 'Korean'],
        ['locale' => 'ru', 'name' => 'Russian'],
        ['locale' => 'ar', 'name' => 'Arabic'],
        ['locale' => 'hi', 'name' => 'Hindi'],
        ['locale' => 'bn', 'name' => 'Bengali'],
        ['locale' => 'ur', 'name' => 'Urdu'],
        ['locale' => 'fa', 'name' => 'Persian'],
        ['locale' => 'el', 'name' => 'Greek'],
        ['locale' => 'he', 'name' => 'Hebrew'],
        ['locale' => 'tr', 'name' => 'Turkish'],
        ['locale' => 'id', 'name' => 'Indonesian'],
        ['locale' => 'ms', 'name' => 'Malay'],
        ['locale' => 'sv', 'name' => 'Swedish'],
        ['locale' => 'nb', 'name' => 'Norwegian (Bokmål)'],
        ['locale' => 'nn', 'name' => 'Norwegian (Nynorsk)'],
        ['locale' => 'da', 'name' => 'Danish'],
        ['locale' => 'pl', 'name' => 'Polish'],
        ['locale' => 'cs', 'name' => 'Czech'],
        ['locale' => 'sk', 'name' => 'Slovak'],
        ['locale' => 'hu', 'name' => 'Hungarian'],
        ['locale' => 'ro', 'name' => 'Romanian'],
        ['locale' => 'uk', 'name' => 'Ukrainian'],
        ['locale' => 'bg', 'name' => 'Bulgarian'],
        ['locale' => 'sr', 'name' => 'Serbian'],
        ['locale' => 'hr', 'name' => 'Croatian'],
        ['locale' => 'bs', 'name' => 'Bosnian'],
        ['locale' => 'sl', 'name' => 'Slovenian'],
        ['locale' => 'lt', 'name' => 'Lithuanian'],
        ['locale' => 'lv', 'name' => 'Latvian'],
        ['locale' => 'et', 'name' => 'Estonian'],
    ];




    public static function get_instance()
    {

        if (null === self::$instance) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    function get_page()
    {

        ?>

            <div id="distantjetUniversalistSettingsApp"></div>

        <?php
    }
}

function universalist_settings()
{

    return DistantJet_Universalist_Settings::get_instance();
}

universalist_settings();
