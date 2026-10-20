<?php
/**
 * Plugin Name:       Fallback Image for ACF Addon
 * Description:       Allows you to set a fallback default image for Advanced Custom Fields (ACF) image fields when no image is selected.
 * Version:           1.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Gillan e Solution
 * Author URI:        https://gillan.co/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       fallback-image-for-acf-addon
 * Domain Path:       /languages
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
final class Fallback_Image_For_ACF_Addon {

	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		// Load translations.
		load_plugin_textdomain( 'fallback-image-for-acf-addon', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

		// Check ACF / Secure Custom Fields dependency.
		if ( ! class_exists( 'ACF' ) && ! function_exists( 'acf' ) && ! class_exists( 'Secure_Custom_Fields' ) ) {
			add_action( 'admin_notices', array( __CLASS__, 'notice_missing_acf' ) );
			return;
		}

		// Register ACF image field setting and fallback filter.
		add_action( 'acf/render_field_settings/type=image', array( __CLASS__, 'render_default_image_setting' ) );
		add_filter( 'acf/load_value/type=image', array( __CLASS__, 'load_default_image_value' ), 10, 3 );
	}

	/**
	 * Render an admin notice if ACF is missing.
	 *
	 * @return void
	 */
	public static function notice_missing_acf() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$install_url = admin_url( 'plugin-install.php?tab=search&s=advanced+custom+fields' );

		printf(
			'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
			sprintf(
				/* translators: 1: Plugin name, 2: ACF link opening tag, 3: ACF link closing tag */
				esc_html__( '%1$s requires %2$sAdvanced Custom Fields%3$s (Free or PRO) or Secure Custom Fields to be installed and active.', 'fallback-image-for-acf-addon' ),
				'<strong>' . esc_html__( 'Fallback Image for ACF Addon', 'fallback-image-for-acf-addon' ) . '</strong>',
				'<a href="' . esc_url( $install_url ) . '">',
				'</a>'
			)
		);
	}

	/**
	 * Add "Default Image" setting to ACF Image fields.
	 *
	 * @param array $field The ACF field settings array.
	 * @return void
	 */
	public static function render_default_image_setting( $field ) {
		if ( function_exists( 'acf_render_field_setting' ) ) {
			acf_render_field_setting(
				$field,
				array(
					'label'         => __( 'Default Image', 'fallback-image-for-acf-addon' ),
					'instructions'  => __( 'Select a default image to be used when no image is selected.', 'fallback-image-for-acf-addon' ),
					'type'          => 'image',
					'name'          => 'default_value',
					'return_format' => 'id',
				)
			);
		}
	}

	/**
	 * Fallback to default image if field value is empty.
	 *
	 * @param mixed      $value   The field value.
	 * @param int|string $post_id The post ID.
	 * @param array      $field   The field settings array.
	 * @return mixed
	 */
	public static function load_default_image_value( $value, $post_id, $field ) {
		if ( ! empty( $value ) || empty( $field['default_value'] ) ) {
			return $value;
		}

		$default    = $field['default_value'];
		$default_id = is_array( $default )
			? absint( $default['ID'] ?? $default['id'] ?? 0 )
			: absint( $default );

		if ( $default_id > 0 && wp_attachment_is_image( $default_id ) ) {
			$value = $default_id;
		}

		/**
		 * Filter the resolved fallback image value.
		 *
		 * @param mixed      $value      The fallback attachment ID (or empty).
		 * @param int|string $post_id    The post ID.
		 * @param array      $field      The ACF field settings array.
		 * @param int        $default_id The default image attachment ID.
		 */
		return apply_filters( 'fallback_image_for_acf_value', $value, $post_id, $field, $default_id );
	}
}

// Boot plugin.
add_action( 'plugins_loaded', array( Fallback_Image_For_ACF_Addon::class, 'init' ) );
