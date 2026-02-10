<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$distantjet_universalist_lang = function_exists( 'distantjet_universalist_detect_lang' ) ? distantjet_universalist_detect_lang() : 'en';

$distantjet_universalist_dom = new DOMDocument();
libxml_use_internal_errors(true);
// Use UTF-8 to prevent character encoding issues with Spanish
$distantjet_universalist_dom->loadHTML('<?xml encoding="utf-8" ?>' . $content);
libxml_clear_errors();

$distantjet_universalist_finder = new DomXPath($distantjet_universalist_dom);

// Target the specific list class defined in edit.js
$distantjet_universalist_targetClass = ($distantjet_universalist_lang === 'es') ? 'universalist-list-es' : 'universalist-list-en';
$distantjet_universalist_nodes = $distantjet_universalist_finder->query("//ul[contains(@class, '$distantjet_universalist_targetClass')]");

echo '<div class="universalist-container">';
if ($distantjet_universalist_nodes->length > 0) {
    // This outputs the ENTIRE <ul> including all <li> children and their classes
    echo wp_kses_post($distantjet_universalist_dom->saveHTML($distantjet_universalist_nodes->item(0)));
} else {
    // Fallback: show the first <ul> found if classes aren't matched
    $distantjet_universalist_allLists = $distantjet_universalist_dom->getElementsByTagName('ul');
    if ($distantjet_universalist_allLists->length > 0) {
        echo wp_kses_post($distantjet_universalist_dom->saveHTML($distantjet_universalist_allLists->item(0)));
    }
}
echo '</div>';