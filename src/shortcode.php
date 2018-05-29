<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode class for rendering in front end
 */
if ( ! class_exists( 'PLUGIN_SHORTCODE' ) ) {

	class PLUGIN_SHORTCODE {



		public function __construct() {

			add_shortcode( 'shortcode_name', array( $this, 'cb' ) );
		}



		public function cb($atts) {

			$data = shortcode_atts( array(
										'type' => 'zip',
									), $atts );

			return $this->html();
		}



		/**
		 * Shortcode Display
		 */
		public function html() { ?>

			<div class="class">
				Some text.
			</div>
		<?php
		}
	}
} ?>