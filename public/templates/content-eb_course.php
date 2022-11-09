<?php
/**
 * The template for displaying course archive content.
 *
 * @package RDM Compas Moodle Connector.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// Variables.
global $post;

/*
 * Filter to get all the initial infos i.e all initial variables which will be used while showing each card of archive page.
 *
 */
$attr             = isset( $attr ) ? $attr : array();
$is_eb_my_courses = isset( $is_eb_my_courses ) ? $is_eb_my_courses : 0;
$course_data      = apply_filters( 'eb_content_course_before', $post->ID, $attr, $is_eb_my_courses );
?>

<div id="<?php echo 'post-' . get_the_ID(); ?>" <?php post_class( 'wdm-col-3-2-1 eb-course-card eb-course-col wdm-course-grid-wrap ' . $course_data['course_class'] ); ?> title="<?php echo esc_html( $course_data['h_title'] ); ?>">
	<div class="eb-grid-container">
		<div class="wdm-course-grid">

		<?php
		// If the cards are for My courses then no need of link for whole card as we are providing button at the bottom.
		if ( ! isset( $course_data['is_eb_my_courses'] ) || ( isset( $course_data['is_eb_my_courses'] ) && ! $course_data['is_eb_my_courses'] ) ) {
			?>
			<div class="wdm-course-thumbnail">
				<!-- Course card image container -->
				<div class="wdm-course-image">
					<img class="rdm-tc_course_thumbnail" src="<?php echo esc_url( $course_data['thumb_url'] ); ?>"/>
				</div>
				<div class="wdm-caption">

					<!--  -->
					<div  class="eb-cat-wrapper-new ">
					<?php
						echo wp_kses( implode( ', ', $course_data['categories'] ), \app\wisdmlabs\edwiserBridge\wdm_eb_sinlge_course_get_allowed_html_tags() );
					?>
					</div>

					<div class="eb-course-title eb-course-card-title"><a href="<?php echo esc_url( $course_data['course_url'] ); ?>" rel="bookmark"><?php the_title(); ?></a></div>

					<div>
						<p class="eb_short_desc">
							<?php echo esc_html( $course_data['short_description'] ); ?> 
						</p>
					</div>
                    <div class="rdm-tc-course-card-buttons">
                        <a class="rdm-tc-button rdm-tc-button-blue" href="<?php echo esc_url( $course_data['moodle_course_url'] ); ?>" target="_blank" rel="noopener"><?php echo __('Start course', 'rdmcompas-moodle-connector'); ?></a>
                        <a class="rdm-tc-button rdm-tc-button-gray" href="<?php echo esc_url( $course_data['course_url'] ); ?>" rel="bookmark"><?php echo __('Show more', 'rdmcompas-moodle-connector'); ?></a>
                    </div>

					<?php
					// Add_action for price type and price div.
//					do_action( 'eb_course_archive_price', $course_data );

					?>
				</div>
			</div>

			<?php

		} else {
			// My courses page cards with progress and everything.
			?>

			<a href="<?php echo esc_url( $course_data['course_url'] ); ?>" rel="bookmark" class="wdm-course-thumbnail">
			<!-- <div> -->

				<div class="wdm-course-image">
					<?php
					if ( isset( $attr ) && isset( $attr['completed'] ) && $attr['completed'] ) {
						?>
						<span class="eb_courses_completed_tag"> <?php esc_html_e( 'Completed', 'rdmcompas-moodle-connector' ); ?> </span>
						<?php
					}
					?>
					<img class="rdm-tc_course_thumbnail" src="<?php echo esc_url( $course_data['thumb_url'] ); ?>"/>
				</div>

				<div class="wdm-caption">
					<div  class="eb-cat-wrapper-new ">
						<?php
							echo wp_kses( implode( ', ', $course_data['categories'] ), \app\wisdmlabs\edwiserBridge\wdm_eb_sinlge_course_get_allowed_html_tags() );
						?>
					</div>

					<div class="eb-course-title eb-course-card-title"><?php the_title(); ?></div>

					<?php
					echo wp_kses_post( $attr['progress_btn_div'] );
					?>
				</div>
			<!-- </div> -->
			</a>

			<?php
		}

		?>
		</div>
	</div>
	<!-- .wdm-course-grid -->
</div><!-- #post -->
