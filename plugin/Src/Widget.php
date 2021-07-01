<?php
namespace NirjharLo\WP_Plugin_Framework\Src;

use League\Plates\Engine as Template;

use \WP_WIDGET as WP_WIDGET;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Widget class
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! class_exists( 'NirjharLo\\WP_Plugin_Framework\\Src\\Widget' ) ) {

	final class Widget extends WP_WIDGET {


		/**
		 * Add widget
		 *
		 * @return Void
		 */
		public function __construct() {

			$widget_ops = array(
							'classname' => 'plugin_widget',
							'description' => __( 'Plugin widget', 'textdomain' ),
							);
			parent::__construct( 'my_widget_id', __( 'Plugin widget', 'textdomain' ), $widget_ops );
		}


		/**
		 * Outputs the content of the widget for front end
		 *
		 * @param Array $args
		 * @param Array $instance
		 *
		 * @return Html
		 */
		public function widget( $args, $instance ) {

			$templates = new Template(PLUGIN_PATH . '/plugin/views/widget');
			echo $templates->render('content', [
				'before_widget' => $args['before_widget'],
				'before_title' => $args['before_title'],
				'title' => apply_filters( 'widget_title', $instance['title'] ),
				'after_title' => $args['after_title'],
				'after_widget' => $args['after_widget']
			]);
		}


		/**
		 * Outputs the options form on admin
		 *
		 * @param Array $instance
		 *
		 * @return Html
		 */
		public function form( $instance ) {

			$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Internal Link Master', 'textdomain' );

			$templates = new Template(PLUGIN_PATH . '/plugin/views/widget');
			echo $templates->render('form', [
				'field_name' => esc_attr( $this->get_field_id( 'title' ) ),
				'field_vale' => esc_attr( $title )
			]);
		}


		/**
		 * Processing widget options on save
		 *
		 * @param Array $new_instance
		 * @param Array $old_instance
		 *
		 * @return Array
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '' );
			return $instance;
		}
	}
} ?>
