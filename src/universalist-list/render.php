<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$lang = function_exists( 'distantjet_universalist_detect_lang' )
    ? distantjet_universalist_detect_lang()
    : 'en';

$items = $lang === 'es'
    ? ($attributes['items_es'] ?? [])
    : ($attributes['items_en'] ?? []);

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


