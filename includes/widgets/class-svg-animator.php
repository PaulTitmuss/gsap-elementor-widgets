<?php
/**
 * SVG Animator widget.
 *
 * @package GSAP_Elementor_Widgets
 */

namespace GSAP_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SVG_Animator
 *
 * Animates inline SVG markup with GSAP: a self-drawing "draw" effect built on
 * the free stroke-dasharray / stroke-dashoffset technique, plus fade+scale,
 * sequential path fade, and rotate-in modes.
 */
class SVG_Animator extends Widget_Base {

	/**
	 * Widget slug / name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'gsap-svg-animator';
	}

	/**
	 * Human readable title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'SVG Animator', 'gsap-elementor-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-shape';
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
		return array( 'gsap', 'svg', 'draw', 'line', 'stroke', 'path', 'animation', 'logo' );
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
	 * Allowed tags / attributes for pasted SVG markup.
	 *
	 * @return array
	 */
	private function svg_allowed_html() {
		$attrs = array(
			'xmlns'             => true,
			'xmlns:xlink'       => true,
			'viewbox'           => true,
			'width'             => true,
			'height'            => true,
			'fill'              => true,
			'fill-rule'        => true,
			'fill-opacity'     => true,
			'stroke'            => true,
			'stroke-width'     => true,
			'stroke-linecap'   => true,
			'stroke-linejoin'  => true,
			'stroke-miterlimit' => true,
			'stroke-dasharray' => true,
			'stroke-dashoffset' => true,
			'stroke-opacity'   => true,
			'opacity'           => true,
			'transform'         => true,
			'class'             => true,
			'id'                => true,
			'style'             => true,
			'd'                 => true,
			'points'            => true,
			'x'                 => true,
			'y'                 => true,
			'x1'                => true,
			'y1'                => true,
			'x2'                => true,
			'y2'                => true,
			'cx'                => true,
			'cy'                => true,
			'r'                 => true,
			'rx'                => true,
			'ry'                => true,
			'offset'            => true,
			'stop-color'       => true,
			'stop-opacity'     => true,
			'gradientunits'     => true,
			'gradienttransform' => true,
			'xlink:href'        => true,
			'href'              => true,
			'preserveaspectratio' => true,
		);

		return array(
			'svg'            => $attrs,
			'g'              => $attrs,
			'path'           => $attrs,
			'circle'         => $attrs,
			'ellipse'        => $attrs,
			'rect'           => $attrs,
			'line'           => $attrs,
			'polyline'       => $attrs,
			'polygon'        => $attrs,
			'defs'           => $attrs,
			'lineargradient' => $attrs,
			'radialgradient' => $attrs,
			'stop'           => $attrs,
			'title'          => $attrs,
			'desc'           => $attrs,
			'use'            => $attrs,
		);
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
				'label' => esc_html__( 'SVG', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'svg_help',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Paste inline SVG markup below (open your .svg file in a text editor and copy everything from <svg> to </svg>). For the Draw effect, shapes with a stroke work best — a stroke colour will be applied automatically if one is missing.', 'gsap-elementor-widgets' ),
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->add_control(
			'svg_code',
			array(
				'label'   => esc_html__( 'SVG Markup', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::CODE,
				'language' => 'html',
				'rows'    => 10,
				'default' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="none" stroke="#6c47ff" stroke-width="3"><circle cx="50" cy="50" r="40"/><path d="M30 52 L45 67 L72 35"/></svg>',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Animation section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_animation',
			array(
				'label' => esc_html__( 'Animation', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'animation_type',
			array(
				'label'   => esc_html__( 'Animation Mode', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'draw',
				'options' => array(
					'draw'         => esc_html__( 'Draw (self-drawing lines)', 'gsap-elementor-widgets' ),
					'fade-scale'   => esc_html__( 'Fade + Scale In', 'gsap-elementor-widgets' ),
					'fade-paths'   => esc_html__( 'Fade Paths Sequentially', 'gsap-elementor-widgets' ),
					'rotate-in'    => esc_html__( 'Rotate In', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'duration',
			array(
				'label'      => esc_html__( 'Duration (seconds)', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '' ),
				'range'      => array(
					'' => array(
						'min'  => 0.1,
						'max'  => 8,
						'step' => 0.1,
					),
				),
				'default'    => array( 'size' => 2 ),
			)
		);

		$this->add_control(
			'stagger',
			array(
				'label'       => esc_html__( 'Stagger Between Shapes (seconds)', 'gsap-elementor-widgets' ),
				'description' => esc_html__( 'Delay between each path / shape being animated.', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '' ),
				'range'       => array(
					'' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.05,
					),
				),
				'default'     => array( 'size' => 0.2 ),
			)
		);

		$this->add_control(
			'delay',
			array(
				'label'      => esc_html__( 'Delay (seconds)', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '' ),
				'range'      => array(
					'' => array(
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'default'    => array( 'size' => 0 ),
			)
		);

		$this->add_control(
			'easing',
			array(
				'label'   => esc_html__( 'Easing', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'power2.inOut',
				'options' => array(
					'none'         => esc_html__( 'Linear', 'gsap-elementor-widgets' ),
					'power1.inOut' => 'Power1',
					'power2.inOut' => 'Power2',
					'power3.inOut' => 'Power3',
					'sine.inOut'   => 'Sine',
					'expo.inOut'   => 'Expo',
					'back.out'     => 'Back',
					'elastic.out'  => 'Elastic',
				),
			)
		);

		$this->add_control(
			'fill_after_draw',
			array(
				'label'        => esc_html__( 'Fade In Fill After Draw', 'gsap-elementor-widgets' ),
				'description'  => esc_html__( 'When the SVG uses fills, fade them in once the line drawing completes.', 'gsap-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'gsap-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'gsap-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'animation_type' => 'draw',
				),
			)
		);

		$this->add_control(
			'trigger',
			array(
				'label'   => esc_html__( 'Trigger', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'scroll',
				'options' => array(
					'load'   => esc_html__( 'On Page Load', 'gsap-elementor-widgets' ),
					'scroll' => esc_html__( 'On Scroll Into View', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'        => esc_html__( 'Loop Continuously', 'gsap-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'gsap-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'gsap-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'repeat',
			array(
				'label'        => esc_html__( 'Repeat On Every Scroll', 'gsap-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'gsap-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'gsap-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'trigger' => 'scroll',
					'loop!'   => 'yes',
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

		$this->add_responsive_control(
			'svg_width',
			array(
				'label'      => esc_html__( 'Width', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 40,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 200,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-svg svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'svg_align',
			array(
				'label'     => esc_html__( 'Alignment', 'gsap-elementor-widgets' ),
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
					'{{WRAPPER}} .gsap-ew-svg' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'stroke_color',
			array(
				'label'       => esc_html__( 'Stroke Color Override', 'gsap-elementor-widgets' ),
				'description' => esc_html__( 'Leave empty to use the colours defined in the SVG.', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}} .gsap-ew-svg svg [stroke], {{WRAPPER}} .gsap-ew-svg svg path, {{WRAPPER}} .gsap-ew-svg svg line, {{WRAPPER}} .gsap-ew-svg svg polyline, {{WRAPPER}} .gsap-ew-svg svg polygon, {{WRAPPER}} .gsap-ew-svg svg circle, {{WRAPPER}} .gsap-ew-svg svg rect, {{WRAPPER}} .gsap-ew-svg svg ellipse' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'stroke_width',
			array(
				'label'      => esc_html__( 'Stroke Width Override', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 0.5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-svg svg [stroke], {{WRAPPER}} .gsap-ew-svg svg path, {{WRAPPER}} .gsap-ew-svg svg line, {{WRAPPER}} .gsap-ew-svg svg polyline, {{WRAPPER}} .gsap-ew-svg svg polygon, {{WRAPPER}} .gsap-ew-svg svg circle, {{WRAPPER}} .gsap-ew-svg svg rect, {{WRAPPER}} .gsap-ew-svg svg ellipse' => 'stroke-width: {{SIZE}}{{UNIT}};',
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

		$svg = isset( $settings['svg_code'] ) ? $settings['svg_code'] : '';
		if ( '' === trim( $svg ) ) {
			return;
		}

		$config = array(
			'animation' => $settings['animation_type'],
			'duration'  => isset( $settings['duration']['size'] ) ? (float) $settings['duration']['size'] : 2,
			'stagger'   => isset( $settings['stagger']['size'] ) ? (float) $settings['stagger']['size'] : 0.2,
			'delay'     => isset( $settings['delay']['size'] ) ? (float) $settings['delay']['size'] : 0,
			'easing'    => $settings['easing'],
			'fillAfter' => ( 'yes' === $settings['fill_after_draw'] ),
			'trigger'   => $settings['trigger'],
			'loop'      => ( 'yes' === $settings['loop'] ),
			'repeat'    => ( 'yes' === $settings['repeat'] ),
		);

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'          => 'gsap-ew-svg',
				'data-gsap-type' => 'svg-animator',
				'data-gsap'      => wp_json_encode( $config ),
			)
		);

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo wp_kses( $svg, $this->svg_allowed_html() );
		echo '</div>';
	}
}
