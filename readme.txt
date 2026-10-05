=== Default Image ACF ===
Contributors: abdulrehmanirfan, gillanesolution
Donate link: https://gillan.co/
Tags: acf, acf-image, default-image, advanced-custom-fields, acf-addon
Requires at least: 5.8
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily set a fallback default image for Advanced Custom Fields (ACF) image fields when no image is selected.

== Description ==

**Default Image ACF** allows WordPress developers and content creators to set a default fallback image directly within Advanced Custom Fields (ACF) Image field settings.

When editing a post, page, or custom post type where an ACF Image field is left empty, the plugin automatically provides the specified default image. This prevents broken layouts, eliminates the need for repeated fallback conditional checks in template files, and ensures a seamless display across your entire WordPress site.

Developed and maintained by **Gillan e Solution** and **Abdul Rehman**.

### Key Features

*   **Native ACF Field Setting**: Adds a "Default Image" uploader directly inside any ACF Image field settings.
*   **Automatic Fallback**: Returns the default image whenever a post or page has no image selected.
*   **Full ACF Return Format Compatibility**: Works seamlessly with all ACF return formats:
    *   Image Array
    *   Image URL
    *   Image ID
*   **Lightweight & Fast**: Zero frontend overhead; hooks directly into ACF's native value loading pipeline.
*   **Extensible for Developers**: Provides the `default_image_acf_value` filter hook for dynamic conditional fallbacks.
*   **ACF PRO & Secure Custom Fields (SCF) Compatible**: Supports Advanced Custom Fields (Free & PRO) as well as Secure Custom Fields.
*   **Fully Compatible with PHP 8.x and Latest WordPress**: Clean, secure, and compliant with WordPress coding standards.

== Installation ==

### From WordPress Dashboard:
1. Log in to your WordPress admin dashboard.
2. Navigate to **Plugins → Add New**.
3. Search for `Default Image ACF`.
4. Click **Install Now**, then click **Activate**.

### Manual Installation:
1. Download the plugin ZIP package (`default-image-acf.zip`).
2. Log in to your WordPress dashboard and go to **Plugins → Add New → Upload Plugin**.
3. Choose the downloaded ZIP file and click **Install Now**.
4. Click **Activate Plugin**.

*Note: Make sure Advanced Custom Fields (ACF) or Secure Custom Fields is installed and activated.*

== Frequently Asked Questions ==

= Does this plugin require Advanced Custom Fields? =
Yes, this plugin is an add-on for Advanced Custom Fields (Free or PRO) and also works with Secure Custom Fields (SCF).

= How do I set a default image for an ACF image field? =
1. Go to **Custom Fields → Field Groups** in your WordPress admin.
2. Edit or add an **Image** field.
3. Under the field settings, locate the **Default Image** setting.
4. Click **Add Image** to choose or upload your default image from the media library.
5. Save the field group.

= What return formats are supported? =
All three ACF return formats are supported: Image Array, Image URL, and Image ID. The plugin returns the default image in whichever format your field is configured to return.

= What happens if the default image is deleted from the Media Library? =
The plugin includes a safeguard check. If the selected default image has been deleted from your Media Library, it safely falls back to returning false or empty to prevent PHP warnings or broken links.

= Is it compatible with PHP 8+ and WordPress 6.x? =
Yes, the plugin is fully tested and compatible with PHP 7.4 through PHP 8.4, and the latest versions of WordPress.

== Screenshots ==

1. The "Default Image" setting inside an ACF Image field.

== Changelog ==

= 1.0 =
* Initial release by Gillan e Solution and Abdul Rehman.
* Fully compatible with WordPress 6.x and PHP 7.4 through 8.4.
* Enhanced ACF compatibility (supports ACF Free, ACF PRO, and Secure Custom Fields).
* Improved code architecture with safe dependency checking.
* Added attachment verification to prevent broken image references if default image is deleted.
* Added developer filter hook `default_image_acf_value`.
* Translation-ready with internationalization support.

== Upgrade Notice ==

= 1.0 =
Initial release of Default Image ACF.
