<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

//Table for settings page
if ( ! class_exists( 'INTERNAL_LINK_MASTER_TABLE' ) ) {

	final class INTERNAL_LINK_MASTER_TABLE extends WP_List_Table {


		//Declare following vars before calling prepare
		public $singularName;
		public $pluralName;
		public $isAjax;
		public static $tableName = 'internal_link_master';



		public function __construct() {

			parent::__construct( [
				'singular' => $this->singularName,
				'plural'   => $this->pluralName,
				'ajax'     => $this->isAjax,
			] );
		}



		//fetch the data using custom named method function
		public static function get_Table( $per_page = 5, $page_number = 1 ) {

			global $wpdb;
			$wpdb->suppress_errors = true;

			$sql = "SELECT * FROM {$wpdb->prefix}" . self::$tableName;

			if ( ! empty( $_REQUEST['orderby'] ) ) {
				$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
				$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			}
			$sql .= " LIMIT $per_page";
			$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

			//get the data from database
			$result = $wpdb->get_results( $sql, 'ARRAY_A' );
			return $result;
		}



		//Delete individual data
		public static function delete_url( $id ) {

			global $wpdb;
			$wpdb->delete("{$wpdb->prefix}" . self::$tableName, array( 'ID' => $id ), array( '%s' ) );
		}



		//If there is no data to show
		public function no_items() {

			_e( 'No URLs Added yet.', 'InLinkMaster' );
		}



		//How many rows are present there
		public static function record_count() {

			global $wpdb;
			$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}" . self::$tableName;
			return $wpdb->get_var( $sql );
		}



		//Display columns content
		public function column_name( $item ) {

			$delete_nonce = wp_create_nonce( 'delete_url' );
			$title = sprintf( '<strong>%s</strong>', $item['URL'] );
			//Change the page instruction where you want to show it
			$actions = array(
					'delete' => sprintf( '<a href="?page=%s&action=%s&instruction=%s&_wpnonce=%s">%s</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce, __( 'Delete', 'myPlugintextDomain' ) )
					);
			return $title . $this->row_actions( $actions );
		}



		//set coulmns name
		public function column_default( $item, $column_name ) {

			switch ( $column_name ) {
				case 'cb':
					//This is the first column
					return $this->column_name( $item );
				case 'has_pa_url':
					$parsed = parse_url($item[ $column_name ]);
					return (array_key_exists('path', $parsed) ? $parsed['path'] : '/');
				case 'has_pa':
				case 'need_pa_url':
					$parsed = parse_url($item[ $column_name ]);
					return (array_key_exists('path', $parsed) ? $parsed['path'] : '/');
				case 'need_pa':
					return $item[ $column_name ];
			default:
				//Show the whole array for troubleshooting purposes
				return print_r( $item, true );
			}
		}



		//Set checkboxes to delete
		public function column_cb( $item ) {

			return sprintf( '<input type="checkbox" name="bulk-select[]" value="%s" />', $item['ID'] );
		}



		//Columns callback
		public function get_columns() {

			$columns = array(
							'cb'		=> '<input type="checkbox" />',
							'has_pa_url'	=> __( 'Has PA', 'InLinkMaster' ),
							'has_pa'	=> __( 'PA', 'InLinkMaster' ),
							'need_pa_url'	=> __( 'Need PA', 'InLinkMaster' ),
							'need_pa'	=> __( 'PA', 'InLinkMaster' )
						);
			return $columns;
		}



		//Decide columns to be sortable by array input
		public function get_sortable_columns() {

			$sortable_columns = array(
				'has_pa_url' => array( 'has_pa_url', false ),
				'has_pa' => array( 'has_pa', true ),
				'need_pa_url' => array( 'need_pa_url', false ),
				'need_pa' => array( 'need_pa', true ),
			);
			return $sortable_columns;
		}



		//Determine bulk actions in the table dropdown
		public function get_bulk_actions() {

			$actions = array( 'bulk-delete' => 'Delete'	);
			return $actions;
		}



		//Prapare the display variables for screen options
		public function prepare_items() {

			$this->_column_headers = $this->get_column_info();
			/** Process bulk action */
			$this->process_bulk_action();
			$per_page     = $this->get_items_per_page( 'link_group_per_page', 5 );
			$current_page = $this->get_pagenum();
			$total_items  = self::record_count();
			$this->set_pagination_args( array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			) );
			$this->items = self::get_Table( $per_page, $current_page );
		}



		//process bulk action
		public function process_bulk_action() {

			//Detect when a bulk action is being triggered...
			if ( 'delete' === $this->current_action() ) {
				//In our file that handles the request, verify the nonce.
				$nonce = esc_attr( $_REQUEST['_wpnonce'] );
				if ( ! wp_verify_nonce( $nonce, 'delete_url' ) ) {
					die( 'Go get a live script kiddies' );
				} else {
					self::delete_url( absint( $_GET['instruction'] ) ); //Remember the instruction param from column_name method
				}
			}
			//If the delete bulk action is triggered
			if ( isset( $_POST['action'] ) ) {
				if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) ) {
					$delete_ids = esc_sql( $_POST['bulk-select'] );
					foreach ( $delete_ids as $id ) {
						self::delete_url( $id );
					}
				}
			}
		}
	}
} ?>