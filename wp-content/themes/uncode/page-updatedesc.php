<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package uncode
 */

get_header();
global $wpdb;

$products = $wpdb->get_results( 'SELECT ID,post_title FROM wp_posts where post_type="product" and post_status="publish" and ID between 15000 and 20000' );

foreach($products as $pro)
{
	
	/*$excerpt = get_post_meta($pro->ID,'sf_product_short_description',true);
	
	
	$my_post = array(
      'ID'           => $pro->ID,
      'post_excerpt'   => $excerpt
	  
  );

  wp_update_post( $my_post );*/
  $wpdb->query('update wp_posts set post_excerpt=(select meta_value from wp_postmeta where post_id='.$pro->ID.' and meta_key="sf_product_short_description" limit 1) where ID='.$pro->ID.'');
  
}
echo 'Products found '.count($products);



get_footer(); ?>