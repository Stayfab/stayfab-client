<?php

/**
 * Template Name: userlists
*/

get_header();
$args = array(
    'post_type' => 'wpsl_stores',
    'post_status' => 'publish'
);
$query = new WP_Query($args);

while ($query->have_posts()) {
    $query->the_post();
    $post_id = get_the_ID();
}
wp_reset_query();



$blogusers = get_users(array('orderby'=>'ID'));

if ($page_header_type !== '' && $page_header_type !== 'none') {
			$page_header = new unheader($metabox_data, $post->post_title);

			$header_html = $page_header->html;
			if ($header_html !== '') {
				echo '<div id="page-header">';
				echo uncode_remove_wpautop( $page_header->html );
				echo '</div>';
			}

			if (!empty($page_header->poster_id) && $page_header->poster_id !== false && $media !== '') {
				$media = $page_header->poster_id;
			}
		}
		echo '<script type="text/javascript">UNCODE.initHeader();</script>';
		if ($show_breadcrumb && !is_front_page() && !is_home())
		{
			if ($breadcrumb_align === '') $breadcrumb_align = 'right';
			$breadcrumb_align = ' text-' . $breadcrumb_align;

			$content_breadcrumb = uncode_breadcrumbs();
			$breadcrumb_title = '<div class="breadcrumb-title h5 text-bold">' . get_the_title() . '</div>';
			echo uncode_get_row_template($breadcrumb_title . $content_breadcrumb, '', $limit_content_width, $style, ' row-breadcrumb row-breadcrumb-' . $style . $breadcrumb_align, 'half', true, 'half');
		}
?>
<table style="margin-left: 50px;margin-bottom:20px">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>City</th>
    <th>Address</th>
    <th>Latitude</th>
    <th>Longitude</th>
  </tr>
<?php  

foreach ( $blogusers as $user ) {
	$u_addr = get_user_meta($user->ID,'address',true);
	$u_city = get_user_meta($user->ID,'city',true);
	$u_lat = get_user_meta($user->ID,'lat',true);
	$u_long = get_user_meta($user->ID,'long',true);
	
	?>
	<tr>
		<td><?php echo $user->ID;?></td>
        <td><?php echo $user->user_login;?></td>
		<td><?php echo $user->user_email;?></td>
		<td><?php echo $u_city;?></td>
		<td><?php echo $u_addr;?></td>
        <td><?php echo $u_lat;?></td>
        <td><?php echo $u_long;?></td>
  	</tr>
    <?php
}
?></table>
<?php get_footer(); ?>