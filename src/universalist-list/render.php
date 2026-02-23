<?php
/**
 * Render template for the Universalist List block.
 * Uses the DistantJet_Universalist class properties for logic.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Access our globalized class instance
$univ = universalist();

// Determine selection once using pre-loaded class properties
$is_secondary   = ( $univ->current_lang === $univ->secondary_lang );
$lang_selection = $is_secondary ? 'secondary' : 'primary';

// Dynamically grab the correct attribute array
$items = $attributes["items_{$lang_selection}"] ?? [];

// Prepare the wrapper (get_block_wrapper_attributes handles escaping)
$wrapper_attributes = get_block_wrapper_attributes([ 
    'class' => 'distantjet-universalist-list--' . $lang_selection
]); 

// Render logic
if ( ! empty( $items ) ) : ?>
    <ul <?php echo $wrapper_attributes; ?>>
        <?php foreach ( $items as $item ) : ?>
            <li><?php echo esc_html( $item ); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>