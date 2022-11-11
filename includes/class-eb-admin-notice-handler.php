<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       https://example.com
 * @since      1.3.4
 * @package    RDM Compas Moodle Connector
 */

namespace app\wisdmlabs\edwiserBridge;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Eb admin handler.
 */
class Eb_Admin_Notice_Handler {

	/**
	 * Check if installed.
	 */
	public function check_if_moodle_plugin_installed() {
		$plugin_installed   = 1;
		$connection_options = get_option( 'eb_connection' );
		$eb_moodle_url      = '';
		if ( isset( $connection_options['eb_url'] ) ) {
			$eb_moodle_url = $connection_options['eb_url'];
		}
		$eb_moodle_token = '';
		if ( isset( $connection_options['eb_access_token'] ) ) {
			$eb_moodle_token = $connection_options['eb_access_token'];
		}
		$request_url = $eb_moodle_url . '/webservice/rest/server.php?wstoken=';

		$moodle_function = 'eb_get_course_progress';
		$request_url    .= $eb_moodle_token . '&wsfunction=' . $moodle_function . '&moodlewsrestformat=json';
		$response        = wp_remote_post( $request_url );

		if ( is_wp_error( $response ) ) {
			$plugin_installed = 0;
		} elseif ( 200 === wp_remote_retrieve_response_code( $response ) ||
				300 === wp_remote_retrieve_response_code( $response ) ) {
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			if ( 'accessexception' === $body->errorcode ) {
				$plugin_installed = 0;
			}
		} else {
			$plugin_installed = 0;
		}

		return $plugin_installed;
	}

	/**
	 * Get Moodle plugin Info.
	 * Currently only version is provided.
	 */
	public function eb_get_mdl_plugin_info() {
		$connection_options = get_option( 'eb_connection' );
		$eb_moodle_url      = '';
		if ( isset( $connection_options['eb_url'] ) ) {
			$eb_moodle_url = $connection_options['eb_url'];
		}
		$eb_moodle_token = '';
		if ( isset( $connection_options['eb_access_token'] ) ) {
			$eb_moodle_token = $connection_options['eb_access_token'];
		}
		$request_url = $eb_moodle_url . '/webservice/rest/server.php?wstoken=';

		$moodle_function = 'eb_get_edwiser_plugins_info';
		$request_url    .= $eb_moodle_token . '&wsfunction=' . $moodle_function . '&moodlewsrestformat=json';
		$request_args    = array(
			'sslverify' => false,
			'body'      => array(),
			'timeout'   => 100,
		);
		$response        = wp_remote_post( $request_url, $request_args );

		$status = 0;

		if ( is_wp_error( $response ) ) {
			return $status;
		} elseif ( 200 === wp_remote_retrieve_response_code( $response ) ||
				300 === wp_remote_retrieve_response_code( $response ) ) {
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			if ( isset( $body->plugin_name ) && isset( $body->version ) && 0 === version_compare( '2.0.7', $body->version ) ) {
				$status = 1;
			}
		} else {
			$status = 0;
		}

		return $status;

	}



	/**
	 * Show admin feedback notice.
	 *
	 * @since 1.3.1
	 */
	public function eb_admin_update_moodle_plugin_notice() {
		$redirection = add_query_arg( 'eb-update-notice-dismissed', true );

		if ( ! get_option( 'eb_mdl_plugin_update_notice_dismissed' ) ) {
			if ( ! $this->check_if_moodle_plugin_installed() ) {
				echo '  <div class="notice  eb_admin_update_notice_message_cont">
							<div class="eb_admin_update_notice_message">

								<div class="eb_update_notice_content">
									' . esc_html__( 'Thanks for updating to the latest version of RDM Compas Moodle Connector plugin, please make sure you have also installed our associated Moodle Plugin to avoid any malfunctioning.', 'rdmcompas-moodle-connector' ) . '
									<a href="https://example.com/wp-content/uploads/edd/2022/02/edwiserbridge-1.zip">' . esc_html__( ' Click here ', 'rdmcompas-moodle-connector' ) . '</a>
									' . esc_html__( ' to download Moodle plugin.', 'rdmcompas-moodle-connector' ) . '

										' . esc_html__( 'For setup assistance check our ', 'rdmcompas-moodle-connector' ) . '
										<a href="https://example.com/bridge/documentation/#tab-b540a7a7-e59f-3">' . esc_html__( ' documentation', 'rdmcompas-moodle-connector' ) . '</a>.
								</div>
								
								<div class="eb_update_notice_dismiss_wrap">
									<span style="padding-left: 5px;">
										<a href="' . esc_html( $redirection ) . '">
											' . esc_html__( ' Dismiss notice', 'rdmcompas-moodle-connector' ) . '
										</a>
									</span>
								</div>

							</div>
							<div class="eb_admin_update_dismiss_notice_message">
									<span class="dashicons dashicons-dismiss eb_update_notice_hide"></span>
							</div>
						</div>';
			} elseif ( ! $this->eb_get_mdl_plugin_info() ) {
				echo '  <div class="notice  eb_admin_update_notice_message_cont">
							<div class="eb_admin_update_notice_message">

								<div class="eb_update_notice_content">
									' . esc_html__( 'Thanks for updating or installing RDM Compas Moodle Connector plugin, please update Moodle Plugin to avoid any malfunctioning.', 'rdmcompas-moodle-connector' ) . '
									<a href="https://example.com/wp-content/uploads/edd/2022/02/edwiserbridge-1.zip">' . esc_html__( ' Click here ', 'rdmcompas-moodle-connector' ) . '</a>
									' . esc_html__( ' to download Moodle plugin.', 'rdmcompas-moodle-connector' ) . '

										' . esc_html__( 'For setup assistance check our ', 'rdmcompas-moodle-connector' ) . '
										<a href="https://example.com/bridge/documentation/#tab-b540a7a7-e59f-3">' . esc_html__( ' documentation', 'rdmcompas-moodle-connector' ) . '</a>.
								</div>
								
								<div class="eb_update_notice_dismiss_wrap">
									<span style="padding-left: 5px;">
										<a href="' . esc_html( $redirection ) . '">
											' . esc_html__( ' Dismiss notice', 'rdmcompas-moodle-connector' ) . '
										</a>
									</span>
								</div>

							</div>
							<div class="eb_admin_update_dismiss_notice_message">
									<span class="dashicons dashicons-dismiss eb_update_notice_hide"></span>
							</div>
						</div>';
			} else {
				update_option( 'eb_mdl_plugin_update_notice_dismissed', 'true', true );
			}
		}
	}


