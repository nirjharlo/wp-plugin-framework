<?php
namespace NirjharLo\WP_Plugin_Framework\Engine\Lib;

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

final class Cron
{
    /**
     * Add corn to wp scheduler.
     */
    public function __construct()
    {
        // Add cron schedules
        add_filter( 'cron_schedules', [ $this, 'registerSchedules' ] );
    }


    /**
     * Set up the schedules.
     */
    public function registerSchedules( array $schedules ): array
    {
        $prefix = 'prefix_'; // Avoid conflict with other crons. Example Reference: cron_30_mins

        // Example schedule options
        $scheduleOptions = [
            '24_hrs' => [
                'display'  => '24 hours',
                'interval' => 86400,
            ],
            '48_hrs' => [
                'display'  => '48 hours',
                'interval' => 172800,
            ],
            '72_hrs' => [
                'display'  => '72 hours',
                'interval' => 259200,
            ],
        ];

        /* Add each custom schedule into the cron job system. */
        foreach ( $scheduleOptions as $scheduleKey => $schedule ) {
            if ( ! isset( $schedules[ $prefix . $scheduleKey ] ) ) {
                $schedules[ $prefix . $scheduleKey ] = [
                    'interval' => $schedule['interval'],
                    'display'  => __( 'Every ' . $schedule['display'] ),
                ];
            }
        }

        return $schedules;
    }


    /**
     * Schedule the task based on added schedules.
     */
    public function schedule( array $task ): bool
    {
        $requiredKeys = [ 'timestamp', 'recurrence', 'hook' ];

        foreach ( $requiredKeys as $key ) {
            if ( ! array_key_exists( $key, $task ) ) {
                return false;
            }
        }

        if ( wp_next_scheduled( $task['hook'] ) ) {
            wp_clear_scheduled_hook( $task['hook'] );
        }

        wp_schedule_event( $task['timestamp'], $task['recurrence'], $task['hook'] );

        return true;
    }

    /**
     * Backwards compatible wrapper for {@see schedule()}.
     */
    public function schedule_task( array $task ): bool
    {
        return $this->schedule( $task );
    }
}
