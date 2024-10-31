<?php

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function payper_settings_init() {
	// Register a new setting for "payper" page.
	register_setting( 'wp-payper', 'wp-payper_options' );

	$option_values = get_option( 'wp-payper_options' );

	$default_values = array (
		'payper_field_token' => '',
		'payper_field_title_tag'  => 'wp-block-post-title',
		'payper_field_content_tag'   => 'wp-block-post-content',
        'payper_field_home_tag_titles' => false,
		'payper_field_production_env' => false,
	);

	// Parse option values into predefined keys, throw the rest away.
	$data = shortcode_atts( $default_values, $option_values );

	// Register a new section in the "payper" page.
	add_settings_section(
		'payper_section_developers',
		__( 'Configuraciones para Payper.', 'wp-payper' ),
        '',
//		'payper_section_developers_callback',
		'wp-payper'
	);

	// Register a new field in the "payper_section_developers" section, inside the "payper" page.
	add_settings_field(
		'payper_field_token',
		// Use $args' label_for to populate the id inside the callback.
		__( 'Clave pública medio', 'wp-payper' ),
		'payper_field_token_cb',
		'wp-payper',
		'payper_section_developers',
		array(
			'label_for'         => 'payper_field_token',
			'class'             => 'payper_row',
			'payper_custom_data' => 'custom',
			'value'       => esc_attr( $data['payper_field_token'] ),
		)
	);
	add_settings_field(
		'payper_field_title_tag',
		// Use $args' label_for to populate the id inside the callback.
		__( 'Clase CSS del título', 'wp-payper' ),
		'payper_field_title_tag_cb',
		'wp-payper',
		'payper_section_developers',
		array(
			'label_for'         => 'payper_field_title_tag',
			'class'             => 'payper_row',
			'payper_custom_data' => 'custom',
			'value'       => esc_attr( $data['payper_field_title_tag'] ),
		)
	);
	add_settings_field(
		'payper_field_content_tag',
		// Use $args' label_for to populate the id inside the callback.
		__( 'Clase CSS del contenido', 'wp-payper' ),
		'payper_field_content_tag_cb',
		'wp-payper',
		'payper_section_developers',
		array(
			'label_for'         => 'payper_field_content_tag',
			'class'             => 'payper_row',
			'payper_custom_data' => 'custom',
			'value'       =>  $data['payper_field_content_tag'],
		)
	);
	add_settings_field(
		'payper_field_home_tag_titles',
		// Use $args' label_for to populate the id inside the callback.
		__( '¿Destacar premium en portada?', 'wp-payper' ),
		'payper_field_home_tag_titles_cb',
		'wp-payper',
		'payper_section_developers',
		array(
			'label_for'         => 'payper_field_home_tag_titles',
			'class'             => 'payper_row',
			'payper_custom_data' => 'custom',
			'value'       =>  $data['payper_field_home_tag_titles'],
		)
	);
	add_settings_field(
		'payper_field_production_env',
		// Use $args' label_for to populate the id inside the callback.
		__( '¿Modo de funcionamiento "en real"?', 'wp-payper' ),
		'payper_field_production_env_cb',
		'wp-payper',
		'payper_section_developers',
		array(
			'label_for'         => 'payper_field_production_env',
			'class'             => 'payper_row',
			'payper_custom_data' => 'custom',
			'value'       =>  $data['payper_field_production_env'],
		)
	);
}

/**
 * Register our payper_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'payper_settings_init' );


/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function payper_section_developers_callback( $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Configura las opciones Payper.', 'wp-payper' ); ?></p>
	<?php
}

/**
 * @param array $args
 */
function payper_field_token_cb( $args ) {
	?>
        <textarea  rows="5" cols="50"
               id="<?php echo esc_attr( $args['label_for'] ); ?>"
               data-custom="<?php echo esc_attr( $args['payper_custom_data'] ); ?>"
               name="wp-payper_options[<?php echo esc_attr( $args['label_for'] ); ?>]"><?php echo esc_attr( $args['value'] ); ?></textarea>

	<p class="description">
		<?php esc_html_e( 'Indica la clave de medio facilitada por Payper.', 'wp-payper' ); ?>
	</p>
	<?php
}

/**
 * @param array $args
 */
function payper_field_title_tag_cb( $args ) {
	?>
    <input type="text"
               id="<?php echo esc_attr( $args['label_for'] ); ?>"
               data-custom="<?php echo esc_attr( $args['payper_custom_data'] ); ?>"
               name="wp-payper_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( $args['value'] ); ?>"></input>

    <p class="description">
		<?php esc_html_e( 'Indica la clase CSS del objeto que contiene el título del artículo.', 'wp-payper' ); ?>
    </p>
	<?php
}

/**
 * @param array $args
 */
function payper_field_content_tag_cb( $args ) {
	?>
    <input type="text"
           id="<?php echo esc_attr( $args['label_for'] ); ?>"
           data-custom="<?php echo esc_attr( $args['payper_custom_data'] ); ?>"
           name="wp-payper_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( $args['value'] ); ?>"></input>

    <p class="description">
		<?php esc_html_e( 'Indica la clase CSS del objeto que contiene el contenido desarrollado del artículo.', 'wp-payper' ); ?>
    </p>
	<?php
}

/**
 * @param array $args
 */
function payper_field_home_tag_titles_cb( $args ) {
	?>
    <input type="checkbox"
           id="<?php echo esc_attr( $args['label_for'] ); ?>"
           data-custom="<?php echo esc_attr( $args['payper_custom_data'] ); ?>"
           name="wp-payper_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="1"
            <?php if (esc_attr( $args['value'] )) echo "checked"; ?> />

    <p class="description">
		<?php esc_html_e( 'Marcar artículos premium en portada', 'wp-payper' ); ?>
    </p>
	<?php
}
/**
 * @param array $args
 */
function payper_field_production_env_cb( $args ) {
	?>
    <input type="checkbox"
           id="<?php echo esc_attr( $args['label_for'] ); ?>"
           data-custom="<?php echo esc_attr( $args['payper_custom_data'] ); ?>"
           name="wp-payper_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="1"
		<?php if (esc_attr( $args['value'] )) echo "checked"; ?> />

    <p class="description">
		<?php esc_html_e( 'Hacer funcionar el plugin contra Payper producción', 'wp-payper' ); ?>
    </p>
	<?php
}

/**
 * Add the top level menu page.
 */
function payper_options_page() {
	add_menu_page(
		'Payper',
		'Payper Options',
		'manage_options',
		'wp-payper',
		'payper_options_page_html',
		PAYPER_URL.'/payper_16x16.png'
	);

}


/**
 * Register our payper_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'payper_options_page' );


/**
 * Top level menu callback function
 */
function payper_options_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'payper_messages', 'payper_message', __( 'Configuraciones guardadas', 'wp-payper' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'payper_messages' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "payper"
			settings_fields( 'wp-payper' );
			// output setting sections and their fields
			// (sections are registered for "payper", each field is registered to a specific section)
			do_settings_sections( 'wp-payper' );
			// output save settings button
			submit_button( 'Save Settings' );
			?>
		</form>
	</div>
	<?php
}

