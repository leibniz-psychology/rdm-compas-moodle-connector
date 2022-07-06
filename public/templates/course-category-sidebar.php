<?php
/*
 * Course category sidebar card
 * Display information about current course category
 */

// Variables.
global $post;
$categories = get_the_terms($post->ID, 'eb_course_cat'); ?>
<div class="kb-nav__container">
    <?php foreach ($categories as $category) { ?>
        <h3><a href="<?php echo esc_url(get_category_link($category->term_id)) ?>"><?php echo $category->name; ?></a>
        </h3>
        <p><?php echo $category->category_description; ?></p>
        <h4><?php echo __('Other Training Units in this Category', 'edwiser-bridge'); ?></h4>
        <?php
        $courses = get_posts(array(
            'post_type' => 'eb_course',
            'numberposts' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'eb_course_cat',
                    'field' => 'term_id',
                    'terms' => $category->term_id,
                )
            )
        )); ?>
        <ul>
            <?php foreach ($courses as $course) {
//                if ($course->ID === $post->ID) {
//                    continue;
//                }
                ?>

                <li><a href="<?php echo esc_url(get_permalink($course->ID)) ?>"><?php echo $course->post_title; ?></a>
                </li>
            <?php } ?>
        </ul>

        <?php wp_reset_postdata(); ?>
    <?php } ?>
</div>
