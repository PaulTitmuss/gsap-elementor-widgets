<?php
/**
 * Motion Path widget.
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
 * Class Motion_Path
 *
 * Moves an image, icon or SVG along a curved path using GSAP's MotionPathPlugin.
 * The path can be one of several ready-made presets (wave, arc, loop, S-curve,
 * zig-zag, diagonal) or a custom SVG path drawn by the user. The object can
 * travel as the visitor scrolls (scrub) or play on load / when scrolled into
 * view, and can optionally rotate to follow the direction of the path.
 */
class Motion_Path extends Widget_Base {

	/**
	 * Ready-made path presets, expressed as SVG path data within a 0 0 1000 500
	 * view box. Because the object is aligned to the rendered path, the coordinate
	 * space scales automatically to whatever size the widget ends up on screen.
	 *
	 * @return array
	 */
	private static function path_presets() {
		return array(
			'wave'     => 'M0,250 Q125,60 250,250 T500,250 T750,250 T1000,250',
			'arc'      => 'M0,470 Q500,-140 1000,470',
			'loop'     => 'M120,250 a200,150 0 1,0 400,0 a200,150 0 1,0 -400,0',
			's-curve'  => 'M80,460 C300,460 300,60 500,60 S700,460 920,460',
			'zigzag'   => 'M0,120 L250,400 L500,120 L750,400 L1000,120',
			'diagonal' => 'M40,460 L960,60',
			'hills'    => 'M0,400 C150,180 350,180 500,400 S850,180 1000,400',
		);
	}

