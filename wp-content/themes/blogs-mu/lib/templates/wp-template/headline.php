<?php if (is_category()) { ?>

<h2 id="post-description"><?php single_cat_title(); ?>&acute;s <?php _e("archives &darr;",TEMPLATE_DOMAIN); ?></h2>

<?php } else if (is_archive()) { ?>

<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

<?php /* If this is a category archive */ if (is_day()) { ?>
<h2 id="post-description">
<?php _e("Archive for",TEMPLATE_DOMAIN); ?> <?php the_time('F jS, Y'); ?> &darr;
</h2>
<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
<h2 id="post-description">
<?php _e("Archive for",TEMPLATE_DOMAIN); ?> <?php the_time('F, Y'); ?> &darr;
</h2>
<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
<h2 id="post-description">
<?php _e("Archive for",TEMPLATE_DOMAIN); ?> <?php the_time('Y'); ?> &darr;
</h2>
<?php } ?>

<?php } else if (is_search()) { ?>

<h2 id="post-description"><?php _e("Search Result For",TEMPLATE_DOMAIN); ?> &quot; <?php the_search_query(); ?> &quot;</h2>

<?php } ?>