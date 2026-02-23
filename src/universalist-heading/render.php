<?php
/**
 * Render template for the Universalist Heading block.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$univ = universalist();

// 1. Determine the language selection ('primary' or 'secondary')
$selection = ( $univ->current_lang === $univ->secondary_lang ) ? 'secondary' : 'primary';

// 2. Grab the content based on selection, fallback to primary if secondary is empty
$display_heading = $attributes["heading_{$selection}"] ?? '';

// Fallback logic: If secondary is chosen but empty, show primary
if ( 'secondary' === $selection && empty( $display_heading ) ) {
    $display_heading = $attributes['heading_primary'] ?? '';
}

// 3. Setup HTML tag and wrapper attributes
$tag                = $attributes['heading_style'] ?? 'h2';
$wrapper_attributes = get_block_wrapper_attributes();

// 4. Output
if ( ! empty( $display_heading ) ) {
    printf(
        '<%1$s %2$s>%3$s</%1$s>',
        esc_attr( $tag ),
        $wrapper_attributes,
        esc_html( $display_heading )
    );
}