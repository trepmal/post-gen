<?php
/*
 * Plugin Name: Post Gen
 * Plugin URI: trepmal.com
 * Description: Post generator.
 * Version:
 * Author: Kailey Lampert
 * Author URI: kaileylampert.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * TextDomain: post-gen
 * DomainPath:
 * Network:
 */


/**
 * Generate one post
 *
 * @return void
 */
function post_gen_create_post() {
	$title = post_gen_get_random_title();
	$args = array(
		'post_type'    => 'post',
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_content' => post_gen_get_random_content(),
		'post_author'  => 1
	);
	$args = apply_filters( 'post_gen_args', $args );
	$postid = wp_insert_post( $args );
	if ( ! is_wp_error( $postid ) ) {
		if ( function_exists('image_gen__create_image' ) ) {
			// generate matching image

			// split long title into reasonable lines
			$image_title = _split_str_by_whitespace( $title, 30 );
			$image_title = array_map( 'trim', $image_title );
			// for generation, set lowgrey to 150 (same as highgrey default) to make solid images (because faster)
			/// see https://github.com/trepmal/image-gen/blob/master/image-gen-cli.php for more args
			$assoc_args = array( 'width' => 1000, 'height' => 800, 'text' => $image_title, 'lowgrey' => 150 );
			$imageid = image_gen__create_image( $title, $assoc_args );
			// make featured
			set_post_thumbnail ( $postid, $imageid );
			// attach to post
			wp_update_post( array(
				'ID'          => $imageid,
				'post_parent' => $postid
			) );
		}
	}
}


/**
 * Generate multiple posts
 *
 * @param int $count Number posts to generate
 * @return void
 */
function post_gen_create_posts( $count = 1 ) {
	$counter = 1;
	do {
		post_gen_create_post();
		++$counter;
	} while ( $counter <= $count);

}

/**
 * Get random title from title list
 *
 * @return void
 */
function post_gen_get_random_title() {
	$titles = file_get_contents( plugin_dir_path(__FILE__) .'/lorem-titles.txt' );
	$titles = explode( "\n", $titles );
	shuffle( $titles );

	return array_pop( $titles );
}

/**
 * Get random content from paragraph list
 *
 * @return void
 */
function post_gen_get_random_content() {
	$paras = file_get_contents( plugin_dir_path(__FILE__) .'/lorem-paragraphs.txt' );
	$paras = explode( "\n", $paras );
	shuffle( $paras );

	$paras = array_slice( $paras, 0, rand( 3, 6 ) );

	return implode( "\n\n", $paras );
}


if ( defined('WP_CLI') && WP_CLI ) {
	include plugin_dir_path( __FILE__ ) . '/post-gen-cli.php';
}