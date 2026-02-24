<?php
/**
 * Render template for the Universalist Text block.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// 1. Access the plugin instance
$distantjet_universalist = universalist();

// 2. Determine which attribute key to use based on pre-loaded properties
$distantjet_universalist_selection = ( $distantjet_universalist->current_lang === $distantjet_universalist->secondary_lang ) ? 'secondary' : 'primary';

// 3. Select content with a fallback to primary if secondary is missing
$distantjet_universalist_display_text = $attributes["text_{$distantjet_universalist_selection}"] ?? '';

if ( 'secondary' === $distantjet_universalist_selection && empty( $distantjet_universalist_display_text ) ) {
    $distantjet_universalist_display_text = $attributes['text_primary'] ?? '';
}

// 4. Generate wrapper (escaped by default in WordPress core)
$distantjet_universalist_wrapper_attributes = get_block_wrapper_attributes();

// 5. Render output
if ( ! empty( $distantjet_universalist_display_text ) ) {
    printf(
        '<div %s>%s</div>',
        $distantjet_universalist_wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        wp_kses_post( $distantjet_universalist_display_text )
    );
}