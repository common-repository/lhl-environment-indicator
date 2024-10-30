<?php
defined( 'ABSPATH' ) || exit;

class LHLNVND_Rest_API extends WP_REST_Posts_Controller {

    protected $namespace = 'lhlnvnd/v1';
    protected $rest_base = 'action';
    protected $terms_options;

    protected $error_code = "def_err_code";
    protected $error_message = "Err Message: Validation Error";

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name = '', $version = '') {
	}

     /**
     * Register the routes for the objects of the controller.
     *
     * Nearly the same as WP_REST_Posts_Controller::register_routes(), but all of these
     * endpoints are hidden from the index.
     */
    public function register_routes() {

        /* Accept Terms
         * wp-json/lhlnvnd/v1/action/send-test-email
         */
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/send-test-email' , array(
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array( $this, 'send_test_email' ),
                'permission_callback' => array( $this, 'send_test_email_permission_check' ),
                'show_in_index'       => false,
            ),
        ) );
    }

    /**
     * Send Test Email
     */

     public function send_test_email_permission_check( $request ) {
        if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
        return false;
    }

    public function send_test_email( $request ) {

        $to = get_bloginfo('admin_email');

        $response = array();
        $response['code'] = "send_test_email";
        $response['message'] = __("Attempted to send test email to: $to", "lhl-environment-indicator");
        $response['data'] = array();

        $error = new WP_Error();

        /**
         * Validate
         */
        if(! $this->__validate($request)){
            $error->add( $this->error_code, $this->error_message, array( 'status' => 401 ) );
            return $error;
        }

        $subject = "LHL Email Redirect TEST";
        $site_url = get_site_url();
        $site_url = preg_replace('#^https?://#i', '', $site_url);
        $message = "This is a test email by LHL Email Redirect and Environment Indicator. Being sent to: $to. Sent by $site_url";

        $result = wp_mail( $to, $subject, $message);
        // error_log("SENDING TEST email to: {$to}; Subject: $subject");

        /**
         * Responce
         */
        if ($result == true){
            return new WP_REST_Response($response, 200);
        }

        $error->add( "email_failed_to_send", "Email Failed to send.", array( 'status' => 503 ) );
        return $error;
        
    }

    private function __validate($request){

        /**
         * 1. Check if user is not logged in
         */
        $user  = wp_get_current_user();
		$user_id   = (int) $user->ID;
        if ($user_id == 0){
            $this->error_code = "no_such_user";
            $this->error_message = __( 'No such user code: 0', 'arpadapp-ai-image-alt-text-generator' );
            return 0;
        }

        /**
         * 2. Check if nonce is bad
         */
        if ( rest_cookie_check_errors($request) ) {
            // Nonce is correct!
        } else {
            $this->error_code = "no_such_user";
            $this->error_message = __( 'No such user code: 1', 'arpadapp-ai-image-alt-text-generator' );
            return 0;
        }

        return 1;
    }

}