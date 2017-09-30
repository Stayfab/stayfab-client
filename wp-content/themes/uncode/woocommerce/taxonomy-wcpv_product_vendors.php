<style>
.row > .column {
  padding: 0 8px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

.column {
  float: left;
  width: 20%;
  max-width: 180px;
    padding: 0px !important;
}

/* The Modal (background) */
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  padding-top: 100px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.76);
  z-index: 9999;
}

/* Modal Content */
.modal-content {
   margin: auto;
   padding: 0 0 15px;
   position: relative;
   width: 50%;
	z-index: 9999;
	max-width: 700px;
  
}

/* The Close Button */
.close {
  	 background: #333333 none repeat scroll 0 0;
    color: white;
    font-size: 30px;
    font-weight: bold;
    height: 36px;
    line-height: 36px;
    position: absolute;
    right: 0;
    text-align: center;
    top: 0;
    width: 36px;
    z-index: 9999;
}

.close:hover,
.close:focus {
  color: #999;
  text-decoration: none;
  cursor: pointer;
}

.mySlides {
  display: none;
}

/* Next & previous buttons */
.prev,
.next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -50px;
  color: white;
  font-weight: bold;
  font-size: 20px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
  -webkit-user-select: none;
  background-color: rgba(0, 0, 0, 0.8);
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover,
.next:hover {
  background-color: rgba(0, 0, 0, 0.9);
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

.caption-container {
  display:none;
}

img.demo {
  opacity: 0.6;
}

img.demo.active, .demo:hover {
  opacity: 1;
  cursor: pointer;
}

img.hover-shadow {
  transition: 0.3s
}

.hover-shadow:hover {
	box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)
}
.wq_css
{
	display: inline-block;
   float: none;
   padding: 15px 10px 15px 10px;
   width: 15%;
}

.wq_center
{
	 text-align: center;
}
</style>

<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 	WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' );

/**
 * DATA COLLECTION - START
 *
 */

/** Init variables **/

$term_data = $wp_query->get_queried_object();

		
		
$limit_width = $limit_content_width = $the_content = $main_content = $layout = $sidebar_style = $sidebar_bg_color = $sidebar = $sidebar_size = $sidebar_sticky = $sidebar_padding = $sidebar_inner_padding = $sidebar_content = $title_content = $navigation_content = $page_custom_width = $row_classes = $main_classes = $footer_classes = $generic_body_content_block = '';
$index_has_navigation = false;

global $wp_query;
$post_type = 'product_index';
$single_post_width = ot_get_option('_uncode_' . $post_type . '_single_width');
set_query_var( 'single_post_width', $single_post_width );

/** Get general datas **/
$style = ot_get_option('_uncode_general_style');
$bg_color = ot_get_option('_uncode_general_bg_color');
$bg_color = ($bg_color == '') ? ' style-'.$style.'-bg' : ' style-'.$bg_color.'-bg';

/** Get page width info **/
$generic_content_full = ot_get_option('_uncode_' . $post_type . '_layout_width');
if ($generic_content_full === '') {
	$main_content_full = ot_get_option('_uncode_body_full');
	if ($main_content_full === '' || $main_content_full === 'off') $limit_content_width = ' limit-width';
} else {
	if ($generic_content_full === 'limit') {
		$generic_custom_width = ot_get_option('_uncode_' . $post_type . '_layout_width_custom');
		if (is_array($generic_custom_width) && !empty($generic_custom_width)) {
			$page_custom_width = ' style="max-width: ' . implode("", $generic_custom_width) . ';"';
		}
	}
}

/** Collect header data **/
$page_header_type = ot_get_option('_uncode_' . $post_type . '_header');
if ($page_header_type !== '' && $page_header_type !== 'none')
{
	$metabox_data['_uncode_header_type'] = array($page_header_type);
	$tax = $wp_query->get_queried_object();
	if (isset($tax->term_id)) {
		$term_back = get_option( '_uncode_taxonomy_' . $tax->term_id );
		if (isset($term_back['term_media']) && $term_back['term_media'] !== '') $featured_image = $term_back['term_media'];
		else $featured_image = get_woocommerce_term_meta( $tax->term_id, 'thumbnail_id', true );
	} else {
		$featured_image = '';
	}
	$meta_data = uncode_get_general_header_data($metabox_data, $post_type, $featured_image);
	$metabox_data = $meta_data['meta'];
	$show_title = $meta_data['show_title'];
}

