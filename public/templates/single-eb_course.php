<?php
/**
 * The template for displaying all single moodle courses.
 *
 * @package RDM Compas Moodle Connector.
 */

/**
 * -------------------------------------
 * INTIALIZATION START
 * Do not repalce these inititializations
 * --------------------------------------
 */

namespace app\wisdmlabs\edwiserBridge;

if (!defined('ABSPATH')) {
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
    <div style="padding-bottom: 1em; background-color: rgba(255,179,100,0.6)"></div>
    <div id="course-<?php the_ID(); ?>" class="training-center-page rdmc-container-page-sidebar"
         itemscope itemtype="http://schema.org/Course"
         style="column-gap:2em;justify-content: space-between;">
            <button type="button" class="sidebar-toggle show-large" onclick="sidebarToggle()">
                <i class="fa fa-bars" aria-hidden="true"></i> <?php echo __('Category', 'rdm-compas-theme') ?>
            </button>
        <div id="rdmc-sidebar">
            <?php $template_loader->wp_get_template_part('navigation', 'back-button'); ?>
            <div style="margin: 0.5rem 2rem 0"><?php get_search_form(); ?></div>
            <?php $template_loader->wp_get_template_part('course-category', 'sidebar'); ?>
        </div>
        <!--content-->
        <?php $template_loader->wp_get_template_part('content-single', get_post_type()); ?>

<!--        --><?php //if (is_singular('eb_course') && (get_previous_post_link() || get_next_post_link())) { ?>
    <!--            <section class="section">-->
    <!--                <div class="section__inner-container container">-->
    <!--                    --><?php //previous_post_link() ?>
    <!--                    --><?php //next_post_link() ?>
    <!--                </div>-->
    <!--            </section>-->
    <!--        --><?php //} ?>
<?php } ?>
    </div>
    <?php
get_footer();
