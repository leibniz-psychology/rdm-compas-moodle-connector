<?php
/**
 * The file that defines the user profile shortcode.
 *
 * @link       https://example.com
 * @since      1.0.2
 * @deprecated 1.2.0 Use shortcode eb_user_account
 * @package    RDM Compas Moodle Connector.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
do_action( 'eb_before_customer_login_form' );
\app\wisdmlabs\edwiserBridge\wdm_eb_login_reg_show_notices();
?>
<div id="user_login" class='wdm-eb-user-login-form-wrap'>
	<?php
	if ( 'eb_register' !== $eb_action ) {
		?>
		<div class="eb-wrap-login-form wdm-eb-login-form-sec-1">
			<form method="post" class="login">
				<?php do_action( 'eb_login_form_start' ); ?>
				<p class="form-row form-row-wide eb-profile-txt-field">
					<label for="wdm_username">
						<?php esc_html_e( 'Username', 'rdmcompas-moodle-connector' ); ?>
						<span class="required">*</span>
					</label>
					<input type="text" class="input-text" placeholder="<?php esc_html_e( 'Enter user name', 'rdmcompas-moodle-connector' ); ?>" name="wdm_username" id="wdm_username" value="<?php echo esc_attr( $username ); ?>" />
				</p>
				<p class="form-row form-row-wide eb-profile-txt-field">
					<label for="wdm_password">
						<?php esc_html_e( 'Password', 'rdmcompas-moodle-connector' ); ?>
						<span class="required">*</span>
					</label>
					<input class="input-text" type="password" placeholder="<?php esc_html_e( 'Enter password', 'rdmcompas-moodle-connector' ); ?>" name="wdm_password" id="wdm_password" />
					<a class='wdm-forgott-psw-link' href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'rdmcompas-moodle-connector' ); ?></a>
				</p>
				<?php do_action( 'eb_login_form' ); ?>
				<p class="form-row">
					<?php wp_nonce_field( 'eb-login' ); ?>
					<label for="rememberme" class="inline">
						<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<?php esc_html_e( 'Remember me', 'rdmcompas-moodle-connector' ); ?>
					</label>
				</p>
				<p>
					<input type="submit" class="eb-login-button button button-primary et_pb_button et_pb_contact_submit" name="wdm_login" value="<?php esc_html_e( 'Login', 'rdmcompas-moodle-connector' ); ?>" />
				</p>
				<?php
				do_action( 'eb_login_form_end' );
				?>
			</form>
			</div>
			<?php
			if ( 'yes' === $enable_registration ) {
				?>
			<div class='wdm-eb-login-form-sec-2'>
					<p class="register-link form-row">
						<?php esc_html_e( 'Don\'t have an Account? Register one!', 'rdmcompas-moodle-connector' ); ?>
					</p>
					<a class='button wdm-eb-login-btn-scondary roll-button et_pb_button et_pb_contact_submit' href='<?php echo esc_url( $reg_link ); ?>'>
						<?php esc_html_e( 'Get Registered', 'rdmcompas-moodle-connector' ); ?>
					</a>
			</div>
					<?php
				}
				?>
		<?php
	}
	?>
	<?php
	if ( $eb_action && 'eb_register' === $eb_action && 'yes' === $enable_registration ) {
		?>
		<div class="eb-user-reg-form wdm-eb-login-form-sec-1">
			<form method="post" class="register">
				<?php do_action( 'eb_register_form_start' ); ?>
				<div class="form-row-wide eb-profile-txt-field  wdm-eb-form-row-flex">
					<p class='form-row-first wdm-eb-form-row-first'>
						<label for="reg_firstname">
							<?php esc_html_e( 'First Name', 'rdmcompas-moodle-connector' ); ?>
							<span class="required">*</span>
						</label>
						<input type="text" class="input-text" name="firstname" id="reg_firstname" value="<?php echo esc_attr( $fname ); ?>" required/>
					</p>
					<p class='form-row-last wdm-eb-form-row-last'>
						<label for="reg_lastname">
							<?php esc_html_e( 'Last Name', 'rdmcompas-moodle-connector' ); ?>
							<span class="required">*</span>
						</label>
						<input type="text" class="input-text" name="lastname" id="reg_lastname" value="<?php echo esc_attr( $lname ); ?>" required/>
					</p>
					</div>

				<p class="form-row form-row-wide eb-profile-txt-field ">
					<label for="reg_email">
						<?php esc_html_e( 'Email', 'rdmcompas-moodle-connector' ); ?>
						<span class="required">*</span>
					</label>
					<input type="email" class="input-text" name="email" id="reg_email" value="<?php echo esc_attr( $email ); ?>" required/>
				</p>
				<div class="form-row-wide eb-profile-txt-field  wdm-eb-form-row-flex">
					<p class="form-row-first wdm-eb-form-row-first">
						<label for="reg_pass">
							<?php esc_html_e( 'Password', 'rdmcompas-moodle-connector' ); ?>
							<span class="required">*</span>
						</label>
						<input type="password" class="input-text" name="user_psw" id="reg_pass" value="" required/>
					</p>
					<p class="form-row-last wdm-eb-form-row-last">
						<label for="reg_pass_confirm">
							<?php esc_html_e( 'Confirm Password', 'rdmcompas-moodle-connector' ); ?>
							<span class="required">*</span>
						</label>
						<input type="password" class="input-text" name="conf_user_psw" id="reg_pass_confirm" value="" required/>
					</p>
				</div>
				<?php
				if ( $eb_terms_and_cond ) {
					?>

				<p class="form-row form-row-wide eb-profile-txt-field ">
					<input type="checkbox" name="reg_terms_and_cond" id="reg_terms_and_cond"  required/>
					<?php esc_html_e( 'I agree to the ', 'rdmcompas-moodle-connector' ); ?>
					<span style="cursor: pointer;" id="eb_terms_cond_check"> <u><?php esc_html_e( 'Terms and Conditions', 'rdmcompas-moodle-connector' ); ?></u></span>
				</p>

				<div class="eb-user-account-terms">
					<div id = "eb-user-account-terms-content" title="<?php esc_html_e( 'Terms and Conditions', 'rdmcompas-moodle-connector' ); ?>">
						<?php echo esc_html( $eb_terms_and_cond ); ?>
					</div>
				</div>
					<?php
				}
				?>
				<!-- Spam Trap -->
				<div style="<?php echo ( is_rtl() ) ? 'right' : 'left'; ?>: -999em; position: absolute;">
					<label for="trap">
						<?php esc_html_e( 'Anti-spam', 'rdmcompas-moodle-connector' ); ?>
					</label>
					<input type="text" name="email_2" id="trap" tabindex="-1" />
				</div>

				<?php
				do_action( 'eb_register_form' );
				?>

				<p class="form-row">
					<?php wp_nonce_field( 'eb-register' ); ?>
					<input type="submit" class="eb-reg-button button button-primary et_pb_button et_pb_contact_submit" name="register" value="<?php esc_html_e( 'Register', 'rdmcompas-moodle-connector' ); ?>" />
				</p>
				<?php do_action( 'eb_register_form_end' ); ?>
			</form>
		</div>
		<div class='wdm-eb-login-form-sec-2'>
			<p class="login-link">
				<?php esc_html_e( 'Already have an account?', 'rdmcompas-moodle-connector' ); ?>
			</p>
			<a class='button wdm-eb-login-btn-scondary roll-button et_pb_button et_pb_contact_submit' href='<?php echo esc_url( \app\wisdmlabs\edwiserBridge\wdm_eb_user_account_url( $redirect_to ) ); ?>'>
				<?php esc_html_e( 'Login', 'rdmcompas-moodle-connector' ); ?>
			</a>
		</div>
		<?php
	}
	?>
</div>
<?php
do_action( 'eb_after_customer_login_form' );
