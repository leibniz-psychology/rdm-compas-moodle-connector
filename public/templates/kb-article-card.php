<?php
global $kb_article;

$title = $kb_article->post_title;
$permalink = get_permalink($kb_article);
$excerpt = get_the_excerpt($kb_article);
?>
<div class="rdm-tc_article-container">
    <a class="rdm-tc_article-link" href="<?php echo $permalink; ?>"><?php echo $title; ?></a>
    <p class="rdm-tc_article-excerpt"><?php echo $excerpt; ?></p>
</div>