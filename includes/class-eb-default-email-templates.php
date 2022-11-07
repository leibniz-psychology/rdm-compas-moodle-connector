<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link       https://example.com
 * @since      1.0.0
 * @package    RDM Compas Moodle Connector.
 */

namespace app\wisdmlabs\edwiserBridge;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Eb_Default_Email_Template' ) ) {

	/**
	 * Email temp.
	 */
	class Eb_Default_Email_Template {

		/**
		 * Preapares the default new user account creation on moodle and WP email
		 * notification tempalte and subject
		 *
		 * @param type $tmpl_id temaplte optoin key for the new user template.
		 * @param type $restore boolean value to restore the email temaplte or not.
		 * @return array returns the array of the email tempalte content and subject.
		 */
		public function new_user_acoount( $tmpl_id, $restore = false ) {
			$data = get_option( $tmpl_id );
			if ( $data && ! $restore ) {
				return $data;
			}
			$data = array(
				'subject' => esc_html__( 'New User Account Details', 'rdmcompas-moodle-connector' ),
				'content' => $this->get_new_user_account_template(),
			);
			return $data;
		}

		/**
		 * Preapares the default link moodle account email notification
		 * tempalte and subject
		 *
		 * @param type $tmpl_id temaplte optoin key for the new user template.
		 * @param type $restore boolean value to restore the email temaplte or not.
		 * @return array returns the array of the email tempalte content and subject
		 */
		public function link_wp_moodle_account( $tmpl_id, $restore = false ) {
			$data = get_option( $tmpl_id );
			if ( $data && ! $restore ) {
				return $data;
			}
			$data = array(
				'subject' => esc_html__( 'Your learning account is linked with moodle', 'rdmcompas-moodle-connector' ),
				'content' => $this->get_link_wp_moodle_account_template(),
			);
			return $data;
		}

		/**
		 * Preapares the default new moolde account creation email notification
		 * tempalte and subject
		 *
		 * @param type $tmpl_id temaplte optoin key for the new user template.
		 * @param type $restore boolean value to restore the email temaplte or not.
		 * @return array returns the array of the email tempalte content and subject
		 */
		public function link_new_moodle_account( $tmpl_id, $restore = false ) {
			$data = get_option( $tmpl_id );
			if ( $data && ! $restore ) {
				return $data;
			}
			$data = array(
				'subject' => esc_html__( 'Your Learning Account Credentials', 'rdmcompas-moodle-connector' ),
				'content' => $this->get_link_new_moodle_account_template(),
			);
			return $data;
		}

		/**
		 * Preapares the default new course order creation email notification
		 * tempalte and subjec
		 *
		 * @param type $tmpl_id temaplte optoin key for the new user template.
		 * @param type $restore boolean value to restore the email temaplte or not.
		 * @return array returns the array of the email tempalte content and subject
		 */
		public function order_complete( $tmpl_id, $restore = false ) {
			$data = get_option( $tmpl_id );
			if ( $data && ! $restore ) {
				return $data;
			}
			$data = array(
				'subject' => esc_html__( 'Your order completed successfully.', 'rdmcompas-moodle-connector' ),
				'content' => $this->get_order_complete_template(),
			);
			return $data;
		}

		/**
		 * Preapares the default course access expire email notification
		 * email tempalte and subject
		 *
		 * @param type $tmpl_id temaplte optoin key for the new user template.
		 * @param type $restore boolean value to restore the email temaplte or not.
		 * @return array returns the array of the email tempalte content and subject.
		 */
		public function course_access_expired( $tmpl_id, $restore = false ) {
			$data = get_option( $tmpl_id );
			if ( $data && ! $restore ) {
				return $data;
			}
			$data = array(
				'subject' => esc_html__( 'Course access expired.', 'rdmcompas-moodle-connector' ),
				'content' => $this->get_course_access_expired_template(),
			);
			return $data;
		}

		/**
		 * Preapares the default refund completion email for the user who placed this order
		 * notification tempalte and subject
		 *
		 * @param type $tmpl_id temaplte optoin key for the new user template.
		 * @param type $restore boolean value to restore the email temaplte or not.
		 * @return array returns the array of the email tempalte content and subject.
		 */
		public function notify_user_on_order_refund( $tmpl_id, $restore = false ) {
			$data = get_option( $tmpl_id );
			if ( $data && ! $restore ) {
				return $data;
			}
			$data = array(
				'subject' => esc_html__( 'Order refund notification', 'rdmcompas-moodle-connector' ),
				'content' => $this->user_refunded_notification_template(),
			);
			return $data;
		}

		/**
		 * Preapares the default refund completion email for all the admins
		 * notification tempalte and subject
		 *
		 * @param type $tmpl_id temaplte optoin key for the new user template.
		 * @param type $restore boolean value to restore the email temaplte or not.
		 * @return array returns the array of the email tempalte content and subject.
		 */
		public function notify_admin_on_order_refund( $tmpl_id, $restore = false ) {
			$data = get_option( $tmpl_id );
			if ( $data && ! $restore ) {
				return $data;
			}
			$data = array(
				'subject' => esc_html__( 'Order refund notification', 'rdmcompas-moodle-connector' ),
				'content' => $this->admin_refunded_notification_template(),
			);
			return $data;
		}


		/**
		 * Preapares the default refund completion email for all the admins
		 * notification tempalte and subject
		 *
		 * @param type $tmpl_id temaplte optoin key for the new user template.
		 * @param type $restore boolean value to restore the email temaplte or not.
		 * @return array returns the array of the email tempalte content and subject.
		 */
		public function moodle_enrollment_trigger( $tmpl_id, $restore = false ) {
			$data = get_option( $tmpl_id );
			if ( $data && ! $restore ) {
				return $data;
			}
			$data = array(
				'subject' => esc_html__( 'Moodle Course Enrollment', 'rdmcompas-moodle-connector' ),
				'content' => $this->moodle_enrollment_trigger_template(),
			);
			return $data;
		}


		/**
		 * Preapares the default refund completion email for all the admins
		 * notification tempalte and subject
		 *
		 * @param type $tmpl_id temaplte optoin key for the new user template.
		 * @param type $restore boolean value to restore the email temaplte or not.
		 * @return array returns the array of the email tempalte content and subject.
		 */
		public function moodle_unenrollment_trigger( $tmpl_id, $restore = false ) {
			$data = get_option( $tmpl_id );
			if ( $data && ! $restore ) {
				return $data;
			}
			$data = array(
				'subject' => esc_html__( 'Moodle Course Un-Enrollment', 'rdmcompas-moodle-connector' ),
				'content' => $this->moodle_unenrollment_trigger_template(),
			);
			return $data;
		}


		/**
		 * Preapares the default refund completion email for all the admins
		 * notification tempalte and subject
		 *
		 * @param type $tmpl_id temaplte optoin key for the new user template.
		 * @param type $restore boolean value to restore the email temaplte or not.
		 * @return array returns the array of the email tempalte content and subject.
		 */
		public function user_deletion_trigger( $tmpl_id, $restore = false ) {
			$data = get_option( $tmpl_id );
			if ( $data && ! $restore ) {
				return $data;
			}
			$data = array(
				'subject' => esc_html__( 'User Account Deleted', 'rdmcompas-moodle-connector' ),
				'content' => $this->moodle_user_deletion_trigger_template(),
			);
			return $data;
		}


		/**
		 * Prepares the html template with constants for the new WP and moodle user account creation.
		 *
		 * @return html returns the email template body content for the new user.
		 * acount creation on moodle and WP
		 */
		private function get_new_user_account_template() {
			ob_start();
			?>
			<div style="background-color: #efefef; width: 100%; -webkit-text-size-adjust: none !important; margin: 0; padding: 70px 70px 70px 70px;">
				<table id="template_container" style="padding-bottom: 20px; box-shadow: 0 0 0 3px rgba(0,0,0,0.025) !important; border-radius: 6px !important; background-color: #dfdfdf;" border="0" width="600" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="background-color: #465c94; border-top-left-radius: 6px !important; border-top-right-radius: 6px !important; border-bottom: 0; font-family: Arial; font-weight: bold; line-height: 100%; vertical-align: middle;">
								<h1 style="color: white; margin: 0; padding: 28px 24px; text-shadow: 0 1px 0 0; display: block; font-family: Arial; font-size: 30px; font-weight: bold; text-align: left; line-height: 150%;"><?php esc_html_e( 'Your Learning Account Credentials', 'rdmcompas-moodle-connector' ); ?></h1>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px; background-color: #dfdfdf; border-radius: 6px !important;" align="center" valign="top">
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
									printf(
										esc_html__( 'Hi', 'rdmcompas-moodle-connector' ) . '%s',
										'{FIRST_NAME}'
									);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Thanks for creating an account on ', 'rdmcompas-moodle-connector' ) . '%s' . esc_html__( '. Your username is', 'rdmcompas-moodle-connector' ) . '%s.',
											'{SITE_NAME}',
											'<strong> {USER_NAME}</strong>'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Your password has been automatically generated: ', 'rdmcompas-moodle-connector' ) . '%s.',
											'<strong>{USER_PASSWORD}</strong>'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'You can access your account here:', 'rdmcompas-moodle-connector' ) . ' %s.',
											'<span style="color: #0000ff;">{USER_ACCOUNT_PAGE_LINK}</span>'
										);
									?>
								</div></td>
						</tr>
						<tr>
							<td style="text-align: center; border-top: 0; -webkit-border-radius: 6px;" align="center" valign="top"><span style="font-family: Arial; font-size: 12px;">{SITE_NAME}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			return '<div>' . ob_get_clean() . '</div>';
		}

		/**
		 * Prepares the html template with constants for the new moodle user account creation.
		 *
		 * @return html returns the email template body content for the new user.
		 * acount creation on moodle.
		 */
		private function get_link_new_moodle_account_template() {
			ob_start();
			?>
			<div style="background-color: #efefef; width: 100%; -webkit-text-size-adjust: none !important; margin: 0; padding: 70px 70px 70px 70px;">
				<table id="template_container" style="padding-bottom: 20px; box-shadow: 0 0 0 3px rgba(0,0,0,0.025) !important; border-radius: 6px !important; background-color: #dfdfdf;" border="0" width="600" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="background-color: #465c94; border-top-left-radius: 6px !important; border-top-right-radius: 6px !important; border-bottom: 0; font-family: Arial; font-weight: bold; line-height: 100%; vertical-align: middle;">
								<h1 style="color: white; margin: 0; padding: 28px 24px; text-shadow: 0 1px 0 0; display: block; font-family: Arial; font-size: 30px; font-weight: bold; text-align: left; line-height: 150%;">
									<?php esc_html_e( 'Your Learning Account Credentials', 'rdmcompas-moodle-connector' ); ?>
								</h1>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px; background-color: #dfdfdf; border-radius: 6px !important;" align="center" valign="top">
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											'Hi %s',
											'{FIRST_NAME}'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										esc_html_e( 'A learning account is linked to your profile.Use credentials given below while accessing your courses.', 'rdmcompas-moodle-connector' );
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Username: ', 'rdmcompas-moodle-connector' ) . '%s',
											'<strong>{USER_NAME}</strong>'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
									printf(
										esc_html__( 'Password: ', 'rdmcompas-moodle-connector' ) . '%s',
										'<strong>{USER_PASSWORD} </strong>'
									);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'You can purchase &amp; access courses here: ', 'rdmcompas-moodle-connector' ) . '%s.',
											'<span style="color: #0000ff;">{COURSES_PAGE_LINK}</span>'
										);
									?>
								</div></td>
						</tr>
						<tr>
							<td style="text-align: center; border-top: 0; -webkit-border-radius: 6px;" align="center" valign="top"><span style="font-family: Arial; font-size: 12px;">{SITE_NAME}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			return '<div>' . ob_get_clean() . '</div>';
		}

		/**
		 * Prepares the html template with constants for the linking moodle user account with WP.
		 *
		 * @return html returns the email template body content for the linking user.
		 * acount to moodle
		 */
		private function get_link_wp_moodle_account_template() {
			ob_start();
			?>
			<div style="background-color: #efefef; width: 100%; -webkit-text-size-adjust: none !important; margin: 0; padding: 70px 70px 70px 70px;">
				<table id="template_container" style="padding-bottom: 20px; box-shadow: 0 0 0 3px rgba(0,0,0,0.025) !important; border-radius: 6px !important; background-color: #dfdfdf;" border="0" width="600" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="background-color: #465c94; border-top-left-radius: 6px !important; border-top-right-radius: 6px !important; border-bottom: 0; font-family: Arial; font-weight: bold; line-height: 100%; vertical-align: middle;">
								<h1 style="color: white; margin: 0; padding: 28px 24px; text-shadow: 0 1px 0 0; display: block; font-family: Arial; font-size: 30px; font-weight: bold; text-align: left; line-height: 150%;">
									<?php esc_html_e( 'Your learning account is linked with moodle', 'rdmcompas-moodle-connector' ); ?>
								</h1>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px; background-color: #dfdfdf; border-radius: 6px !important;" align="center" valign="top">
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Hi ', 'rdmcompas-moodle-connector' ) . '%s',
											'{FIRST_NAME}'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										esc_html_e( 'A learning account is linked to your moodle profile.', 'rdmcompas-moodle-connector' );
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'You can purchase &amp; access courses here: ', 'rdmcompas-moodle-connector' ) . '%s.',
											'<span style="color: #0000ff;">{COURSES_PAGE_LINK}</span>'
										);
									?>
								</div>
							</td>
						</tr>
						<tr>
							<td style="text-align: center; border-top: 0; -webkit-border-radius: 6px;" align="center" valign="top"><span style="font-family: Arial; font-size: 12px;">{SITE_NAME}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			return '<div>' . ob_get_clean() . '</div>';
		}

		/**
		 * Prepares the html template with constants for the new course order
		 * creation
		 *
		 * @return html returns the email template body content for the new.
		 * course order creation
		 */
		private function get_order_complete_template() {
			ob_start();
			?>
			<div style="background-color: #efefef; width: 100%; -webkit-text-size-adjust: none !important; margin: 0; padding: 70px 70px 70px 70px;">
				<table id="template_container" style="padding-bottom: 20px; box-shadow: 0 0 0 3px rgba(0,0,0,0.025) !important; border-radius: 6px !important; background-color: #dfdfdf;" border="0" width="600" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="background-color: #465c94; border-top-left-radius: 6px !important; border-top-right-radius: 6px !important; border-bottom: 0; font-family: Arial; font-weight: bold; line-height: 100%; vertical-align: middle;">
								<h1 style="color: white; margin: 0; padding: 28px 24px; text-shadow: 0 1px 0 0; display: block; font-family: Arial; font-size: 30px; font-weight: bold; text-align: left; line-height: 150%;">
									<?php esc_html_e( 'Your order completed successfully.', 'rdmcompas-moodle-connector' ); ?>
								</h1>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px; background-color: #dfdfdf; border-radius: 6px !important;" align="center" valign="top">
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Hi ', 'rdmcompas-moodle-connector' ) . '%s',
											'{FIRST_NAME}'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Thanks for purchasing ', 'rdmcompas-moodle-connector' ) . '%s course.',
											'<strong>{COURSE_NAME}</strong>'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Your order with ID ', 'rdmcompas-moodle-connector' ) . '%s' . esc_html__( ' completed successfully.', 'rdmcompas-moodle-connector' ),
											'<strong>{ORDER_ID}</strong>'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'You can access your account here: ', 'rdmcompas-moodle-connector' ) . '%s.',
											'<span style="color: #0000ff;">{USER_ACCOUNT_PAGE_LINK}</span>'
										);
									?>
								</div></td>
						</tr>
						<tr>
							<td style="text-align: center; border-top: 0; -webkit-border-radius: 6px;" align="center" valign="top"><span style="font-family: Arial; font-size: 12px;">{SITE_NAME}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			return '<div>' . ob_get_clean() . '</div>';
		}

		/**
		 * Prepares the html template with constants for the course access expire
		 * creation
		 *
		 * @return html returns the email template body content for the course.
		 * access expire
		 */
		private function get_course_access_expired_template() {
			ob_start();
			?>
			<div style="background-color: #efefef; width: 100%; -webkit-text-size-adjust: none !important; margin: 0; padding: 70px 70px 70px 70px;">
				<table id="template_container" style="padding-bottom: 20px; box-shadow: 0 0 0 3px rgba(0,0,0,0.025) !important; border-radius: 6px !important; background-color: #dfdfdf;" border="0" width="600" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="background-color: #465c94; border-top-left-radius: 6px !important; border-top-right-radius: 6px !important; border-bottom: 0; font-family: Arial; font-weight: bold; line-height: 100%; vertical-align: middle;">
								<h1 style="color: white; margin: 0; padding: 28px 24px; text-shadow: 0 1px 0 0; display: block; font-family: Arial; font-size: 30px; font-weight: bold; text-align: left; line-height: 150%;">
									<?php
										printf(
											'Your %s' . esc_html__( ' course access is expired.', 'rdmcompas-moodle-connector' ),
											'{COURSE_NAME}'
										);
									?>
								</h1>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px; background-color: #dfdfdf; border-radius: 6px !important;" align="center" valign="top">
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Hi ', 'rdmcompas-moodle-connector' ) . '%s',
											'{FIRST_NAME}'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Your Subscription for ', 'rdmcompas-moodle-connector' ) . '%s' . esc_html__( ' course has expired.', 'rdmcompas-moodle-connector' ),
											'{COURSE_NAME}'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Please purchase the course again to continue with it. ', 'rdmcompas-moodle-connector' ) . '%s' . esc_html__( ' to purchase now!', 'rdmcompas-moodle-connector' ),
											'{WP_COURSE_PAGE_LINK}'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php esc_html_e( 'Thank you!', 'rdmcompas-moodle-connector' ); ?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div></td>
						</tr>
						<tr>
							<td style="text-align: center; border-top: 0; -webkit-border-radius: 6px;" align="center" valign="top"><span style="font-family: Arial; font-size: 12px;">{SITE_NAME}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			return '<div>' . ob_get_clean() . '</div>';
		}


		/**
		 * User refund initiated notification email.
		 */
		public function user_refunded_notification_template() {
			ob_start();
			?>
			<div style="background-color: #efefef; width: 100%; padding: 70px 70px 70px 70px; margin: auto; height: auto;">
				<table id="template_container" style="padding-bottom: 20px; box-shadow: 1px 2px 0px 1px #d0d0d0; border-radius: 6px !important; background-color: #dfdfdf; margin: auto;" border="0" width="600" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="background-color: #465c94; border-radius: 6px 6px 0px 0px; border-bottom: 0; font-family: Arial;">
								<h1 style="color: white; margin: 0; padding: 28px 24px; text-shadow: 0 1px 0 0; display: block; font-family: Arial; font-size: 30px; font-weight: bold; text-align: left; line-height: 150%;">
									<?php
									printf( esc_html__( 'Your order ', 'rdmcompas-moodle-connector' ) . '%s ' . esc_html__( ' has been successfully refunded.', 'rdmcompas-moodle-connector' ), '{ORDER_ID}' );
									?>
								</h1>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px; background-color: #dfdfdf; border-radius: 6px !important;" align="center" valign="top">
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php printf( esc_html__( 'Hello ', 'rdmcompas-moodle-connector' ) . '%s %s,', '{FIRST_NAME}', '{LAST_NAME}' ); ?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
									printf(
										esc_html__( 'This is to inform you that, The amount ', 'rdmcompas-moodle-connector' ) . '%s ' . esc_html__( '  has been refunded successfully, against the order ', 'rdmcompas-moodle-connector' ) . '%s by {SITE_NAME}.',
										'{CURRENT_REFUNDED_AMOUNT}',
										'{ORDER_ID}',
										'{SITE_NAME}'
									);
									?>
								</div>
								<div></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php printf( esc_html__( 'Order ', 'rdmcompas-moodle-connector' ) . '%s' . esc_html__( ' Details:', 'rdmcompas-moodle-connector' ), '{ORDER_ID}' ); ?>
								</div>
								<div></div>
								<div style="font-family: Arial;">
									<table style="border-collapse: collapse;">
										<tbody>
											<tr style="border: 1px solid #465b94; padding: 5px;">
												<td style="border: 1px solid #465b94; padding: 5px;">
													<?php esc_html_e( 'Order Item', 'rdmcompas-moodle-connector' ); ?>
												</td>
												<td style="border: 1px solid #465b94; padding: 5px;">
													{ORDER_ITEM}
												</td>
											</tr>
											<tr style="border: 1px solid #465b94; padding: 5px;">
												<td style="border: 1px solid #465b94; padding: 5px;">
													<?php esc_html_e( 'Total Amount Paid', 'rdmcompas-moodle-connector' ); ?>
												</td>
												<td style="border: 1px solid #465b94; padding: 5px;">
													{TOTAL_AMOUNT_PAID}
												</td>
											</tr>
											<tr style="border: 1px solid #465b94; padding: 5px;">
												<td style="border: 1px solid #465b94; padding: 5px;">
													<?php esc_html_e( 'Current Refunded Amount', 'rdmcompas-moodle-connector' ); ?>
												</td>
												<td style="border: 1px solid #465b94; padding: 5px;">
													{CURRENT_REFUNDED_AMOUNT}
												</td>
											</tr>
											<tr style="border: 1px solid #465b94; padding: 5px;">
												<td style="border: 1px solid #465b94; padding: 5px;">
													<?php esc_html_e( 'Total Refunded Amount', 'rdmcompas-moodle-connector' ); ?>
												</td>
												<td style="border: 1px solid #465b94; padding: 5px;">
													{TOTAL_REFUNDED_AMOUNT}
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
							</td>
						</tr>
						<tr>
							<td style="text-align: center; border-top: 0; -webkit-border-radius: 6px;" align="center" valign="top"><span style="font-family: Arial; font-size: 12px;">{SITE_NAME}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			return '<div>' . ob_get_clean() . '</div>';
		}


		/**
		 * Notification dend to admin on refund initiation.
		 */
		public function admin_refunded_notification_template() {
			ob_start();
			?>
			<div style="background-color: #efefef; width: 100%; padding: 70px 70px 70px 70px; margin: auto; height: auto;">
				<table id="template_container" style="padding-bottom: 20px; box-shadow: 1px 2px 0px 1px #d0d0d0; border-radius: 6px !important; background-color: #dfdfdf; margin: auto;" border="0" width="600" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="background-color: #465c94; border-radius: 6px 6px 0px 0px; border-bottom: 0; font-family: Arial;">
								<h1 style="color: white; margin: 0; padding: 28px 24px; text-shadow: 0 1px 0 0; display: block; font-family: Arial; font-size: 30px; font-weight: bold; text-align: left; line-height: 150%;">
								<?php printf( esc_html__( 'Refund notification for the order id: ', 'rdmcompas-moodle-connector' ) . '%s.', '{ORDER_ID}' ); ?>
								</h1>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px; background-color: #dfdfdf; border-radius: 6px !important;" align="center" valign="top">
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									Hello,</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
								<?php printf( esc_html__( 'This is to inform you that, Refund for the order id ', 'rdmcompas-moodle-connector' ) . '%s' . esc_html__( ' has been ', 'rdmcompas-moodle-connector' ) . '%s.', '{ORDER_ID}', '{ORDER_REFUND_STATUS}' ); ?>
									.
								</div>
								<div></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
								<?php printf( esc_html__( 'Order ', 'rdmcompas-moodle-connector' ) . '%s' . esc_html__( ' Details:', 'rdmcompas-moodle-connector' ), '{ORDER_ID}' ); ?>
								</div>
								<div></div>
								<div style="font-family: Arial;">
									<table style="border-collapse: collapse;">
										<tbody>
											<tr style="border: 1px solid #465b94; padding: 5px;">
												<td style="border: 1px solid #465b94; padding: 5px;"> <?php esc_html_e( 'Customer Details', 'rdmcompas-moodle-connector' ); ?></td>
												<td style="border: 1px solid #465b94; padding: 5px;">{CUSTOMER_DETAILS}</td>
											</tr>
											<tr style="border: 1px solid #465b94; padding: 5px;">
												<td style="border: 1px solid #465b94; padding: 5px;"> <?php esc_html_e( 'Order Item', 'rdmcompas-moodle-connector' ); ?></td>
												<td style="border: 1px solid #465b94; padding: 5px;">{ORDER_ITEM}</td>
											</tr>
											<tr style="border: 1px solid #465b94; padding: 5px;">
												<td style="border: 1px solid #465b94; padding: 5px;"> <?php esc_html_e( 'Total paid amount', 'rdmcompas-moodle-connector' ); ?></td>
												<td style="border: 1px solid #465b94; padding: 5px;">{TOTAL_AMOUNT_PAID}</td>
											</tr>
											<tr style="border: 1px solid #465b94; padding: 5px;">
												<td style="border: 1px solid #465b94; padding: 5px;"> <?php esc_html_e( 'Current Refunded Amount', 'rdmcompas-moodle-connector' ); ?></td>
												<td style="border: 1px solid #465b94; padding: 5px;">{CURRENT_REFUNDED_AMOUNT}</td>
											</tr>
											<tr style="border: 1px solid #465b94; padding: 5px;">
												<td style="border: 1px solid #465b94; padding: 5px;"> <?php esc_html_e( 'Total Refunded Amount', 'rdmcompas-moodle-connector' ); ?></td>
												<td style="border: 1px solid #465b94; padding: 5px;">{TOTAL_REFUNDED_AMOUNT}</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div></td>
						</tr>
						<tr>
							<td style="text-align: center; border-top: 0; -webkit-border-radius: 6px;" align="center" valign="top"><span style="font-family: Arial; font-size: 12px;">{SITE_NAME}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			return '<div>' . ob_get_clean() . '</div>';
		}


		/**
		 * Send enrollment email on  enrollment request from Moodle.
		 */
		public function moodle_enrollment_trigger_template() {
			ob_start();
			?>
			<div style="background-color: #efefef; width: 100%; -webkit-text-size-adjust: none !important; margin: 0; padding: 70px 70px 70px 70px;">
				<table id="template_container" style="padding-bottom: 20px; box-shadow: 0 0 0 3px rgba(0,0,0,0.025) !important; border-radius: 6px !important; background-color: #dfdfdf;" border="0" width="600" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="background-color: #465c94; border-top-left-radius: 6px !important; border-top-right-radius: 6px !important; border-bottom: 0; font-family: Arial; font-weight: bold; line-height: 100%; vertical-align: middle;">
								<h1 style="color: white; margin: 0; padding: 28px 24px; text-shadow: 0 1px 0 0; display: block; font-family: Arial; font-size: 30px; font-weight: bold; text-align: left; line-height: 150%;">
									<?php esc_html_e( 'Course Enrollment.', 'rdmcompas-moodle-connector' ); ?>
								</h1>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px; background-color: #dfdfdf; border-radius: 6px !important;" align="center" valign="top">
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Hi ', 'rdmcompas-moodle-connector' ) . '%s',
											'{FIRST_NAME}'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'You are successfully enrolled in ', 'rdmcompas-moodle-connector' ) . '%s course.',
											'<strong>{COURSE_NAME}</strong>'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>

								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'You can access your account here: ', 'rdmcompas-moodle-connector' ) . '%s.',
											'<span style="color: #0000ff;">{USER_ACCOUNT_PAGE_LINK}</span>'
										);
									?>
								</div></td>
						</tr>
						<tr>
							<td style="text-align: center; border-top: 0; -webkit-border-radius: 6px;" align="center" valign="top"><span style="font-family: Arial; font-size: 12px;">{SITE_NAME}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			return '<div>' . ob_get_clean() . '</div>';
		}


		/**
		 * Send Unenrollment email on  unenrollment request from Moodle.
		 */
		public function moodle_unenrollment_trigger_template() {
			ob_start();
			?>
			<div style="background-color: #efefef; width: 100%; -webkit-text-size-adjust: none !important; margin: 0; padding: 70px 70px 70px 70px;">
				<table id="template_container" style="padding-bottom: 20px; box-shadow: 0 0 0 3px rgba(0,0,0,0.025) !important; border-radius: 6px !important; background-color: #dfdfdf;" border="0" width="600" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="background-color: #465c94; border-top-left-radius: 6px !important; border-top-right-radius: 6px !important; border-bottom: 0; font-family: Arial; font-weight: bold; line-height: 100%; vertical-align: middle;">
								<h1 style="color: white; margin: 0; padding: 28px 24px; text-shadow: 0 1px 0 0; display: block; font-family: Arial; font-size: 30px; font-weight: bold; text-align: left; line-height: 150%;">
									<?php esc_html_e( 'Course Un-Enrollment.', 'rdmcompas-moodle-connector' ); ?>
								</h1>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px; background-color: #dfdfdf; border-radius: 6px !important;" align="center" valign="top">
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Hi ', 'rdmcompas-moodle-connector' ) . '%s',
											'{FIRST_NAME}'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'You are un-enrolled from ', 'rdmcompas-moodle-connector' ) . '%s course.',
											'<strong>{COURSE_NAME}</strong>'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>

								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'You can access your account here: ', 'rdmcompas-moodle-connector' ) . '%s.',
											'<span style="color: #0000ff;">{USER_ACCOUNT_PAGE_LINK}</span>'
										);
									?>
								</div></td>
						</tr>
						<tr>
							<td style="text-align: center; border-top: 0; -webkit-border-radius: 6px;" align="center" valign="top"><span style="font-family: Arial; font-size: 12px;">{SITE_NAME}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			return '<div>' . ob_get_clean() . '</div>';
		}



		/**
		 * Send User deletion email on  user deletion request from Moodle.
		 */
		public function moodle_user_deletion_trigger_template() {
			ob_start();
			?>
			<div style="background-color: #efefef; width: 100%; -webkit-text-size-adjust: none !important; margin: 0; padding: 70px 70px 70px 70px;">
				<table id="template_container" style="padding-bottom: 20px; box-shadow: 0 0 0 3px rgba(0,0,0,0.025) !important; border-radius: 6px !important; background-color: #dfdfdf;" border="0" width="600" cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<td style="background-color: #465c94; border-top-left-radius: 6px !important; border-top-right-radius: 6px !important; border-bottom: 0; font-family: Arial; font-weight: bold; line-height: 100%; vertical-align: middle;">
								<h1 style="color: white; margin: 0; padding: 28px 24px; text-shadow: 0 1px 0 0; display: block; font-family: Arial; font-size: 30px; font-weight: bold; text-align: left; line-height: 150%;">
									<?php esc_html_e( 'User Deleted', 'rdmcompas-moodle-connector' ); ?>
								</h1>
							</td>
						</tr>
						<tr>
							<td style="padding: 20px; background-color: #dfdfdf; border-radius: 6px !important;" align="center" valign="top">
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Hi ', 'rdmcompas-moodle-connector' ) . '%s',
											'{FIRST_NAME}'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;">
									<?php
										printf(
											esc_html__( 'Your user account is deleted from ', 'rdmcompas-moodle-connector' ) . '%s.',
											'<strong>{SITE_URL}</strong>'
										);
									?>
								</div>
								<div style="font-family: Arial; font-size: 14px; line-height: 150%; text-align: left;"></div>

							</td>
						</tr>
						<tr>
							<td style="text-align: center; border-top: 0; -webkit-border-radius: 6px;" align="center" valign="top"><span style="font-family: Arial; font-size: 12px;">{SITE_NAME}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			return '<div>' . ob_get_clean() . '</div>';
		}
	}
}
