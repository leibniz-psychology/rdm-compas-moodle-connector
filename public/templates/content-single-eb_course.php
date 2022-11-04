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
 * Filter to get all the initial infos i.e all initial variables which will be used while showing on single course page.
 *
 */
$single_course_data = apply_filters('eb_content_single_course_before', $post->ID);
//array:
//'eb_plugin_url'
//'categories'
//'course_institution'
//'course_contact_person'
//'course_date_start'
//'course_date_modified'
//'course_format'
//'course_target_group'
//'course_discipline'
//'course_number_participants'
//'course_duration'
//'course_required_material'
//'course_persistent_identifier'
//'course_license'
?>

<!-- Curse details wrapper. -->
<div class="rdm-tc-course-overview-wrapper">

    <!-- Course overview wrapper -->
    <div class="rdm-tc-course-overview">
        <h4><?php echo __("Overview", 'edwiser-bridge') ?></h4>
        <?php

        if (!is_search()) {
            echo '<p><strong>' . __('Target', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_target_group'] . '</p>';
            echo '<p><strong>' . __('Discipline', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_discipline'] . '</p>';
            echo '<p><strong>' . __('Duration', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_duration'] . '</p>';
            echo '<p><strong>' . __('License', 'edwiser-bridge') . '</strong>: ' . getCCLicense('CC BY 4.0') . '</p>';
            if ($single_course_data['course_required_material']) {
                echo '<p><strong>' . __('Required Material', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_required_material'] . '</p>';
            }
            if ($single_course_data['course_persistent_identifier']) {
                echo '<p><strong>' . __('Persistent Identifier', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_persistent_identifier'] . '</p>';
            }
            echo '<p><strong>' . __('Duration', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_duration'] . '</p>';
            echo '<p><strong>' . __('Last modified', 'edwiser-bridge') . '</strong>: ' . date('F j, Y', intval($single_course_data['course_date_modified'])) . '</p>';
        }
        ?>
    </div>
</div>
<!--Course summary wrapper-->
<div class="eb-course-desc-wrapper rdm-tc-course-summary">
    <h1 class="intro__title"><?php the_title(); ?></h1>
    <!--         Course image wrapper-->
    <!--    <div class="eb-course-img-wrapper">-->
    <!--    <div>-->
    <!--        <div>-->
    <!--            --><?php
    //            if (has_post_thumbnail()) {
    //                the_post_thumbnail();
    //            } else {
    //                echo '<img src="' . esc_html($single_course_data['eb_plugin_url']) . 'images/no-image.jpg" />';
    //            }
    //
//    ?>
    <!--        </div>-->
    <!--    </div>-->
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
        <h4><?php esc_html_e('Summary', 'edwiser-bridge'); ?></h4>
        <?php
        the_content();
    } ?>
    <p> <?php __('This course is offered by','edwiser-bridge') . $single_course_data['course_institution']; ?> </p>
    <?php if ($single_course_data['course_format'] == "Webinar blended-learning") { ?>
        <div class="eb-validity-wrapper">
            <div>
                <span class="dashicons dashicons-clock"></span>
            </div>
            <div>
                <?php echo $single_course_data['course_date_start']; ?>
            </div> ?>
            <?php echo '<p><strong>' . __('Contact person', 'edwiser-bridge') . '</strong>: ' . $single_course_data['course_contact_person'][0] . '</p>'; ?>
        </div>
    <?php } else { ?>
        <div class="rdm-tc-course-button">
            <a class="rdm-tc-button rdm-tc-button-blue" href="https://trainingcenter.rdm-compas.org/" target="_blank"
               rel="noopener"><?php echo __('Start now!', 'edwiser-bridge'); ?></a>
        </div>
    <?php } ?>
</div>
