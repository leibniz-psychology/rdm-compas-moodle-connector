<?php
/**
 * This class works as a connection helper to connect with Moodle webservice API.
 *
 * @link       https://example.com
 * @since      1.0.0
 * @package    RDM Compas Moodle Connector.
 */

namespace app\wisdmlabs\edwiserBridge;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Connection helper.
 */
class EBConnectionHelper {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var string The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var string The current version of this plugin.
	 */
	private $version;

	/**
	 * Instance.
	 *
	 * @var EBConnectionHelper The single instance of the class
	 *
	 * @since 1.0.0
	 */
	protected static $instance = null;

	/**
	 * Main EBConnectionHelper Instance.
	 *
	 * Ensures only one instance of EBConnectionHelper is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 *
	 * @see EBConnectionHelper()
	 * @param text $plugin_name plugin_name.
	 * @param text $version version.
	 * @return EBConnectionHelper - Main instance
	 */
	public static function instance( $plugin_name, $version ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $plugin_name, $version );
		}

		return self::$instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since   1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'rdmcompas-moodle-connector' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since   1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'rdmcompas-moodle-connector' ), '1.0.0' );
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     1.0.0
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Wp_remote_post() has default timeout set as 5 seconds
	 * increase it to 40 seconds to remove timeout problem.
	 *
	 * @since  1.0.2
	 *
	 * @param int $time seconds before timeout.
	 *
	 * @return int
	 */
	public function connection_timeout_extender( $time = 50 ) {
		return $time;
	}





	/**
	 * Sends an API request to moodle server based on the credentials entered by user.
	 * returns response to ajax initiater.
	 *
	 * @since     1.0.0
	 *
	 * @param string $url   moodle URL.
	 * @param string $token moodle access token.
	 *
	 * @return array returns array containing the success & response message
	 */
	public function connection_test_helper( $url, $token ) {
		$success          = 1;
		$response_message = 'success';

		// function to check if webservice token is properly set.

		$webservice_function = 'core_course_get_courses';

		$request_url  = $url . '/webservice/rest/server.php?wstoken=';
		$request_url .= $token . '&wsfunction=';
		$request_url .= $webservice_function . '&moodlewsrestformat=json';
		$request_args = array(
			'timeout' => 100,
		);
		$settings   = get_option( 'eb_general' );
  		$request_args['sslverify'] = false;
 		if ( isset( $settings['eb_ignore_ssl'] ) && 'no' === $settings['eb_ignore_ssl'] ) {
  			$request_args['sslverify'] = true;
  		}
		$response     = wp_remote_post( $request_url, $request_args );
		
		if ( is_wp_error( $response ) ) {
			$success          = 0;
			$response_message = $this->create_response_message( $request_url, $response->get_error_message() );
		} elseif ( wp_remote_retrieve_response_code( $response ) === 200 ||
				wp_remote_retrieve_response_code( $response ) === 303 ) {
			$body = json_decode( wp_remote_retrieve_body( $response ) );
			if ( null === $body ) {
				$url_link         = "<a href='$url/local/edwiserbridge/edwiserbridge.php?tab=summary'>here</a>";
				$success          = 0;
				$response_message = $this->create_response_message(
					$request_url,
					__( 'Please check moodle web service configuration, Got invalid JSON,Check moodle web summary ', 'rdmcompas-moodle-connector' ) . $url_link
				);

			} elseif ( ! empty( $body->exception ) ) {
				$success          = 0;
				$response_message = $this->create_response_message( $request_url, print_r($body, 1) );

			} else {
				// added else to check the other services access error.
				$access_control_result = $this->check_service_access( $url, $token );

				if ( ! $access_control_result['success'] ) {
					$success          = 0;
					$response_message = $this->create_response_message( $url, $access_control_result['response_message'] );
				}
			}
		} else {
			$success          = 0;
				$response_message = $this->create_response_message( $request_url, esc_html__( 'Please check Moodle URL or Moodle plugin configuration !', 'rdmcompas-moodle-connector' ) );

		}

		return array(
			'success'          => $success,
			'response_message' => $response_message,
		);
	}




	/**
	 * This is called on the test connection.
	 *
	 * @param text $url url.
	 * @param text $token token.
	 */
	public function check_service_access( $url, $token ) {
		$success          = 1;
		$response_message = '<div>';

		$response_message .= '<div>' . esc_html__( 'Below are the functions which don\'t have access to the web service you created. This is due to :', 'rdmcompas-moodle-connector' ) . '</div>
								<div>
									<div>
										<ol>
											<li>' . esc_html__( 'Function is not added to the web service', 'rdmcompas-moodle-connector' ) . '</li>
											<li>' . esc_html__( 'Authorised user don\'t have enough capabilities i.e he is not admin', 'rdmcompas-moodle-connector' ) . '</li>
											<li>' . esc_html__( 'Edwiser Moodle extensions are not installed or have the lower version', 'rdmcompas-moodle-connector' ) . '</li>
										</ol>
									</div>
								</div>
								<div>
									<div>' . esc_html__( 'Services:', 'rdmcompas-moodle-connector' ) . '</div>
									<div>
										';

		$webservice_functions    = \app\wisdmlabs\edwiserBridge\wdm_eb_get_all_web_service_functions();
		$missing_web_service_fns = array();
		
		$request_args           = array( 'timeout' => 100 );
		$settings                  = get_option( 'eb_general' );
  		$request_args['sslverify'] = false;
 		if ( isset( $settings['eb_ignore_ssl'] ) && 'no' === $settings['eb_ignore_ssl'] ) {
  			$request_args['sslverify'] = true;
  		}

		foreach ( $webservice_functions as $webservice_function ) {
			$request_url  = $url . '/webservice/rest/server.php?wstoken=';
			$request_url .= $token . '&wsfunction=';
			$request_url .= $webservice_function . '&moodlewsrestformat=json';
			$response     = wp_remote_post( $request_url, $request_args );

			if ( 200 === wp_remote_retrieve_response_code( $response ) ||
			303 === wp_remote_retrieve_response_code( $response ) ) {
				$body = json_decode( wp_remote_retrieve_body( $response ) );
				if ( ! empty( $body->exception ) && isset( $body->errorcode ) && 'accessexception' === $body->errorcode ) {
						$success = 0;
						array_push( $missing_web_service_fns, $webservice_function );
				}
			}
		}

		if ( count( $missing_web_service_fns ) > 0 ) {
			$response_message .= implode( ' , ', $missing_web_service_fns );

			$response_message .= '
										</div>
									</div>';
			// Add new message here.

			$response_message .= esc_html__( 'You can check added webservice here ', 'rdmcompas-moodle-connector' ) . '<a href="' . \app\wisdmlabs\edwiserBridge\wdm_eb_get_moodle_url() . '/admin/settings.php?section=externalservices">' . \app\wisdmlabs\edwiserBridge\wdm_eb_get_moodle_url() . '/admin/settings.php?section=externalservices</a>' . esc_html__( ' or you can directly create new token and webservice in our Moodle edwiser settings here ', 'rdmcompas-moodle-connector' ) . '<a href="' . \app\wisdmlabs\edwiserBridge\wdm_eb_get_moodle_url() . 'local/edwiserbridge/edwiserbridge.php?tab=service">' . \app\wisdmlabs\edwiserBridge\wdm_eb_get_moodle_url() . 'local/edwiserbridge/edwiserbridge.php?tab=service</a>';

			$response_message .= '</div>';
		}

		return array(
			'success'          => $success,
			'response_message' => $response_message,
		);
	}


	/**
	 *
	 *
	 */	
	public function create_response_message( $url, $message ) {
		$msg = '<div>
                        <div class="eb_connection_short_msg">
                            ' . esc_html__( 'Test Connection failed, To check more information about issue click', 'rdmcompas-moodle-connector' ) . ' <span class="eb_test_connection_log_open"> ' . esc_html__( 'here', 'rdmcompas-moodle-connector') . ' </span>.
                        </div>

                        <div class="eb_test_connection_log">
                        	<div style="display:flex;">
	                            <div class="eb_connection_err_response">
	                                <h4> ' . esc_html__( 'An issue is detected.', 'rdmcompas-moodle-connector' ) . ' </h4>
	                                <div style="display:flex;">
	                                	<div> <b>' . esc_html__( 'Status : ', 'rdmcompas-moodle-connector' ) . '</b></div>
	                                	<div>' . esc_html__( 'Connection Failed', 'rdmcompas-moodle-connector' ) . ' </div>
	                                </div>
	                                <div>
	                                	<div><b>' . esc_html__( 'Url : ', 'rdmcompas-moodle-connector' ) . '</b></div>
	                                	<div class="eb_test_conct_log_url">' . $url .'</div>
	                                </div>
	                                <div>
	                                	<div><b>' . esc_html__( 'Response : ', 'rdmcompas-moodle-connector' ) . '</b></div>
	                                	<div>' . $message .'</div>
	                                </div>
	                            </div>

	                            <div class="eb_admin_templ_dismiss_notice_message">
									<span class="eb_test_connection_log_close dashicons dashicons-dismiss"></span> 
								</div>
							<div>
                        </div>
                    </div>';
        return $msg;
	}




	/**
	 * Helper function, recieves request to fetch data from moodle.
	 * accepts a paramtere for webservice function to be called on moodle.
	 *
	 * Fetches data from moodle and returns response.
	 *
	 * @since  1.0.0
	 *
	 * @param string $webservice_function accepts webservice function as an argument.
	 *
	 * @return array returns response to caller function
	 */
	public function connect_moodle_helper( $webservice_function = null ) {
		$success          = 1;
		$response_message = 'success';
		$response_data    = array();
		$eb_access_token  = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_get_access_token();
		$eb_access_url    = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_get_access_url();

		$request_url  = $eb_access_url . '/webservice/rest/server.php?wstoken=';
		$request_url .= $eb_access_token . '&wsfunction=' . $webservice_function . '&moodlewsrestformat=json';

		$request_args = array( 'timeout' => 100 );
		$settings                  = get_option( 'eb_general' );
  		$request_args['sslverify'] = false;
 		if ( isset( $settings['eb_ignore_ssl'] ) && 'no' === $settings['eb_ignore_ssl'] ) {
  			$request_args['sslverify'] = true;
  		}
		$response     = wp_remote_post( $request_url, $request_args );

		if ( is_wp_error( $response ) ) {
			$success          = 0;
			$response_message = $response->get_error_message();
		} elseif ( wp_remote_retrieve_response_code( $response ) === 200 ||
				wp_remote_retrieve_response_code( $response ) === 303 ) {
			$body = json_decode( wp_remote_retrieve_body( $response ) );
			if ( ! empty( $body->exception ) ) {
				$success          = 0;
				$response_message = $body->message;
			} else {
				$success       = 1;
				$response_data = $body;
			}
		} else {
			$success          = 0;
			$response_message = esc_html__( 'Please check Moodle connection details.', 'rdmcompas-moodle-connector' );
		}

		return array(
			'success'          => $success,
			'response_message' => $response_message,
			'response_data'    => $response_data,
		);
	}



	/**
	 * Helper function, recieves request to fetch data from moodle.
	 * accepts a paramtere for webservice function to be called on moodle.
	 *
	 * Fetches data from moodle and returns response.
	 *
	 * @since  1.0.0
	 *
	 * @param string $webservice_function accepts webservice function as an argument.
	 * @param string $request_data accepts webservice function as an argument.
	 *
	 * @return array returns response to caller function
	 */
	public function connect_moodle_with_args_helper( $webservice_function, $request_data ) {
		$success          = 1;
		$response_message = 'success';
		$response_data    = array();
		$eb_access_token  = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_get_access_token();
		$eb_access_url    = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_get_access_url();

		$request_url  = $eb_access_url . '/webservice/rest/server.php?wstoken=';
		$request_url .= $eb_access_token . '&wsfunction=' . $webservice_function . '&moodlewsrestformat=json';

		$request_args = array(
			'body'    => $request_data,
			'timeout' => 100,
		);
		$settings                  = get_option( 'eb_general' );
  		$request_args['sslverify'] = false;
 		if ( isset( $settings['eb_ignore_ssl'] ) && 'no' === $settings['eb_ignore_ssl'] ) {
  			$request_args['sslverify'] = true;
  		}

		$response = wp_remote_post( $request_url, $request_args );

		if ( is_wp_error( $response ) ) {
			$success          = 0;
			$response_message = $response->get_error_message();
		} elseif ( wp_remote_retrieve_response_code( $response ) === 200 ) {
			$body = json_decode( wp_remote_retrieve_body( $response ) );
			if ( ! empty( $body->exception ) ) {
				$success = 0;
				if ( isset( $body->debuginfo ) ) {
					$response_message = $body->message . ' - ' . $body->debuginfo;
				} else {
					$response_message = $body->message;
				}
			} else {
				$success       = 1;
				$response_data = $body;
			}
		} else {
			$success          = 0;
			$response_message = esc_html__( 'Please check Moodle URL !', 'rdmcompas-moodle-connector' );
		}

		return array(
			'success'          => $success,
			'response_message' => $response_message,
			'response_data'    => $response_data,
		);
	}
}
