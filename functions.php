<?php


if(class_exists('noleam/NoleamPlugin')) {

//    NoleamTheme::instance()->init([
//            'styles' => [
//                [
//                    'handle' => 'choices',
//                    'file' => 'vendor/choices.min.css'
//                ],
//                [
//                    'handle' => 'cd2024',
//                    'file' => 'style.css', 'deps' => [
//                    'choices',
//                ]
//                ],
//            ],
//
//            'scripts' => [
//                'noleam' => [
//                    'type' => 'module',
//                    'handle' => 'noleam',
//                    'file' => 'noleam.js',
//                    'deps' => ['choices']
//                ],
//                'choices' => [
//                    'handle' => 'choices',
//                    'file' => 'vendor/choices/choices.min.js',
//                ],
//            ],
//            ,
//            'add_editor_style' => ['style.css'],
//        ]
//    );
}

/**
 *  Some theme features
 */
foreach([
    'editor-styles', 'wp-block-styles', 'align-wide', 'block-template-parts', 'block-template'
        ] as $feature) {
    add_theme_support($feature);
}

/**
 * Allow common smartphone audio formats in the media library.
 * WordPress groups M4A with MP3 by default, so keep the formats separate
 * to report M4A with its audio/mp4 MIME type.
 */
function cd2024_add_smartphone_audio_mime_types( $mimes ) {
    if ( isset( $mimes['mp3|m4a|m4b'] ) ) {
        $mimes['mp3|m4b'] = $mimes['mp3|m4a|m4b'];
        unset( $mimes['mp3|m4a|m4b'] );
    }

    $mimes['m4a'] = 'audio/mp4';
    $mimes['amr'] = 'audio/amr';
    $mimes['caf'] = 'audio/x-caf';

    return $mimes;
}
add_filter( 'upload_mimes', 'cd2024_add_smartphone_audio_mime_types' );

/**
 * Apply WordPress's global button styles to button controls rendered by blocks.
 * MailPoet and Forminator keep their dedicated CSS below.
 */
function cd2024_extend_wordpress_button_style_selectors( $nodes ) {
    $selectors = array(
        '.wp-site-blocks button:not([class*="mailpoet"]):not([class*="forminator"])',
        '.wp-site-blocks input[type="button"]:not([class*="mailpoet"]):not([class*="forminator"])',
        '.wp-site-blocks input[type="submit"]:not([class*="mailpoet"]):not([class*="forminator"]):not(.mailpoet_form input[type="submit"])',
        '.wp-site-blocks input[type="reset"]:not([class*="mailpoet"]):not([class*="forminator"])',
        '.wp-site-blocks input[type="image"]:not([class*="mailpoet"]):not([class*="forminator"])',
        '.wp-site-blocks [role="button"]:not([class*="mailpoet"]):not([class*="forminator"])',
        '.wp-site-blocks .button:not([class*="mailpoet"]):not([class*="forminator"])',
        '.wp-site-blocks a[class*="button"]:not([class*="mailpoet"]):not([class*="forminator"])',
        '.wp-site-blocks .woocommerce a.button:not([class*="mailpoet"]):not([class*="forminator"])',
        '.wp-site-blocks .woocommerce button.button:not([class*="mailpoet"]):not([class*="forminator"])',
    );

    $button_states = array( ':hover', ':focus', ':focus-visible', ':active', ':visited', ':link', ':any-link' );

    foreach ( $nodes as &$node ) {
        if ( ! isset( $node['path'], $node['selector'] ) || array( 'styles', 'elements', 'button' ) !== $node['path'] ) {
            continue;
        }

        $state = '';
        foreach ( $button_states as $button_state ) {
            if ( substr( $node['selector'], -strlen( $button_state ) ) === $button_state ) {
                $state = $button_state;
                break;
            }
        }

        $state_selectors = array_map(
            static function ( $selector ) use ( $state ) {
                return $selector . $state;
            },
            $selectors
        );

        $node['selector'] .= ', ' . implode( ', ', $state_selectors );
    }
    unset( $node );

    return $nodes;
}
add_filter( 'wp_theme_json_get_style_nodes', 'cd2024_extend_wordpress_button_style_selectors' );

function cd2024_add_wordpress_button_class( $block_content ) {
    if ( ! class_exists( 'WP_HTML_Tag_Processor' ) || ! is_string( $block_content ) || '' === $block_content ) {
        return $block_content;
    }

    $processor = new WP_HTML_Tag_Processor( $block_content );

    while ( $processor->next_tag() ) {
        $tag          = $processor->get_tag();
        $class_string = (string) $processor->get_attribute( 'class' );
        $classes      = preg_split( '/\\s+/', trim( $class_string ), -1, PREG_SPLIT_NO_EMPTY );

        // Keep the plugin buttons on their dedicated styles.
        $is_plugin_button = false;
        $has_button_class = false;

        foreach ( $classes as $class_name ) {
            if ( preg_match( '/^(mailpoet|forminator)(?:[-_]|$)/i', $class_name ) ) {
                $is_plugin_button = true;
            }

            if ( preg_match( '/(^|[-_])button($|[-_])/', $class_name ) ) {
                $has_button_class = true;
            }
        }

        if ( $is_plugin_button || in_array( 'wp-element-button', $classes, true ) ) {
            continue;
        }

        $type = strtolower( (string) $processor->get_attribute( 'type' ) );
        $role = strtolower( (string) $processor->get_attribute( 'role' ) );

        $is_button = 'BUTTON' === $tag
            || ( 'INPUT' === $tag && in_array( $type, array( 'button', 'submit', 'reset', 'image' ), true ) )
            || 'button' === $role
            || ( 'A' === $tag && $has_button_class );

        if ( $is_button ) {
            $processor->add_class( 'wp-element-button' );
        }
    }

    return $processor->get_updated_html();
}
add_filter( 'render_block', 'cd2024_add_wordpress_button_class', 10, 1 );

/**
 * Some styles and scripts
 */

function enqueueScriptAndStyle() {
    wp_enqueue_style(  );

}
function enqueueAll() {
    wp_enqueue_style('cd2024', get_stylesheet_directory_uri(). '/assets/css/style.css' );
  //  wp_enqueue_script( 'testimonial',  get_stylesheet_directory_uri() . '/template-parts/blocks/testimonial/testimonial.js' );
}
add_action( 'enqueue_block_assets', 'enqueueAll' );

/**
 * No remote block patterns
 */
add_filter( 'should_load_remote_block_patterns', '__return_false' );

/**
 * Add a specific ptter directory
 */
add_action( 'init', function () {
    register_block_pattern_category( 'cd2024', array(
        'label'       => __( 'Christine Deloupy', 'cd2024' ),
        'description' => __( 'Compositions pour Chritine Deloupy', 'themeslug' )
    ));
});
