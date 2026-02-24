<?php
/**
 * Render template for the Universalist Heading block.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$distantjet_universalist = universalist();

// 1. Determine the language selection ('primary' or 'secondary')
$distantjet_universalist_selection = ( $distantjet_universalist->current_lang === $distantjet_universalist->secondary_lang ) ? 'secondary' : 'primary';

// 2. Grab the content based on selection, fallback to primary if secondary is empty
$distantjet_universalist_display_heading = $attributes["heading_{$distantjet_universalist_selection}"] ?? '';

// Fallback logic: If secondary is chosen but empty, show primary
if ( 'secondary' === $distantjet_universalist_selection && empty( $distantjet_universalist_display_heading ) ) {
    $distantjet_universalist_display_heading = $attributes['heading_primary'] ?? '';
}

// 3. Setup HTML tag and wrapper attributes
$distantjet_universalist_tag                = $attributes['heading_style'] ?? 'h2';
$distantjet_universalist_wrapper_attributes = get_block_wrapper_attributes();

// 4. Output
// if ( ! empty( $distantjet_universalist_display_heading ) ) {
//     printf(
//         '<%1$s %2$s>%3$s</%1$s>',
//         esc_attr( $distantjet_universalist_tag ),
//        $distantjet_universalist_wrapper_attributes,
//         esc_html( $distantjet_universalist_display_heading )
//     );
// }

printf(
    '<%1$s %2$s>%3$s</%1$s>',
    esc_attr( $distantjet_universalist_tag ),
    $distantjet_universalist_wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    esc_html( $distantjet_universalist_display_heading )
);