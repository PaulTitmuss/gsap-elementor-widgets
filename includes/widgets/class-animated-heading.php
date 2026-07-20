<?php
/**
 * Animated Heading widget.
 *
 * @package GSAP_Elementor_Widgets
 */

namespace GSAP_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Animated_Heading
 *
 * Text reveal animations: fade in, slide up, split-text letter-by-letter,
 * typewriter effect. All GSAP powered.
 */
class Animated_Heading extends Widget_Base {

	/**
	 * Widget slug / name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'gsap-animated-heading';
	}

	/**
	 * Human readable title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Animated Heading', 'gsap-elementor-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-animation-text';
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
		return array( 'gsap', 'heading', 'title', 'animation', 'reveal', 'typewriter', 'split' );
	}

	/**
	 * Scripts this widget depends on. Elementor enqueues these only when the
	 * widget is present on the page.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'gsap', 'gsap-scrolltrigger', 'gsap-textplugin', 'gsap-ew-frontend' );
	}

	/**
	 * Styles this widget depends on.
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
			'heading_text',
			array(
				'label'       => esc_html__( 'Heading Text', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => esc_html__( 'Animated Heading', 'gsap-elementor-widgets' ),
				'placeholder' => esc_html__( 'Enter your heading', 'gsap-elementor-widgets' ),
			)
		);

		$this->add_control(
			'html_tag',
			array(
				'label'   => esc_html__( 'HTML Tag', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'         => esc_html__( 'Link', 'gsap-elementor-widgets' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'gsap-elementor-widgets' ),
				'default'       => array(
					'url' => '',
				),
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
				'label'   => esc_html__( 'Animation Type', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade-in',
				'options' => array(
					'fade-in'    => esc_html__( 'Fade In', 'gsap-elementor-widgets' ),
					'slide-up'   => esc_html__( 'Slide Up', 'gsap-elementor-widgets' ),
					'split-text' => esc_html__( 'Split Text (letter by letter)', 'gsap-elementor-widgets' ),
					'typewriter' => esc_html__( 'Typewriter', 'gsap-elementor-widgets' ),
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
						'max'  => 5,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 1,
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
				'default'    => array(
					'size' => 0,
				),
			)
		);

		$this->add_control(
			'stagger',
			array(
				'label'       => esc_html__( 'Letter Stagger (seconds)', 'gsap-elementor-widgets' ),
				'description' => esc_html__( 'Only applies to Split Text animation.', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '' ),
				'range'       => array(
					'' => array(
						'min'  => 0.01,
						'max'  => 0.3,
						'step' => 0.01,
					),
				),
				'default'     => array(
					'size' => 0.04,
				),
				'condition'   => array(
					'animation_type' => 'split-text',
				),
			)
		);

		$this->add_control(
			'easing',
			array(
				'label'   => esc_html__( 'Easing', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'power2.out',
				'options' => array(
					'none'         => esc_html__( 'Linear', 'gsap-elementor-widgets' ),
					'power1.out'   => 'Power1',
					'power2.out'   => 'Power2',
					'power3.out'   => 'Power3',
					'power4.out'   => 'Power4',
					'elastic.out'  => 'Elastic',
					'bounce.out'   => 'Bounce',
					'back.out'     => 'Back',
					'circ.out'     => 'Circ',
					'expo.out'     => 'Expo',
					'sine.out'     => 'Sine',
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
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'gsap-elementor-widgets' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'gsap-elementor-widgets' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'gsap-elementor-widgets' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-heading' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .gsap-ew-heading',
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

		$text = isset( $settings['heading_text'] ) ? $settings['heading_text'] : '';
		if ( '' === trim( $text ) ) {
			return;
		}

		$tag = in_array( $settings['html_tag'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ), true )
			? $settings['html_tag']
			: 'h2';

		$config = array(
			'animation' => $settings['animation_type'],
			'duration'  => isset( $settings['duration']['size'] ) ? (float) $settings['duration']['size'] : 1,
			'delay'     => isset( $settings['delay']['size'] ) ? (float) $settings['delay']['size'] : 0,
			'stagger'   => isset( $settings['stagger']['size'] ) ? (float) $settings['stagger']['size'] : 0.04,
			'easing'    => $settings['easing'],
			'trigger'   => $settings['trigger'],
			'repeat'    => ( 'yes' === $settings['repeat'] ),
		);

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'          => 'gsap-ew-heading gsap-ew-animated-heading',
				'data-gsap-type' => 'animated-heading',
				'data-gsap'      => wp_json_encode( $config ),
			)
		);

		$has_link = ! empty( $settings['link']['url'] );
		if ( $has_link ) {
			$this->add_link_attributes( 'link', $settings['link'] );
		}

		// Text content. For the typewriter/split effects the JS reads the plain
		// text out of the element, so we escape it as text here.
		$safe_text = esc_html( $text );

		printf( '<%1$s %2$s>', esc_attr( $tag ), $this->get_render_attribute_string( 'wrapper' ) );

		if ( $has_link ) {
			printf(
				'<a %1$s class="gsap-ew-heading-link">%2$s</a>',
				$this->get_render_attribute_string( 'link' ),
				$safe_text // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped above.
			);
		} else {
			echo $safe_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped above.
		}

		printf( '</%1$s>', esc_attr( $tag ) );
	}
}
