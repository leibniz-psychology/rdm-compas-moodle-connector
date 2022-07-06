<?php
/**
 * The template for displaying single course content.
 *
 * This template can be overridden by copying it to yourtheme/edwiser-bridge/
 *
 * @version     1.2.0
 * @package     eb_course
 */

namespace app\wisdmlabs\edwiserBridge;

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Variables.
global $post;

/*
 * Filter to get all the initial infoe i.e all initial variables which will be used while showing on single course page.
 *
 */
$single_course_data = apply_filters('eb_content_single_course_before', $post->ID);

?>

<!--<article class="type-post hentry single-course" >-->

<!-- Curse details wrapper. -->
<div class="rdm-tc-course-overview-wrapper">

    <!-- Course summary wrapper -->
    <div class="rdm-tc-course-overview">
        <h4><?php echo __("Training Unit Overview", 'edwiser-bridge') ?></h4>
        <?php

        if (!is_search()) {

            if (count($single_course_data['categories'])) {
                ?>
                <div class="eb-cat-wrapper-new ">
						<span>
							<strong>
								<?php esc_html_e('CATEGORY: ', 'edwiser-bridge'); ?>
							</strong>
							<?php echo wp_kses(implode(', ', $single_course_data['categories']), \app\wisdmlabs\edwiserBridge\wdm_eb_sinlge_course_get_allowed_html_tags()); ?>
						</span>
                </div>
                <?php
            }
            echo '<p><strong>' . __('Format', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_format'] . '</p>';
            echo '<p><strong>' . __('Target', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_target_group'] . '</p>';
            echo '<p><strong>' . __('Duration', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_duration'] . '</p>';
//            echo '<p><strong>' . __('Institution', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_institution'] . '</p>';
            echo '<p><strong>' . __('Discipline', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_discipline'] . '</p>';
            echo '<p><strong>' . __('License', 'edwiser-bridge') . '</strong>: ' . getCCLicense('CC BY 4.0') . '</p>';
            echo '<p><strong>' . __('Created', 'edwiser-bridge') . '</strong>: ' . date('F j, Y', intval($single_course_data['course_date_created'])) . '</p>';
            ?>
            <!-- Entry title wrap -->
            <!--				<h1 class="entry-title eb_single_course_title">-->
            <!--					--><?php
//					if ( is_single() ) {
//						the_title();
//					} else {
//						?>
            <!--						<a href="--><?php //the_permalink(); ?><!--" rel="bookmark">--><?php //the_title(); ?><!--</a>-->
            <!--						--><?php
//					}
//					?>
            <!--				</h1>-->

            <!---->
            <!--				<div  class="eb-validity-wrapper">-->
            <!--					<div>-->
            <!--						<span class="dashicons dashicons-clock"></span>-->
            <!--					</div>-->
            <!--					<div>-->
            <!--						--><?php //echo wp_kses( $single_course_data['expiry_date_time'], \app\wisdmlabs\edwiserBridge\wdm_eb_sinlge_course_get_allowed_html_tags() ); ?>
            <!--					</div>-->
            <!--				</div>-->

            <?php


//				if ( ! $single_course_data['has_access'] || ! is_user_logged_in() || $single_course_data['suspended'] ) {
//					?>
            <!--					<div class='eb_single_course_price_wrapper'>-->
            <!--					--><?php
//						// Add_action for price type and price div.
//						do_action( 'eb_course_archive_price', $single_course_data );
//
//						// Echo take this course Button.
//						echo wp_kses( Eb_Payment_Manager::take_course_button( $post->ID ), \app\wisdmlabs\edwiserBridge\wdm_eb_sinlge_course_get_allowed_html_tags() );
//					?>
            <!--					</div>-->
            <!--					--><?php
//				} else {
//					?>
            <!--					<div class='eb_single_course_price_wrapper'>-->
            <!--						--><?php
//						// Echo take access course button.
//						echo wp_kses( Eb_Payment_Manager::access_course_button( $post->ID ), \app\wisdmlabs\edwiserBridge\wdm_eb_sinlge_course_get_allowed_html_tags() );
//
//						?>
            <!--					</div>-->
            <!--					--><?php
//				}

            ?>

            <?php
        }
        ?>
    </div>
</div>
<!--Course summary wrapper-->
<div class="eb-course-desc-wrapper rdm-tc-course-summary">
    <!--         Course image wrapper-->
<!--    <div class="eb-course-img-wrapper">-->
    <div>
        <div>
            <?php
            if (has_post_thumbnail()) {
                the_post_thumbnail('course_single');
            } else {
                echo '<img src="' . esc_html($single_course_data['eb_plugin_url']) . 'images/no-image.jpg" />';
            }
            ?>
        </div>
    </div>
    <?php
    if (is_search()) {
        ?>
        <div class="entry-summary">
            <?php
            the_excerpt();
            ?>
        </div>
        <?php
    } else {
        ?>
        <h4><?php esc_html_e('Course Overview', 'edwiser-bridge'); ?></h4>
        <?php
        the_content();
    }
    ?>
</div>
<!--</article>-->
<!-- #post -->
