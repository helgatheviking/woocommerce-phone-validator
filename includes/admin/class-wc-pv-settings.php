<?php
/**
 * Admin Notices
 * 
 * Special thanks to Helgatheviking :)
 * 
 * @author   Precious Omonzejele (CodeXplorer)
 * @package  Phone Validator/Admin
 * @since    2.0.0
 */

// Intruder -_-
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_PV_Settings Class.
 *
 * Handle the setting page
 */

class Wc_PV_Settings {

	/**
	 * The single instance of the class.
	 *
	 * @var Wc_Ls_Loyalty
	 * @since 2.0.0
	 */
	protected static $_instance = null;

	/**
	 * The notice message
	 * 
	 * @var string
	 */
	protected $notice_msg = '';

	/**
	 * Logged in user id
	 * 
	 * @var int
	 */
	protected $current_user_id = 0;

	/**
	 * Page slug
	 * 
	 * @var string
	 */
	private $page_slug = '';

	/**
	 * User capability
	 * 
	 * @var string
	 */
	private $capability = '';

	/**
	 * Transient value for success note
	 * @var string
	 */
	private $transient_s_value = 'wc_pv-setting-note-s';

	/**
	 * Transient value for failed note
	 * @var string
	 */
	private $transient_f_value = 'wc_pv-setting-note-f';

	/**
	 * Transient timeout in seconds
	 * @var int
	 */
	private $transient_timeout = 5;

	/**
	 * Main instance
	 * @return class object
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->capability = wc_pv()->user_capability();
		$this->current_user_id = get_current_user_id();

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 10 );
		add_action( 'admin_menu', array($this, 'menu'), 10 );
		add_action( 'admin_footer', array ($this, 'add_table_style' ) );
	}

	/**
	 * init menu
	 */
	public function menu() {
		$parent_slug = 'woocommerce';
		//menu stuff
		$page_title = __( 'Phone Validator for WooCommerce', WC_PV_TEXT_DOMAIN );
		$menu_title = __( 'Phone Validator', WC_PV_TEXT_DOMAIN );
		$menu_slug = $this->page_slug;
		$capability = $this->capability;
		$function = array( $this, 'page_content' );
		$icon = '';
		$menu_page_hook_view = add_submenu_page($parent_slug, $page_title, $menu_slug, $capability, $parent_slug, $function);
	//	add_action("load-$menu_page_hook_view", array($this, 'add_loyalty_details_option'));
	}

	/**
	 * Register and enqueue admin styles and scripts
	 */
	public function admin_scripts() {
		// Register styles.
		wp_register_style( 'wc_pv_admin_styles', wc_pv()->plugin_url() . '/assets/css/admin-main' . WC_PV_MIN_SUFFIX . '.css', array(), WC_PV_PLUGIN_VERSION );
		wp_enqueue_style( 'wc_pv_admin_styles' );
	}
	
	/**
	 * Display Page Content
	 */
	public function page_content() {
	}

	/**
	 * updates the chosen program
	 */
	public function update_loyalty_program(){
		global $wc_ls_option_meta;
		if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ls-update-program']) && wp_verify_nonce($_GET['ls-update-program'], 'ls-update-program')) {
			
			if(wc_loystar()->is_merchant_subscription_expired()){//prevent users with expired subscription
				//Delete transient for the success value
				delete_transient($this->transient_s_value.$this->current_user_id);
				set_transient($this->transient_f_value.$this->current_user_id,'Sorry, your subscription is expired, you are not allowed to activate a loyalty program.',$this->transient_timeout);
				//redirect safely to show new value
				wp_safe_redirect(add_query_arg(array('notice'=>true), admin_url(wc_loystar()->parent_slug(true) ) ));
				exit();
			}
			$box = filter_input(INPUT_GET, 'wc_ls_updater');
			//redirect safely to show new value
			wp_safe_redirect(add_query_arg(array('notice'=>true), admin_url(wc_loystar()->parent_slug(true) ) ));
			exit();
		}
				
	}

	/**
	 * Successful submit message
	 */
	public function success_submit_notice(){
		$t_value = $this->transient_s_value.$this->current_user_id;
		$success_f_value = $this->transient_f_value.$this->current_user_id;//remove the failed transient value 
		$transient = get_transient($t_value);
		if(!empty($transient)){
		?>
		<div class="notice notice-success is-dismissible">
    		<p><?php echo $transient; ?></p>
		</div>
		<?php
		}
	}

	/**
	 * Faild submit message
	 */
	public function failed_submit_notice(){
		$t_value = $this->transient_f_value.$this->current_user_id;
		$success_t_value = $this->transient_s_value.$this->current_user_id;//remove the success transient value 
		$transient = get_transient($t_value);
		if(!empty($transient)){
		?>
		<div class="error notice">
    		<p><?php echo $transient; ?></p>
		</div>
		<?php
		}
	}
}
new Wc_PV_Settings();
