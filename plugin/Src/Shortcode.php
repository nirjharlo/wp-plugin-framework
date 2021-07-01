<?php
namespace NirjharLo\WP_Plugin_Framework\Src;

use League\Plates\Engine as Template;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode class for rendering in front end
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! class_exists( 'NirjharLo\\WP_Plugin_Framework\\Src\\Shortcode' ) ) {

	class Shortcode {

		/**
		 * Add Shortcode
		 *
		 * @return Void
		 */
		public function __construct() {

			add_shortcode( 'shortcode_name', array( $this, 'cb' ) );
		}

	   /**
	    * Shortcode callback
		*
		* @param Array $atts
		*
		* @return Html
		*/
		public function cb($atts) {

			$data = shortcode_atts( array(
										'type' => 'zip',
									), $atts );

			$this->html($data);
		}


		/**
		 * Shortcode Display
		 *
		 * @return Html
		 */
		private function html($data) {

			$templates = new Template(PLUGIN_PATH . '/plugin/views');
			echo $templates->render('shortcode', [
				'data' => $data
			]);
		}
	}
} ?>
