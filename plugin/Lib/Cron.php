<?php
namespace NirjharLo\WP_Plugin_Framework\Lib;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Cron schedules and cron task callback
 *
 * $cron = new \Cron();
 * $cron->schedule_task(
 * array(
 * 'timestamp' => current_time('timestamp'),
 * 'recurrence' => 'schedule',
 * 'hook' => 'custom_cron_hook'
 * ));
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! class_exists( 'NirjharLo\\WP_Plugin_Framework\\Lib\\Cron' ) ) {

	final class Cron {


		/**
		 * Add corn to wp scheduler
		 *
		 * @return Void
		 */
		public function __construct() {

			// Add cron schedules
			add_filter( 'cron_schedules', array( $this, 'cron_schedules' ) );
		}


		/**
		 * Set up the schedules
		 *
		 * @return Void
		 */
		public function cron_schedules( $schedules ) {

			$prefix = 'prefix_'; // Avoid conflict with other crons. Example Reference: cron_30_mins

			// Example schedule options
			$schedule_options = array(
				'24_hrs' => array(
					'display'  => '24 hours',
					'interval' => 86400,
				),
				'48_hrs' => array(
					'display'  => '48 hours',
					'interval' => 172800,
				),
				'72_hrs' => array(
					'display'  => '72 hours',
					'interval' => 259200,
				),
			);

			/* Add each custom schedule into the cron job system. */
			foreach ( $schedule_options as $schedule_key => $schedule ) {

				if ( ! isset( $schedules[ $prefix . $schedule_key ] ) ) {

					$schedules[ $prefix . $schedule_key ] = array(
						'interval' => $schedule['interval'],
						'display'  => __( 'Every ' . $schedule['display'] ),
					);
				}
			}

			return $schedules;
		}


		/**
		 * Schedule the task based on added schedules
		 *
		 * @return Bool
		 */
		public function schedule_task( $task ) {

			if ( ! $task ) {
				return false;
			}

			$required_keys = array(
				'timestamp',
				'recurrence',
				'hook',
			);
			$missing_keys  = array();
			foreach ( $required_keys as $key ) {
				if ( ! array_key_exists( $key, $task ) ) {
					$missing_keys[] = $key;
				}
			}

			if ( ! empty( $missing_keys ) ) {
				return false;
			}

			if ( wp_next_scheduled( $task['hook'] ) ) {
				wp_clear_scheduled_hook( $task['hook'] );
			}

			wp_schedule_event( $task['timestamp'], $task['recurrence'], $task['hook'] );
			return true;
		}
	}
}
