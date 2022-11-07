<?php
/**
 * EDW PayPal settings page
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

if ( ! class_exists( 'Eb_Settings_PayPal' ) ) :

	/**
	 * Eb_Settings_PayPal.
	 */
	class Eb_Settings_PayPal extends EBSettingsPage {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->_id   = 'paypal';
			$this->label = __( 'PayPal', 'rdmcompas-moodle-connector' );

			add_filter( 'eb_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'eb_settings_' . $this->_id, array( $this, 'output' ) );
			add_action( 'eb_settings_save_' . $this->_id, array( $this, 'save' ) );
		}

		/**
		 * Output the settings.
		 *
		 * @since  1.0.0
		 */
		public function output() {
			global $current_section;

			$settings = $this->get_settings( $current_section );

			EbAdminSettings::output_fields( $settings );
		}

		/**
		 * Save settings.
		 *
		 * @since  1.0.0
		 */
		public function save() {
			global $current_section;

			$settings = $this->get_settings( $current_section );
			EbAdminSettings::save_fields( $settings );
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
				'eb_paypal_settings',
				array(
					array(
						'title' => __( 'PayPal Settings', 'rdmcompas-moodle-connector' ),
						'type'  => 'title',
						'id'    => 'paypal_options',
					),
					array(
						'title'             => __( 'PayPal Email', 'rdmcompas-moodle-connector' ),
						'desc'              => __( 'Enter your PayPal email here.', 'rdmcompas-moodle-connector' ),
						'id'                => 'eb_paypal_email',
						'css'               => 'min-width:350px;',
						'default'           => '',
						'type'              => 'email',
						'desc_tip'          => true,
						'custom_attributes' => array( 'required' => 'required' ),
					),
					array(
						'title'    => __( 'PayPal Currency', 'rdmcompas-moodle-connector' ),
						'desc'     => __( 'Select transaction currency code, Default is USD.', 'rdmcompas-moodle-connector' ),
						'id'       => 'eb_paypal_currency',
						'css'      => 'min-width:350px;',
						'default'  => '',
						'type'     => 'select',
						'desc_tip' => true,
						'options'  => array(
							'USD' => __( 'U.S. Dollar (USD)', 'rdmcompas-moodle-connector' ),
							'CAD' => __( 'Canadian Dollar (CAD)', 'rdmcompas-moodle-connector' ),
							'NZD' => __( 'New Zealand Dollar (NZD)', 'rdmcompas-moodle-connector' ),
							'HKD' => __( 'Hong Kong Dollar (HKD)', 'rdmcompas-moodle-connector' ),
							'EUR' => __( 'Euro (EUR)', 'rdmcompas-moodle-connector' ),
							'JPY' => __( 'Japanese Yen (JPY)', 'rdmcompas-moodle-connector' ),
							'MXN' => __( 'Mexican Peso (MXN)', 'rdmcompas-moodle-connector' ),
							'CHF' => __( 'Swiss Franc (CHF)', 'rdmcompas-moodle-connector' ),
							'GBP' => __( 'Pound Sterling (GBP)', 'rdmcompas-moodle-connector' ),
							'AUD' => __( 'Australian Dollar (AUD)', 'rdmcompas-moodle-connector' ),
							'PLN' => __( 'Polish Zloty (PLN)', 'rdmcompas-moodle-connector' ),
							'DKK' => __( 'Danish Krone (DKK)', 'rdmcompas-moodle-connector' ),
							'SGD' => __( 'Singapore Dollar (SGD)', 'rdmcompas-moodle-connector' ),
						),
					),
					array(
						'title'             => __( 'PayPal Country', 'rdmcompas-moodle-connector' ),
						'desc'              => __( 'Enter your country code here.', 'rdmcompas-moodle-connector' ),
						'id'                => 'eb_paypal_country_code',
						'css'               => 'min-width:350px;',
						'default'           => 'US',
						'type'              => 'text',
						'desc_tip'          => true,
						'custom_attributes' => array( 'required' => 'required' ),
					),
					array(
						'title'             => __( 'PayPal Cancel URL', 'rdmcompas-moodle-connector' ),
						'desc'              => __( 'Enter the URL used for purchase cancellations.', 'rdmcompas-moodle-connector' ),
						'id'                => 'eb_paypal_cancel_url',
						'css'               => 'min-width:350px;',
						'default'           => site_url(),
						'type'              => 'url',
						'desc_tip'          => true,
						'custom_attributes' => array( 'required' => 'required' ),
					),
					array(
						'title'             => __( 'PayPal Return URL', 'rdmcompas-moodle-connector' ),
						'desc'              => __(
							'Enter the URL used for completed purchases (a thank you page).',
							'rdmcompas-moodle-connector'
						),
						'id'                => 'eb_paypal_return_url',
						'css'               => 'min-width:350px;',
						'default'           => site_url( '/thank-you-for-purchase/ ' ),
						'type'              => 'url',
						'desc_tip'          => true,
						'custom_attributes' => array( 'required' => 'required' ),
					),
					array(
						'title'             => __( 'PayPal Notify URL', 'rdmcompas-moodle-connector' ),
						'desc'              => __( 'Enter the URL used for IPN notifications.', 'rdmcompas-moodle-connector' ),
						'id'                => 'eb_paypal_notify_url',
						'css'               => 'min-width:350px;',
						'default'           => site_url( '/eb/paypal-notify' ),
						'type'              => 'url',
						'desc_tip'          => true,
						'custom_attributes' => array( 'readonly' => 'readonly' ),
					),
					array(
						'title'           => __( 'Use PayPal Sandbox', 'rdmcompas-moodle-connector' ),
						'desc'            => __( 'Check to enable the PayPal sandbox.', 'rdmcompas-moodle-connector' ),
						'id'              => 'eb_paypal_sandbox',
						'default'         => 'no',
						'type'            => 'checkbox',
						'show_if_checked' => 'option',
						'autoload'        => false,
					),
					array(
						'type' => 'sectionend',
						'id'   => 'paypal_options',
					),
					array(
						'title' => __( 'PayPal API Credentials (Optional)', 'rdmcompas-moodle-connector' ),
						'type'  => 'title',
						'id'    => 'paypal_api_options',
						'desc'  => __( 'To use order refunds following fields are mandatory.', 'rdmcompas-moodle-connector' ),
					),
					array(
						'title'    => __( 'API username', 'rdmcompas-moodle-connector' ),
						'id'       => 'eb_api_username',
						'css'      => 'min-width:350px;',
						'default'  => '',
						'type'     => 'text',
						'autoload' => false,
					),
					array(
						'title'    => __( 'API password', 'rdmcompas-moodle-connector' ),
						'id'       => 'eb_api_password',
						'css'      => 'min-width:350px;',
						'default'  => '',
						'type'     => 'password',
						'autoload' => false,
					),
					array(
						'title'    => __( 'API signature', 'rdmcompas-moodle-connector' ),
						'id'       => 'eb_api_signature',
						'css'      => 'min-width:350px;',
						'default'  => '',
						'type'     => 'text',
						'autoload' => false,
					),
					array(
						'type' => 'sectionend',
						'id'   => 'paypal_api_options',
					),
				)
			);

			return apply_filters( 'eb_get_settings_' . $this->_id, $settings, $current_section );
		}
	}

endif;

return new Eb_Settings_PayPal();
