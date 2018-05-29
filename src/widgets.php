<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Widget class
 */
if ( ! class_exists( 'PLUGIN_WIDGET' ) ) {

	final class PLUGIN_WIDGET extends WP_WIDGET {



		// Add basic actions
		public function __construct() {

			$widget_ops = array( 
							'classname' => 'plugin_widget',
							'description' => __( 'Plugin widget', 'textdomain' ),
							);
			parent::__construct( 'my_widget_id', __( 'Plugin widget', 'textdomain' ), $widget_ops );
		}



		//Outputs the content of the widget
		public function widget( $args, $instance ) {

			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			echo esc_html__( 'Hello, World!', 'textdomain' );
			echo $args['after_widget'];
		}



		//Outputs the options form on admin
		public function form( $instance ) {

			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Internal Link Master', 'textdomain' ); ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'textdomain' ); ?></label> 
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
		<?php 
		}



		//Processing widget options on save
		public function update( $new_instance, $old_instance ) {
			
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}
	}
} ?>