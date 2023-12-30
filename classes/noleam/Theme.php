<?php

namespace noleam;
const NOLEAM = 'noleam';
const VENDOR = 'vendor';

define('noleam\THEME_URI', get_stylesheet_directory_uri() . '/');
define('noleam\THEME_PATH', get_stylesheet_directory() . '/');

const ASSETS = 'assets/';
define("noleam\ASSETS_URL", sprintf('%s/%s', THEME_URI, ASSETS));
define("noleam\ASSETS_PATH", sprintf('%s/%s', THEME_PATH, ASSETS));
define("noleam\PATTERNS_PATH", sprintf('%spatterns/', THEME_PATH));
define("noleam\CLASSES", sprintf('%sclasses/', THEME_PATH));


class Theme {

    private static ?Theme $instance = null;
    private static array $config = [];

    private function __construct() {
    }

    /**
     * It's a Singleton , this method is used to retrieve the instance
     *
     * @param null|string $configuration
     *
     * @return \noleam\Theme
     * @access  public
     *
     * @since   1.0
     */
    public static function instance(): Theme {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Autoloader
     */
    private function autoloadRegister($class): void {
        
        Debug::print($class);

        if (!str_contains($class, NOLEAM) && !str_contains($class, VENDOR)) {
            return;
        }
        $class = str_replace('\\', '/', $class);

        require_once CLASSES . $class . '.php';
    }


    public function init($config) {

        self::$config = $config;

        spl_autoload_register([$this, 'autoloadRegister']);
        add_filter('script_loader_tag', [$this, 'setType'], 10, 3);


        /***************************
         * Enqueue styles in frontend
         */
        add_action('wp_enqueue_scripts', function () use ($config) {
            foreach ($config['styles'] ?? [] as $style) {
                wp_enqueue_style($style['handle'],
                    sprintf('%s%s/%s', ASSETS_URL, 'css', $style['file']), $style['deps'] ?? [],
                    $style['version'] ?? wp_get_theme()->get('Version')
                );
            }
        });

        /***************************
         * Enqueue styles in backend
         */
        add_action('enqueue_block_editor_assets', function () use ($config) {
            foreach ($config['styles'] ?? [] as $style) {
                $style['deps'] = [...  ['wp-blocks', 'wp-element', 'wp-i18n']];
                wp_enqueue_style($style['handle'],
                    sprintf('%s%s/%s', ASSETS_URL, 'css', $style['file']), $style['deps'] ?? [],
                    $style['version'] ?? wp_get_theme()->get('Version')
                );
            }
        });

        /***************************
         * Enqueue scripts
         */

        add_action('wp_enqueue_scripts', function () use ($config) {
            foreach ($config['scripts'] ?? [] as $script) {
                wp_enqueue_script($script['handle'],
                    sprintf('%s%s/%s', ASSETS_URL, 'js', $script['file']), $script['deps'] ?? [],
                    $script['version'] ?? wp_get_theme()->get('Version'),
                    $script['in-footer'] ?? true,
                );
            }
        });

        /***************************
         * After Setup Theme
         */
        add_action('after_setup_theme', function () use ($config) {
            // Theme supports
            foreach ($config['add_theme_support'] ?? [] as $feature) {
                add_theme_support($feature);
            }

            // Custom editor styles
            foreach ($config['add_editor_style'] ?? [] as $style) {
                add_editor_style(sprintf('%scss/%s', ASSETS, $style));
            }
        });

        /***************************
         * Add patterns
         */

        add_action('init', function () {
            register_block_pattern_category(
                NOLEAM,
                array('label' => __(NOLEAM, NOLEAM))
            );
        });

        /*******************************
         * Woo Commerce
         */
        add_filter('woocommerce_output_related_products_args', function ($args) {
            $args['posts_per_page'] = 3; // 3 related products
            return $args;
        }, 20);


    }

    public function setType($tag, $handle, $src): string {
        if ((self::$config['styles']['type'] ?? 'script') === 'script') {
            return $tag;
        }

        // change the script tag by adding type="module" and return it.
        return '<script type="module" src="' . esc_url($src) . '"></script>';
    }
}