/** Get layout info **/
$activate_sidebar = ot_get_option('_uncode_' . $post_type . '_activate_sidebar');
if ($activate_sidebar !== 'off')
{
	$layout = ot_get_option('_uncode_' . $post_type . '_sidebar_position');
	if ($layout === '') $layout = 'sidebar_right';
	$sidebar = ot_get_option('_uncode_' . $post_type . '_sidebar');
	$sidebar_style = ot_get_option('_uncode_' . $post_type . '_sidebar_style');
	$sidebar_size = ot_get_option('_uncode_' . $post_type . '_sidebar_size');
	$sidebar_sticky = ot_get_option('_uncode_' . $post_type . '_sidebar_sticky');
	$sidebar_sticky = ($sidebar_sticky === 'on') ? ' sticky-element sticky-sidebar' : '';
	$sidebar_fill = ot_get_option('_uncode_' . $post_type . '_sidebar_fill');
	$sidebar_bg_color = ot_get_option('_uncode_' . $post_type . '_sidebar_bgcolor');
	$sidebar_bg_color = ($sidebar_bg_color !== '') ? ' style-' . $sidebar_bg_color . '-bg' : '';
	if ($sidebar_style === '') $sidebar_style = $style;
}

/** Get breadcrumb info **/
$generic_breadcrumb = ot_get_option('_uncode_' . $post_type . '_breadcrumb');
$show_breadcrumb = ($generic_breadcrumb === 'off') ? false : true;
if ($show_breadcrumb) $breadcrumb_align = ot_get_option('_uncode_' . $post_type . '_breadcrumb_align');

/** Get title info **/
$generic_show_title = ot_get_option('_uncode_' . $post_type . '_title');
$show_title = ($generic_show_title === 'off') ? false : true;

/**
 * DATA COLLECTION - END
 *
 */

/** Build header **/
if ($page_header_type !== '' && $page_header_type !== 'none')
{
	$get_title = woocommerce_page_title(false);
	$get_subtitle = get_queried_object()->description;
	$page_header = new unheader($metabox_data, $get_title, $get_subtitle);

	$header_html = $page_header->html;
	if ($header_html !== '') {
		echo '<div id="page-header">';
		echo uncode_remove_wpautop( $page_header->html );
		echo '</div>';
	}
}
echo '<script type="text/javascript">UNCODE.initHeader();</script>';

/** Build breadcrumb **/

if ($show_breadcrumb)
{
	if ($breadcrumb_align === '') $breadcrumb_align = 'right';
	$breadcrumb_align = ' text-' . $breadcrumb_align;

	$content_breadcrumb = uncode_breadcrumbs();
	$breadcrumb_title = '<div class="breadcrumb-title h5 text-bold">' . uncode_archive_title() . '</div>';
	echo uncode_get_row_template($breadcrumb_title . $content_breadcrumb, '', ($page_custom_width !== '' ? ' limit-width' : $limit_content_width), $style, ' row-breadcrumb row-breadcrumb-' . $style . $breadcrumb_align, 'half', true, 'half');
}

/** Build title **/

if ($show_title)
{
	$get_title = get_queried_object()->description !== '' ? get_queried_object()->description : woocommerce_page_title(false);
	$title_content = '<div class="post-title-wrapper"><h1 class="post-title">' . $get_title . '</h1></div>';
}


?>
<div class="row limit-width row-parent">
 <?php  

//echo $term_data->slug;

?>



<div class="fb-text form-group field-text-city-name">
<h3><?php echo get_term_meta( $term_data->term_id, 'by', true ); ?></h3>
</div>
<br>
<?php
	$vendor_term_logo = get_term_meta($term_data->term_id, 'vendor_data', true);
	
	
	$logo_post_id = $vendor_term_logo['logo']; 
	
	$logo_post = get_the_guid($logo_post_id);
	
	$logo_image = wp_get_attachment_image_src($logo_post_id,'thumbnail');
	$shopphoto_post_id = $vendor_term_logo['shopphoto']; 
	
	$shopphoto_post = get_the_guid($shopphoto_post_id);
	
	

