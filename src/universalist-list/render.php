<?php
// if ( ! defined( 'ABSPATH' ) ) exit;

// $distantjet_universalist_lang = function_exists( 'distantjet_universalist_detect_lang' ) ? distantjet_universalist_detect_lang() : 'en';

// if($distantjet_universalist_lang == 'es') {

//     echo $attributes['items_es'][0];
// }
// else {

    
//     echo $attributes['items_en'][0];
// }



if ( ! defined( 'ABSPATH' ) ) exit;

$lang = function_exists( 'distantjet_universalist_detect_lang' )
    ? distantjet_universalist_detect_lang()
    : 'en';

$items = $lang === 'es'
    ? ($attributes['items_es'] ?? [])
    : ($attributes['items_en'] ?? []);


if (!empty($items)) {
    echo '<ul class="universalist-list-'.$lang.'">';
    foreach ($items as $item) {
        echo '<li>' . esc_html($item) . '</li>';
    }
    echo '</ul>';
}

// if (!empty($items)) {
//     echo '<ul class="universalist-list">';
//     foreach ($items as $item) {
//         echo '<li>' . esc_html($item) . '</li>';
//     }
//     echo '</ul>';
// }

