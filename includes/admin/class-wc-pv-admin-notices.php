<?php
/**
 * Admin Notices
 * 
 * Special thanks to Helgatheviking :)
 * 
 * @author   Precious Omonzejele (CodeXplorer)
 * @package  WooCommerce Phone Validator/Admin
 * @since    2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_PV_Admin_Notices Class.
 *
 * Handle the addition/display of admin notices.
 */
class WC_PV_Admin_Notices {

	/**
	 * Metabox Notices.
	 *
	 * @var array
	 */
	public static $meta_box_notices = array();

	/**
	 * Admin Notices.
	 *
	 * @var array
	 */
	public static $admin_notices = array();

	/**
	 * Maintenance Notices.
	 *
	 * @var array
	 */
	public static $maintenance_notices = array();

	/**
	 * Array of maintenance notice types - name => callback.
	 *
	 * @var array
	 */
	private static $maintenance_notice_types = array(
		'updating' => 'updating_notice',
	);

	/**
	 * Constructor.
	 */
	public static function init() {
        
    }

	/**
	 * Add a notice/error.
	 *
	 * @param  string $text
	 * @param  mixed  $args
	 * @param  bool   $save_notice
	 */
	public static function add_notice( $text, $args, $save_notice = false ) {

		if ( is_array( $args ) ) {
			$type          = $args['type'];
			$dismiss_class = isset( $args['dismiss_class'] ) ? $args['dismiss_class'] : false;
		} else {
			$type          = $args;
			$dismiss_class = false;
		}

		$notice = array(
			'type'          => $type,
			'content'       => $text,
			'dismiss_class' => $dismiss_class,
		);

		if ( $save_notice ) {
			self::$meta_box_notices[] = $notice;
		} else {
			self::$admin_notices[] = $notice;
		}
	}

    /**
	 * Show maintenance notices.
	 */
	public static function hook_maintenance_notices() {

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		foreach ( self::$maintenance_notice_types as $type => $callback ) {
			if ( in_array( $type, self::$maintenance_notices ) ) {
				call_user_func( array( __CLASS__, $callback ) );
			}
		}
	}

	/**
	 * Add a maintenance notice to be displayed.
	 */
	public static function add_maintenance_notice( $notice_name ) {
		self::$maintenance_notices = array_unique( array_merge( self::$maintenance_notices, array( $notice_name ) ) );
	}

	/**
	 * Remove a maintenance notice.
	 */
	public static function remove_maintenance_notice( $notice_name ) {
		self::$maintenance_notices = array_diff( self::$maintenance_notices, array( $notice_name ) );
    }

}

WC_NYP_Admin_Notices::init();
