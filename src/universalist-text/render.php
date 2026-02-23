<?php
/**
 * Render template for the Universalist Text block.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// 1. Access the plugin instance
$univ = universalist();

// 2. Determine which attribute key to use based on pre-loaded properties
$selection = ( $univ->current_lang === $univ->secondary_lang ) ? 'secondary' : 'primary';

// 3. Select content with a fallback to primary if secondary is missing
$display_text = $attributes["text_{$selection}"] ?? '';

if ( 'secondary' === $selection && empty( $display_text ) ) {
    $display_text = $attributes['text_primary'] ?? '';
}

// 4. Generate wrapper (escaped by default in WordPress core)
$wrapper_attributes = get_block_wrapper_attributes();

// 5. Render output
if ( ! empty( $display_text ) ) {
    printf(
        '<div %s>%s</div>',
        $wrapper_attributes,
        wp_kses_post( $display_text )
    );
}