<?php
/**
 * The template for displaying all single moodle courses.
 *
 * @package Edwiser Bridge.
 */

/**
 * -------------------------------------
 * INTIALIZATION START
 * Do not repalce these inititializations
 * --------------------------------------
 */

namespace app\wisdmlabs\edwiserBridge;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

//$wrapper_args = array();
//
//$eb_template = get_option( 'eb_template' );
//if ( isset( $eb_template['single_enable_right_sidebar'] ) && 'yes' === $eb_template['single_enable_right_sidebar'] ) {
//    $wrapper_args['enable_right_sidebar'] = true;
//    $wrapper_args['parentcss']            = '';
//} else {
//    $wrapper_args['enable_right_sidebar'] = false;
//    $wrapper_args['parentcss']            = 'width:100%;';
//}
//$wrapper_args['sidebar_id'] = isset( $eb_template['single_right_sidebar'] ) ? $eb_template['single_right_sidebar'] : '';

$template_loader = new EbTemplateLoader(
    edwiser_bridge_instance()->get_plugin_name(),
    edwiser_bridge_instance()->get_version()
);

/*
 * -------------------------------------
 * INTIALIZATION END
 * --------------------------------------
 **/


get_header();

if (have_posts()) {
    the_post();
    get_template_part('template-parts/breadcrumb'); ?>
    <!--intro-->
    <div style="background-image: url(<?php echo get_the_post_thumbnail_url(get_the_ID(), '16:9_xl'); ?> );">
    <div class="intro intro__top topic-training-center">
        <div class="intro__inner-container">
            <h1 class="intro__title"><?php the_title(); ?></h1>
        </div>
    </div>
    </div>
    <div id="course-<?php the_ID(); ?>" class="training-center-page rdmc-container-page-sidebar" style="column-gap:2em;justify-content: space-between;">
<!--    <button type="button" class="sidebar-toggle show-medium" onclick="sidebarToggle()">-->
<!--        <i class="fa fa-bars" aria-hidden="true"></i> --><?php //echo __('Category', 'rdm-compas-theme') ?>
<!--    </button>-->
    <div id="rdm-tc-sidebar">
        <?php $template_loader->wp_get_template_part('navigation', 'back-button'); ?>
        <?php $template_loader->wp_get_template_part( 'course-category', 'sidebar'); ?>
    </div>
    <!--content-->
<!--    <div>-->
<!--        <h2>--><?php //echo __("Training Unit Overview", "rdm-compas-theme") ?><!--</h2>-->
<!--        --><?php //get_template_part("template-parts/training-unit-metadata"); ?>
<?php        $template_loader->wp_get_template_part( 'content-single', get_post_type() ); ?>
<!--        <h2>--><?php //echo __("Summary", "rdm-compas-theme") ?><!--</h2>-->
<!--        --><?php //the_content(); ?>


        <!--        --><?php //if (comments_open()) { ?>
        <!---->
        <!--            <section class="section">-->
        <!--                <div class="section__inner-container container">-->
        <!--                    --><?php //comments_template(); ?>
        <!--                </div>-->
        <!--            </section>-->
        <!---->
        <!--        --><?php //} ?>
        <!---->
        <!--        --><?php //if (is_singular('post') && (get_previous_post_link() || get_next_post_link())) { ?>
        <!---->
        <!--            <section class="section">-->
        <!---->
        <!--                <div class="section__inner-container container">-->
        <!---->
        <!--                    --><?php //previous_post_link() ?>
        <!--                    --><?php //next_post_link() ?>
        <!---->
        <!--                </div>-->
        <!---->
        <!--            </section>-->
        <!---->
        <!--        --><?php //} ?>

<!--    </div>-->
<?php } ?>
    </div>
<?php
get_footer();
