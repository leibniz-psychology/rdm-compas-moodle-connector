<?php
/**
 * EDW Licensing Management
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    RDM Compas Moodle Connector
 * @subpackage RDM Compas Moodle Connector/admin
 */

namespace app\wisdmlabs\edwiserBridge;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Eb_Bridge_Summary' ) ) :

	/**
	 * Eb_Settings_Licensing.
	 */
	class Eb_Bridge_Summary extends EBSettingsPage {

		/**
		 * Addon licensing.
		 *
		 * @var text $addon_licensing addon licensing
		 */
		public $addon_licensing;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->_id   = 'summary';
			$this->label = __( 'Stats', 'rdmcompas-moodle-connector' );

			add_filter( 'eb_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'eb_settings_' . $this->_id, array( $this, 'output' ) );
		}

		/**
		 * Output the settings.
		 *
		 * @since  1.0.0
		 */
		public function output() {
			// Hide the save button.
			$GLOBALS['hide_save_button'] = true;
			$porducts                    = array();
			$plugin_path                 = plugin_dir_path( __DIR__ );
			$this->get_edwiser_envirment( $plugin_path );
			$this->get_server_envirment_info( $plugin_path );

		}

		/**
		 * Function to get the RDM Compas Moodle Connector plugins list with the version numbers.
		 *
		 * @param string $plugin_path Plugin file path.
		 */
		private function get_edwiser_envirment( $plugin_path ) {
//			$response   = wp_remote_get( 'https://example.com/edwiserdemoimporter/bridge-free-plugin-info.json' );
//            $response   = wp_remote_get( '' );
//			$fetch_data = false;
//			if ( isset( $_GET['fetch_data'] ) && 'true' === $_GET['fetch_data'] ) { // WPCS: CSRF ok, input var ok. @codingStandardsIgnoreLine
//				$fetch_data = true;
//			}
//			$free_plugin_data = array();
//			if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
//				$responce = json_decode( wp_remote_retrieve_body( $response ) );
//				foreach ( $responce as $key => $value ) {
//					$free_plugin_data[ $key ] = array(
//						'name'    => $value->name,
//						'version' => $value->version,
//						'url'     => $value->url,
//					);
//				}
//			}
			$data = array(
				array(
					'<span class="eb-summary-lbl-heading">' . __( 'Wordpress Plugins', 'rdmcompas-moodle-connector' ) . '</span>',
					'',
					'',
				),
				array(
					'<span class="eb-summary-lbl-text">' . __( 'RDM Compas Moodle Connector:', 'rdmcompas-moodle-connector' ) . '</span>',
                    'Installed',
                    '<a href="https://github.com/leibniz-psychology/rdmcompas-moodle-connector">Github repository</a>',
				),
			);
			if ( ! class_exists( 'Eb_Licensing_Manager' ) ) {
				include_once $plugin_path . 'licensing/class-eb-licensing-manager.php';
			}
//			$products_data = Eb_Licensing_Manager::get_plugin_data();
//
//			foreach ( $products_data as $product ) {
//				$version_info = wdm_get_plugin_version( $product['path'] );
//				$remote_data  = $this->get_plugin_remote_version( $product, $fetch_data );
//				$data[]       = array(
//					'<span class="eb-summary-lbl-text">' . $product['item_name'] . ' :</span>',
//					$version_info,
//					$this->show_plugin_version( $remote_data, $version_info ),
//				);
//
//			}
			$data           = array_merge(
				$data,
				array(
					array(
						'<span class="eb-summary-lbl-heading">' . __( 'Moodle Plugins', 'rdmcompas-moodle-connector' ) . '</span>',
						'',
						'',
					),
					array(
						'<span class="eb-summary-lbl-text">' . __( 'Moodle RDM Compas Moodle Connector:', 'rdmcompas-moodle-connector' ) . '</span>',
						'<a href="https://trainingcenter.rdm-compas.org/admin/plugins.php">Check installation</a>',
						'<a href="https://edwiser.org/documentation/edwiser-bridge/moodle-website-configuration-for-v1-4-3-only/">Download plugin</a>',
					),
//					array(
//						'<span class="eb-summary-lbl-text">' . __( 'Moodle Edwiser Single Sign On :', 'rdmcompas-moodle-connector' ) . '</span>',
//						'---',
//						$this->show_plugin_version( $free_plugin_data['moodle_edwiser_bridge_sso'] ),
//					),
//					array(
//						'<span class="eb-summary-lbl-text">' . __( 'Moodle Edwiser Bulk Purchase :', 'rdmcompas-moodle-connector' ) . '</span>',
//						'---',
//						$this->show_plugin_version( $free_plugin_data['moodle_edwiser_bridge_bp'] ),
//					),
				)
			);
			$refresh_url    = admin_url( '/admin.php?page=eb-settings&tab=summary&fetch_data=true' );
			$refresh_button = '<a class="wdm-stat-reload" title="' . __( 'Check update again', 'rdmcompas-moodle-connector' ) . '" href="' . $refresh_url . '"><span class="dashicons dashicons-update-alt"></span></a>';
			$headings       = array(
				__( 'RDM Compas Moodle Connector Plugin Summary', 'rdmcompas-moodle-connector' ),
				__( 'Installed', 'rdmcompas-moodle-connector' ),
				__( 'Latest version', 'rdmcompas-moodle-connector' ) . $refresh_button,
			);
			include $plugin_path . 'partials/html-bridge-summary.php';
		}

		/**
		 * Function to get the RDM Compas Moodle Connector plugin configuration info.
		 *
		 * @param array  $product Array of the plugin data.
		 * @param string $plugin_path Plugin file path.
		 * @param bool   $fetch_data Should force to fetch the remote data.
		 */
		private function get_plugin_version_info( $product, $plugin_path = false, $fetch_data = false ) {
			$version_info = false;
			if ( $plugin_path ) {
				$version_info = wdm_get_plugin_version( $plugin_path );
			}
			$remote_data = $this->get_plugin_remote_version( $product, $fetch_data );
			return $this->show_plugin_version( $remote_data, $version_info );
		}

		/**
		 * Function to display the curent plugin version and compair it with remote version of plugin and show appropriate message.
		 *
		 * @param array  $remote_data Remote plugin version detials.
		 * @param string $version_info installed plugin version numbers.
		 */
		private function show_plugin_version( $remote_data, $version_info = false ) {
//            TODO fix update notice. this is a temporary fix
            $version_info = false;
			ob_start();
			if ( ! $version_info ) {
				?>
				<?php echo esc_attr( $remote_data['version'] ); ?>
				<a style='padding-left:0.5rem;' target='_blank' href="<?php echo esc_url( $remote_data['url'] ); ?>" title='<?php esc_attr_e( 'Plugin is not installed, Click to download the plugin file.', 'rdmcompas-moodle-connector' ); ?>'><?php esc_attr_e( 'Download Plugin', 'rdmcompas-moodle-connector' ); ?></a>
				<?php
			} elseif ( $remote_data['version'] ) {
				if ( version_compare( $remote_data['version'], $version_info, '>' ) ) {
					?>
					<?php echo esc_attr( $remote_data['version'] ); ?>
					<a style='padding-left:0.5rem;' target='_blank' href="<?php echo esc_url( $remote_data['url'] ); ?>" title='<?php esc_attr_e( 'Click to download the plugin file. Or you can update the from plugin page.', 'rdmcompas-moodle-connector' ); ?>'><?php echo esc_attr_e( 'Download', 'rdmcompas-moodle-connector' ); ?></a>
					<?php
				} elseif ( version_compare( $remote_data['version'], $version_info, '<=' ) ) {
					?>
					<span style='color:limegreen;'>
						<?php esc_attr_e( 'Latest version installed', 'rdmcompas-moodle-connector' ); ?>
					</span>
					<?php
				}
			} else {
				?>
				<span>
					<?php esc_attr_e( 'Not available', 'rdmcompas-moodle-connector' ); ?>
					<abbr class="help" title="<?php esc_attr_e( 'You might have invalid license key. Enter the valid licese key or Remove the invalid license key to get the plugin latest version information.', 'rdmcompas-moodle-connector' ); ?>"><i class=" dashicons dashicons-editor-help"></i></abbr>
				</span>
				<?php
			}
			return ob_get_clean();
		}

		/**
		 * Function to get the remote vesion of the product.
		 *
		 * @param array $data Array of the plugin information.
		 * @param bool  $force_remote_data Should force to fetch the remote data.
		 */
		private function get_plugin_remote_version( $data, $force_remote_data = false ) {
			$plugin_slug = $data['slug'];
			$remote_data = get_transient( 'eb_stats_' . $data['slug'] );
			if ( ! $remote_data || $force_remote_data ) {
				$l_key    = get_option( $data['key'], '' );
				$responce = wdm_request_edwiser(
					array(
						'edd_action'      => 'get_version',
						'name'            => $data['item_name'],
						'slug'            => $data['slug'],
						'current_version' => $data['current_version'],
						'license'         => $l_key,
					)
				);
				if ( 200 === $responce['status'] ) {
					$data        = $responce['data'];
					$remote_data = array(
						'version'  => isset( $data->new_version ) ? $data->new_version : '',
						'url'      => isset( $data->download_link ) ? $data->download_link : '',
						'homepage' => isset( $data->homepage ) ? $data->homepage : '',
					);
					set_transient( 'eb_stats_' . $plugin_slug, $remote_data, 60 * 60 * 24 * 7 );
				}
			}
			return array(
				'version' => isset( $remote_data['version'] ) ? $remote_data['version'] : false,
				'url'     => ! empty( $remote_data['url'] ) ? $remote_data['url'] : $remote_data['homepage'],
			);
		}
		/**
		 * Function to get the RDM Compas Moodle Connector plugin configuration information.
		 *
		 * @param string $plugin_path Plugin main file path to get the plugin information.
		 */
		private function get_server_envirment_info( $plugin_path ) {
			$course_count = \wp_count_posts( 'eb_course' );
			$data         = array(
				array(
					__( 'Wordpress Site URL:', 'rdmcompas-moodle-connector' ),
					get_home_url(),
				),
				array(
					__( 'Moodle Site URL:', 'rdmcompas-moodle-connector' ),
					wdm_edwiser_bridge_plugin_get_access_url(),
				),
				array(
					__( 'Access Token:', 'rdmcompas-moodle-connector' ),
					wdm_edwiser_bridge_plugin_get_access_token(),
				),
				array(
					__( 'Permalink Structure:', 'rdmcompas-moodle-connector' ),
					get_option( 'permalink_structure' ),
				),
				array(
					__( 'Number of Courses:', 'rdmcompas-moodle-connector' ),
					sprintf( __( 'Publish (%1$d), Draft(%2$d), Trash (%3$d), Private(%4$d)', 'rdmcompas-moodle-connector' ), $course_count->publish, $course_count->draft, $course_count->trash, $course_count->private ), // @codingStandardsIgnoreLine
				),
			);
			$headings     = array( __( 'Server Environment Information', 'rdmcompas-moodle-connector' ), '' );
			include $plugin_path . 'partials/html-bridge-summary.php';
		}

		/**
		 * Get settings array.
		 *
		 * @since  1.0.0
		 * @param text $current_section current section.
		 * @return array
		 */
		public function get_settings( $current_section = '' ) {
			$settings = apply_filters(
				'eb_licensing',
				array(
					array(
						'title' => __( 'Licenses', 'rdmcompas-moodle-connector' ),
						'type'  => 'title',
						'id'    => 'licensing_management',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'licensing_management',
					),
				)
			);

			return apply_filters( 'eb_get_settings_' . $this->_id, $settings, $current_section );
		}
	}

endif;

return new Eb_Bridge_Summary();
