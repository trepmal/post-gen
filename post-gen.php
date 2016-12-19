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
function post_gen_create_post( $args = array() ) {

	$defaults = array(
		'post_type'    => 'post',
		'post_status'  => 'publish',
		'paragraphs'   => '4,7',
		'noimage'      => 0,
		'days-offset'  => array( 1, 300, 5 ),
		'hours-offset' => array( 1, 24 ),
		'img-height'   => 800,
		'img-width'    => 1000,
		'img-lowgrey'  => 150,
		'img-highgrey' => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$img_args = array();
	foreach( $args as $k => $v ) {
		if ( strpos( $k, 'img-' ) === 0 ) {
			$new_key = str_replace( 'img-', '', $k );
			$img_args[ $new_key ] = $v;
			unset( $args[ $k ] );
		}
	}

	$title = post_gen_get_random_title();
	$day = post_gen_convert_to_value( $args['days-offset'] );
	$hour = post_gen_convert_to_value( $args['hours-offset'] );
	$date = date( 'Y-m-d H:i:s', strtotime( "-$day days -$hour hours" ) );

	$post_args = array(
		'post_type'     => $args['post_type'],
		'post_status'   => $args['post_status'],
		'post_title'    => $title,
		'post_content'  => post_gen_get_random_content( $args['paragraphs'] ),
		'post_author'   => 1,
		'post_date'     => $date,
		'post_date_gmt' => $date,
	);
	$post_args = apply_filters( 'post_gen_args', $post_args );
	$postid = wp_insert_post( $post_args );
	if ( ! is_wp_error( $postid ) ) {
		if ( function_exists('image_gen__create_image' ) && ! $args['noimage'] ) {
			// generate matching image

			// split long title into reasonable lines
			$image_title = _split_str_by_whitespace( $title, 30 );
			$image_title = array_map( 'trim', $image_title );

			// todo, only run this over integer vals
			$img_args = array_map( 'post_gen_convert_to_value', $img_args );

			// if no highgrey, make the same as low. this will make the image solid and faster to generate
			$img_args['highgrey'] = empty( $img_args['highgrey'] ) ? $img_args['lowgrey'] : $img_args['highgrey'];

			$img_args['text'] = empty( $img_args['text'] ) ? $image_title : $img_args['text'];

			$imageid = image_gen__create_image( $title, $img_args );
			// make featured
			set_post_thumbnail ( $postid, $imageid );

			// attach to post
			wp_update_post( array(
				'ID'          => $imageid,
				'post_parent' => $postid,
				'post_content' => print_r( $img_args, true ),
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
	} while ( $counter <= $count );
}

/**
 * Get random title from title list
 *
 * @return string HTML
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
 * @param int|array $number_paragraphs One number, or array of 2
 * @return string HTML
 */
function post_gen_get_random_content( $number_paragraphs = array( 3, 6 ) ) {
	$paras = file_get_contents( plugin_dir_path(__FILE__) .'/lorem-paragraphs.txt' );
	$paras = explode( "\n", $paras );
	shuffle( $paras );

	$num = post_gen_convert_to_value( $number_paragraphs );

	$paras = array_slice( $paras, 0, $num );

	return implode( "\n\n", $paras );
}

/**
 * Helper function.
 *
 * Convert given input to single value
 * Input could be single value, comma-separated string, array
 *
 * @param int|string|array $input
 * @return int|string
 */
function post_gen_convert_to_value( $input ) {
	// if not array, explode
	if ( ! is_array( $input ) ) {
		$input = explode( ',', $input );
	}

	$input = array_map( 'intval', $input );

	// if a third param, assume last is incremental value
	// get range, randomize, return
	if ( isset( $input[2] ) ) {
		$incr = array_pop( $input );
		sort( $input );
		$range = range( $input[0], $input[1], $incr );
		shuffle( $range );
		return array_shift( $range );
	}
	// otherwise, just get random in range
	if ( isset( $input[1] ) ) {
		sort( $input );
		$input = rand( $input[0], $input[1] );
	} else {
		$input = $input[0];
	}

	return $input;
}

if ( defined('WP_CLI') && WP_CLI ) {
	include plugin_dir_path( __FILE__ ) . '/post-gen-cli.php';
}