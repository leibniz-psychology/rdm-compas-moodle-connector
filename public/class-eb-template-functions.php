<?php
/**
 * Handles template related dependncies
 *
 * @link       https://example.com
 * @since      1.0.0
 * @package    RDM Compas Moodle Connector.
 */

namespace app\wisdmlabs\edwiserBridge;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Theme compat.
 */
class Eb_Template_Functions
{

    /**
     * This function called from content_eb-course template.
     * This function includes to load all required variables and their logical part.
     *
     * @param int $post_id Post id.
     * @param array $attr (default: array()).
     * @param array $is_eb_my_courses parameter to check if the page is my courses page.
     */
    public function content_eb_course_tml_dependency($post_id, $attr, $is_eb_my_courses)
    {
        global $post;

        /**
         * Default data initilization.
         * This data is part of the template, In case of template overriding this need to be reinitialize.
         */
        $course_id = $post->ID;
        $eb_plugin_url = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_url();

        // get currency.
//		$payment_options   = get_option( 'eb_paypal' );
//		$currency          = isset( $payment_options['eb_paypal_currency'] ) ? $payment_options['eb_paypal_currency'] : 'USD';
//		$course_price_type = 'free';
//		$course_price      = '0';
        $short_description = '';
        $course_class = '';
        $user_id = get_current_user_id();
        $logged_in = !empty($user_id);
        $enroll_manag = \app\wisdmlabs\edwiserBridge\edwiser_bridge_instance()->enrollment_manager();
        $has_access = $enroll_manag->user_has_course_access($user_id, $post->ID);
        $course_options = get_post_meta($course_id, 'eb_course_options', true);
        /**
         * Legacy data.
         */
        $thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url() : $eb_plugin_url . 'images/no-image.jpg';

        if (is_array($course_options)) {
//			$course_price_type = ( isset( $course_options['course_price_type'] ) ) ? $course_options['course_price_type'] : 'free';
//			$course_price      = ( isset( $course_options['course_price'] ) && is_numeric( $course_options['course_price'] ) ) ? $course_options['course_price'] : '0';
//			$course_closed_url = ( isset( $course_options['course_closed_url'] ) ) ? $course_options['course_closed_url'] : '#';
            $moodle_course_url = 'https://trainingcenter.rdm-compas.org/course/view.php?id=' . $course_options['moodle_course_id'];
            $short_description = (isset($course_options['course_short_description'])) ? $course_options['course_short_description'] : '';
        }

//		if ( is_numeric( $course_price ) ) {
//			$currency_sym           = 'USD' === $currency ? '$' : $currency;
//			$course_price_formatted = '0' === $course_price ? __( 'Free', 'rdmcompas-moodle-connector' ) : $currency_sym . ' ' . $course_price;
//		}

        // Course associated Categories.
        $categories = \app\wisdmlabs\edwiserBridge\wdm_eb_course_terms($post_id);

        /*
         * To add class according to user access.
         */

        $course_class = 'no-access';
        /* Translators 1: title */
        $h_title = sprintf(esc_html__('Click to read more about', 'rdmcompas-moodle-connector') . ' %s' . esc_html__(' course', 'rdmcompas-moodle-connector'), get_the_title(get_the_ID()));
        if ($has_access) {
            $course_class = 'has-access';
            /* Translators 1: title */
            $h_title = sprintf(esc_html__('Click to access', 'rdmcompas-moodle-connector') . ' %s' . esc_html__(' course', 'rdmcompas-moodle-connector'), get_the_title(get_the_ID()));
        }

        // Shortcode eb_my_courses.
        if (isset($is_eb_my_courses) && $is_eb_my_courses && isset($attr)) {
            $course_class .= ' eb_my_course_article';
            $course_mang = \app\wisdmlabs\edwiserBridge\edwiser_bridge_instance()->course_manager();
            $mdl_course_id = $course_mang->get_moodle_course_id($course_id);
            $moodle_user_id = get_user_meta($user_id, 'moodle_user_id', true);
            $course_url = \app\wisdmlabs\edwiserBridge\wdm_eb_get_my_course_url($moodle_user_id, $mdl_course_id);
        } else {
            $is_eb_my_courses = false;
            $course_url = get_permalink();
        }

        return array(
            'course_class' => $course_class,
            'h_title' => $h_title,
            'thumb_url' => $thumb_url,
            'course_url' => $course_url,
            'moodle_course_url' => $moodle_course_url,
            'short_description' => $short_description,
//			'course_price_formatted' => $course_price_formatted,
            'is_eb_my_courses' => $is_eb_my_courses,
//			'course_price_type'      => $course_price_type,
            'categories' => $categories,
        );
    }


