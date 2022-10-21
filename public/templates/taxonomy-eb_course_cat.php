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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$wrapper_args = array();

$eb_template = get_option('eb_template');
if (isset($eb_template['single_enable_right_sidebar']) && 'yes' === $eb_template['single_enable_right_sidebar']) {
    $wrapper_args['enable_right_sidebar'] = true;
    $wrapper_args['parentcss'] = '';
} else {
    $wrapper_args['enable_right_sidebar'] = false;
    $wrapper_args['parentcss'] = 'width:100%;';
}
$wrapper_args['sidebar_id'] = isset($eb_template['single_right_sidebar']) ? $eb_template['single_right_sidebar'] : '';

$template_loader = new EbTemplateLoader(
    edwiser_bridge_instance()->get_plugin_name(),
    edwiser_bridge_instance()->get_version()
);

global $wp_query;
$term = $wp_query->get_queried_object();
//var_dump($wp_query);
//die();
/*
 * -------------------------------------
 * INTIALIZATION END
 * --------------------------------------
 **/

get_header();
get_template_part('template-parts/breadcrumb'); ?>
    <!--intro-->
    <div class="intro intro__top topic-training-center">
        <div class="intro__inner-container">
            <h1 class="intro__title"><?php echo $term->name; ?></h1>
        </div>
    </div>
    <div id="curse-cat-<?php echo $term->term_id ?>" class="training-center-page rdmc-container-page-sidebar">
        <button type="button" class="sidebar-toggle show-medium" onclick="sidebarToggle()">
            <i class="fa fa-bars" aria-hidden="true"></i> <?php echo __('Navigation', 'rdm-compas-theme') ?>
        </button>
        <div id="rdmc-sidebar">
            <?php $template_loader->wp_get_template_part('navigation', 'sidebar'); ?>
        </div>
        <div>
<!--            <h2>--><?php //echo $term->name; ?><!--</h2>-->
            <!--        Description-->
<!--            <h2>--><?php //echo __('Description', 'edwiser-bridge'); ?><!--</h2>-->
            <p><?php echo $term->description; ?></p>
            <!--        Courses list-->
            <h4><?php echo __('Training Units', 'edwiser-bridge'); ?></h4>
            <?php
            if ($wp_query->have_posts()) {
                while ($wp_query->have_posts()) :
                    $wp_query->the_post();
                    $template_loader->wp_get_template_part('content', 'eb_course');
                endwhile;
            } else {
                $template_loader->wp_get_template_part('content', 'none');
            }
            wp_reset_postdata(); ?>
            <!--        Related articles in KB-->
            <?php
            $kb_articles = get_field("linked_knowledge_base_articles", "category_" . $term->term_id);
            if($kb_articles) {
                echo '<h4>'.__('Related Knowledge Base Articles', 'edwiser-bridge') .'</h4>';
                foreach ($kb_articles as $kb_article) {
                    global $kb_article;
                    $template_loader->wp_get_template_part('kb-article', 'card');
                }
            }


            ?>
        </div>
    </div>
    <?php
get_footer();