	/**
	 * Widget slug / name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'gsap-motion-path';
	}

	/**
	 * Human readable title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Motion Path', 'gsap-elementor-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-path';
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
		return array( 'gsap', 'motion', 'path', 'move', 'svg', 'icon', 'image', 'curve', 'follow', 'scroll' );
	}

	/**
	 * Script dependencies.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'gsap', 'gsap-scrolltrigger', 'gsap-motionpath', 'gsap-ew-frontend' );
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
	 * Allowed tags / attributes for pasted SVG markup (matches the SVG Animator).
	 *
	 * @return array
	 */
	private function svg_allowed_html() {
		$attrs = array(
			'xmlns'               => true,
			'xmlns:xlink'         => true,
			'viewbox'             => true,
			'width'               => true,
			'height'              => true,
			'fill'                => true,
			'fill-rule'           => true,
			'fill-opacity'        => true,
			'stroke'              => true,
			'stroke-width'        => true,
			'stroke-linecap'      => true,
			'stroke-linejoin'     => true,
			'stroke-miterlimit'   => true,
			'stroke-dasharray'    => true,
			'stroke-dashoffset'   => true,
			'stroke-opacity'      => true,
			'opacity'             => true,
			'transform'           => true,
			'class'               => true,
			'id'                  => true,
			'style'               => true,
			'd'                   => true,
			'points'              => true,
			'x'                   => true,
			'y'                   => true,
			'x1'                  => true,
			'y1'                  => true,
			'x2'                  => true,
			'y2'                  => true,
			'cx'                  => true,
			'cy'                  => true,
			'r'                   => true,
			'rx'                  => true,
			'ry'                  => true,
			'offset'              => true,
			'stop-color'          => true,
			'stop-opacity'        => true,
			'gradientunits'       => true,
			'gradienttransform'   => true,
			'xlink:href'          => true,
			'href'                => true,
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
		 * Content — the moving object
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_object',
			array(
				'label' => esc_html__( 'Moving Object', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'object_type',
			array(
				'label'   => esc_html__( 'Object Type', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'image' => esc_html__( 'Image', 'gsap-elementor-widgets' ),
					'icon'  => esc_html__( 'Icon', 'gsap-elementor-widgets' ),
					'svg'   => esc_html__( 'Inline SVG', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'object_image',
			array(
				'label'     => esc_html__( 'Image', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'object_type' => 'image',
				),
			)
		);

		$this->add_control(
			'object_icon',
			array(
				'label'     => esc_html__( 'Icon', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-rocket',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'object_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'object_svg',
			array(
				'label'     => esc_html__( 'SVG Markup', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::CODE,
				'language'  => 'html',
				'rows'      => 8,
				'default'   => '<svg xmlns="https://d585tldpucybw.cloudfront.net/sfimages/default-source/blogs/2024/2024-04/circle.png?sfvrsn=268b6cf7_2" viewBox="0 0 100 100" fill="#6c47ff"><circle cx="50" cy="50" r="45"/></svg>',
				'condition' => array(
					'object_type' => 'svg',
				),
			)
		);

		$this->add_responsive_control(
			'object_size',
			array(
				'label'      => esc_html__( 'Object Size', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 16,
						'max'  => 400,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 64,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-motionpath-object' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gsap-ew-motionpath-object i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'object_color',
			array(
				'label'       => esc_html__( 'Icon Colour', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#6c47ff',
				'selectors'   => array(
					'{{WRAPPER}} .gsap-ew-motionpath-object i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .gsap-ew-motionpath-object svg' => 'fill: {{VALUE}};',
				),
				'condition'   => array(
					'object_type' => array( 'icon', 'svg' ),
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Content — the path
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_path',
			array(
				'label' => esc_html__( 'Path', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'path_preset',
			array(
				'label'   => esc_html__( 'Path Shape', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'wave',
				'options' => array(
					'wave'     => esc_html__( 'Wave', 'gsap-elementor-widgets' ),
					'hills'    => esc_html__( 'Rolling Hills', 'gsap-elementor-widgets' ),
					'arc'      => esc_html__( 'Arc (rainbow)', 'gsap-elementor-widgets' ),
					'loop'     => esc_html__( 'Loop (circle)', 'gsap-elementor-widgets' ),
					's-curve'  => esc_html__( 'S-Curve', 'gsap-elementor-widgets' ),
					'zigzag'   => esc_html__( 'Zig-Zag', 'gsap-elementor-widgets' ),
					'diagonal' => esc_html__( 'Diagonal Line', 'gsap-elementor-widgets' ),
					'custom'   => esc_html__( 'Custom (advanced)', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'custom_path_help',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Paste SVG path data (the value of a path\'s "d" attribute) drawn on a 1000 × 500 canvas. Tip: draw your path in any vector tool sized 1000×500, then copy the "d" value.', 'gsap-elementor-widgets' ),
				'content_classes' => 'elementor-descriptor',
				'condition'       => array(
					'path_preset' => 'custom',
				),
			)
		);

		$this->add_control(
			'custom_path',
			array(
				'label'       => esc_html__( 'Custom Path Data (d)', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'default'     => 'M0,250 Q250,50 500,250 T1000,250',
				'condition'   => array(
					'path_preset' => 'custom',
				),
			)
		);

		$this->add_control(
			'reverse_path',
			array(
				'label'        => esc_html__( 'Travel In Reverse', 'gsap-elementor-widgets' ),
				'description'  => esc_html__( 'Move the object from the end of the path to the start instead.', 'gsap-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'gsap-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'gsap-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'auto_rotate',
			array(
				'label'        => esc_html__( 'Rotate To Follow Path', 'gsap-elementor-widgets' ),
				'description'  => esc_html__( 'Turn the object so it always points in the direction it is travelling (great for a rocket, plane or arrow).', 'gsap-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'gsap-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'gsap-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_path',
			array(
				'label'        => esc_html__( 'Show The Path Line', 'gsap-elementor-widgets' ),
				'description'  => esc_html__( 'Draw the path itself as a visible line behind the object. Style it in the Path Line section.', 'gsap-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'gsap-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'gsap-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Content — animation
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_animation',
			array(
				'label' => esc_html__( 'Animation', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'trigger',
			array(
				'label'       => esc_html__( 'Trigger', 'gsap-elementor-widgets' ),
				'description' => esc_html__( 'Scroll (scrub): the object moves along the path as the visitor scrolls, and moves back if they scroll up. On Scroll Into View / On Page Load: it plays through once automatically.', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'scrub',
				'options'     => array(
					'scrub'  => esc_html__( 'Scroll (scrub along path)', 'gsap-elementor-widgets' ),
					'scroll' => esc_html__( 'On Scroll Into View', 'gsap-elementor-widgets' ),
					'load'   => esc_html__( 'On Page Load', 'gsap-elementor-widgets' ),
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
						'min'  => 0.5,
						'max'  => 15,
						'step' => 0.1,
					),
				),
				'default'    => array( 'size' => 4 ),
				'condition'  => array(
					'trigger!' => 'scrub',
				),
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
				'condition'  => array(
					'trigger!' => 'scrub',
				),
			)
		);

		$this->add_control(
			'easing',
			array(
				'label'     => esc_html__( 'Easing', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'power1.inOut',
				'options'   => array(
					'none'         => esc_html__( 'Linear', 'gsap-elementor-widgets' ),
					'power1.inOut' => 'Power1',
					'power2.inOut' => 'Power2',
					'power3.inOut' => 'Power3',
					'sine.inOut'   => 'Sine',
					'expo.inOut'   => 'Expo',
					'back.inOut'   => 'Back',
				),
				'condition' => array(
					'trigger!' => 'scrub',
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
				'condition'    => array(
					'trigger' => 'load',
				),
			)
		);

		$this->add_control(
			'yoyo',
			array(
				'label'        => esc_html__( 'Reverse On Each Loop (yo-yo)', 'gsap-elementor-widgets' ),
				'description'  => esc_html__( 'When looping, travel back and forth along the path instead of jumping to the start.', 'gsap-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'gsap-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'gsap-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'trigger' => 'load',
					'loop'    => 'yes',
				),
			)
		);

		$this->add_control(
			'repeat',
			array(
				'label'        => esc_html__( 'Replay On Every Scroll', 'gsap-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'gsap-elementor-widgets' ),
				'label_off'    => esc_html__( 'No', 'gsap-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'trigger' => 'scroll',
				),
			)
		);

		$this->add_control(
			'scrub_length',
			array(
				'label'       => esc_html__( 'Scroll Length (% of screen)', 'gsap-elementor-widgets' ),
				'description' => esc_html__( 'How much scrolling it takes to travel the whole path. Larger = slower, more drawn-out.', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '' ),
				'range'       => array(
					'' => array(
						'min'  => 50,
						'max'  => 300,
						'step' => 10,
					),
				),
				'default'     => array( 'size' => 120 ),
				'condition'   => array(
					'trigger' => 'scrub',
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Style — stage
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_style_stage',
			array(
				'label' => esc_html__( 'Stage', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'stage_height',
			array(
				'label'      => esc_html__( 'Stage Height', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min'  => 120,
						'max'  => 900,
						'step' => 10,
					),
					'vh' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 400,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-motionpath' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'stage_bg',
			array(
				'label'     => esc_html__( 'Stage Background', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-motionpath' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'stage_radius',
			array(
				'label'      => esc_html__( 'Corner Radius (px)', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 60,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-motionpath' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Style — path line (only relevant when "Show the path line" is on)
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_style_line',
			array(
				'label'     => esc_html__( 'Path Line', 'gsap-elementor-widgets' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_path' => 'yes',
				),
			)
		);

		$this->add_control(
			'line_color',
			array(
				'label'     => esc_html__( 'Line Colour', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(108,71,255,0.35)',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-motionpath-path' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'line_width',
			array(
				'label'      => esc_html__( 'Line Thickness (px)', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 20,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 3,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-motionpath-path' => 'stroke-width: {{SIZE}};',
				),
			)
		);

		$this->add_control(
			'line_style',
			array(
				'label'     => esc_html__( 'Line Style', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'gsap-elementor-widgets' ),
					'dashed' => esc_html__( 'Dashed', 'gsap-elementor-widgets' ),
					'dotted' => esc_html__( 'Dotted', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Resolve the SVG path "d" data from the chosen preset (or custom field).
	 *
	 * @param array $settings Widget settings.
	 * @return string
	 */
	private function get_path_data( $settings ) {
		$preset = isset( $settings['path_preset'] ) ? $settings['path_preset'] : 'wave';

		if ( 'custom' === $preset ) {
			$d = isset( $settings['custom_path'] ) ? trim( (string) $settings['custom_path'] ) : '';
			return '' !== $d ? $d : 'M0,250 Q250,50 500,250 T1000,250';
		}

		$presets = self::path_presets();
		return isset( $presets[ $preset ] ) ? $presets[ $preset ] : $presets['wave'];
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$object_type = isset( $settings['object_type'] ) ? $settings['object_type'] : 'icon';
		$path_data   = $this->get_path_data( $settings );
		$show_path   = ( isset( $settings['show_path'] ) && 'yes' === $settings['show_path'] );
		$line_style  = isset( $settings['line_style'] ) ? $settings['line_style'] : 'solid';

		$config = array(
			'trigger'     => isset( $settings['trigger'] ) ? $settings['trigger'] : 'scrub',
			'duration'    => isset( $settings['duration']['size'] ) ? (float) $settings['duration']['size'] : 4,
			'delay'       => isset( $settings['delay']['size'] ) ? (float) $settings['delay']['size'] : 0,
			'easing'      => isset( $settings['easing'] ) ? $settings['easing'] : 'power1.inOut',
			'autoRotate'  => ( isset( $settings['auto_rotate'] ) && 'yes' === $settings['auto_rotate'] ),
			'reverse'     => ( isset( $settings['reverse_path'] ) && 'yes' === $settings['reverse_path'] ),
			'loop'        => ( isset( $settings['loop'] ) && 'yes' === $settings['loop'] ),
			'yoyo'        => ( isset( $settings['yoyo'] ) && 'yes' === $settings['yoyo'] ),
			'repeat'      => ( isset( $settings['repeat'] ) && 'yes' === $settings['repeat'] ),
			'scrubLength' => isset( $settings['scrub_length']['size'] ) ? (float) $settings['scrub_length']['size'] : 120,
		);

		$wrapper_class = 'gsap-ew-motionpath';
		if ( $show_path ) {
			$wrapper_class .= ' gsap-ew-motionpath--show-path gsap-ew-motionpath--line-' . $line_style;
		}

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'          => $wrapper_class,
				'data-gsap-type' => 'motion-path',
				'data-gsap'      => wp_json_encode( $config ),
			)
		);

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// The path lives inside an SVG that overlays the stage. The object is
		// aligned to this rendered path by GSAP, so the coordinate space scales
		// with the widget automatically.
		echo '<svg class="gsap-ew-motionpath-svg" viewBox="0 0 1000 500" preserveAspectRatio="none" aria-hidden="true" focusable="false">';
		echo '<path class="gsap-ew-motionpath-path" d="' . esc_attr( $path_data ) . '" fill="none" />';
		echo '</svg>';

		// The moving object.
		echo '<div class="gsap-ew-motionpath-object">';
		if ( 'image' === $object_type && ! empty( $settings['object_image']['url'] ) ) {
			echo '<img src="' . esc_url( $settings['object_image']['url'] ) . '" alt="" />';
		} elseif ( 'svg' === $object_type && ! empty( $settings['object_svg'] ) ) {
			echo wp_kses( $settings['object_svg'], $this->svg_allowed_html() );
		} elseif ( 'icon' === $object_type && ! empty( $settings['object_icon']['value'] ) ) {
			\Elementor\Icons_Manager::render_icon( $settings['object_icon'], array( 'aria-hidden' => 'true' ) );
		}
		echo '</div>';

		echo '</div>';
	}
}
