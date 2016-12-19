<?php

/**
 * Generate Posts
 */
class Post_Gen_CLI extends WP_CLI_Command {

	/**
	 * Generate posts (More Options than core)
	 *
	 * If Image-Gen (https://github.com/trepmal/image-gen) is installed,
	 * all `wp image-gen create` options are accepted when prefixed with 'img-'
	 *
	 * ## OPTIONS
	 *
	 * <count>
	 * : Number of posts
	 *
	 * [--post_type=<type>]
	 * : The type of the generated posts, default post
	 *
	 * [--post_status=<status>]
	 * : The status of the generated posts, default publish
	 *
	 * [--paragraphs=<paragraphs>]
	 * : Single number or range, default 4,7
	 *
	 * [--noimage]
	 * : No image
	 *
	 * [--days-offset=<days-offset>]
	 * : Number of days in past to offset new posts or range, default 1,300,5
	 *
	 * [--hours-offset=<hours-offset>]
	 * : Number of hours in past to offset new posts or range, default 1,24
	 *
	 * [--img-width=<img-width>]
	 * : Width for the image in pixels, default 1000
	 *
	 * [--img-height=<img-height>]
	 * : Height for the image in pixels, default 800
	 *
	 * [--img-lowgrey=<img-lowgrey>]
	 * : Lower grey value (0-255), default 150
	 *
	 * [--img-highgrey=<img-highgrey>]
	 * : Higher grey value (0-255), default 150
	 *
	 * [--img-alpha=<img-alpha>]
	 * : Alpha transparancy (0-127), default 0
	 *
	 * [--img-blurintensity=<img-blurintensity>]
	 * : How often to apply the blur effect, default 2
	 *
	 * [--img-filename=<img-filename>]
	 * : old value
	 *
	 * [--img-text=<img-text>]
	 * : Text to place on the image, default generated post title
	 *
	 * [--img-linespacing=<img-linespacing>]
	 * : Linespacing in pixels, default 10
	 *
	 * [--img-textsize=<img-textsize>]
	 * : Text size in pixels, default 40
	 *
	 * [--img-font=<img-font>]
	 * : Path to font true type file, default {plugin-path}/fonts/SourceSansPro-BoldIt.otf
	 *
	 * [--img-fontcolor=<img-fontcolor>]
	 * : Font color. Either RGB as an array or a hexcode string, default array(0, 80, 80),
 	 *
	 * ## EXAMPLES
	 *
	 *     wp post-gen create 10 --img-lowgrey=20,140 --img-highgrey=160,220 --img-width=1300
	 *
	 */
	public function create( $args = array(), $assoc_args = array() ) {
		list( $count ) = $args;

		$notify = \WP_CLI\Utils\make_progress_bar( "Generating $count post(s)", $count );
		$counter = 1;
		do {
			post_gen_create_post( $assoc_args );
			$notify->tick();
			++$counter;
		} while ( $counter <= $count);

		$notify->finish();

		WP_CLI::success( "Done.");

	}

}

WP_CLI::add_command( 'post-gen', 'Post_Gen_CLI' );