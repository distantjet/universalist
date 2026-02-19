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

$distantjet_universalist_wrapper_attributes = get_block_wrapper_attributes();

$distantjet_universalist_wrapper_attributes = get_block_wrapper_attributes([ 

    'class' => 'universalist-list-'.$lang

]); 

if (!empty($items)) {

    $distantjet_universalist_content = '';
    
    foreach ($items as $item) {
        $distantjet_universalist_content .= '<li>' . esc_html($item) . '</li>';
    }

    

    printf(
        '<ul %s>%s</ul>',
        wp_kses_post($distantjet_universalist_wrapper_attributes),
        wp_kses_post( $distantjet_universalist_content )
    );

}
// if (!empty($items)) {
//     echo '<ul class="universalist-list-'.$lang.'">';
//     foreach ($items as $item) {
//         echo '<li>' . esc_html($item) . '</li>';
//     }
//     echo '</ul>';
// }


