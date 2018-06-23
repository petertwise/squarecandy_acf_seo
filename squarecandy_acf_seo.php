<?php
/*
Plugin Name: Square Candy ACF SEO Plugin
Plugin URI:  http://squarecandydesign.com
Description: provides basic SEO meta fields, defaults and per post overrides
Version:     v1.1.0
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

add_action( 'init', 'squarecandy_acf_seo_init', 1 );
function squarecandy_acf_seo_init() {

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
				'return_format' => 'id',
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
			array (
				'key' => 'field_shuef27googlean8b4f',
				'label' => 'Google Analytics',
				'name' => 'googleanalytics',
				'type' => 'text',
				'instructions' => 'Google Analitics Tracking ID',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '000000000-1',
				'prepend' => 'UA-',
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

	// Add Seo Fields to Post/Page/Custom Edit Screen
	if( function_exists('acf_add_local_field_group') && is_admin() ):

	if ( is_admin() && empty($_GET['post']) ) {
		$message = __('publish / update to view the preview', 'acf');
	}
	else {
		$message = squarecandy_acf_seo_google_preview_html();
	}

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
				'return_format' => 'id',
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
			array (
				'key' => 'field_5914654378PREVIEW',
				'label' => 'Preview',
				'name' => '',
				'type' => 'message',
				'message' => $message,
				'new_lines' => '',
				'esc_html' => 0,
			),
			array (
				'key' => 'field_canonical75483997034290',
				'label' => 'Canonical URL',
				'name' => 'canonical_url',
				'type' => 'url',
				'instructions' => 'Enter the canonical URL if appropriate. This is the "primary location" of the content. <a href="https://moz.com/learn/seo/canonicalization">More infomation</a>. Leave this blank in most cases.',
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

}

// Remove the "generator" tag
function squarecandy_acf_seo_remove_version() {
	return '';
}
add_filter('the_generator', 'squarecandy_acf_seo_remove_version');

function squarecandy_acf_seo_image_sizes() {
	// Add the custom image sizes needed
	add_image_size('squarecandy-acf-seo-facebook', 1200, 627, true);
	add_image_size('squarecandy-acf-seo-twitter', 1024, 512, true);
	add_image_size('squarecandy-acf-seo-thumb', 527, 275, true);
	// make sure wordpress is not supplying a default <title> tag
	remove_theme_support( 'title-tag' );
}
add_action( 'plugins_loaded', 'squarecandy_acf_seo_image_sizes' );


function squarecandy_acf_seo_get_data() {
	global $post;
	// $post = $wp_query->post;
	if ( empty($post) && isset($_GET['post']) ) {
		$post = get_post($_GET['post']);
	}
	if ( empty($post) ) {
		return false;
	}
	setup_postdata( $post );

	// get the data
	$return = array();
	// title
	if ( function_exists('get_field') && get_field('seo_title_override') ) {
		$return['head_title'] = get_field('seo_title_override');
	}
	else {
		if ( is_admin() ) {
			$return['head_title'] = $post->post_title . ' — ' .get_bloginfo('name');
		}
		else {
			$return['head_title'] = wp_title('—',false,'right') . get_bloginfo('name');
		}
	}

	// social title
	if ( function_exists('get_field') && get_field('seo_social_title_override') ) {
		$return['social_title'] = get_field('seo_social_title_override');
	}
	elseif ( function_exists('get_field') && get_field('seo_title_override') ) {
		$return['social_title'] = get_field('seo_title_override');
	}
	else {
		$return['social_title'] = wp_title('—',false,'right') . get_bloginfo('name');
	}

	// description
	// if post-specific description is not empty
	if ( function_exists('get_field') && get_field('seo_meta_description') ) {
		$return['description'] = get_field('seo_meta_description');
	}
	// else if we can get an excerpt for the post
	elseif ( is_single() ) {
		$excerpt = get_the_excerpt();
		// https://wordpress.stackexchange.com/a/70924/41488
		$limit = 160;
		$excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
		$excerpt = strip_shortcodes($excerpt);
		$excerpt = strip_tags($excerpt);
		$excerpt = substr($excerpt, 0, $limit);
		$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
		$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
		$return['description'] = $excerpt;
	}
	// else if default description field is not empty
	elseif ( function_exists('get_field') && get_field('default_meta_description', 'options') ) {
		$return['description'] = get_field('default_meta_description', 'options');
	}
	// else if WordPress Tagline is not empty
	elseif ( get_bloginfo('description') ) {
		$return['description'] = get_bloginfo('description');
	}
	// else don't display any of the description meta tags
	else {
		$return['description'] = false;
	}

	// image
	// if post-specific social media image is not empty
	if ( function_exists('get_field') && get_field('seo_social_media_image') ) {
		$image = get_field('seo_social_media_image', $post->ID);
		$facebookimage = wp_get_attachment_image_src( $image, 'squarecandy-acf-seo-facebook' );
		$return['facebookimage'] = $facebookimage[0];
		$twitterimage = wp_get_attachment_image_src( $image, 'squarecandy-acf-seo-twitter' );
		$return['twitterimage'] = $twitterimage[0];
	}
	// else if WordPress Featured Image exists for this post
	elseif ( get_post_thumbnail_id() ) {
		$image = true;
		$facebookimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'squarecandy-acf-seo-facebook' );
		$return['facebookimage'] = $facebookimage[0];
		$twitterimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'squarecandy-acf-seo-twitter' );
		$return['twitterimage'] = $twitterimage[0];
	}
	// else if default social media image is not empty
	elseif ( function_exists('get_field') && get_field('default_social_media_image', 'options') ) {
		$image = get_field('default_social_media_image', 'options');
		$facebookimage = wp_get_attachment_image_src( $image, 'squarecandy-acf-seo-facebook' );
		$return['facebookimage'] = $facebookimage[0];
		$twitterimage = wp_get_attachment_image_src( $image, 'squarecandy-acf-seo-twitter' );
		$return['twitterimage'] = $twitterimage[0];
	}
	// else don't display any of the image meta tags
	else {
		$image = false;
		$return['facebookimage'] = false;
		$return['twitterimage'] = false;
	}

	// set the twitter card type
	if ($image) {
		$return['twitter_card'] = 'summary_large_image';
	}
	else {
		$return['twitter_card'] = 'summary';
	}

	// twitter handles
	$return['twittersite'] = false;
	$return['twitterauthor'] = false;
	// if default twitter handle exists
	if ( function_exists('get_field') && get_field('twitter_handle', 'options') ) {
		$return['twittersite'] = get_field('twitter_handle', 'options');
		$return['twitterauthor'] = $return['twittersite'];
	}
	// if post specific twitter handle exists, override the author handle
	if ( function_exists('get_field') && get_field('seo_twitter_handle') ) {
		$return['twitterauthor'] = get_field('seo_twitter_handle');
		if (!$return['twittersite']) {
			$return['twittersite'] = get_field('seo_twitter_handle');
		}
	}

	// if the canonical url is set add it to the array
	if ( function_exists('get_field') && get_field('canonical_url') ) {
		$return['canonical'] = get_field('canonical_url');
	}
	else {
		$return['canonical'] = false;
	}

	wp_reset_postdata();

	// return the array
	return $return;
}

// remove the default canonical and shorlink from <head> - we want to use only our overrides.
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head');



function squarecandy_acf_seo_google_preview_html() {
	$preview = "<style>@import url('https://fonts.googleapis.com/css?family=Roboto');.rc,.rc h3{max-width:600px;width:100%;font-weight:400}.rc,.rc cite,.rc h3,.st{font-weight:400}.rc{margin:1em;color:#222;display:block;font-family:Roboto,arial,sans-serif;font-size:13px;line-height:15.6px}.rc h3{font-size:18px;height:21px;line-height:21.6px;margin:0;overflow:hidden;padding:0;text-overflow:ellipsis;white-space:nowrap}.rc a{color:#1a0dab;text-decoration:none}.rc a:hover{color:#1a0dab;text-decoration:underline}.rc cite{color:#006621;display:inline;font-size:14px;line-height:16px;font-style:normal}.st{color:#545454;font-size:13px;line-height:18.2px;word-wrap:break-word}</style>";

	$data = squarecandy_acf_seo_get_data();

	$url = get_permalink($_GET['post']);
	$url = str_replace('http://', '', $url);

	$preview .= '<div class="rc"><h3 class="r"><a href="javascript:;">' . $data['head_title'] . '</a></h3>
	<div class="s"><div class="f" style="white-space:nowrap"><cite>' . $url . '</cite></div>
	<span class="st">' . $data['description'] . '</span></div>
	</div>';

	return $preview;
}


// add the tags to the HTML <head>
function squarecandy_acf_seo_hook_header() {

	// global $post;
	global $wp_query;
	if ( isset($wp_query->post) ) {
		$post = $wp_query->post;
	}
	elseif ( isset($_GET['post']) ) {
		$post = get_post($_GET['post']);
	}
	else {
		return false;
	}

	setup_postdata( $post );

	$data = squarecandy_acf_seo_get_data($post->ID);

	// output the code
	?>

	<title><?php echo $data['head_title']; ?></title>

	<?php if ( $data['description'] ) : ?>
		<meta name="description" content="<?php echo $data['description']; ?>" />
		<meta name="twitter:description" content="<?php echo $data['description']; ?>">
		<meta property="og:description" content="<?php echo $data['description']; ?>" />
	<?php endif; ?>

	<!-- Twitter Card data -->
	<meta name="twitter:title" content="<?php echo $data['social_title']; ?>">
	<meta name="twitter:card" content="<?php echo $data['twitter_card']; ?>">
	<?php if ( $data['twittersite'] ) : ?>
		<meta name="twitter:site" content="<?php echo $data['twittersite']; ?>">
	<?php endif; ?>
	<?php if ( $data['twitterauthor'] ) : ?>
		<meta name="twitter:creator" content="<?php echo $data['twitterauthor']; ?>">
	<?php endif; ?>
	<?php if ( $data['twitterimage'] ) : ?>
		<meta name="twitter:image:src" content="<?php echo $data['twitterimage']; ?>">
	<?php endif; ?>


	<!-- Open Graph data -->
	<meta property="og:title" content="<?php echo $data['social_title']; ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php the_permalink(); ?> " />
	<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
	<?php if ( $data['facebookimage'] ) : ?>
		<meta property="og:image" content="<?php echo $data['facebookimage']; ?>" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="627" />
	<?php endif; ?>

	<?php if ( $data['canonical'] ) : ?>
		<!-- Canonical URL (points to primary source) -->
		<link rel="canonical" href="<?php echo $data['canonical']; ?>" />
	<?php endif; ?>

	<?php
	wp_reset_postdata();
}
add_action('wp_head','squarecandy_acf_seo_hook_header');

// add google analtyics to the header
function squarecandy_googleanatlyics_header() {

	if (
		WP_DEBUG !== true &&
		substr($_SERVER['SERVER_NAME'],0,3) != 'dev' &&
		function_exists('get_field') &&
		$googleanalytics = get_field('googleanalytics', 'options')
	) {
		echo "<!-- squarecandy_acf_seo googleanalytics -->
			<!-- Global site tag (gtag.js) - Google Analytics -->
			<script async src='https://www.googletagmanager.com/gtag/js?id=UA-" . $googleanalytics . "'></script>
			<script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());
			gtag('config', 'UA-" . $googleanalytics . "');</script>";
	}
	elseif (
		function_exists('get_field') &&
		!get_field('googleanalytics', 'options')
	) {
		echo "<!-- OOPS - enter your google analytics UA account number on the settings page --- squarecandy_acf_seo googleanalytics -->";
	}
	else {
		echo '<!-- squarecandy_acf_seo googleanalytics will go here on the live site. -->';
	}

}
add_action( 'wp_head', 'squarecandy_googleanatlyics_header', 9999 );
