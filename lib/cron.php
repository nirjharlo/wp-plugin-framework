<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *
 * Add Cron schedules and cron task callback
 *
 */
if ( ! class_exists( 'PLUGIN_CRON' ) ) {

	final class PLUGIN_CRON {

		public function __construct() {

			//Add cron schedules
			add_filter('cron_schedules', array( $this, 'cron_schedules' ) );

			//Add cron callbacks

		}

		public function cron_schedules( $schedules ) {

			$prefix = 'prefix_'; // Avoid conflict with other crons. Example Reference: cron_30_mins

			// Example schedule options
			$schedule_options = array(
						'24_hrs' => array(
								'display' => '24 hours',
								'interval' => 86400
								),
						'48_hrs' => array(
								'display' => '48 hours',
								'interval' => 172800
								),
						'72_hrs' => array(
								'display' => '72 hours',
								'interval' => 259200
								)
						);

			/* Add each custom schedule into the cron job system. */
			foreach($schedule_options as $schedule_key => $schedule){

				if(!isset($schedules[$prefix.$schedule_key])) {

					$schedules[$prefix.$schedule_key] = array(
									'interval' => $schedule['interval'],
									'display' => __('Every '.$schedule['display'])
									);
				}
			}

			return $schedules;
		}

		//Called in autoload.php
		public function schedule_task($task) {

			if( ! $task ) {
				return false;
			}

			$required_keys = array(
						'timestamp',
						'recurrence',
						'hook'
					);
			$missing_keys = array();
			foreach( $required_keys as $key ){
				if( ! array_key_exists( $key, $task ) ) {
					$missing_keys[] = $key;
				}
			}

			if( ! empty( $missing_keys ) ){
				return false;
			}

			if( wp_next_scheduled( $task['hook'] ) ){
				wp_clear_scheduled_hook($task['hook']);
			}

			wp_schedule_event($task['timestamp'], $task['recurrence'], $task['hook']);
			return true;
		}
	}
} ?>
