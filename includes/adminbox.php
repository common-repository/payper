<?php
abstract class Payper_Meta_Box {


	/**
	 * Set up and add the meta box.
	 */
	public static function add() {

		$post_types = get_post_types( array('public' => true) );

//		$post_types = [ 'post', 'wporg_cpt' ];

		foreach ( $post_types as $screen ) {
			add_meta_box(
				'0_payper_box_id',          // Unique ID
				'Payper', // Box title
				[ self::class, 'html' ],   // Content callback, must be of type callable
				$screen,                  // Post type
                'side',
				'high'
			);

		}
	}


	/**
	 * Save the meta box selections.
	 *
	 * @param int $post_id  The post ID.
	 */
	public static function save( int $post_id ) {
		if ( array_key_exists( 'payper_field', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_payper_premium_meta_key',
				"1"
			);
		} else {
			update_post_meta(
				$post_id,
				'_payper_premium_meta_key',
				"0"
			);
        }
	}


	/**
	 * Display the meta box HTML to the user.
	 *
	 * @param WP_Post $post   Post object.
	 */
	public static function html( $post ) {
		$value = get_post_meta( $post->ID, '_payper_premium_meta_key', true );
		?>

        <label for="payper_field">
            <input type="checkbox" name="payper_field" id="payper_field" value="1" <?php if($value=="1") echo ("checked") ?> />
            ¿Premium?
        </label>
		<?php
	}
}

add_action( 'add_meta_boxes', [ 'Payper_Meta_Box', 'add' ] );
add_action( 'save_post', [ 'Payper_Meta_Box', 'save' ] );
