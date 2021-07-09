<?php
/**
 * The Third Party integration with AMP plugin.
 *
 * @since		2.9.8.6
 * @package		LiteSpeed_Cache
 * @subpackage	LiteSpeed_Cache/thirdparty
 * @author		LiteSpeed Technologies <info@litespeedtech.com>
 */
namespace LiteSpeed\Thirdparty ;

defined( 'WPINC' ) || exit ;

use \LiteSpeed\API ;

class AMP {
	/**
	 * @since 4.2
	 */
	private static function maybe_amp( $amp_function ) {
		if ( is_admin() ) {
			return;
		}
		if ( ! isset( $_GET[ 'amp' ] ) && ( ! function_exists( $amp_function ) || ! $amp_function() ) ) {
			return;
		}
		! defined( 'LITESPEED_NO_PAGEOPTM' ) && define( 'LITESPEED_NO_PAGEOPTM', true );
		! defined( 'LITESPEED_NO_LAZY' ) && define( 'LITESPEED_NO_LAZY', true );
		! defined( 'LITESPEED_GUEST' ) && define( 'LITESPEED_GUEST', false );
	}
	public static function maybe_acc_mob_pages() {
		self::maybe_amp( 'ampforwp_is_amp_endpoint' );
	}
	/**
	 * CSS async will affect AMP result and
	 * Lazyload will inject JS library which AMP not allowed
	 * need to force set false before load
	 *
	 * @since 2.9.8.6
	 * @access public
	 */
	public static function preload()
	{
		// ampforwp_is_amp_endpoint() from Accelerated Mobile Pages
		add_action( 'wp', __CLASS__ . '::maybe_acc_mob_pages' ) ;

		// amp_is_request() from AMP
		self::maybe_amp( 'amp_is_request' );
		// add_filter( 'litespeed_can_optm', '__return_false' );
		// do_action( 'litespeed_conf_force', API::O_OPTM_CSS_ASYNC, false );
		// do_action( 'litespeed_conf_force', API::O_MEDIA_LAZY, false );
		// do_action( 'litespeed_conf_force', API::O_MEDIA_IFRAME_LAZY, false );
	}
}