	/**
	 * Handle notice dismiss
	 *
	 * @since 1.3.1
	 */
	public function eb_admin_update_notice_dismiss_handler() {
		if ( true === filter_input( INPUT_GET, 'eb-update-notice-dismissed', FILTER_VALIDATE_BOOLEAN ) ) {
			update_option( 'eb_mdl_plugin_update_notice_dismissed', 'true', true );
		}
	}


	/**
	 * Functionality to show admin dashboard template compatibility notice.
	 * We will remove it in the next version.
	 *
	 * @since 1.3.1
	 */
	public function eb_admin_template_notice() {
		// Notice dismiss handler code here.
		if ( true === filter_input( INPUT_GET, 'eb_templ_compatibility_dismissed', FILTER_VALIDATE_BOOLEAN ) ) {
			$user_id = get_current_user_id();
			add_user_meta( $user_id, 'eb_templ_compatibility_dismissed', filter_input( INPUT_GET, 'eb_templ_compatibility_dismissed', FILTER_VALIDATE_BOOLEAN ), true );
		} else {
			$redirection    = add_query_arg( 'eb_templ_compatibility_dismissed', true );
			$user_id        = get_current_user_id();
			$templ_usermeta = get_user_meta( $user_id, 'eb_templ_compatibility_dismissed', true );
			if ( empty( $templ_usermeta ) || ! $templ_usermeta ) {
				$msg = esc_html__( 'If you have overridden the standard RDM Compas Moodle Connector templates previously then please make sure that your templates are made compatible with the NEW RDM Compas Moodle Connector template. It may cause CSS breaks if not done. ', 'rdmcompas-moodle-connector' );

				echo '<div class="notice notice-warning eb_template_notice_wrap"">
						<div class="eb_template_notice">
							' . esc_html__( 'If you have overridden the standard', 'rdmcompas-moodle-connector' ) . '
							<b> RDM Compas Moodle Connector </b>' . esc_html__( 'templates previously then please make sure that your templates are made compatible with the ', 'rdmcompas-moodle-connector' ) . ' <b>NEW RDM Compas Moodle Connector</b>
							' . esc_html__( 'template. It may cause CSS breaks if not done.', 'rdmcompas-moodle-connector' ) . '
							<div class="">
								' . esc_html__( 'Please refer to', 'rdmcompas-moodle-connector' ) . '<a href="https://example.com/blog/how-to-make-rdmcompas-moodle-connector-compatible-with-your-theme/" target="_blank"> <b>' . esc_html__( ' this ', 'rdmcompas-moodle-connector' ) . '</b> </a>' . esc_html__( ' article for theme compatibility', 'rdmcompas-moodle-connector' ) . '
							</div>
						</div>
						<div class="eb_admin_templ_dismiss_notice_message">
							<a href="' . esc_html( $redirection ) . '">
								<span class="dashicons dashicons-dismiss"></span> 
							</a>
						</div>
					</div>';
			}
		}

	}


	/**
	 * Handle notice dismiss
	 *
	 * @since 1.3.1
	 */
	public function eb_admin_notice_dismiss_handler() {
		if ( true === filter_input( INPUT_GET, 'eb-feedback-notice-dismissed', FILTER_VALIDATE_BOOLEAN ) ) {
			$user_id = get_current_user_id();
			add_user_meta( $user_id, 'eb_feedback_notice_dismissed', filter_input( INPUT_GET, 'eb-feedback-notice-dismissed', FILTER_VALIDATE_BOOLEAN ), true );
		}
	}






	/**
	 * SHow notfi.
	 *
	 * @param text $curr_plugin_meta_data curr_plugin_meta_data.
	 * @param text $new_plugin_meta_data new_plugin_meta_data.
	 */
	public function eb_show_inline_plugin_update_notification( $curr_plugin_meta_data, $new_plugin_meta_data ) {
		ob_start();
		?>
<p>
    <strong><?php echo esc_html__( 'Important Update Notice:', 'rdmcompas-moodle-connector' ); ?></strong>
    <?php echo esc_html__( 'Please download and update associated edwiserbridge Moodle plugin.', 'rdmcompas-moodle-connector' ); ?>
    <a href="https://example.com/bridge/"><?php echo esc_html__( 'Click here ', 'rdmcompas-moodle-connector' ); ?></a>
    <?php echo esc_html__( ' to download', 'rdmcompas-moodle-connector' ); ?>

</p>

<?php
		echo wp_kses( ob_get_clean(), \app\wisdmlabs\edwiserBridge\wdm_eb_get_allowed_html_tags() );

		// added this just for commit purpose.
		unset( $curr_plugin_meta_data );
		unset( $new_plugin_meta_data );
	}
}
