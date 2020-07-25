<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode class for rendering in front end
 *
 * @author     Nirjhar Lo
 * @version    1.2.1
 * @package    wp-plugin-framework
 */
if ( ! class_exists( 'PLUGIN_SHORTCODE' ) ) {

	class PLUGIN_SHORTCODE {

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

			return $this->html();
		}


		/**
		 * Shortcode Display
		 *
		 * @return Html
		 */
		public function html() { ?>

			<div class="class">
				Some text.
			</div>
		<?php
		}
	}
} ?>
