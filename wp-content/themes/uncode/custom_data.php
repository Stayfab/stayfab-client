<?php
$type = 'wpsl_stores';
$args=array(
  'post_type' => $type,
  'post_status' => 'publish',
  'posts_per_page' => -1,
);

query_posts($args);

if( have_posts() ) {
  while (have_posts()) : the_post(); ?>
    <p><?php the_title(); ?></p>
    <?php
  endwhile;
}
?>