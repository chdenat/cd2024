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