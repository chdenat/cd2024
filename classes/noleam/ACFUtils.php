<?php
	
	namespace noleam;

	
	class ACFUtils {
		
		private static ?ACFUtils $instance = null;
		
		private function __construct() {}

        /**
         * It's a Singleton , this method is used to retrieve the instance
         *
         * @return ACFUtils
         * @access  public
         *
         * @since   1.0
         */
		public static function instance()
		: ACFUtils {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}

		
		public function init( ) {

            /**
             * Save json in assets/jsons directory
             */
            add_filter('acf/settings/save_json', function ( $path ) {

                // update path
                $path =  ASSETS_PATH.'/jsons';

                // return
                return $path;

            });

            /**
             * Read json from assets/jsons directory
             */
            add_filter('acf/settings/load_json', function( $paths ) {

                // remove original path (optional)
                unset($paths[0]);

                // append path
                $paths[] = ASSETS_PATH.'/jsons';

                // return
                return $paths;

            });

            /**
             * Register blocks
             */

            add_action( 'init', function () {
                register_block_type( PATTERNS_PATH . '/blocks/title-or-text' );
            });
		}
	}
