<?php
/**
 * The REST related class.
 *
 * @since      	2.9.4
 */
namespace LiteSpeed;

defined( 'WPINC' ) || exit;

class REST extends Root {
	private $_internal_rest_status = false;

	/**
	 * Confructor of ESI
	 *
	 * @since    2.9.4
	 */
	public function __construct() {
		// Hook to internal REST call
		add_filter( 'rest_request_before_callbacks', array( $this, 'set_internal_rest_on' ) );
		add_filter( 'rest_request_after_callbacks', array( $this, 'set_internal_rest_off' ) );

		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	/**
	 * Register REST hooks
	 *
	 * @since  3.0
	 * @access public
	 */
	public function rest_api_init() {
		register_rest_route( 'litespeed/v1', '/tool/check_ip', array(
			'methods' => 'GET',
			'callback' => array( $this, 'check_ip' ),
			'permission_callback'	=> function() {
				return current_user_can( 'manage_network_options' ) || current_user_can( 'manage_options' );
			}
		) );

		// IP callback validate
		register_rest_route( 'litespeed/v1', '/ip_validate', array(
			'methods' => 'POST',
			'callback' => array( $this, 'ip_validate' ),
			'permission_callback'	=> array( $this, 'is_from_cloud' ),
		) );

		// Token callback validate
		register_rest_route( 'litespeed/v1', '/token', array(
			'methods' => 'POST',
			'callback' => array( $this, 'token' ),
			'permission_callback'	=> array( $this, 'is_from_cloud' ),
		) );
		register_rest_route( 'litespeed/v1', '/token', array(
			'methods' => 'GET',
			'callback' => array( $this, 'token_get' ),
			'permission_callback'	=> array( $this, 'is_from_cloud' ),
		) );
		register_rest_route( 'litespeed/v1', '/ping', array(
			'methods' => 'GET',
			'callback' => array( $this, 'ping' ),
			'permission_callback'	=> '__return_true',
		) );

		// API key callback notification
		register_rest_route( 'litespeed/v1', '/apikey', array(
			'methods' => 'POST',
			'callback' => array( $this, 'apikey' ),
			'permission_callback'	=> array( $this, 'is_from_cloud' ),
		) );

		register_rest_route( 'litespeed/v1', '/notify_ccss', array(
			'methods' => 'POST',
			'callback' => array( $this, 'notify_ccss' ),
			'permission_callback'	=> array( $this, 'is_from_cloud' ),
		) );

		register_rest_route( 'litespeed/v1', '/notify_ucss', array(
			'methods' => 'POST',
			'callback' => array( $this, 'notify_ucss' ),
			'permission_callback'	=> array( $this, 'is_from_cloud' ),
		) );

		register_rest_route( 'litespeed/v1', '/notify_lqip', array(
			'methods' => 'POST',
			'callback' => array( $this, 'notify_lqip' ),
			'permission_callback'	=> array( $this, 'is_from_cloud' ),
		) );

		register_rest_route( 'litespeed/v1', '/notify_vpi', array(
			'methods' => 'POST',
			'callback' => array( $this, 'notify_vpi' ),
			'permission_callback'	=> array( $this, 'is_from_cloud' ),
		) );

		// Image optm notify_img
		// Need validation
		register_rest_route( 'litespeed/v1', '/notify_img', array(
			'methods' => 'POST',
			'callback' => array( $this, 'notify_img' ),
			'permission_callback'	=> array( $this, 'is_from_cloud' ),
		) );

		// Image optm check_img
		// Need validation
		register_rest_route( 'litespeed/v1', '/check_img', array(
			'methods' => 'POST',
			'callback' => array( $this, 'check_img' ),
			'permission_callback'	=> array( $this, 'is_from_cloud' ),
		) );

	}

	/**
	 * Check if the request is from cloud nodes
	 *
	 * @since 4.2
	 */
	public function is_from_cloud() {
		return $this->cls( 'Cloud' )->is_from_cloud();
	}

	/**
	 * Token get for
	 *
	 * @since  3.0.4
	 */
	public function token_get() {
		return Cloud::ok();
	}

	/**
	 * Ping pong
	 *
	 * @since  3.0.4
	 */
	public function ping() {
		return Cloud::ok( array( 'ver' => Core::VER ) );
	}

	/**
	 * Launch api call
	 *
	 * @since  3.0
	 */
	public function check_ip() {
		return Tool::cls()->check_ip();
	}

	/**
	 * Launch api call
	 *
	 * @since  3.0
	 */
	public function ip_validate() {
		return $this->cls( 'Cloud' )->ip_validate();
	}

	/**
	 * Launch api call
	 *
	 * @since  3.0
	 */
	public function token() {
		return $this->cls( 'Cloud' )->token_validate();
	}

	/**
	 * Launch api call
	 *
	 * @since  3.0
	 */
	public function apikey() {
		return $this->cls( 'Cloud' )->save_apikey();
	}

	/**
	 * Notify CCSS
	 *
	 * @since  4.2
	 */
	public function notify_ccss() {
		return $this->cls( 'css' )->notify( 'ccss' );
	}

	/**
	 * Notify UCSS
	 *
	 * @since  4.2
	 */
	public function notify_ucss() {
		return $this->cls( 'css' )->notify( 'ucss' );
	}

	/**
	 * Notify lqip
	 *
	 * @since  4.2
	 */
	public function notify_lqip() {
		return $this->cls( 'placeholder' )->notify();
	}

	/**
	 * Notify viewport images
	 *
	 * @since  4.2
	 */
	public function notify_vpi() {
		return $this->cls( 'media' )->notify();
	}

	/**
	 * Launch api call
	 *
	 * @since  3.0
	 */
	public function notify_img() {
		return Img_Optm::cls()->notify_img();
	}

	/**
	 * Launch api call
	 *
	 * @since  3.0
	 */
	public function check_img() {
		return Img_Optm::cls()->check_img();
	}

	/**
	 * Set internal REST tag to ON
	 *
	 * @since  2.9.4
	 * @access public
	 */
	public function set_internal_rest_on( $not_used = null )
	{
		$this->_internal_rest_status = true;
		Debug2::debug2( '[REST] ✅ Internal REST ON [filter] rest_request_before_callbacks' );

		return $not_used;
	}

	/**
	 * Set internal REST tag to OFF
	 *
	 * @since  2.9.4
	 * @access public
	 */
	public function set_internal_rest_off( $not_used = null )
	{
		$this->_internal_rest_status = false;
		Debug2::debug2( '[REST] ❎ Internal REST OFF [filter] rest_request_after_callbacks' );

		return $not_used;
	}

	/**
	 * Get internal REST tag
	 *
	 * @since  2.9.4
	 * @access public
	 */
	public function is_internal_rest()
	{
		return $this->_internal_rest_status;
	}

	/**
	 * Check if an URL or current page is REST req or not
	 *
	 * @since  2.9.3
	 * @since  2.9.4 Moved here from Utility, dropped static
	 * @access public
	 */
	public function is_rest( $url = false )
	{
		// For WP 4.4.0- compatibility
		if ( ! function_exists( 'rest_get_url_prefix' ) ) {
			return defined( 'REST_REQUEST' ) && REST_REQUEST;
		}

		$prefix = rest_get_url_prefix();

		// Case #1: After WP_REST_Request initialisation
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}

		// Case #2: Support "plain" permalink settings
		if ( isset( $_GET[ 'rest_route' ] ) && strpos( trim( $_GET[ 'rest_route' ], '\\/' ), $prefix , 0 ) === 0 ) {
			return true;
		}

		if ( ! $url ) {
			return false;
		}

		// Case #3: URL Path begins with wp-json/ (REST prefix) Safe for subfolder installation
		$rest_url = wp_parse_url( site_url( $prefix ) );
		$current_url = wp_parse_url( $url );
		// Debug2::debug( '[Util] is_rest check [base] ', $rest_url );
		// Debug2::debug( '[Util] is_rest check [curr] ', $current_url );
		// Debug2::debug( '[Util] is_rest check [curr2] ', wp_parse_url( add_query_arg( array( ) ) ) );
		return strpos( $current_url[ 'path' ], $rest_url[ 'path' ] ) === 0;
	}
}
