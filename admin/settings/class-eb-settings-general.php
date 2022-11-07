<?php
/**
 * EDW General Settings
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

if ( ! class_exists( 'Eb_Settings_General' ) ) :

	/**
	 * Eb_Settings_General.
	 */
	class Eb_Settings_General extends EBSettingsPage {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->_id   = 'general';
			$this->label = __( 'General', 'rdmcompas-moodle-connector' );

			add_filter( 'eb_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'eb_settings_' . $this->_id, array( $this, 'output' ) );
			add_action( 'eb_settings_save_' . $this->_id, array( $this, 'save' ) );
		}

		/**
		 * Get settings array.
		 *
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function get_settings() {
			/*
			* translators: the user account page url will go here
			*/
			$user_acc_desc = sprintf( __( 'Select user account page here. Default page is %s ', 'rdmcompas-moodle-connector' ), '<a href="' . esc_url( site_url( '/user-account' ) ) . '">' . __( 'User Account', 'rdmcompas-moodle-connector' ) . '</a>' );

			/*
			* translators: select courses page here
			*/
			$courses_page_desc = sprintf( __( 'Select courses page here. Default page is %s ', 'rdmcompas-moodle-connector' ), '<a href="' . esc_url( site_url( '/eb_courses' ) ) . '">' . __( 'Courses page', 'rdmcompas-moodle-connector' ) . '</a>' );

			/*
			* translators: My Courses page setting description.
			*/
			$redirect_desc     = sprintf( __( 'Redirect user to the My Courses page on %1$s from the %2$s page.', 'rdmcompas-moodle-connector' ), '<strong>' . __( 'Login / Registration', 'rdmcompas-moodle-connector' ) . '</strong>', '<a href="' . esc_url( site_url( '/user-account' ) ) . '">' . __( 'User Account', 'rdmcompas-moodle-connector' ) . '</a>' );
			$courses_arch_desc = sprintf( __( 'Controlls whether to Show/Hide courses archive page. ', 'rdmcompas-moodle-connector' ) . '%s', '<a href="' . esc_url( site_url( '/courses' ) ) . '">' . __( 'Courses', 'rdmcompas-moodle-connector' ) . '</a>' );



			$settings = apply_filters(
				'eb_general_settings',
				array(
					array(
						'title' => __( 'General Options', 'rdmcompas-moodle-connector' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'general_options',
					),
					array(
						'title'    => __( 'Enable Registration', 'rdmcompas-moodle-connector' ),
						'desc'     => __( 'Enable user registration on user-account page.', 'rdmcompas-moodle-connector' ),
						'id'       => 'eb_enable_registration',
						'default'  => 'no',
						'type'     => 'checkbox',
						'autoload' => false,
					),
					array(
						'title'    => __( 'User Account Page', 'rdmcompas-moodle-connector' ),
						'desc'     => '<br/>' . $user_acc_desc,
						'id'       => 'eb_useraccount_page_id',
						'type'     => 'single_select_page',
						'default'  => '',
						'css'      => 'min-width:300px;',
						'args'     => array(
							'show_option_none'  => __( 'Select a page', 'rdmcompas-moodle-connector' ),
							'option_none_value' => '',
						),
						'desc_tip' => __( 'This sets the user account page, where user can see his/her purchase history.', 'rdmcompas-moodle-connector' ),
					),
					array(
						'title'    => __( 'Select Role', 'rdmcompas-moodle-connector' ),
						'desc'     => '<br/>' .
							__( 'Select WordPress default user role for users on registration from User Account Page.', 'rdmcompas-moodle-connector' ),
						'id'       => 'eb_default_role',
						'type'     => 'select',
						'default'  => __( 'Select Role', 'rdmcompas-moodle-connector' ),
						'css'      => 'min-width:300px;',
						'options'  => \app\wisdmlabs\edwiserBridge\wdm_eb_get_all_wp_roles(),
						'desc_tip' => __( 'Select default role for users on registration from User Account Page.', 'rdmcompas-moodle-connector' ),
					),
					array(
						'title'    => __( 'Moodle User Role ID', 'rdmcompas-moodle-connector' ),
						'desc'     => '<br/>' . __( 'Moodle user role can be found here ', 'rdmcompas-moodle-connector' ) . \app\wisdmlabs\edwiserBridge\wdm_eb_get_moodle_url() . '/admin/roles/manage.php',
						'id'       => 'eb_moodle_role_id',
						'default'  => '5',
						'type'     => 'text',
						'css'      => 'min-width:300px;',
						'desc_tip' => __( 'Generally users enrolled in default student role whose role id is 5 but if the default user role id is changed then user must enter new user role id here.', 'rdmcompas-moodle-connector' ),
					),
					array(
						'title'    => __( 'Courses page', 'rdmcompas-moodle-connector' ),
						'desc'     => '<br/>' . $courses_page_desc,
						'id'       => 'eb_courses_page_id',
						'type'     => 'single_select_page',
						'default'  => '',
						'css'      => 'min-width:300px;',
						'args'     => array(
							'show_option_none'  => __( 'Select a page', 'rdmcompas-moodle-connector' ),
							'option_none_value' => '',
						),
						'desc_tip' => __( 'This sets the courses page, where user can see courses page.', 'rdmcompas-moodle-connector' ),
					),
					array(
						'title'    => __( 'Moodle Language Code', 'rdmcompas-moodle-connector' ),
						'desc'     => '<br>' . __( 'Enter language code which you get from moodle language settings. You can check language code in Moodle ', 'rdmcompas-moodle-connector' ) . '<a href="' . \app\wisdmlabs\edwiserBridge\wdm_eb_get_moodle_url() . '/local/edwiserbridge/edwiserbridge.php?tab=summary" >' . __( ' here ', 'rdmcompas-moodle-connector' ) . ' </a>' ,
						'id'       => 'eb_language_code',
						'default'  => 'en',
						'type'     => 'text',
						'css'      => 'min-width:300px;',
						// 'desc_tip' => true,
					),
					array(
						'title'    => __( 'Disable SSL certificate check', 'rdmcompas-moodle-connector' ),
						'desc'     => __( 'This option will try to ignore errors regariding SSL certificate.', 'rdmcompas-moodle-connector' ),
						'id'       => 'eb_ignore_ssl',
						'default'  => 'yes',
						'type'     => 'checkbox',
						'autoload' => false,
					),
					array(
						'title'    => __( 'Show Courses Archive page', 'rdmcompas-moodle-connector' ),
						'desc'     => $courses_arch_desc,
						'id'       => 'eb_show_archive',
						'default'  => 'yes',
						'type'     => 'checkbox',
						'autoload' => true,
					),
					array(
						'title'    => __( 'Redirect to My Courses', 'rdmcompas-moodle-connector' ),
						'desc'     => sprintf(
							/**
							* Translators: My Courses page setting description.
							*/
							__( 'Redirect user to the My Courses page on ', 'rdmcompas-moodle-connector' ) . '%s' . __( ' from the ', 'rdmcompas-moodle-connector' ) . '%s' . __( ' page.', 'rdmcompas-moodle-connector' ),
							'<strong>' . __( 'Login / Registration', 'rdmcompas-moodle-connector' ) . '</strong>',
							'<a href="' . esc_url( site_url( '/user-account' ) ) . '">' . __( 'User Account', 'rdmcompas-moodle-connector' ) . '</a>'
						),
						__( 'Redirect user to the My Courses page after login and registration', 'rdmcompas-moodle-connector' ),
						'id'       => 'eb_enable_my_courses',
						'default'  => 'no',
						'type'     => 'checkbox',
						'autoload' => false,
					),
					array(
						'title'    => __( 'My Courses Page', 'rdmcompas-moodle-connector' ),
						'desc'     => '<br/>' . sprintf(
							/**
							* Translators: My Courses page setting description.
							*/
							__( 'Select my courses page here. Default page is', 'rdmcompas-moodle-connector' ) . ' %s ',
							'<a href="' . esc_url( site_url( '/eb-my-courses' ) ) . '">' . __( 'My Courses', 'rdmcompas-moodle-connector' ) . '</a>'
						),
						'id'       => 'eb_my_courses_page_id',
						'type'     => 'single_select_page',
						'default'  => '',
						'css'      => 'min-width:300px;',
						'args'     => array(
							'show_option_none'  => __( 'Select a page', 'rdmcompas-moodle-connector' ),
							'option_none_value' => '',
						),
						'desc_tip' => __( "This sets 'My Courses' page, where the user can see all his purchased courses and access them directly. You have to use this shortcode [eb_my_courses] to create this page.", 'rdmcompas-moodle-connector' ),
					),
					array(
						'title'    => __( 'Empty My courses Page Redirect Link', 'rdmcompas-moodle-connector' ),
						'desc'     => __( 'Enter the link to where you want to redirect user from My Courses page when no course is enrolled if empty then will be redirected to the courses page', 'rdmcompas-moodle-connector' ),
						'id'       => 'eb_my_course_link',
						'default'  => '',
						'type'     => 'text',
						'css'      => 'min-width:300px;',
						'desc_tip' => true,
					),
					array(
						'title'    => __( 'Erase associated Moodle data from Moodle site', 'rdmcompas-moodle-connector' ),
						'desc'     => __( 'Erase associated Moodle data from Moodle site on erase personal data of wordpress site', 'rdmcompas-moodle-connector' ),
						'id'       => 'eb_erase_moodle_data',
						'default'  => 'no',
						'type'     => 'checkbox',
						'autoload' => false,
					),
					array(
						'type' => 'sectionend',
						'id'   => 'general_options',
					),
					array(
						'title' => __( 'Privacy Policy', 'rdmcompas-moodle-connector' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'general_privacy_policy',
					),
					array(
						'title'    => __( 'Enable terms and conditions', 'rdmcompas-moodle-connector' ),
						'desc'     => __( 'Check this to use terms and conditions checkbox on the user-account page.', 'rdmcompas-moodle-connector' ),
						'id'       => 'eb_enable_terms_and_cond',
						'default'  => 'no',
						'type'     => 'checkbox',
						'autoload' => false,
					),
					array(
						'title'    => __( 'Terms and Conditions', 'rdmcompas-moodle-connector' ),
						'desc'     => __(
							'Please enter the Terms and Conditions you want to show on user-account page.',
							'rdmcompas-moodle-connector'
						),
						'id'       => 'eb_terms_and_cond',
						'default'  => '',
						'type'     => 'textarea',
						'css'      => 'min-width:300px; min-height: 110px;',
						'desc_tip' => true,
					),
					array(
						'type' => 'sectionend',
						'id'   => 'general_privacy_policy',
					),
					array(
						'title' => __( 'Recommended Courses Settings', 'rdmcompas-moodle-connector' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'general_recommended_options',
					),
					array(
						'title'    => __( 'Show Recommended Courses', 'rdmcompas-moodle-connector' ),
						'desc'     => sprintf( __( 'Show recommended courses on eb-my-courses page.', 'rdmcompas-moodle-connector' ) ),
						'id'       => 'eb_enable_recmnd_courses',
						'default'  => 'no',
						'type'     => 'checkbox',
						'autoload' => false,
					),
					array(
						'title'    => __( 'Show Default Recommended Courses', 'rdmcompas-moodle-connector' ),
						'desc'     => sprintf( __( 'Show category wise selected recommended courses on eb-my-courses page.', 'rdmcompas-moodle-connector' ) ),
						'id'       => 'eb_show_default_recmnd_courses',
						'default'  => 'yes',
						'type'     => 'checkbox',
						'autoload' => false,
					),
					array(
						'title'    => __( 'Select Courses', 'rdmcompas-moodle-connector' ),
						'desc'     => '<br/>' . sprintf( __( 'Select courses to show in custom courses in recommended course section.', 'rdmcompas-moodle-connector' ) ),
						'id'       => 'eb_recmnd_courses',
						'type'     => 'multiselect',
						'default'  => '',
						'options'  => \app\wisdmlabs\edwiserBridge\wdm_eb_get_all_eb_sourses(),
						'desc_tip' => '',
					),
					array(
						'type' => 'sectionend',
						'id'   => 'general_recommended_options',
					),
					array(
						'title' => __( 'Refund Notification Settings', 'rdmcompas-moodle-connector' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'refund_options',
					),
					array(
						'title'    => __( 'Notify Admin', 'rdmcompas-moodle-connector' ),
						'desc'     => sprintf( __( 'Notify admin users on refund.', 'rdmcompas-moodle-connector' ) ),
						'id'       => 'eb_refund_mail_to_admin',
						'default'  => 'yes',
						'type'     => 'checkbox',
						'autoload' => false,
					),
					array(
						'title'    => __( 'Notification Email', 'rdmcompas-moodle-connector' ),
						'desc'     => '<br/>' . sprintf( __( 'Email address to send refund notification.', 'rdmcompas-moodle-connector' ) ),
						'id'       => 'eb_refund_mail',
						'type'     => 'text',
						'default'  => '',
						'desc_tip' => __( 'Specify email address to send refund notification, otherwise keep it blank.', 'rdmcompas-moodle-connector' ),
					),
					array(
						'type' => 'sectionend',
						'id'   => 'refund_options',
					),
					array(
						'title' => __( 'Usage Tracking', 'rdmcompas-moodle-connector' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'refund_options',
					),
					array(
						'title'    => __( 'Allow Usage Tracking', 'rdmcompas-moodle-connector' ),
						'desc'     => sprintf( __( 'This will help us in building more useful functionalities for you.', 'rdmcompas-moodle-connector' ) ),
						'id'       => 'eb_usage_tracking',
						'default'  => 'no',
						'type'     => 'checkbox',
						'autoload' => false,
					),
					array(
						'type' => 'cust_html',
						'html' => $this->get_Popup_Code(),
					),
					array(
						'type' => 'sectionend',
						'id'   => 'general_options',
					),

				)
			);
			return apply_filters( 'eb_get_settings_' . $this->_id, $settings );
		}


		/**
		 * Get Popup Code.
		 */
		private function get_Popup_Code() {
			ob_start(); ?>
			<div id="dialog-tnc" style="display:none;">
				<p>Here is an overview of the different data collected by Edwiser products and why it will be beneficial for the Edwiser community.</p>
				<div>
					<h3>Goal</h3>
					<ul>
						<li>To generate statistics about the usage of Edwiser products and its various features.</li>
						<li>This knowledge will help us improve those features that are popularly used by the community.</li>
						<li>In turn, help in catering to Edwiser communities needs in a better way.</li>
						<li>Provide future updates to existing products after taking into consideration the various WordPress & Moodle environments of Edwiser users.</li>
						<li>This information also be used to better debugging and the roll-out of the zero-error product</li>
					<ul>
				</div>
				<div>
					<h3>Things we would NEVER do</h3>
					<ul>
						<li>Edwiser would never collect any personal or sensitive information like email address, user results, etc.</li>
						<li>Nor would we collect any type of information that could expose the personal information of you or your students,</li>
					</ul>
				</div>
				<div>
					<h3>Data Collected during this process </h3>
					<ul>
						<li>The data is automatically gathered unless disabled within the product.</li>
						<li>All the data points mentioned here may not be included as part of all Edwiser products some of these are product specific. The ones which are specific to the product will be explicitly mentioned below.</li>
					</ul>
				</div>
				<h4> The Data collected primarily falls under the following categories:</h4>
				<div>
					<h3>Site Details</h3>
					<ul>
						<li>Information like Edwiser plugin settings, Site URL, Moodle Version, Active WordPress theme, Active Moodle Theme, Active WordPress plugins, Active Moodle plugins, Total Courses, Categories & Users, etc.</li>
						<li>This information helps us understand the WordPress and Moodle environment used by Edwiser users and accordingly develop solutions that would work well in these environments.</li>
					</ul>
				</div>
				<div>
					<h3>Debug</h3>
					<p>Many times we end up losing a lot of time when it comes to resolving issues that arise on the sites of a few WordPress and Moodle users.</p>
					<p>Usually, these WordPress and Moodle sites are hosted on a shared hosting service, and without the necessary information about the server doing any debugging could cause more issues.</p>
					<p>To reduce the time lost in debugging an issue and to always provide a stable solution to you we need the following details,</p>
					<ul>
						<li><i>Installed Plugins:</i> To check whether 3rd party plugins are causing any conflicts with Edwiser products.</li>
						<li><i>Product Settings:</i> To understand the various features used by Edwiser users,</li>
					</ul>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}





		/**
		 * Save settings.
		 *
		 * @since  1.0.0
		 */
		public function save() {
			$settings = $this->get_settings();
			EbAdminSettings::save_fields( $settings );
		}
	}
endif;

return new Eb_Settings_General();
