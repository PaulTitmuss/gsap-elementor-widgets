<?php
/**
 * Main plugin class.
 *
 * @package GSAP_Elementor_Widgets
 */

namespace GSAP_Elementor_Widgets;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

/**
 * Class Plugin
 *
 * Registers the custom Elementor widget category, the widgets,
 * and enqueues the GSAP frontend assets.
 */
final class Plugin {

        /**
         * Custom Elementor category slug.
         *
         * @var string
         */
        const CATEGORY_SLUG = 'gsap-animations';

        /**
         * Singleton instance.
         *
         * @var Plugin|null
         */
        private static $instance = null;

        /**
         * Tracks whether frontend assets have been registered.
         *
         * @var bool
         */
        private $assets_registered = false;

        /**
         * Retrieve the singleton instance.
         *
         * @return Plugin
         */
        public static function instance() {
                if ( null === self::$instance ) {
                        self::$instance = new self();
                }
                return self::$instance;
        }

        /**
         * Constructor. Sets up the compatibility checks and hooks.
         */
        private function __construct() {
                // Verify environment before wiring anything else up.
                if ( ! $this->is_compatible() ) {
                        return;
                }

                $this->register_hooks();
        }

        /**
         * Check that Elementor is installed, active and the minimum version.
         *
         * Registers appropriate admin notices when a check fails.
         *
         * @return bool True when the environment is compatible.
         */
        public function is_compatible() {
                // Elementor must be loaded.
                if ( ! did_action( 'elementor/loaded' ) ) {
                        add_action( 'admin_notices', array( $this, 'notice_missing_elementor' ) );
                        return false;
                }

                // Elementor minimum version.
                if ( defined( 'ELEMENTOR_VERSION' ) && ! version_compare( ELEMENTOR_VERSION, GSAP_EW_MIN_ELEMENTOR_VERSION, '>=' ) ) {
                        add_action( 'admin_notices', array( $this, 'notice_minimum_elementor_version' ) );
                        return false;
                }

                // PHP minimum version.
                if ( version_compare( PHP_VERSION, GSAP_EW_MIN_PHP_VERSION, '<' ) ) {
                        add_action( 'admin_notices', array( $this, 'notice_minimum_php_version' ) );
                        return false;
                }

                return true;
        }

