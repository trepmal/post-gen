<?php

/**
 * Generate Posts
 */
class Post_Gen_CLI extends WP_CLI_Command {

	/**
	 * Generate posts (More Options than core)
	 *
	 * ## OPTIONS
	 *
	 * <count>
	 * : Number of posts
	 *
 	 *
	 * ## EXAMPLES
	 *
	 *     wp post-gen create "CLI Image" --text="Fancy That" --width=400 --fontcolor=c0ffee
	 *
	 */
	public function create( $args = array(), $assoc_args = array() ) {
		list( $count ) = $args;

		$notify = \WP_CLI\Utils\make_progress_bar( "Generating $count post(s)", $count );
		$counter = 1;
		do {
			post_gen_create_post();
			$notify->tick();
			++$counter;
		} while ( $counter <= $count);

		$notify->finish();

		WP_CLI::success( "Done.");

	}

}

WP_CLI::add_command( 'post-gen', 'Post_Gen_CLI' );