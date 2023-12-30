<?php

use noleam\Theme;

if(class_exists('noleam/NoleamPlugin')) {

    Theme::instance()->init([
            'styles' => [
                [
                    'handle' => 'choices',
                    'file' => 'vendor/choices.min.css'],
                [
                    'handle' => 'cd20242023',
                    'file' => 'style.css', 'deps' => [
                    'choices',
                ]
                ],
            ],

            'scripts' => [
                'noleam' => [
                    'type' => 'module',
                    'handle' => 'noleam',
                    'file' => 'noleam.js',
                    'deps' => ['choices']
                ],
                'choices' => [
                    'handle' => 'choices',
                    'file' => 'vendor/choices/choices.min.js',
                ],
            ],
            'add_theme_support' => ['editor-styles', 'wp-block-styles', 'align-wide', 'block-template-parts', 'block-template'],
            'add_editor_style' => ['style.css'],
        ]
    );
}

add_filter( 'should_load_remote_block_patterns', '__return_false' );

add_action( 'init', 'themeslug_register_pattern_categories' );

function themeslug_register_pattern_categories() {
    register_block_pattern_category( 'cd2024', array(
        'label'       => __( 'Chrisistine Deloupy', 'cd2024' ),
        'description' => __( 'Compositions pour Chritine Deloupy', 'themeslug' )
    ) );
}