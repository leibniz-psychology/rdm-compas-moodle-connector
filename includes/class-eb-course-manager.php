<?php
/**
 * This class defines all code necessary for moodle course synchronization.
 *
 * @link       https://example.com
 * @since      1.0.0
 * @package    RDM Compas Moodle Connector
 */

namespace app\wisdmlabs\edwiserBridge;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Course manager.
 */
class Eb_Course_Manager
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     *
     * @var string The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     *
     * @var string The current version of this plugin.
     */
    private $version;

    /**
     * Instance.
     *
     * @var Eb_Course_Manager The single instance of the class
     *
     * @since 1.0.0
     */
    protected static $instance = null;

    /**
     * Main Eb_Course_Manager Instance.
     *
     * Ensures only one instance of Eb_Course_Manager is loaded or can be loaded.
     *
     * @param string $plugin_name plugin_name.
     * @param string $version version.
     * @return Eb_Course_Manager - Main instance
     * @see Eb_Course_Manager()
     * @since 1.0.0
     * @static
     *
     */
    public static function instance($plugin_name, $version)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($plugin_name, $version);
        }

        return self::$instance;
    }

    /**
     * Cloning is forbidden.
     *
     * @since   1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, esc_html__('Cheatin&#8217; huh?', 'rdmcompas-moodle-connector'), '1.0.0');
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since   1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, esc_html__('Cheatin&#8217; huh?', 'rdmcompas-moodle-connector'), '1.0.0');
    }

    /**
     * Main Eb_Course_Manager contsructor.
     *
     * @param string $plugin_name plugin_name.
     * @param string $version version.
     * @return Eb_Course_Manager - Main instance
     * @see Eb_Course_Manager()
     * @since 1.0.0
     * @static
     *
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Initiate the synchronization process.
     * Called by course_synchronization_initiater() from class Eb_Settings_Ajax_Initiater.
     *
     * @param array $sync_options course sync options.
     *
     * @return array $response     array containing status & response message
     * @since   1.0.0
     *
     */
    public function course_synchronization_handler($sync_options = array())
    {
        edwiser_bridge_instance()->logger()->add('user', 'Initiating course & category sync process....'); // add course log.
        $response_array = array(); // contains response message to be displayed to user.
        $courses_updated = array(); // store updated course ids ( WordPress course ids ).
        $courses_created = array(); // store newely created course ids ( WordPress course ids ).
        $error_course_count = 0;
        $eb_access_token = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_get_access_token();
        $eb_access_url = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_get_access_url();

        // checking if moodle connection is working properly.
        $connected = edwiser_bridge_instance()->connection_helper()->connection_test_helper($eb_access_url, $eb_access_token);

        $response_array['connection_response'] = $connected['success']; // add connection response in response array.

        if (1 === $connected['success']) {
            /*
             * Sync Moodle course categories to WordPress conditionally.
             * Executes only if user chooses to sync categories.
             */
            if (isset($sync_options['eb_synchronize_categories']) && '1' === $sync_options['eb_synchronize_categories']) {

                $moodle_category_resp = $this->get_moodle_course_categories(); // get categories from moodle.

                // creating categories based on received data.
                if (1 === $moodle_category_resp['success']) {

                    $this->create_course_categories_on_wordpress($moodle_category_resp['response_data']);
                }

                // push category response in array.
                $response_array['category_success'] = $moodle_category_resp['success'];
                $response_array['category_response_message'] = $moodle_category_resp['response_message'];
            }

            /*
             * sync moodle courses to WordPress.
             */
            $moodle_course_resp = $this->get_moodle_courses(); // get courses from moodle.

            if ((isset($sync_options['eb_synchronize_draft'])) || (isset($sync_options['eb_synchronize_previous']) && '1' === $sync_options['eb_synchronize_previous'])) {

                // creating courses based on received data.
                if (1 === $moodle_course_resp['success']) {
                    foreach ($moodle_course_resp['response_data'] as $course_data) {
                        /*
                         * moodle always returns moodle frontpage as first course,
                         * below step is to avoid the frontpage to be added as a course.
                         */
                        if (1 === $course_data->id) {
                            continue;
                        }

                        // check if course is previously synced.
                        $existing_course_id = $this->is_course_presynced($course_data->id);

                        // creates new course or updates previously synced course conditionally.
                        if (!is_numeric($existing_course_id)) {
                            $course_id = $this->create_course_on_wordpress($course_data, $sync_options);
                            $courses_created[] = $course_id; // push course id in courses created array.
                        } elseif (is_numeric($existing_course_id) &&
                            isset($sync_options['eb_synchronize_previous']) &&
                            '1' === $sync_options['eb_synchronize_previous']) {
                            // Code to check if course meta moodle_course_id have post type other than eb_course. If yes then show notice.
                            // This is to reduce support from WooMoodle.
                            $error_course_count = $error_course_count + $this->check_post_type($existing_course_id);

                            $course_id = $this->update_course_on_wordpress(
                                $existing_course_id,
                                $course_data,
                                $sync_options
                            );
                            $courses_updated[] = $course_id; // push course id in courses updated array.

                        }
                    }
                }
                $response_array['course_success'] = $moodle_course_resp['success'];
                // push course response in array.
                $response_array['course_response_message'] = $moodle_course_resp['response_message'];

                if ($error_course_count) {
                    $response_array['course_success'] = 0;
                    // push course response in array.
                    $response_array['course_response_message'] = esc_html__('Unable to create/update a few courses, since they might have already being synced by a third-party plugin. Please check complete error ', 'rdmcompas-moodle-connector') . '<a target="_blank" href="https://edwiser.helpscoutdocs.com/article/235-courses-do-not-synchronize-from-moodle-to-wordpress">' . esc_html__(' here', 'rdmcompas-moodle-connector') . '</a>';
                }
            }


            // Sync enrollment Methods.
            if (isset($moodle_course_resp['response_data'])) {


                $this->sync_course_enrollment_method(1);
            }

            /*
             * hook to be run on course completion
             * we are passing all new created and updated courses as arg
             */
            do_action('eb_course_synchronization_complete', $courses_created, $courses_updated, $sync_options);
        } else {
            edwiser_bridge_instance()->logger()->add(
                'course',
                'Connection problem in synchronization, Response:' . print_r($connected, true) // @codingStandardsIgnoreLine
            ); // add connection log.
        }

        return $response_array;
    }


    /**
     * Sync course enrollment methods.
     *
     * @param array $courses course array.
     * @param array $sync_options course sync options.
     *
     * @since   1.0.0
     */
    public function sync_course_enrollment_method($update = 0)
    {

        // Check sync option.
        $response = edwiser_bridge_instance()->connection_helper()->connect_moodle_with_args_helper(
            'edwiserbridge_local_get_course_enrollment_method',
            array()
        );

        if (1 === $response['success'] && !empty($response['response_data']) && is_array($response['response_data'])) {
            if (1 === $update) {
                foreach ($response['response_data'] as $course_data) {
                    $wp_course_id = $this->is_course_presynced($course_data->courseid);
                    update_post_meta($wp_course_id, 'eb_course_manual_enrolment_enabled', $course_data->enabled);
                }
            } else {
                return $response['response_data'];
            }
        } else {

        }

    }


    /**
     * Sync course enrollment methods.
     *
     * @param array $courses course array.
     * @param array $sync_options course sync options.
     *
     * @since   1.0.0
     */
    public function edwiserbridge_local_update_course_enrollment_method($course_array)
    {

        // Check sync option.
        $response = edwiser_bridge_instance()->connection_helper()->connect_moodle_with_args_helper(
            'edwiserbridge_local_update_course_enrollment_method',
            $course_array
        );


        if (1 == $response['success'] && !empty($response['response_data']) && is_array($response['response_data'])) {

            foreach ($response['response_data'] as $course_data) {
                $wp_course_id = $this->is_course_presynced($course_data->courseid);
                if ($course_data->status) {
                    update_post_meta($wp_course_id, 'eb_course_manual_enrolment_enabled', $course_data->status);
                }
            }
            return $response['response_data'];

        } else {
            return $response;
        }

    }



    /**
     * Fetches the courses from moodle ( all courses or courses of a specfic user ).
     *
     * Uses connect_moodle_helper() and connect_moodle_with_args_helper()
     *
     * @param int $moodle_user_id moodle user_id of a WordPress user passed to connection helper.
     *
     * @return array stores moodle web service response.
     */
    public function get_moodle_courses($moodle_user_id = null)
    {
        $response = '';

        if (!empty($moodle_user_id)) {
            $webservice_function = 'core_enrol_get_users_courses'; // get a users enrolled courses from moodle.
            $request_data = array('userid' => $moodle_user_id); // prepare request data array.

            $response = edwiser_bridge_instance()->connection_helper()->connect_moodle_with_args_helper(
                $webservice_function,
                $request_data
            );

            // add course log.
            edwiser_bridge_instance()->logger()->add('course', 'User course response: ' . serialize($response)); // @codingStandardsIgnoreLine
        } elseif (empty($moodle_user_id)) {
            $webservice_function = 'core_course_get_courses'; // get all courses from moodle.
            $response = edwiser_bridge_instance()->connection_helper()->connect_moodle_helper($webservice_function);
            // add course log.
            edwiser_bridge_instance()->logger()->add('course', 'Response: ' . serialize($response)); // @codingStandardsIgnoreLine
        }

        return $response;
    }


    /**
     * Fetches the courses categories from moodle.
     * uses connect_moodle_helper().
     *
     * @param string $webservice_function the webservice function passed to connection helper.
     *
     * @return array stores moodle web service response.
     */
    public function get_moodle_course_categories($webservice_function = null)
    {
        if (null === $webservice_function) {
            $webservice_function = 'core_course_get_categories';
        }

        $response = edwiser_bridge_instance()->connection_helper()->connect_moodle_helper($webservice_function);
        edwiser_bridge_instance()->logger()->add('course', serialize($response)); // @codingStandardsIgnoreLine

        return $response;
    }


    /**
     * Checks if a course is previously synced from moodle.
     *
     * @param int $course_id the id of course as on moodle.
     *
     * @return bool returns respective course id on WordPress if exist else returns null
     */
    public function check_post_type($course_id)
    {
        if (get_post_type($course_id) !== 'eb_course') {
            return 1;
        }
        return 0;
    }



    /**
     * Checks if a course is previously synced from moodle.
     *
     * @param int $course_id_on_moodle the id of course as on moodle.
     *
     * @return bool returns respective course id on WordPress if exist else returns null
     */
    public function is_course_presynced($course_id_on_moodle)
    {
        global $wpdb;

        // Get id of course on WordPress based on id on moodle $course_id =.
        $course_id = $wpdb->get_var( // @codingStandardsIgnoreLine
            $wpdb->prepare(
                "SELECT post_id
				FROM {$wpdb->prefix}postmeta
				WHERE meta_key = 'moodle_course_id'
				AND meta_value = %s",
                $course_id_on_moodle
            )
        );

        // Check if post is availabke or not.
        // This code is to avoid conflict with MooWoodle plugin.
        if (false == get_post_status($course_id)) {
            // The post does not exist, delete post meta also.
            delete_post_meta($course_id, 'moodle_course_id');
            $course_id = false;
        }

        return $course_id;
    }


    /**
     * Return the moodle id of a course using its WordPress id.
     *
     * @param int $course_id_on_wp the id of course synced on WordPress.
     *
     * @return int returns respective course id on moodle
     */
    public function get_moodle_course_id($course_id_on_wp)
    {
        return get_post_meta($course_id_on_wp, 'moodle_course_id', true);
    }

    /**
     * Return the moodle id of a course using its WordPress id.
     *
     * @param int $course_id_on_wp the id of course synced on WordPress.
     *
     * @return int returns respective course id on moodle
     */
    public function get_moodle_wp_course_id_pair($course_id_on_wp)
    {
        return array($course_id_on_wp => get_post_meta($course_id_on_wp, 'moodle_course_id', true));
    }



    /**
     * Create course on WordPress.
     *
     * @param array $course_data course data recieved from initiate_course_sync_process().
     * @param string $sync_options sync_options.
     * @return int returns id of course
     */
    public function create_course_on_wordpress($course_data, $sync_options = array())
    {
        global $wpdb;

        $status = (isset($sync_options['eb_synchronize_draft']) &&
            '1' === $sync_options['eb_synchronize_draft']) ? 'draft' : 'publish'; // manage course status.

        $course_args = array(
            'post_title' => $course_data->fullname,
            'post_content' => $course_data->summary,
            'post_status' => $status,
            'post_type' => 'eb_course',
        );

        $wp_course_id = wp_insert_post($course_args); // create a course on WordPress.

        $term_id = $wpdb->get_var( // @codingStandardsIgnoreLine
            $wpdb->prepare(
                "SELECT term_id
				FROM {$wpdb->prefix}termmeta
				WHERE meta_key = 'eb_moodle_cat_id'
				AND meta_value = %d",
                $course_data->categoryid
            )
        );

        // set course terms.
        if ($term_id > 0) {
            wp_set_post_terms($wp_course_id, $term_id, 'eb_course_cat');
        }

        // add course id on moodle in course meta on WP.
        $eb_course_options = $this->get_moodle_course_options($course_data);
        add_post_meta($wp_course_id, 'moodle_course_id', $course_data->id);
        add_post_meta($wp_course_id, 'eb_course_options', $eb_course_options);
        // set language
        if (function_exists('pll_set_post_language')) {
            $lang = $course_data->lang ?? 'en';
            pll_set_post_language($wp_course_id, $lang);
        }

        /*
         * execute your own action on course creation on WorPress
         * we are passing newly created course id as well as its respective moodle id in arguments
         *
         * sync_options are also passed as it can be used in a custom action on hook.
         */
        do_action('eb_course_created_wp', $wp_course_id, $course_data, $sync_options);

        return $wp_course_id;
    }



    /**
     * Update previous synced course on WordPress.
     *
     * @param int $wp_course_id existing id of course on WordPress.
     * @param array $course_data course data recieved from initiate_course_sync_process().
     * @param array $sync_options sync_options.
     *
     * @return int returns id of course
     */
    public function update_course_on_wordpress($wp_course_id, $course_data, $sync_options)
    {
        global $wpdb;

        $course_args = array(
            'ID' => $wp_course_id,
            'post_title' => $course_data->fullname,
            'post_content' => $course_data->summary,
        );

        // updater course on WordPress.
        wp_update_post($course_args);

        $term_id = $wpdb->get_var( // @codingStandardsIgnoreLine
            $wpdb->prepare(
                "SELECT term_id
				FROM {$wpdb->prefix}termmeta
				WHERE meta_key = 'eb_moodle_cat_id'
				AND meta_value = %d",
                $course_data->categoryid
            )
        );

        // set course terms.
        if ($term_id > 0) {
            wp_set_post_terms($wp_course_id, $term_id, 'eb_course_cat');
        }

//        update course meta
        $eb_course_options = $this->get_moodle_course_options($course_data);
        update_post_meta($wp_course_id, 'eb_course_options', $eb_course_options);

        // set language
        if (function_exists('pll_set_post_language')) {
            $lang = $course_data->lang ?? 'en';
            pll_set_post_language($wp_course_id, $lang);
        }
        /*
         * execute your own action on course updation on WordPress
         * we are passing newly created course id as well as its respective moodle id in arguments
         *
         * sync_options are also passed as it can be used in a custom action on hook.
         */
        do_action('eb_course_updated_wp', $wp_course_id, $course_data, $sync_options);

        return $wp_course_id;
    }

    /**
     * In case a course is permanentaly deleted from moodle course list,
     * update course enrollment table appropriately by deleting records for course being deleted.
     *
     * @param int $course_id course_id.
     * @since  1.0.0
     *
     */
    public function delete_enrollment_records_on_course_deletion($course_id)
    {
        global $wpdb;

        if ('eb_course' === get_post_type($course_id)) {
            // removing course from enrollment table.
            $wpdb->delete($wpdb->prefix . 'moodle_enrollment', array('course_id' => $course_id), array('%d')); // @codingStandardsIgnoreLine
        }
    }


    /**
     * Uses the response received from get_eb_course_categories() function.
     * creates terms of eb_course_cat taxonomy.
     *
     * @param array $category_response accepts categories fetched from moodle.
     */
    public function create_course_categories_on_wordpress($category_response)
    {
        global $wpdb;

        // sort category response by id in incremental order.
        usort($category_response, '\app\wisdmlabs\edwiserBridge\wdm_eb_usort_numeric_callback');

        foreach ($category_response as $category) {
            $cat_name_clean = preg_replace('/\s*/', '', $category->name);
            $cat_name_lower = strtolower($cat_name_clean);
            $parent = (0 === $category->parent ? 0 : $category->parent);

            if ($parent > 0) {
                // get parent term if exists.

                $parent_term = $wpdb->get_var( // @codingStandardsIgnoreLine
                    $wpdb->prepare(
                        "SELECT term_id
						FROM {$wpdb->prefix}termmeta
						WHERE meta_key = 'eb_moodle_cat_id'
						AND meta_value = %d",
                        $category->parent
                    )
                );

                if ($parent_term && !term_exists($cat_name_lower, 'eb_course_cat', $parent_term)) {
                    $created_term = wp_insert_term(
                        $category->name,
                        'eb_course_cat',
                        array(
                            'slug' => $cat_name_lower,
                            'parent' => $parent_term,
                            'description' => $category->description,
                        )
                    );

                    if (!is_wp_error($created_term) && is_array($created_term)) {
                        update_term_meta($created_term['term_id'], 'eb_moodle_cat_id', $category->id);
                    }

                    // Save the moodle id of category in options.
                }
            } else {
                if (!term_exists($cat_name_lower, 'eb_course_cat')) {
                    $created_term = wp_insert_term(
                        $category->name,
                        'eb_course_cat',
                        array(
                            'slug' => $cat_name_lower,
                            'description' => $category->description,
                        )
                    );

                    if (!is_wp_error($created_term) && is_array($created_term)) {
                        update_term_meta($created_term['term_id'], 'eb_moodle_cat_id', $category->id);
                    }

                    // Save the moodle id of category in options.
                }
            }
        }
    }

    /**
     * Add a new column price type to courses table in admin.
     *
     * @param array $columns default columns array.
     *
     * @return array $new_columns   updated columns array.
     * @since  1.0.0
     *
     */
    public function add_course_price_type_column($columns)
    {
        $new_columns = array(); // new columns array.

        foreach ($columns as $key => $value) {
            if ('title' === $key) {
                $new_columns[$key] = esc_html__('Course Title', 'rdmcompas-moodle-connector');
                $new_columns['mdl_course_id'] = esc_html__('Moodle Course Id', 'rdmcompas-moodle-connector');
//                $new_columns['course_type'] = esc_html__('Course Type', 'rdmcompas-moodle-connector');
//                $new_columns['course_enrollment_method'] = esc_html__('Manual Enrollment', 'rdmcompas-moodle-connector');
            } else {
                $new_columns[$key] = $value;
            }
            $new_columns = apply_filters('eb_course_each_table_header', $new_columns);
        }

        $new_columns = apply_filters('eb_course_table_headers', $new_columns);

        return $new_columns;
    }

    /**
     * Add content to course price type column.
     *
     * @param array $column_name name of a column.
     * @param array $post_id id of a column.
     * @since  1.0.0
     *
     */
    public function add_column_in_courses_table($column_name, $post_id)
    {

        if ('course_type' === $column_name) {
            $status = Eb_Post_Types::get_post_options($post_id, 'course_price_type', 'eb_course');
            $options = array(
                'free' => esc_html__('Free', 'rdmcompas-moodle-connector'),
                'paid' => esc_html__('Paid', 'rdmcompas-moodle-connector'),
                'closed' => esc_html__('Closed', 'rdmcompas-moodle-connector'),
            );
            $status = $status ? $status : 'free';
            echo esc_html(isset($options[$status]) ? $options[$status] : ucfirst($status));
        } elseif ('mdl_course_id' === $column_name) {
            $mdl_course_id = Eb_Post_Types::get_post_options($post_id, 'moodle_course_id', 'eb_course');
            $mdl_course_deleted = Eb_Post_Types::get_post_options($post_id, 'mdl_course_deleted', 'eb_course');

            echo !empty($mdl_course_deleted) ? '<span style="color:red;">' . esc_html__('Deleted', 'rdmcompas-moodle-connector') . '<span>' : esc_html($mdl_course_id);
        } elseif ('course_enrollment_method' === $column_name) {
            //check if course is deleted
            $mdl_course_deleted = Eb_Post_Types::get_post_options($post_id, 'mdl_course_deleted', 'eb_course');
            if (!empty($mdl_course_deleted)) {
                $html = '<span style="padding: 2px 8px;"> — </span>';
            } else {
                // Get stored sync data.
                $enrolment_enabled = get_post_meta($post_id, 'eb_course_manual_enrolment_enabled', 1);

                // Get Moodle course id.
                $moodle_course_id = get_post_meta($post_id, 'moodle_course_id', 1);

                // If data is not synced show refresh icon to sync
                // store status in DB.
                // $html = '<span style="color:red;font-size:25px;" class="dashicons dashicons-warning"></span> ' . '<span data-courseid="'. $moodle_course_id .'" class="eb-enable-manual-enrolment"  style="color: #2271b1;cursor: pointer;">' . esc_html__( 'Enable', 'rdmcompas-moodle-connector' );
                // if ( $enrolment_enabled ) {
                // 	// $html = '<span style="color:green;font-size:30px;" class="dashicons dashicons-yes"></span>';
                // 	$html = '<span style="color:green;font-size:30px;" class="dashicons dashicons-yes"></span>' /*. esc_html__( 'Enabled', 'rdmcompas-moodle-connector' )*/;
                // }
                // $html .= ' <span data-courseid="'. $post_id .'"  style="padding-left: 10px;padding-top: 5px;color: #392ee1;cursor: pointer;" class="dashicons dashicons-update eb-reload-enrolment-method"></span>';
                if ($enrolment_enabled) {
                    $html = '<span style="color:green;font-size:30px;" class="dashicons dashicons-yes"></span>' /*. esc_html__( 'Enabled', 'rdmcompas-moodle-connector' )*/
                    ;
                } elseif ("" == $enrolment_enabled) {
                    $html = '<span style="color:#2271b1;font-size:20px;" class="dashicons dashicons-update"></span> ' . '<span data-courseid="' . $moodle_course_id . '" class="eb-enable-manual-enrolment"  style="color: #2271b1;cursor: pointer;">' . esc_html__('Sync', 'rdmcompas-moodle-connector');
                } else {
                    $html = '<span style="color:red;font-size:25px;" class="dashicons dashicons-warning"></span> ' . '<span data-courseid="' . $moodle_course_id . '" class="eb-enable-manual-enrolment"  style="color: #2271b1;cursor: pointer;">' . esc_html__('Enable', 'rdmcompas-moodle-connector');
                }
            }
            echo $html;
        }

        do_action('eb_course_table_content', $column_name, $post_id);
    }


    /**
     * Adds the view moodle course link in courses list table for admin.
     *
     * @param array $bulk_actions An array of row action links. .
     */
    public function add_custom_bulk_action($bulk_actions)
    {
        $bulk_actions['sync_enrollment'] = __('Sync Enrollment Method', 'rdmcompas-moodle-connector');
        $bulk_actions['enable_manual_enrollment'] = __('Enable Enrollment Method', 'rdmcompas-moodle-connector');
        return $bulk_actions;
    }


    /**
     * Handle course enrollment bulk action synchronization.
     *
     * @param string $redirect_url redirect url.
     * @param string $action action.
     * @param array $post_ids course id array.
     */
    public function handle_custom_bulk_action($redirect_url, $action, $post_ids)
    {

        $eb_bulk_action_nonce = wp_create_nonce('eb_bulk_action_nonce');
        $request_refer = isset($_SERVER['HTTP_REFERER']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_REFERER'])) : '';
        $request_refer = strtok($request_refer, '?');
        // Create courses data i.e create required course array
        if ('sync_enrollment' == $action) {
            $courses_data = $this->sync_course_enrollment_method();
            $response_array = array();
            // get all courses data.
            foreach ($courses_data as $course_data) {
                $wp_course_id = $this->is_course_presynced($course_data->courseid);
                $response_array[] = $wp_course_id;
            }

            $count = 0;
            foreach ($post_ids as $post_id) {

                if (in_array($post_id, $response_array)) {
                    update_post_meta($post_id, 'eb_course_manual_enrolment_enabled', $course_data->enabled);
                } else {
                    update_post_meta($post_id, 'eb_course_manual_enrolment_enabled', 0);
                }
                $count++;
            }
            $sendback = add_query_arg(
                array(
                    'post_type' => 'eb_course',
                    'eb_bulk_action_nonce' => $eb_bulk_action_nonce,
                    'message1' => "success",
                    'count' => $count,
                    'action1' => $action
                ),
                $request_refer
            );

            return $sendback;

        } elseif ('enable_manual_enrollment' == $action) {
            $mdl_course_ids = array();
            $count = 0;
            foreach ($post_ids as $wp_course_id) {
                $mdl_course_id = get_post_meta($wp_course_id, 'moodle_course_id', 1);
                $mdl_course_deleted = Eb_Post_Types::get_post_options($wp_course_id, 'mdl_course_deleted', 'eb_course');
                if ($mdl_course_id && empty($mdl_course_deleted)) {
                    $mdl_course_ids[] = $mdl_course_id;
                    $count++;
                }
            }

            $course_data = $this->edwiserbridge_local_update_course_enrollment_method(array('courseid' => $mdl_course_ids));
            if (isset($course_data['success']) && 0 === $course_data['success']) { //CHANGE YET TO COMMIT
                if ($course_data['response_message'] == "Class 'enrol_manual_plugin' not found") {
                    $data = esc_html__("Manual Enrollment Plugin is not enabled/installed on moodle site.", 'rdmcompas-moodle-connector');
                } else {
                    $data = esc_html__($course_data['response_message'], 'rdmcompas-moodle-connector');
                }
            } else {
                $data = "success";
            }

            $sendback = add_query_arg(
                array(
                    'post_type' => 'eb_course',
                    'eb_bulk_action_nonce' => $eb_bulk_action_nonce,
                    'message1' => urlencode($data),
                    'count' => $count,
                    'action1' => $action
                ),
                $request_refer
            );

            return $sendback;
        }

        return $redirect_url;
    }

    /**
     * Handle course enrollment bulk action result admin notice.
     */
    public function handle_custom_bulk_action_result_admin_notice()
    {
        global $post_type, $pagenow;
        if (!isset($_REQUEST['eb_bulk_action_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['eb_bulk_action_nonce'])), 'eb_bulk_action_nonce')) {
            return;
        }
        if ('edit.php' == $pagenow && 'eb_course' == $post_type) {
            $action = $_REQUEST['action1'];
            if ('sync_enrollment' == $action) {
                $count = isset($_REQUEST['count']) ? $_REQUEST['count'] : '';
                $count = ($count != '') ? $count : 0;
                $message = isset($_REQUEST['message1']) ? $_REQUEST['message1'] : '';
                if ($message == "success") {
                    $message = "<div class='updated'><p>" . sprintf(esc_html__('Manual enrollment status synced for' . ' %s ' . "courses", 'rdmcompas-moodle-connector'), number_format_i18n(sanitize_text_field(wp_unslash($count)))) . "</p></div>";
                } else {
                    $message = "<div class='notice notice-error'><p>" . esc_html__("Error in manual enrollment status sync", 'rdmcompas-moodle-connector') . "</p></div>";
                }
                echo $message;
            } elseif ('enable_manual_enrollment' == $action) {
                $count = isset($_REQUEST['count']) ? $_REQUEST['count'] : '';
                $count = ($count != '') ? $count : 0;
                $message = isset($_REQUEST['message1']) ? $_REQUEST['message1'] : '';
                if ($message == "success") {
                    $message = "<div class='updated'><p>" . sprintf(esc_html__('Manual enrollment enabled for' . ' %s ' . "courses", 'rdmcompas-moodle-connector'), number_format_i18n(sanitize_text_field(wp_unslash($count)))) . "</p></div>";
                } else {
                    $message = "<div class='notice notice-error'><p>" . esc_html__('Please check if Course is deleted from Moodle or WordPress also check if Moodle Manual Enrollment is activate on Moodle ', 'rdmcompas-moodle-connector') . "</p><p>" . esc_html__('Error Message  : ' . $message, 'rdmcompas-moodle-connector') . "</p></div>";
                }
                echo $message;
            }
        }
    }

    /**
     * Handle single course synchronization.
     *
     */
    public function eb_enable_course_enrollment_method($course_id = '')
    {

        // verifying generated nonce we created earlier.
        if (!isset($_POST['_wpnonce_field']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce_field'])), 'check_sync_action')) {
            die('Busted!');
        }

        // start working on request.
        $course_id = isset($_POST['course_id']) ? sanitize_text_field(wp_unslash($_POST['course_id'])) : '';

        if ($course_id) {

            // $courses_data = $this->sync_course_enrollment_method();

            // Update course enrollment method.
            $course_data = $this->edwiserbridge_local_update_course_enrollment_method(array('courseid' => array($course_id)));
            if (0 === $course_data['success']) {
                if ($course_data['response_message'] == "Class 'enrol_manual_plugin' not found") {
                    wp_send_json_error(array('message' => esc_html__("Manual Enrollment Plugin is not enabled/installed on moodle site.", 'rdmcompas-moodle-connector')));
                } else {
                    wp_send_json_error(array('message' => esc_html__($course_data['response_message'], 'rdmcompas-moodle-connector')));
                }
            } else {
                wp_send_json_success();
            }
        }
    }


    /**
     * Adds the view moodle course link in courses list table for admin.
     *
     * @param array $actions An array of row action links. .
     * @param object $post post object.
     */
    public function view_moodle_course_link($actions, $post)
    {
        if ('eb_course' === $post->post_type) {
            $eb_access_url = wdm_edwiser_bridge_plugin_get_access_url();
            $mdl_course_id = $this->get_moodle_course_id($post->ID);
            $course_url = $eb_access_url . '/course/view.php?id=' . $mdl_course_id;
            $actions['moodle_link'] = "<a href='{$course_url}' title='' target='_blank' rel='permalink'>" . __('View on Moodle', 'rdmcompas-moodle-connector') . '</a>';
        }
        return $actions;
    }

    /**
     * @param array $course_data course data received from initiate_course_sync_process().
     * @return array course options saved in post meta key 'eb_course_options'
     */
    public function get_moodle_course_options(object $course_data): array
    {
        // parse custom fields
        $custom_fields = array();
        if (isset($course_data->customfields)) {
            foreach ($course_data->customfields as $custom_field) {
                $custom_fields[$custom_field->shortname] = $custom_field->value;
            }
        }
        // add course id on moodle in course meta on WP.
        // get course metadata
        return array(
            'moodle_course_id'                  => $course_data->id,
            'moodle_course_institution'         => $custom_fields['institution'],
            'moodle_course_contact_person'      => $custom_fields['contact_person_name'],
            'moodle_course_contact_person_email'=> $custom_fields['contact_person_email'],
            'moodle_course_date_start'          => date('M j, Y', intval($course_data->startdate)),
            'moodle_course_date_modified'       => date('M j, Y', intval($course_data->timemodified)),
            'moodle_course_format'              => $custom_fields['course_format'],
            'moodle_course_target_group'        => $custom_fields['target_group'],
            'moodle_course_discipline'          => $custom_fields['discipline'],
            'moodle_course_number_participants' => $custom_fields['number_participants'],
            'moodle_course_duration'            => $custom_fields['duration'],
            'moodle_course_required_material'   => $custom_fields['required_material'],
            'moodle_course_previous_experience' => $custom_fields['previous_experience'],
            'moodle_course_persistent_identifier' => $custom_fields['persistent_identifier'],
            'moodle_course_license'             => $custom_fields['license'],
        );
    }

    /**
     * Fetches the courses from moodle ( all courses or courses of a specfic user ).
     *
     * Uses connect_moodle_helper() and connect_moodle_with_args_helper()
     *
     * @param int $moodle_user_id moodle user_id of a WordPress user passed to connection helper.
     *
     * @return array stores moodle web service response.
     */
//    public function get_course_author($moodle_course_id)
//    {
//        $response = '';
//
//        if (!empty($moodle_course_id)) {
//            $webservice_function = 'core_enrol_get_enrolled_users_with_capability'; // get enrolled users with given capability
//            $request_data = array(
//                array(
//                    'courseid' => $moodle_course_id,
//                    'capabilities' => array(
//                        'moodle/course:create'
//                    ))); // prepare request data array.
//
//            $response = edwiser_bridge_instance()->connection_helper()->connect_moodle_with_args_helper(
//                $webservice_function,
//                $request_data
//            );
//
//            // add course log.
//            edwiser_bridge_instance()->logger()->add('course', 'User course response: ' . serialize($response)); // @codingStandardsIgnoreLine
//        } elseif (empty($moodle_user_id)) {
//            $webservice_function = 'core_course_get_courses'; // get all courses from moodle.
//            $response = edwiser_bridge_instance()->connection_helper()->connect_moodle_helper($webservice_function);
//            // add course log.
//            edwiser_bridge_instance()->logger()->add('course', 'Response: ' . serialize($response)); // @codingStandardsIgnoreLine
//        }
//
//        return $response;
//    }
}
