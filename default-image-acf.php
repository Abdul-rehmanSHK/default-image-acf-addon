<?php
/**
 * Plugin Name:       Default Image ACF
 * Description:       Allows you to set a fallback default image for Advanced Custom Fields (ACF) image fields when no image is selected.
 * Version:           1.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Gillan e Solution
 * Author URI:        https://gillan.co/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       default-image-acf
 * Domain Path:       /languages
 *
 * @package Default_Image_ACF
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
final class GES_Default_Image_ACF {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '1.0';

	/**
	 * Singleton instance of the class.
	 *
	 * @var GES_Default_Image_ACF|null
	 */
	private static $instance = null;

	/**
	 * Returns the main plugin instance.
	 *
	 * @return GES_Default_Image_ACF
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initialize plugin components and register hooks.
	 *
	 * @return void
	 */
	public function init() {
		// Check if ACF or Secure Custom Fields is active.
		if ( ! $this->is_acf_active() ) {
			add_action( 'admin_notices', array( $this, 'notice_missing_acf' ) );
			return;
		}

		// Register ACF field setting and value filter.
		add_action( 'acf/render_field_settings/type=image', array( $this, 'render_default_image_setting' ) );
		add_filter( 'acf/load_value/type=image', array( $this, 'load_default_image_value' ), 10, 3 );
	}

	/**
	 * Check if Advanced Custom Fields or Secure Custom Fields is active.
	 *
	 * @return bool
	 */
	public function is_acf_active() {
		return class_exists( 'ACF' ) || function_exists( 'acf' ) || class_exists( 'Secure_Custom_Fields' );
	}

	/**
	 * Render an admin notice if ACF is missing.
	 *
	 * @return void
	 */
	public function notice_missing_acf() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$acf_install_url = admin_url( 'plugin-install.php?tab=search&s=advanced+custom+fields' );
		?>
		<div class="notice notice-warning is-dismissible">
			<p>
				<?php
				printf(
					/* translators: 1: Plugin name, 2: ACF link opening tag, 3: ACF link closing tag */
					esc_html__( '%1$s requires %2$sAdvanced Custom Fields%3$s (Free or PRO) or Secure Custom Fields to be installed and active.', 'default-image-acf' ),
					'<strong>' . esc_html__( 'Default Image ACF', 'default-image-acf' ) . '</strong>',
					'<a href="' . esc_url( $acf_install_url ) . '">',
					'</a>'
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Add "Default Image" field setting to ACF Image field type.
	 *
	 * @param array $field The ACF field settings array.
	 * @return void
	 */
	public function render_default_image_setting( $field ) {
		if ( ! function_exists( 'acf_render_field_setting' ) ) {
			return;
		}

		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Default Image', 'default-image-acf' ),
				'instructions' => __( 'Select a default image to be used when no image is selected.', 'default-image-acf' ),
				'type'         => 'image',
				'name'         => 'default_value',
				'return_format'=> 'id',
			)
		);
	}

	/**
	 * Fallback to the default image if the field value is empty.
	 *
	 * @param mixed      $value   The field value.
	 * @param int|string $post_id The post ID.
	 * @param array      $field   The field array.
	 * @return mixed
	 */
	public function load_default_image_value( $value, $post_id, $field ) {
		// Bail early if a value is already set or field settings are missing.
		if ( ! empty( $value ) || ! is_array( $field ) || empty( $field['default_value'] ) ) {
			return $value;
		}

		$default    = $field['default_value'];
		$default_id = 0;

		// Extract numeric attachment ID whether stored as an array or numeric value.
		if ( is_array( $default ) && ! empty( $default['id'] ) ) {
			$default_id = absint( $default['id'] );
		} elseif ( is_numeric( $default ) ) {
			$default_id = absint( $default );
		}

		// Ensure the attachment actually exists in the Media Library.
		if ( $default_id > 0 && wp_get_attachment_url( $default_id ) ) {
			$value = $default_id;
		}

		/**
		 * Filter the resolved fallback image value.
		 *
		 * Allows developers to conditionally alter the default image value.
		 *
		 * @param mixed      $value      The fallback attachment ID (or empty if not found).
		 * @param int|string $post_id    The post ID.
		 * @param array      $field      The ACF field settings array.
		 * @param int        $default_id The resolved default image attachment ID.
		 */
		return apply_filters( 'default_image_acf_value', $value, $post_id, $field, $default_id );
	}
}

// Instantiate the plugin.
GES_Default_Image_ACF::get_instance();

// Alias for backwards compatibility if referenced by previous name.
class_alias( 'GES_Default_Image_ACF', 'Default_Image_ACF' );

/**
 * Global helper function to retrieve the plugin instance.
 *
 * @return GES_Default_Image_ACF
 */
function ges_default_image_acf() {
	return GES_Default_Image_ACF::get_instance();
}