        /**
         * Register all WordPress / Elementor hooks.
         *
         * @return void
         */
        private function register_hooks() {
                // Register the custom widget category. Runs at a very late priority
                // so it executes after other plugins (Elementor Pro, Ultimate Addons,
                // etc.) have registered their categories, letting us reliably move
                // ours to the top of the list.
                add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ), 9999 );

                // Register the widgets (current Elementor API).
                add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

                // Register frontend assets (available for both front-end and the editor preview).
                add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) );
                add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_frontend_assets' ) );
                add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'register_frontend_assets' ) );

                // Ensure assets load inside the Elementor editor preview so animations are visible while editing.
                add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        }

        /**
         * Register the "GSAP Animations" Elementor category.
         *
         * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
         * @return void
         */
        public function register_category( $elements_manager ) {
                $properties = array(
                        'title' => esc_html__( 'GSAP Animations', 'gsap-elementor-widgets' ),
                        'icon'  => 'fa fa-magic',
                );

                // Register the category first so it always exists, even if the
                // re-ordering below is not possible on a given Elementor build.
                $elements_manager->add_category( self::CATEGORY_SLUG, $properties );

                // The `add_category` offset argument is applied at call time, but other
                // plugins (Elementor Pro, Ultimate Addons, etc.) register their own
                // categories on the same hook and can end up before ours. To guarantee
                // our category appears at the very top of the widget panel we re-order
                // the manager's internal categories array, moving ours to the front.
                // Reflection is used because the property is protected; it is wrapped in
                // a guard so any future API change simply leaves the category in its
                // default position rather than causing an error.
                try {
                        $reflection = new \ReflectionObject( $elements_manager );
                        if ( $reflection->hasProperty( 'categories' ) ) {
                                $property = $reflection->getProperty( 'categories' );
                                $property->setAccessible( true );
                                $categories = $property->getValue( $elements_manager );

                                if ( is_array( $categories ) && isset( $categories[ self::CATEGORY_SLUG ] ) ) {
                                        $ours = array( self::CATEGORY_SLUG => $categories[ self::CATEGORY_SLUG ] );
                                        unset( $categories[ self::CATEGORY_SLUG ] );
                                        $property->setValue( $elements_manager, $ours + $categories );
                                }
                        }
                } catch ( \Exception $e ) {
                        // Leave the category in its default position on any failure.
                        unset( $e );
                }
        }

        /**
         * Register all widgets with Elementor.
         *
         * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
         * @return void
         */
        public function register_widgets( $widgets_manager ) {
                $widgets = array(
                        'class-animated-heading.php' => '\GSAP_Elementor_Widgets\Widgets\Animated_Heading',
                        'class-scroll-counter.php'   => '\GSAP_Elementor_Widgets\Widgets\Scroll_Counter',
                        'class-parallax-section.php' => '\GSAP_Elementor_Widgets\Widgets\Parallax_Section',
                        'class-staggered-grid.php'   => '\GSAP_Elementor_Widgets\Widgets\Staggered_Grid',
                        'class-timeline-reveal.php'  => '\GSAP_Elementor_Widgets\Widgets\Timeline_Reveal',
                        'class-animated-text.php'    => '\GSAP_Elementor_Widgets\Widgets\Animated_Text',
                        'class-icon-box-3d.php'      => '\GSAP_Elementor_Widgets\Widgets\Icon_Box_3D',
                        'class-reveal-on-scroll.php' => '\GSAP_Elementor_Widgets\Widgets\Reveal_On_Scroll',
                        'class-svg-animator.php'     => '\GSAP_Elementor_Widgets\Widgets\SVG_Animator',
                        'class-hero-bento.php'       => '\GSAP_Elementor_Widgets\Widgets\Hero_Bento',
                        'class-motion-path.php'      => '\GSAP_Elementor_Widgets\Widgets\Motion_Path',
                );

                foreach ( $widgets as $file => $class_name ) {
                        $path = GSAP_EW_PATH . 'includes/widgets/' . $file;
                        if ( file_exists( $path ) ) {
                                require_once $path;
                                if ( class_exists( $class_name ) ) {
                                        $widgets_manager->register( new $class_name() );
                                }
                        }
                }
        }

        /**
         * Register (but do not force-enqueue) the GSAP libraries and plugin assets.
         *
         * Elementor will automatically enqueue the scripts/styles listed by each
         * widget's get_script_depends() / get_style_depends() only on pages where
         * the widget is actually rendered, keeping unused pages lightweight.
         *
         * @return void
         */
        public function register_frontend_assets() {
                if ( $this->assets_registered ) {
                        return;
                }

                // GSAP core from CDN.
                wp_register_script(
                        'gsap',
                        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
                        array(),
                        '3.12.5',
                        true
                );

                // ScrollTrigger plugin from CDN (depends on GSAP core).
                wp_register_script(
                        'gsap-scrolltrigger',
                        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js',
                        array( 'gsap' ),
                        '3.12.5',
                        true
                );

                // TextPlugin for the typewriter / split effects (optional, depends on GSAP core).
                wp_register_script(
                        'gsap-textplugin',
                        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js',
                        array( 'gsap' ),
                        '3.12.5',
                        true
                );

                // MotionPathPlugin for the Motion Path widget (depends on GSAP core).
                wp_register_script(
                        'gsap-motionpath',
                        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/MotionPathPlugin.min.js',
                        array( 'gsap' ),
                        '3.12.5',
                        true
                );

                // Our frontend initialisation logic.
                wp_register_script(
                        'gsap-ew-frontend',
                        GSAP_EW_URL . 'assets/js/gsap-widgets-frontend.js',
                        array( 'gsap', 'gsap-scrolltrigger', 'gsap-textplugin' ),
                        GSAP_EW_VERSION,
                        true
                );

                // Widget styling.
                wp_register_style(
                        'gsap-ew-frontend',
                        GSAP_EW_URL . 'assets/css/gsap-widgets.css',
                        array(),
                        GSAP_EW_VERSION
                );

                $this->assets_registered = true;
        }

        /**
         * Force-enqueue the frontend assets.
         *
         * Used inside the Elementor editor preview where the conditional
         * per-widget enqueueing does not run the same way.
         *
         * @return void
         */
        public function enqueue_frontend_assets() {
                $this->register_frontend_assets();

                wp_enqueue_script( 'gsap' );
                wp_enqueue_script( 'gsap-scrolltrigger' );
                wp_enqueue_script( 'gsap-textplugin' );
                wp_enqueue_script( 'gsap-motionpath' );
                wp_enqueue_script( 'gsap-ew-frontend' );
                wp_enqueue_style( 'gsap-ew-frontend' );
        }

        /**
         * Admin notice: Elementor is not installed / active.
         *
         * @return void
         */
        public function notice_missing_elementor() {
                if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                        unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                }

                $message = sprintf(
                        /* translators: 1: Plugin name, 2: Elementor plugin name. */
                        esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'gsap-elementor-widgets' ),
                        '<strong>' . esc_html__( 'GSAP Elementor Widgets', 'gsap-elementor-widgets' ) . '</strong>',
                        '<strong>' . esc_html__( 'Elementor', 'gsap-elementor-widgets' ) . '</strong>'
                );

                printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
        }

        /**
         * Admin notice: Elementor version too old.
         *
         * @return void
         */
        public function notice_minimum_elementor_version() {
                if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                        unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                }

                $message = sprintf(
                        /* translators: 1: Plugin name, 2: Elementor name, 3: Required Elementor version. */
                        esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'gsap-elementor-widgets' ),
                        '<strong>' . esc_html__( 'GSAP Elementor Widgets', 'gsap-elementor-widgets' ) . '</strong>',
                        '<strong>' . esc_html__( 'Elementor', 'gsap-elementor-widgets' ) . '</strong>',
                        GSAP_EW_MIN_ELEMENTOR_VERSION
                );

                printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
        }

        /**
         * Admin notice: PHP version too old.
         *
         * @return void
         */
        public function notice_minimum_php_version() {
                if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                        unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                }

                $message = sprintf(
                        /* translators: 1: Plugin name, 2: Required PHP version. */
                        esc_html__( '"%1$s" requires PHP version %2$s or greater.', 'gsap-elementor-widgets' ),
                        '<strong>' . esc_html__( 'GSAP Elementor Widgets', 'gsap-elementor-widgets' ) . '</strong>',
                        GSAP_EW_MIN_PHP_VERSION
                );

                printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
        }
}
