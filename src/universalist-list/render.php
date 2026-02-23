<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$lang = function_exists( 'distantjet_universalist_detect_lang' )
    ? distantjet_universalist_detect_lang()
    : 'en-US';    

$lang_secondary = get_option('distantjet_univ_option_lang_secondary');
$lang_primary = get_option('distantjet_univ_option_lang_primary');

$items = $lang === $lang_secondary
    ? ($attributes['items_secondary'] ?? [])
    : ($attributes['items_primary'] ?? []);

$lang_selection = $lang == $lang_secondary ? 'secondary' : 'primary';


$distantjet_universalist_wrapper_attributes = get_block_wrapper_attributes([ 

    'class' => 'distantjet-universalist-list--'.$lang_selection

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