?>

<?php

	$shopphoto_image=  wp_get_attachment_image_src($vendor_term_logo['shopphoto'],'thumbnail');
	$shopphoto1_image=  wp_get_attachment_image_src($vendor_term_logo['shopphoto1'] ,'thumbnail');
	$shopphoto2_image=  wp_get_attachment_image_src($vendor_term_logo['shopphoto2'] ,'thumbnail');
	
	
	
	$shopphoto_large1 = get_the_guid($vendor_term_logo['shopphoto']);
	$shopphoto_large2 = get_the_guid($vendor_term_logo['shopphoto1']);
	$shopphoto_large3 = get_the_guid($vendor_term_logo['shopphoto2']);
	
?>




<div class="fb-text form-group field-text-Logo">
<img style="    max-width: 250px;    max-height: 120px;padding-bottom: 20px;" src="<?php echo $logo_post;?>">
</div>

<br>
<div class="row">
  <div class="column">
    <img src="<?php echo $shopphoto_image[0];?>" onclick="openModal();currentSlide(1)" class="hover-shadow">
  </div>
  <div class="column">
    <img src="<?php echo $shopphoto1_image[0];?>" onclick="openModal();currentSlide(2)" class="hover-shadow">
  </div>
  <div class="column">
    <img src="<?php echo $shopphoto2_image[0];?>" onclick="openModal();currentSlide(3)" class="hover-shadow">
  </div>
</div>

<div id="myModal" class="modal">
  
  <div class="modal-content">
		<span class="close cursor" onclick="closeModal()">&times;</span>
    <div class="mySlides">
    
        <img src="<?php echo $shopphoto_large1;?>" style="width:100%">
    </div>

    <div class="mySlides">
     
        <img src="<?php echo $shopphoto_large2;?>" style="width:100%">
    </div>

    <div class="mySlides">
     
        <img src="<?php echo $shopphoto_large3;?>" style="width:100%">
    </div>

    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>

    <div class="caption-container">
      <p id="caption"></p>
    </div>

   <div class="wq_center">
    <div class="column wq_css">
      <img class="demo" src="<?php echo $shopphoto_image[0];?>" onclick="currentSlide(1)">
    </div>

    <div class="column wq_css">
      <img class="demo" src="<?php echo $shopphoto1_image[0];?>" onclick="currentSlide(2)">
    </div>

    <div class="column wq_css">
      <img class="demo" src="<?php echo $shopphoto2_image[0];?>" onclick="currentSlide(3)">
    </div>
   </div>
    
  </div>
</div>






<div class="fb-text form-group field-text-Vendor">
<label for="text-Vendor" class="fb-text-label"><h2 style="margin-bottom: 20px;"><?php echo $term_data->name; ?></h2></label>
<?php /*?><input type="text" class="form-control" name="text-Vendor" id="text-Vendor" value="<?php echo $term_data->name; ?>"><?php */?>
</div>
<?php $vendor_desc = term_description( $term_data->term_id, 'wcpv_product_vendors' ) ; 
$vendor_desc = str_replace('<p>','',$vendor_desc);
$vendor_desc = str_replace('</p>','',$vendor_desc);
?>
<div class="fb-text form-group field-text-Vendordesc" style="padding:10px 0px;">
<label for="text-Vendordesc" class="fb-text-label" style="font-weight:bold">Beskrivelse:</label>
<?php echo $vendor_desc;?>
</div>

<div class="fb-text form-group field-text-address" style="padding:10px 0px;">
<label for="text-address" class="fb-text-label" style="font-weight:bold">Adresse:</label>
<?php echo get_term_meta( $term_data->term_id, 'address', true ); ?>
</div>

<div class="fb-text form-group field-text-phone" style="padding:10px 0px 100px 0px;">
<label for="text-phone" class="fb-text-label" style="font-weight:bold">Telefon nr.:</label>
<?php echo get_term_meta( $term_data->term_id, 'phone', true ); ?>
</div>

</div>

<?php get_footer( 'shop' ); ?>