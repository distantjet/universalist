<?php
/**
 * Render template for the Universalist List block.
 * Uses the DistantJet_Universalist class properties for logic.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Access our globalized class instance
$distantjet_universalist = universalist();

// Determine selection once using pre-loaded class properties
$distantjet_universalist_is_secondary   = ( $distantjet_universalist->current_lang === $distantjet_universalist->secondary_lang );
$distantjet_universalist_lang_selection = $distantjet_universalist_is_secondary ? 'secondary' : 'primary';

// Dynamically grab the correct attribute array
$distantjet_universalist_items = $attributes["items_{$distantjet_universalist_lang_selection}"] ?? [];

// Prepare the wrapper (get_block_wrapper_attributes handles escaping)
$distantjet_universalist_wrapper_attributes = get_block_wrapper_attributes([ 
    'class' => 'distantjet-universalist-list--' . $distantjet_universalist_lang_selection
]); 

// Render logic
if ( ! empty( $distantjet_universalist_items ) ) : ?>
    <ul <?php echo $distantjet_universalist_wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
        <?php foreach ( $distantjet_universalist_items as $distantjet_universalist_item ) : ?>
            <li><?php echo esc_html( $distantjet_universalist_item ); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>