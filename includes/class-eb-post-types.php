<?php
/**
 * The post type registration functionality of the plugin.
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
 * Eb post type.
 */
class Eb_Post_Types
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
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     *
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register EDW taxonomies.
     */
    public function register_taxonomies()
    {
        if (taxonomy_exists('eb_course_cat')) {
            return;
        }

        do_action('eb_register_taxonomy');

        register_taxonomy(
            'eb_course_cat',
            apply_filters('eb_taxonomy_objects_eb_course_cat', array('eb_course')),
            apply_filters(
                'eb_taxonomy_args_eb_course_cat',
                array(
                    'hierarchical' => true,
                    'label' => __('Course Categories', 'rdmcompas-moodle-connector'),
                    'labels' => array(
                        'name' => __('Course Categories', 'rdmcompas-moodle-connector'),
                        'singular_name' => __('Course Category', 'rdmcompas-moodle-connector'),
                        'menu_name' => _x('Course Categories', 'Admin menu name', 'rdmcompas-moodle-connector'),
                        'search_items' => __('Search Course Categories', 'rdmcompas-moodle-connector'),
                        'all_items' => __('All Course Categories', 'rdmcompas-moodle-connector'),
                        'parent_item' => __('Parent Course Categories', 'rdmcompas-moodle-connector'),
                        'parent_item_colon' => __('Parent Course Category:', 'rdmcompas-moodle-connector'),
                        'edit_item' => __('Edit Course Category', 'rdmcompas-moodle-connector'),
                        'update_item' => __('Update Course Category', 'rdmcompas-moodle-connector'),
                        'add_new_item' => __('Add New Course Category', 'rdmcompas-moodle-connector'),
                        'new_item_name' => __('New Course Category Name', 'rdmcompas-moodle-connector'),
                    ),
                    'show_ui' => true,
                    'query_var' => true,
                    'hierarchical' => true,
                    'rewrite' => array('slug' => 'eb_category'),
                )
            )
        );
        do_action('eb_after_register_taxonomy');
    }

    /**
     * Register core post types.
     */
    public function register_post_types()
    {
        do_action('eb_register_post_type');
        $settings = get_option('eb_general');
        $show_archive = true;
        if (isset($settings['eb_show_archive']) && 'no' === $settings['eb_show_archive']) {
            $show_archive = false;
        }
        if (!post_type_exists('eb_course')) {
            register_post_type(
                'eb_course',
                apply_filters(
                    'eb_register_post_type_courses',
                    array(
                        'labels' => array(
                            'name' => __('Course', 'rdmcompas-moodle-connector'),
                            'singular_name' => __('Courses', 'rdmcompas-moodle-connector'),
                            'menu_name' => _x('Training Center', 'Admin menu name', 'rdmcompas-moodle-connector'),
                            'all_items' => __('Courses', 'rdmcompas-moodle-connector'),
                            'add_new' => __('Add Course', 'rdmcompas-moodle-connector'),
                            'add_new_item' => __('Add New Course', 'rdmcompas-moodle-connector'),
                            'edit' => __('Edit', 'rdmcompas-moodle-connector'),
                            'edit_item' => __('Edit Course', 'rdmcompas-moodle-connector'),
                            'new_item' => __('New Course', 'rdmcompas-moodle-connector'),
                            'view' => __('View Course', 'rdmcompas-moodle-connector'),
                            'view_item' => __('View Course', 'rdmcompas-moodle-connector'),
                            'search_items' => __('Search Courses', 'rdmcompas-moodle-connector'),
                            'not_found' => __('No Courses found', 'rdmcompas-moodle-connector'),
                            'not_found_in_trash' => __('No Courses found in trash', 'rdmcompas-moodle-connector'),
                        ),
                        'description' => __(
                            'This is where you can add new courses to your Moodle LMS.',
                            'rdmcompas-moodle-connector'
                        ),
                        'public' => true,
                        'capability_type' => 'post',
                        'capabilities' => array(
                            'create_posts' => false,
                        ),
                        'map_meta_cap' => true,
                        'show_ui' => true,
                        'show_in_menu' => true,
                        'menu_icon' => 'dashicons-book-alt',
                        'menu_position' => 54,
                        'hierarchical' => false, // Hierarchical causes memory issues - WP loads all records!
                        'rewrite' => array('slug' => 'courses'),
                        'query_var' => true,
                        'supports' => array('title', 'editor', 'thumbnail'),//, 'comments'),
                        'has_archive' => $show_archive,
                        'show_in_nav_menus' => true,
                        'taxonomies' => array('eb_course_cat'),
                    )
                )
            );
            flush_rewrite_rules(true);
        }

        do_action('eb_after_register_post_type');
    }

    /**
     * Register core post type meta boxes.
     *
     * @since         1.0.0
     */
    public function register_meta_boxes()
    {
        // register metabox for course post type options.
        add_meta_box(
            'eb_course_options',
            __('Course Options', 'rdmcompas-moodle-connector'),
            array($this, 'post_options_callback'),
            'eb_course',
            'advanced',
            'default',
            array('post_type' => 'eb_course')
        );

        // register metabox for recommended course section on single course page.
        add_meta_box(
            'eb_blended_course_options',
            __('Blended Course Settings', 'rdmcompas-moodle-connector'),
            array($this, 'post_options_callback'),
            'eb_course',
            'advanced',
            'default',
            array('post_type' => 'eb_course')
        );
    }

    /**
     * Callback for metabox fields.
     *
     * @param object $post current $post object.
     * @param array $args arguments supplied to the callback function.
     * @since         1.0.0
     *
     */
    public function post_options_callback($post, $args)
    {
        $post;
        // get fields for a specific post type.

        if ('eb_blended_course_options' === $args['id']) {
            $fields = $this->populate_metabox_fields($args['id']);
        } else {
            $fields = $this->populate_metabox_fields($args['args']['post_type']);
        }

        $css_class = '';
        echo '<div>';
        if ('eb_order' === $args['args']['post_type']) {
            $css_class = 'eb-wdm-order-meta';
            echo '<strong>';
            echo esc_html__('Order ', 'rdmcompas-moodle-connector') . esc_html(printf('#%s ', get_the_id())) . esc_html__('Details ', 'rdmcompas-moodle-connector');
            echo '</strong>';
            echo "<div id='" . esc_html($args['args']['post_type']) . "'_options' class='post-options " . esc_html($css_class) . "'>";
        } else {
            echo "<div id='" . esc_html($args['args']['post_type']) . "'_options' class='post-options'>";
        }

        // render fields using our render_metabox_fields() function.
        foreach ($fields as $key => $values) {
            $field_args = array(
                'field_id' => $key,
                'field' => $values,
                'post_type' => $args['args']['post_type'],
            );

            $this->render_metabox_fields($field_args);
        }
        // display content before order options, only if post type is moodle order.
        if ('eb_order' === $args['args']['post_type']) {
            $order_meta = new Eb_Order_Meta($this->plugin_name, $this->version);
            $order_meta->get_order_details(get_the_id());
        }
        wp_nonce_field('eb_post_meta_nonce', 'eb_post_meta_nonce');
        echo '</div>';
        do_action('eb_post_add_meta', $args);
        echo '</div>';
    }

    /**
     * Method to populate metabox fields for core post types.
     *
     * @param string $post_type returns array of fields for specific post type.
     *
     * @return array $args_array returns complete fields array.
     * @since     1.0.0
     *
     */
    private function populate_metabox_fields($post_type)
    {
        global $post;

        $post_id = get_the_id();

        $deletion_status = self::get_post_options($post_id, 'mdl_course_deleted', $post_type);

        $args_array = array(
            'eb_course' => array(
                'moodle_course_format' => array( //moodle custom field
                    'label' => __('Course Format', 'rdmcompas-moodle-connector'),
                    'description' => __('Course Format', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_id' => array(
                    'label' => __('Moodle Course ID', 'rdmcompas-moodle-connector'),
                    'description' => __('This field is disabled. Do not change the course id this will affect the course access for the existing enrollment.', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                    'default' => '0',
                    'note' => isset($deletion_status) && !empty($deletion_status) ? '<span style="color:red;">' . __('This course is deleted on Moodle', 'rdmcompas-moodle-connector') . '</span>' : '',
                ),
                'moodle_course_institution' => array( //moodle custom field
                    'label' => __('Institution', 'rdmcompas-moodle-connector'),
                    'description' => __('Institution', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_date_modified' => array( //moodle timemodified
                    'label' => __('Date Modified', 'rdmcompas-moodle-connector'),
                    'description' => __('Date Modified', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_target_group' => array( //moodle custom field
                    'label' => __('Target Group', 'rdmcompas-moodle-connector'),
                    'description' => __('Target Group', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_discipline' => array( //moodle custom field
                    'label' => __('Data Curation Lifecycle steps', 'rdmcompas-moodle-connector'),
                    'description' => __('Data Curation Lifecycle steps', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_duration' => array( //moodle custom field
                    'label' => __('Duration', 'rdmcompas-moodle-connector'),
                    'description' => __('Duration', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_required_material' => array( //moodle custom field
                    'label' => __('Required Material', 'rdmcompas-moodle-connector'),
                    'description' => __('Required Material', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_previous_experience' => array( //moodle custom field
                    'label' => __('Previous experience', 'rdmcompas-moodle-connector'),
                    'description' => __('Previous experience', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_persistent_identifier' => array( //moodle custom field
                    'label' => __('Persistent Identifier', 'rdmcompas-moodle-connector'),
                    'description' => __('Persistent Identifier', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_license' => array( //moodle custom field
                    'label' => __('License', 'rdmcompas-moodle-connector'),
                    'description' => __('License', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
            ),
            'eb_blended_course_options' => array(
                'moodle_course_contact_person' => array( //moodle custom field
                    'label' => __('Contact person', 'rdmcompas-moodle-connector'),
                    'description' => __('Contact person', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_contact_person_email' => array( //moodle custom field
                    'label' => __('Contact person Email', 'rdmcompas-moodle-connector'),
                    'description' => __('Contact person Email', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_date_start' => array( //moodle custom field
                    'label' => __('Start date', 'rdmcompas-moodle-connector'),
                    'description' => __('Start date', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
                'moodle_course_number_participants' => array( //moodle custom field
                    'label' => __('Number of participants', 'rdmcompas-moodle-connector'),
                    'description' => __('Number of participants', 'rdmcompas-moodle-connector'),
                    'type' => 'text',
                    'placeholder' => '',
                    'attr' => 'readonly',
                ),
//                'enable_recmnd_courses' => array(
//                    'label' => __('Show Recommended Courses', 'rdmcompas-moodle-connector'),
//                    'description' => __('Show recommended courses on single course page.', 'rdmcompas-moodle-connector'),
//                    'default' => 'no',
//                    'type' => 'checkbox',
//                    'autoload' => false,
//                ),
//                'show_default_recmnd_course' => array(
//                    'label' => __('Show Category Wise Recommended Courses', 'rdmcompas-moodle-connector'),
//                    'description' => __('Show category wise selected recommended courses on single course page.', 'rdmcompas-moodle-connector'),
//                    'default' => 'no',
//                    'type' => 'checkbox',
//                    'autoload' => false,
//                ),
//                'enable_recmnd_courses_single_course' => array(
//                    'label' => __('Select Courses', 'rdmcompas-moodle-connector'),
//                    'description' => __('Select courses to show in custom courses in recommended course section.', 'rdmcompas-moodle-connector'),
//                    'type' => 'select_multi',
//                    'options' => isset($post->ID) ? \app\wisdmlabs\edwiserBridge\wdm_eb_get_all_eb_sourses($post->ID) : array(),
//                    'default' => array('pending'),
//                ),
            ),
        );

        $args_array = apply_filters('eb_post_options', $args_array);

        if (!empty($post_type)) {
            if (isset($args_array[$post_type])) {
                return $args_array[$post_type];
            } else {
                return $args_array;
            }
        }
    }

    /**
     * Generate HTML for displaying metabox fields.
     *
     * @param array $args Field data.
     * @since               1.0.0
     *
     */
    public function render_metabox_fields($args)
    {
        $post_id = get_the_id();
        $field_id = $args['field_id'];
        $field = $args['field'];
        $post_type = $args['post_type'];
        $html = '';
        $option_name = $post_type . '_options[' . $field_id . ']';
        $option = self::get_post_options($post_id, $field_id, $post_type);
        $eb_plugin_url = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_url();

        $data = '';
        if ($option) {
            $data = $option;
        } elseif (isset($field['default'])) {
            $data = $field['default'];
        }

        if (!isset($field['placeholder'])) {
            $field['placeholder'] = '';
        }
        $label = '';
        if (isset($field['label'])) {
            $label = $field['label'];
        }
        $attr = '';
        if (isset($field['attr'])) {
            $attr = $field['attr'];
        }
        ?>
        <div id='<?php echo esc_html($post_type . '_' . $field_id); ?>' class='field-input-box'>
			<span class='eb-option-label'>
				<label class='field-label'><?php echo esc_html($label); ?></label>
			</span>

            <span class='eb-option-input'>
				<div class='eb-option-div'>

		<?php
        switch ($field['type']) {
            case 'title':
                ?>
                <h2 id=" <?php esc_attr($field_id); ?>"> <?php echo esc_attr($field['label']); ?></h2>
                <?php
                break;
            case 'label':
                ?>
                <span id="<?php echo esc_attr($field_id); ?>"><b> <?php echo esc_html($data); ?></b></span>
                <?php
                break;
            case 'text':
            case 'password':
            case 'number':
                ?>
                <input id="<?php echo esc_attr($field_id); ?>" type="<?php echo esc_attr($field['type']); ?>"
                       name="<?php echo esc_attr($option_name); ?>"
                       placeholder="<?php echo esc_attr($field['placeholder']); ?>"
                       value="<?php echo esc_html($data); ?>" <?php echo esc_html($attr); ?>/>
                <?php
                break;
            case 'date':
                ?>
                <input id="<?php echo esc_attr($field_id); ?>" type="date"
                       name="<?php echo esc_attr($option_name); ?>"
                       placeholder="<?php echo esc_attr($field['placeholder']); ?>"
                       value="<?php echo date('Y-m-d', intval($data)); ?>" <?php echo esc_html($attr); ?>/>
                <?php
                break;
            case 'text_secret':
                ?>
                <input id="<?php echo esc_attr($field_id); ?>" type="text" name="<?php echo esc_attr($option_name); ?>"
                       placeholder="<?php echo esc_attr($field['placeholder']); ?>" value=""/>
                <?php
                break;
            case 'textarea':
                ?>
                '<textarea id="<?php echo esc_attr($field_id); ?>" rows="5" cols="50"
                           name="<?php echo esc_attr($option_name); ?>"
                           placeholder="<?php echo esc_attr($field['placeholder']); ?>"> <?php echo esc_html($data); ?></textarea>
                <?php
                break;
            case 'checkbox':
                $checked = '';
                if ($option && 'yes' === $option) {
                    $checked = 'checked="checked"';
                }
                ?>
                <input id="<?php echo esc_attr($field_id); ?>" type="<?php echo esc_attr($field['type']); ?>"
                       name="<?php echo esc_attr($option_name); ?>" <?php echo esc_html($checked); ?>/>
                <?php
                break;
            case 'checkbox_multi':
                foreach ($field['options'] as $k => $v) {
                    $checked = false;
                    if (in_array($k, $data, true)) {
                        $checked = true;
                    }
                    ?>
                    <label for="<?php echo esc_attr($field_id . '_' . $k); ?>' name="
                           <?php echo esc_attr($option_name); ?>[]" value="<?php echo esc_attr($k); ?>" id="<?php echo esc_attr($field['id'] . '_' . $k); ?>" /> <?php echo esc_html($v); ?></label>
                    <?php
                }
                break;
            case 'radio':
                foreach ($field['options'] as $k => $v) {
                    $checked = false;
                    if ($k === $data) {
                        $checked = true;
                    }
                    ?>
                    <label for="<?php echo esc_attr($field_id . '_' . $k); ?>"><input
                                type="radio" <?php echo checked($checked, true, false); ?> name="<?php echo esc_attr($option_name); ?>"
                                value="<?php echo esc_attr($k); ?>"
                                id="<?php echo esc_attr($field['id'] . '_' . $k); ?>"/> <?php echo esc_html($v); ?></label>
                    <?php
                }
                break;
            case 'select':
                ?>
                <select name=" <?php echo esc_attr($option_name); ?>" id="<?php echo esc_attr($field_id); ?>">
				<?php
                foreach ($field['options'] as $k => $v) {
                    $selected = false;
                    if ($k === $data) {
                        $selected = true;
                    }
                    ?>
                    <option <?php echo selected($selected, true, false); ?> value="<?php echo esc_attr($k); ?>"><?php echo esc_html($v); ?></option>
                    <?php
                }
                ?>
				</select>
                <?php
                break;
            case 'select_multi':
                ?>
                <select name="<?php echo esc_attr($option_name); ?>[]" id="<?php echo esc_attr($field_id); ?>"
                        multiple="multiple">
				<?php
                foreach ($field['options'] as $k => $v) {
                    $selected = false;
                    if (in_array(trim($k), $data, true)) {
                        $selected = true;
                    }
                    ?>
                    <option <?php echo selected($selected, true, false); ?>
                    value="<?php echo esc_attr($k); ?>" /><?php echo esc_html($v); ?></label>
                    <?php
                }
                ?>
				</select>
                <?php
                break;
        }

        switch ($field['type']) {
            case 'textarea':
            case 'select_multi':
                ?>
                <em><p class="description-label <?php esc_attr($field_id); ?> "><?php echo esc_attr($field['description']); ?></p></em>
                <?php
                break;
            default:
                ?>
                <span class="description-label <?php esc_attr($field_id); ?>"><img class="help-tip"
                                                                                   src="<?php echo esc_html($eb_plugin_url); ?>images/question.png"
                                                                                   data-tip="<?php echo esc_attr($field['description']); ?>"/></span>
                <?php echo isset($field['note']) ? wp_kses($field['note'], \app\wisdmlabs\edwiserBridge\wdm_eb_sinlge_course_get_allowed_html_tags()) : ''; ?>
                <?php
                break;
        }
        ?>
		</div>
        </span>
        </div>
        <?php
    }

    /**
     * Hanlder to save post data on post save
     * At first we are cleaning & formatting the data then saving in post meta.
     *
     * @param int $post_id id of current post.
     *
     * @return bool returns true
     * @since           1.0.0
     *
     */
    public function handle_post_options_save($post_id)
    {
        $fields = array();
        $save_status = true;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            $save_status = false;
        } else {

            // Options to update will be stored here.
            $update_post_options = array();
            // get current post type.
            $post_type = get_post_type($post_id);

            if (in_array($post_type, array('eb_course', 'eb_order'), true)) {
                if ('eb_course' === $post_type) {
                    $fields = $this->populate_metabox_fields($post_type);
                    $fields = array_merge($this->populate_metabox_fields('eb_recommended_course_options'), $fields);
                }
                $post_options = array();

                if (isset($_POST['eb_post_meta_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['eb_post_meta_nonce'])), 'eb_post_meta_nonce')) {
                    if (isset($_POST[$post_type . '_options'])) {
                        $post_options = \app\wisdmlabs\edwiserBridge\wdm_eb_edwiser_sanitize_array($_POST[$post_type . '_options']); // WPCS: input var ok, CSRF ok, sanitization ok.
                    }
                    if (!empty($post_options)) {
                        foreach ($fields as $key => $values) {
                            $option_name = $key;
                            $option_value = null;
                            if (isset($post_options[$key])) {
                                $option_value = wp_unslash($post_options[$key]);
                            }
                            // format the values.
                            // switch ( sanitize_title( $values['type'] ) ) {.
                            switch ($values['type']) {
                                case 'checkbox':
                                    if (is_null($option_value)) {
                                        $option_value = 'no';
                                    } else {
                                        $option_value = 'yes';
                                    }
                                    break;
                                case 'textarea':
                                    $option_value = wp_kses_post(trim($option_value));
                                    break;
                                case 'text':
                                case 'text_secret':
                                case 'number':
                                case 'select':
                                case 'password':
                                case 'radio':
                                    $option_value = wdm_edwiser_bridge_wp_clean($option_value);
                                    break;
                                case 'select_multi':
                                case 'checkbox_multi':
                                    $option_value = array_filter(array_map('wpClean', (array)$option_value));
                                    break;
                                default:
                                    break;
                            }

                            if (!is_null($option_value)) {
                                $update_post_options[$option_name] = $option_value;
                            }
                        }

                        if (is_array($update_post_options)) {
                            /*
                            * merge previous values in array with new values retrieved
                            * replace old values with new values and save as option
                            *
                            * To keep custom buyer data saved in same order meta key, so that it is not erased on post save.
                            */
                            $previous = get_post_meta($post_id, $post_type . '_options', true);
                            $merged = array_merge($previous, $update_post_options);
                            update_post_meta($post_id, $post_type . '_options', $merged);
                        }
                    }
                }
            }
        }

        return $save_status;
    }

    /**
     * Update post updated messages for all CPTs added by our plugin.
     *
     * @param [type] $messages messages.
     * @since  1.0.0
     *
     */
    public function custom_post_type_update_messages($messages)
    {
        global $post;

        $post_ID = $post->ID;
        $post_type = get_post_type($post_ID);

        $obj = get_post_type_object($post_type);
        $singular = $obj->labels->singular_name;

        $messages[$post_type] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf('%s', esc_attr($singular)) .
                __(' updated.', 'rdmcompas-moodle-connector') . '<a href="' . sprintf('%s" target="_blank">', esc_url(get_permalink($post_ID))) . __(' View ', 'rdmcompas-moodle-connector') .
                sprintf(
                    '%s</a>',
                    strtolower($singular)
                ),
            2 => __('Custom field updated.', 'rdmcompas-moodle-connector'),
            3 => __('Custom field deleted.', 'rdmcompas-moodle-connector'),
            4 => sprintf('%s ', esc_attr($singular)) . __('updated.', 'rdmcompas-moodle-connector'),
            5 => isset($_GET['revision']) ? sprintf( // WPCS: input var ok, CSRF ok, sanitization ok.
                    '%s ',
                    wp_post_revision_title(filter_input(INPUT_GET, 'revision', FILTER_SANITIZE_NUMBER_INT), false)
                ) . __('restored to revision from ', 'rdmcompas-moodle-connector') . sprintf(
                    '%s ',
                    esc_attr($singular)
                ) : false,
            6 => sprintf(
                '%1$s published. <a href="%2$s">View %3$s</a>',
                $singular,
                esc_url(get_permalink($post_ID)),
                strtolower($singular)
            ),
            7 => sprintf('%s ', esc_attr($singular)) . __('saved.', 'rdmcompas-moodle-connector'),
            8 => sprintf(
                '%1$s submitted. <a href="%2$s" target="_blank">Preview %3$s</a>',
                $singular,
                esc_url(add_query_arg('preview', 'true', get_permalink($post_ID))),
                strtolower($singular)
            ),
            9 => sprintf(
                '%1$s' . __('scheduled for:  ', 'rdmcompas-moodle-connector') . '<strong>' . '%2$s' . '</strong><a href="' . '%3$s' . '" target="_blank">' . __('Preview ', 'rdmcompas-moodle-connector') . '%4$s</a>', // @codingStandardsIgnoreLine
                $singular,
                date_i18n(
                    __('M j, Y @ G:i'),
                    strtotime($post->post_date)
                ),
                esc_url(
                    get_permalink($post_ID)
                ),
                strtolower($singular)
            ),
            10 => sprintf(
                '%1$s' . __(' draft updated. ', 'rdmcompas-moodle-connector') . '<a href="' . '%2$s' . '" target="_blank">' . __('Preview ', 'rdmcompas-moodle-connector') . '%3$s </a>', // @codingStandardsIgnoreLine
                $singular,
                esc_url(add_query_arg('preview', 'true', get_permalink($post_ID))),
                strtolower($singular)
            ),
        );

        return $messages;
    }

    /**
     * Get post option.
     *
     * @param string $post_id post id.
     * @param string $key key id.
     * @param string $post_type post_type id.
     * @param string $default default id.
     */
    public static function get_post_options($post_id, $key, $post_type, $default = false)
    {
        if (empty($key)) {
            return $default;
        }

        $post_options = get_post_meta($post_id, $post_type . '_options', true);

        if (is_array($key)) {
            foreach ($key as $k) {
                $value[$k] = isset($post_options[$k]) ? $post_options[$k] : $default;
            }
        } else {
            $value = isset($post_options[$key]) ? $post_options[$key] : $default;
        }

        return $value;
    }
}
