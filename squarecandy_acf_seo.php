<?php
/*
Plugin Name: Square Candy ACF SEO Plugin
Plugin URI:  http://squarecandydesign.com
Description: provides basic SEO meta fields, defaults and per post overrides
Version:     20170511
Author:      Square Candy Design
Author URI:  http://squarecandydesign.com
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Text Domain: squarecandy_acf_seo
*/

// don't let users activate w/o ACF
register_activation_hook( __FILE__, 'squarecandy_acf_seo_activate' );
function squarecandy_acf_seo_activate(){
	if ( !function_exists('acf_add_options_page') || !function_exists('get_field') ) {
		// check that ACF functions we need are available. Complain and bail out if they are not
		wp_die('The Square Candy ACF SEO Plugin requires ACF
			(<a href="https://www.advancedcustomfields.com">Advanced Custom Fields</a>).
			<br><br><button onclick="window.history.back()">&laquo; back</button>');
	}
}

// Add SEO Options Page
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'SEO & Meta Data Default Settings',
		'menu_title'	=> 'SEO Settings',
		'menu_slug' 	=> 'squarecandy-acf-seo-settings',
		'capability'	=> 'edit_theme_options',
		'redirect'		=> false
	));
}


if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_5914613407e76',
	'title' => 'SEO Settings',
	'fields' => array (
		array (
			'key' => 'field_5914613d4d0d5',
			'label' => 'Default Meta Description',
			'name' => 'default_meta_description',
			'type' => 'textarea',
			'instructions' => 'Add a default description for you site. This will appear in google results under the title and URL.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => 160,
			'rows' => 2,
			'new_lines' => '',
		),
		array (
			'key' => 'field_591461db4d0d6',
			'label' => 'Default Social Media Image',
			'name' => 'default_social_media_image',
			'type' => 'image',
			'instructions' => '1200 x 630',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'preview_size' => 'squarecandy-acf-seo-thumb',
			'library' => 'all',
			'min_width' => 1200,
			'min_height' => 630,
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array (
			'key' => 'field_591479e548b4e',
			'label' => 'Twitter Handle',
			'name' => 'twitter_handle',
			'type' => 'text',
			'instructions' => 'Be sure to visit the <a href="https://cards-dev.twitter.com/validator">twitter card validator</a> to setup validation for twitter cards for this site.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '@exampletwitteruser',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array (
			'key' => 'field_59147a4a48b4f',
			'label' => 'Post Types',
			'name' => 'seo_post_types',
			'type' => 'text',
			'instructions' => 'Comma separated list of Post Types to add Custom SEO field options to',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'post,page',
			'placeholder' => 'post,page,custom_type',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'squarecandy-acf-seo-settings',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;

// get the array of post types to add
$types = array();
if ( function_exists('get_field') && get_field('seo_post_types', 'options') ) {
	$typesdata = get_field('seo_post_types', 'options');
	$typesdata = explode(',', $typesdata);
}
else {
	$typesdata = array('post', 'page');
}
foreach ($typesdata as $item) {
	$types[] = array(
		array (
			'param' => 'post_type',
			'operator' => '==',
			'value' => trim($item),
		),
	);
}

// print '<pre>'; print_r($types); print '</pre>';

// Add Seo Fields to Post/Page/Custom Edit Screen
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_59147c153ee90',
	'title' => 'SEO & Social Media',
	'fields' => array (
		array (
			'key' => 'field_59147d1e64317',
			'label' => 'SEO Meta Description',
			'name' => 'seo_meta_description',
			'type' => 'textarea',
			'instructions' => 'Add a description for this page. This will appear in google results under the title and URL and in social media sharing contexts.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'maxlength' => 160,
			'rows' => 2,
			'new_lines' => '',
		),
		array (
			'key' => 'field_59147d2464318',
			'label' => 'Social Media Image',
			'name' => 'seo_social_media_image',
			'type' => 'image',
			'instructions' => 'Upload an image to use in social sharing contexts. It\'s a great idea to include a unique image for every page!',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
			'preview_size' => 'squarecandy-acf-seo-thumb',
			'library' => 'all',
			'min_width' => 1200,
			'min_height' => 630,
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array (
			'key' => 'field_59147d3564319',
			'label' => 'Twitter Handle',
			'name' => 'seo_twitter_handle',
			'type' => 'text',
			'instructions' => 'Override the twitter:creator meta tag if appropriate.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '@exampletwitteruser',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array (
			'key' => 'field_59147deb0b502',
			'label' => 'SEO Title Override',
			'name' => 'seo_title_override',
			'type' => 'text',
			'instructions' => 'Leave this blank in most cases to use the default title. If you need to override the default &lt;title&gt; tag for this particular page, enter your custom title here.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => 60,
		),
		array (
			'key' => 'field_5914921d37359',
			'label' => 'Social Sharing Title Override',
			'name' => 'seo_social_title_override',
			'type' => 'text',
			'instructions' => 'Leave this blank in most cases to use the default title. If you need to override the title on social sharing for this particular page, enter your custom title here.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => 60,
		),
	),
	'location' => $types,
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;

// Remove the "generator" tag
function squarecandy_acf_seo_remove_version() {
	return '';
}
add_filter('the_generator', 'squarecandy_acf_seo_remove_version');

function squarecandy_acf_seo_init() {
	// Add the custom image sizes needed
	add_image_size('squarecandy-acf-seo-facebook', 1200, 627, true);
	add_image_size('squarecandy-acf-seo-twitter', 1024, 512, true);
	add_image_size('squarecandy-acf-seo-thumb', 527, 275, true);
	// make sure wordpress is not supplying a default <title> tag
	remove_theme_support( 'title-tag' );
}
add_action( 'plugins_loaded', 'squarecandy_acf_seo_init' );

// add the tags to the HTML <head>
function squarecandy_acf_seo_hook_header() {

	// get the data

	// title
	if ( function_exists('get_field') && get_field('seo_title_override') ) {
		$head_title = get_field('seo_title_override');
	}
	else {
		$head_title = wp_title('—',false,'right') . get_bloginfo('name');
	}

	// social title
	if ( function_exists('get_field') && get_field('seo_social_title_override') ) {
		$social_title = get_field('seo_social_title_override');
	}
	elseif ( function_exists('get_field') && get_field('seo_title_override') ) {
		$social_title = get_field('seo_title_override');
	}
	else {
		$social_title = wp_title('—',false,'right') . get_bloginfo('name');
	}

	// description
	// if post-specific description is not empty
	if ( function_exists('get_field') && get_field('seo_meta_description') ) {
		$description = get_field('seo_meta_description');
	}
	// else if we can get an excerpt for the post
	elseif ( is_single() ) {
		global $post;
		setup_postdata( $post );
		$excerpt = get_the_excerpt();
		// https://wordpress.stackexchange.com/a/70924/41488
		$limit = 160;
		$excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
		$excerpt = strip_shortcodes($excerpt);
		$excerpt = strip_tags($excerpt);
		$excerpt = substr($excerpt, 0, $limit);
		$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
		$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
		$description = $excerpt;
	}
	// else if default description field is not empty
	elseif ( function_exists('get_field') && get_field('default_meta_description', 'options') ) {
		$description = get_field('default_meta_description', 'options');
	}
	// else if WordPress Tagline is not empty
	elseif ( get_bloginfo('description') ) {
		$description = get_bloginfo('description');
	}
	// else don't display any of the description meta tags
	else {
		$description = false;
	}

	// image
	// if post-specific social media image is not empty
	if ( function_exists('get_field') && get_field('seo_social_media_image') ) {
		$image = get_field('seo_social_media_image');
		$facebookimage = $image['sizes']['squarecandy-acf-seo-facebook'];
		$twitterimage = $image['sizes']['squarecandy-acf-seo-twitter'];
	}
	// else if WordPress Featured Image exists for this post
	elseif ( get_post_thumbnail_id() ) {
		$image = true;
		$facebookimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'squarecandy-acf-seo-facebook' );
		$facebookimage = $facebookimage[0];
		$twitterimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'squarecandy-acf-seo-twitter' );
		$twitterimage = $twitterimage[0];
	}
	// else if default social media image is not empty
	elseif ( function_exists('get_field') && get_field('default_social_media_image', 'options') ) {
		$image = get_field('default_social_media_image', 'options');
		$facebookimage = $image['sizes']['squarecandy-acf-seo-facebook'];
		$twitterimage = $image['sizes']['squarecandy-acf-seo-twitter'];
	}
	// else don't display any of the image meta tags
	else {
		$image = false;
	}

	// set the twitter card type
	if ($image) {
		$twitter_card = 'summary_large_image';
	}
	else {
		$twitter_card = 'summary';
	}

	// twitter handles
	$twittersite = false;
	$twitterauthor = false;
	// if default twitter handle exists
	if ( function_exists('get_field') && get_field('twitter_handle', 'options') ) {
		$twittersite = get_field('twitter_handle', 'options');
		$twitterauthor = $twittersite;
	}
	// if post specific twitter handle exists, override the author handle
	if ( function_exists('get_field') && get_field('seo_twitter_handle') ) {
		$twitterauthor = get_field('seo_twitter_handle');
		if (!$twittersite) {
			$twittersite = get_field('seo_twitter_handle');
		}
	}

	// output the code
	?>

	<title><?php echo $head_title; ?></title>

	<?php if ($description) : ?>
		<meta name="description" content="<?php echo $description; ?>" />
		<meta name="twitter:description" content="<?php echo $description; ?>">
		<meta property="og:description" content="<?php echo $description; ?>" />
	<?php endif; ?>

	<!-- Twitter Card data -->
	<meta name="twitter:title" content="<?php echo $social_title; ?>">
	<meta name="twitter:card" content="<?php echo $twitter_card; ?>">
	<?php if ($twittersite) : ?>
		<meta name="twitter:site" content="<?php echo $twittersite; ?>">
	<?php endif; ?>
	<?php if ($twitterauthor) : ?>
		<meta name="twitter:creator" content="<?php echo $twitterauthor; ?>">
	<?php endif; ?>
	<?php if ($image) : ?>
		<meta name="twitter:image:src" content="<?php echo $twitterimage; ?>">
	<?php endif; ?>


	<!-- Open Graph data -->
	<meta property="og:title" content="<?php echo $social_title; ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php the_permalink(); ?> " />
	<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
	<?php if ($image) : ?>
		<meta property="og:image" content="<?php echo $facebookimage; ?>" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="627" />
	<?php endif; ?>

	<?php
}
add_action('wp_head','squarecandy_acf_seo_hook_header');
