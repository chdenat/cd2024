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