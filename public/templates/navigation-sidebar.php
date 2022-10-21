<?php
/*
 * Training center  sidebar navigation
 * Output HTML formatted list of categories, hierarchically ordered
 */
$top_categories = get_categories(array(
    'taxonomy' => 'eb_course_cat',
    'parent' => '0',
    'hide_empty' => '0'
));
//sort categories by acf value
usort($top_categories, function ($a, $b) {
    $idx_a = get_field("category_index", "category_" . $a->term_id);
    $idx_b = get_field("category_index", "category_" . $b->term_id);

    return $idx_a < $idx_b ? -1 : 1;
});
//get current category id
global $wp_query;
$term = $wp_query->get_queried_object();
?>
<div class="kb-nav__container">
    <?php
    foreach ($top_categories as $top_category) {
        if ($top_category->name === 'Uncategorized') {
            continue;
        }
        $current_cat = $top_category->cat_ID === $term->term_id ? ' current-cat' : '';
        echo '<li class="cat-item cat-item-' . $top_category->cat_ID . $current_cat . ' top-cat-item"><a href="' . get_category_link($top_category->cat_ID) . '">' . $top_category->name . '</a></li>';
        $child_categories = get_categories('hide_empty=0&parent=' . $top_category->cat_ID);
        if ($child_categories) {
            usort($child_categories, function ($a, $b) {
                return get_field("category_index", "category_" . $a->term_id) - get_field("category_index", "category_" . $b->term_id);
            }); ?>
            <ul class="kb-nav__list children">
                <?php
                foreach ($child_categories as $child) {
                    $current_cat = $child->cat_ID === $term->term_id ? ' current-cat' : '';
                    echo '<li class="cat-item cat-item-' . $child->cat_ID . $current_cat . '"><a href=' . get_category_link($child->cat_ID) . '>' . $child->name . '</a></li>';
//                    list articles if there is more than 1
                    $args = array(
                        'post_type' => 'articles',
                        'category' => $child->cat_ID,
                        'orderby' => 'menu_order',
                        'numberposts' => -1
                    );
                    $articles = get_posts($args);
                    if (count($articles) > 1) {
                        usort($articles, function ($a, $b) {
                            return get_field("article_index", $a->ID) - get_field("article_index", $b->ID);
                        });
                        echo '<ul class="kb-nav__list children">';
                        foreach ($articles as $article) {
                            $current_article = $article->ID === $term->ID ? ' current-cat' : '';
                            echo '<li class="cat-item cat-item-' . $article->ID . $current_article . '"><a href=' . get_permalink($article->ID) . '>' . $article->post_title . '</a></li>';
                        }
                        echo '</ul>';
                    }
                } ?>
            </ul>
        <?php }
    } ?>
</div>
