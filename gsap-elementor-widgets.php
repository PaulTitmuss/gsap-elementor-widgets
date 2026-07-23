<?php
/**
 * Plugin Name:       GSAP Elementor Widgets
 * Plugin URI:        https://example.com/gsap-elementor-widgets
 * Description:       Adds 10 GSAP-powered Elementor widgets (Animated Heading, Scroll Counter, Parallax Section, Staggered Card Grid, Timeline Reveal, Animated Text, 3D Icon Box, Reveal on Scroll, SVG Animator, Hero to Bento Scroll) with full no-code panel controls. Compatible with Elementor Pro 3.x / 4.x.
 * Version:           1.2.5
 * Author:            Bristol Website Design Ltd
 * Author URI:        https://example.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gsap-elementor-widgets
 * Domain Path:       /languages
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Tested up to:      6.6
 * Requires Plugins:  elementor
 *
 * @package GSAP_Elementor_Widgets
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

/**
 * Plugin version.
 */
define( 'GSAP_EW_VERSION', '1.2.5' );

/**
 * Absolute path to the plugin directory (with trailing slash).
 */
define( 'GSAP_EW_PATH', plugin_dir_path( __FILE__ ) );

/**
 * URL to the plugin directory (with trailing slash).
 */
define( 'GSAP_EW_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main plugin file (for reference).
 */
define( 'GSAP_EW_FILE', __FILE__ );

/**
 * Minimum Elementor version required.
 */
define( 'GSAP_EW_MIN_ELEMENTOR_VERSION', '3.0.0' );

/**
 * Minimum PHP version required.
 */
define( 'GSAP_EW_MIN_PHP_VERSION', '7.4' );

/**
 * Bootstrap the plugin once all plugins are loaded.
 *
 * This ensures Elementor (and Elementor Pro) have had the chance to load
 * before we attempt to hook into their APIs.
 *
 * @return void
 */
function gsap_ew_bootstrap() {
        // Load the main plugin class.
        require_once GSAP_EW_PATH . 'includes/class-plugin.php';

        // Kick things off.
        \GSAP_Elementor_Widgets\Plugin::instance();
}
add_action( 'plugins_loaded', 'gsap_ew_bootstrap' );