    /**
     * This function called from content-single-eb_course template.
     * This function includes to load all required variables and their logical part.
     */
    public function content_single_eb_course_tml_dependency()
    {
        global $post;

        $post_id = $post->ID; // @codingStandardsIgnoreLine.
        $eb_plugin_url = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_url();

        $categories = \app\wisdmlabs\edwiserBridge\wdm_eb_course_terms($post_id);
        $course_options = get_post_meta($post_id, 'eb_course_options', true);

        return [
            'eb_plugin_url' => $eb_plugin_url,
            'moodle_course_url' => 'https://trainingcenter.rdm-compas.org/course/view.php?id=' . $course_options['moodle_course_id'],
            'categories' => $categories,
            'course_institution' => $course_options['moodle_course_institution'] ?? null,
            'course_contact_person' => $course_options['moodle_course_contact_person'] ?? null, //for blended learning, contains name and email
            'course_contact_person_email' => $course_options['moodle_course_contact_person_email'] ?? null, //for blended learning, contains name and email
            'course_date_start' => $course_options['moodle_course_date_start'] ?? __('Start now!', 'rdmcompas-moodle-connector'), //for blended learning
            'course_date_modified' => $course_options['moodle_course_date_modified'] ?? null,
            'course_format' => $course_options['moodle_course_format'] ?? __('Lesson', 'rdmcompas-moodle-connector'),
            'course_target_group' => $course_options['moodle_course_target_group'] ?? __('All', 'rdmcompas-moodle-connector'),
            'course_discipline' => $course_options['moodle_course_discipline'] ?? __('General', 'rdmcompas-moodle-connector'),
            'course_number_participants' => $course_options['moodle_course_number_participants'] ?? null, //for blended learning
            'course_duration' => $course_options['moodle_course_duration'] ?? null,
            'course_required_material' => $course_options['moodle_course_required_material'] ?? null,
            'course_previous_experience' => $course_options['moodle_course_previous_experience'] ?? null,
            'course_persistent_identifier' => $course_options['moodle_course_persistent_identifier'] ?? null,
            'course_license' => $course_options['moodle_course_license'] ?? null,

        ];
    }


    /**
     * This function called from content_eb-course which gets called from archive page template.
     * This function loads the price related conetnt.
     *
     * @param int $course_data course data.
     */
    public function eb_course_archive_price_tmpl($course_data)
    {
        $template_loader = new EbTemplateLoader(
            edwiser_bridge_instance()->get_plugin_name(),
            edwiser_bridge_instance()->get_version()
        );

        $template_loader->wp_get_template(
            'courses/courses-price.php',
            $course_data
        );
    }


    /**
     * This function called from content-single-eb_course which gets called from single course page template.
     * This function loads the course progress reated data when the page is my-courses page.
     *
     * @param int $course_data course data.
     * @param array $shortcode_attr shortcode attr.
     */
    public function eb_my_course_archive_progress_tmpl($course_data, $shortcode_attr)
    {

        $template_loader = new EbTemplateLoader(
            edwiser_bridge_instance()->get_plugin_name(),
            edwiser_bridge_instance()->get_version()
        );

        $template_loader->wp_get_template(
            'courses/my-courses-progress.php',
            array(
                'course_data' => $course_data,
                'shortcode_attr' => $shortcode_attr,
            )
        );
    }

    /**
     * This function called from archive-eb_course.php file.
     * Functionality to show filters and sorting on course archive page.
     *
     * @param string $filter filter attr.
     * @param string $sorting sorting attr.
     */
    public function eb_show_course_filters_and_sorting($filter, $sorting)
    {
        $template_loader = new EbTemplateLoader(
            edwiser_bridge_instance()->get_plugin_name(),
            edwiser_bridge_instance()->get_version()
        );

        // Course associated Categories.
        $categories = \app\wisdmlabs\edwiserBridge\wdm_eb_course_terms();
        if (!is_array($categories) || empty($categories)) {
            $categories = array();
        }

        $template_loader->wp_get_template(
            'course-filters.php',
            array(
                'sorting' => $sorting,
                'filter' => $filter,
                'categories' => $categories,
            )
        );
    }


    /**
     * This functions sorts the wp query args according to the selected filter on eb_course page.
     *
     * @param string $args args attr.
     * @param string $sorting sorting attr.
     */
    public function eb_get_course_sorting_data($args, $sorting)
    {
        if (is_array($args)) {
            switch ($sorting) {
                case 'eb_archive_sort_a_z':
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                    break;

                case 'eb_archive_sort_z_a':
                    $args['orderby'] = 'title';
                    $args['order'] = 'DESC';
                    break;

                case 'eb_archive_latest':
                    $args['orderby'] = 'date';
                    $args['order'] = 'ASC';
                    break;

                case 'eb_archive_oldest':
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                    break;

                default:
                    $args['orderby'] = 'title';
                    $args['order'] = 'ASC';
                    break;
            }
        }

        return $args;
    }


    /**
     * This function modifies the category data according to the selected filter on the eb_course page.
     *
     * @param string $cat Category.
     * @param string $filter filter attr.
     */
    public function eb_get_course_filter_data($cat, $filter)
    {
        /*
         * There are 2 types of filters
         * 1. Per category and
         * 2. all courses in this condition we won't modify any of the category filter data.
         */
        if ('eb_archive_filter_all' === $filter) {
            $cat = get_terms(array('taxonomy' => 'eb_course_cat'));
        } elseif (!empty($filter)) {
            $cat = array();
            $cat[] = get_term($filter, 'eb_course_cat');
        }

        return $cat;
    }


    /**
     * FUnction to add custom classes to the pagination links.
     */
    public function posts_link_attributes()
    {
        return 'class="page-numbers eb_primary_btn button button-primary et_pb_button et_pb_contact_submit"';
    }

}
