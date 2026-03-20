<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'theme-name',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );


// Allow SVG Support
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
	// $mimes['psd'] = 'image/vnd.adobe.photoshop'; - add additional MIMES. update file extension and mime type.
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');


// Gravity Forms - Add a UK Telephone Number Format
add_filter( 'gform_phone_formats', 'uk_phone_format' );
function uk_phone_format( $phone_formats ) {
    $phone_formats['uk'] = array(
        'label'       => 'UK Telephone Number',
        'mask'        => false,
        'regex'       => '/^(((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\#(\d{4}|\d{3}))?$/',
        'instruction' => false,
    );

    return $phone_formats;
}

// Gravity Forms - Populate form field with CPT values
// Update _1 to required form ID
add_filter( 'gform_pre_render_1', 'populate_posts' );
add_filter( 'gform_pre_validation_1', 'populate_posts' );
add_filter( 'gform_pre_submission_filter_1', 'populate_posts' );
add_filter( 'gform_admin_pre_render_1', 'populate_posts' );
function populate_posts( $form ) {

		foreach ( $form['fields'] as &$field ) {

				// On effect the field with class-name
				if ( $field->type != 'select' || strpos( $field->cssClass, 'class-name' ) === false ) {
						continue;
				}

				// you can add additional parameters here to alter the posts that are retrieved
				// more info: http://codex.wordpress.org/Template_Tags/get_posts
				$posts = get_posts( 'post_type=print-product&numberposts=-1&post_status=publish' );

				$choices = array();

				foreach ( $posts as $post ) {
						$choices[] = array( 'text' => $post->post_title, 'value' => $post->post_name );
				}

				// update 'Select a Post' to whatever you'd like the instructive option to be
				$field->placeholder = 'Select a Product...';
				$field->choices = $choices;

		}

		return $form;
}

// Let's have a [year] shortcode
function display_year() {
    $year = date('Y');
    return $year;
}
add_shortcode('year', 'display_year');
