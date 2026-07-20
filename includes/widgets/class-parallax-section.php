<?php
/**
 * Parallax Section widget.
 *
 * @package GSAP_Elementor_Widgets
 */

namespace GSAP_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Parallax_Section
 *
 * A wrapper widget that applies a GSAP ScrollTrigger parallax scrolling
 * effect to its background image or inner content.
 */
class Parallax_Section extends Widget_Base {

	/**
	 * Widget slug / name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'gsap-parallax-section';
	}

	/**
	 * Human readable title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Parallax Section', 'gsap-elementor-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-parallax';
	}

	/**
	 * Widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'gsap-animations' );
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'gsap', 'parallax', 'scroll', 'background', 'section' );
	}

	/**
	 * Script dependencies.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'gsap', 'gsap-scrolltrigger', 'gsap-ew-frontend' );
	}

	/**
	 * Style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'gsap-ew-frontend' );
	}

	/**
	 * Register the Elementor panel controls.
	 *
	 * @return void
	 */
	protected function register_controls() {

		/* ---------------------------------------------------------------
		 * Content section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'apply_to',
			array(
				'label'   => esc_html__( 'Apply Parallax To', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'background',
				'options' => array(
					'background' => esc_html__( 'Background Image', 'gsap-elementor-widgets' ),
					'content'    => esc_html__( 'Content', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'background_image',
			array(
				'label'     => esc_html__( 'Background Image', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => '',
				),
				'condition' => array(
					'apply_to' => 'background',
				),
			)
		);

		$this->add_control(
			'content_heading',
			array(
				'label'   => esc_html__( 'Heading', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Parallax Section', 'gsap-elementor-widgets' ),
			)
		);

		$this->add_control(
			'content_text',
			array(
				'label'   => esc_html__( 'Text', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => esc_html__( 'Scroll to see the parallax effect in action.', 'gsap-elementor-widgets' ),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Parallax settings section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_parallax',
			array(
				'label' => esc_html__( 'Parallax Settings', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'       => esc_html__( 'Parallax Speed', 'gsap-elementor-widgets' ),
				'description' => esc_html__( '0 = no movement. Negative moves opposite to scroll, positive moves with scroll.', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '' ),
				'range'       => array(
					'' => array(
						'min'  => -1,
						'max'  => 1,
						'step' => 0.05,
					),
				),
				'default'     => array(
					'size' => 0.3,
				),
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'   => esc_html__( 'Direction', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => array(
					'vertical'   => esc_html__( 'Vertical', 'gsap-elementor-widgets' ),
					'horizontal' => esc_html__( 'Horizontal', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'scrub',
			array(
				'label'        => esc_html__( 'Scrub (tie to scrollbar)', 'gsap-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'gsap-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'gsap-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => esc_html__( 'Element Height', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1200,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 500,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-parallax' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Style section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'     => esc_html__( 'Overlay Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.3)',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-parallax-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => esc_html__( 'Content Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-parallax-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_align',
			array(
				'label'     => esc_html__( 'Content Alignment', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'gsap-elementor-widgets' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'gsap-elementor-widgets' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'gsap-elementor-widgets' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-parallax-inner' => 'justify-content: {{VALUE}}; text-align: center;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$config = array(
			'speed'     => isset( $settings['speed']['size'] ) ? (float) $settings['speed']['size'] : 0.3,
			'direction' => $settings['direction'],
			'applyTo'   => $settings['apply_to'],
			'scrub'     => ( 'yes' === $settings['scrub'] ),
		);

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'          => 'gsap-ew-parallax gsap-ew-parallax--' . esc_attr( $settings['apply_to'] ),
				'data-gsap-type' => 'parallax-section',
				'data-gsap'      => wp_json_encode( $config ),
			)
		);

		$has_bg = ( 'background' === $settings['apply_to'] && ! empty( $settings['background_image']['url'] ) );

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $has_bg ) {
			$bg_url = $settings['background_image']['url'];
			printf(
				'<div class="gsap-ew-parallax-bg" style="background-image:url(%1$s);"></div>',
				esc_url( $bg_url )
			);
			echo '<div class="gsap-ew-parallax-overlay"></div>';
		}

		echo '<div class="gsap-ew-parallax-inner">';
		echo '<div class="gsap-ew-parallax-content">';

		if ( '' !== $settings['content_heading'] ) {
			echo '<h2 class="gsap-ew-parallax-heading">' . esc_html( $settings['content_heading'] ) . '</h2>';
		}

		if ( '' !== $settings['content_text'] ) {
			echo '<div class="gsap-ew-parallax-desc">' . wp_kses_post( wpautop( $settings['content_text'] ) ) . '</div>';
		}

		echo '</div>'; // .gsap-ew-parallax-content
		echo '</div>'; // .gsap-ew-parallax-inner
		echo '</div>'; // .gsap-ew-parallax
	}
}
