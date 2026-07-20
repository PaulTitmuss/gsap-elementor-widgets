<?php
/**
 * Reveal on Scroll widget.
 *
 * @package GSAP_Elementor_Widgets
 */

namespace GSAP_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Reveal_On_Scroll
 *
 * Reveals a piece of text or an image on scroll using clip-path / mask style
 * wipes, zoom reveals and blur reveals — with optional scrubbing tied to the
 * scroll position.
 */
class Reveal_On_Scroll extends Widget_Base {

	/**
	 * Widget slug / name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'gsap-reveal-on-scroll';
	}

	/**
	 * Human readable title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Reveal on Scroll', 'gsap-elementor-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-eye';
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
		return array( 'gsap', 'reveal', 'scroll', 'clip', 'mask', 'wipe', 'image', 'text' );
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
			'content_type',
			array(
				'label'   => esc_html__( 'Content Type', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => array(
					'image' => esc_html__( 'Image', 'gsap-elementor-widgets' ),
					'text'  => esc_html__( 'Text / Heading', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => esc_html__( 'Image', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'content_type' => 'image',
				),
			)
		);

		$this->add_control(
			'text_content',
			array(
				'label'     => esc_html__( 'Text', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::TEXTAREA,
				'rows'      => 3,
				'default'   => esc_html__( 'Revealed on scroll', 'gsap-elementor-widgets' ),
				'condition' => array(
					'content_type' => 'text',
				),
			)
		);

		$this->add_control(
			'text_tag',
			array(
				'label'     => esc_html__( 'HTML Tag', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => array(
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
				'condition' => array(
					'content_type' => 'text',
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
				'label'   => esc_html__( 'Reveal Type', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'clip',
				'options' => array(
					'clip'      => esc_html__( 'Clip Wipe', 'gsap-elementor-widgets' ),
					'fade-slide' => esc_html__( 'Fade + Slide', 'gsap-elementor-widgets' ),
					'zoom'      => esc_html__( 'Zoom Reveal', 'gsap-elementor-widgets' ),
					'blur'      => esc_html__( 'Blur Reveal', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'     => esc_html__( 'Direction', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'   => esc_html__( 'From Left', 'gsap-elementor-widgets' ),
					'right'  => esc_html__( 'From Right', 'gsap-elementor-widgets' ),
					'top'    => esc_html__( 'From Top', 'gsap-elementor-widgets' ),
					'bottom' => esc_html__( 'From Bottom', 'gsap-elementor-widgets' ),
				),
				'condition' => array(
					'animation_type' => array( 'clip', 'fade-slide' ),
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
						'max'  => 4,
						'step' => 0.1,
					),
				),
				'default'    => array( 'size' => 1.1 ),
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
				'default' => 'power3.out',
				'options' => array(
					'none'       => esc_html__( 'Linear', 'gsap-elementor-widgets' ),
					'power1.out' => 'Power1',
					'power2.out' => 'Power2',
					'power3.out' => 'Power3',
					'power4.out' => 'Power4',
					'back.out'   => 'Back',
					'circ.out'   => 'Circ',
					'expo.out'   => 'Expo',
					'sine.out'   => 'Sine',
				),
			)
		);

		$this->add_control(
			'start_position',
			array(
				'label'       => esc_html__( 'Start When', 'gsap-elementor-widgets' ),
				'description' => esc_html__( 'How far into the viewport the element should be before revealing.', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'top 85%',
				'options'     => array(
					'top 95%'    => esc_html__( 'As soon as it appears', 'gsap-elementor-widgets' ),
					'top 85%'    => esc_html__( 'Slightly into view', 'gsap-elementor-widgets' ),
					'top 70%'    => esc_html__( 'A third into view', 'gsap-elementor-widgets' ),
					'top center' => esc_html__( 'When it hits the middle', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'scrub',
			array(
				'label'        => esc_html__( 'Tie To Scroll (Scrub)', 'gsap-elementor-widgets' ),
				'description'  => esc_html__( 'Progresses the reveal as the user scrolls instead of playing once.', 'gsap-elementor-widgets' ),
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
					'scrub!' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Image style section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_image_style',
			array(
				'label'     => esc_html__( 'Image', 'gsap-elementor-widgets' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'content_type' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label'      => esc_html__( 'Width', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
					'px' => array(
						'min' => 50,
						'max' => 1200,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-reveal-media' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 8,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-reveal-media' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_shadow',
				'selector' => '{{WRAPPER}} .gsap-ew-reveal-media',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Text style section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_text_style',
			array(
				'label'     => esc_html__( 'Text', 'gsap-elementor-widgets' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'content_type' => 'text',
				),
			)
		);

		$this->add_responsive_control(
			'text_alignment',
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
					'{{WRAPPER}} .gsap-ew-reveal-text' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-reveal-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .gsap-ew-reveal-text',
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
			'animation' => $settings['animation_type'],
			'direction' => $settings['direction'],
			'duration'  => isset( $settings['duration']['size'] ) ? (float) $settings['duration']['size'] : 1.1,
			'delay'     => isset( $settings['delay']['size'] ) ? (float) $settings['delay']['size'] : 0,
			'easing'    => $settings['easing'],
			'start'     => $settings['start_position'],
			'scrub'     => ( 'yes' === $settings['scrub'] ),
			'repeat'    => ( 'yes' === $settings['repeat'] ),
		);

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'          => 'gsap-ew-reveal',
				'data-gsap-type' => 'reveal-on-scroll',
				'data-gsap'      => wp_json_encode( $config ),
			)
		);

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="gsap-ew-reveal-clip">';

		if ( 'text' === $settings['content_type'] ) {
			$text = isset( $settings['text_content'] ) ? $settings['text_content'] : '';
			$tag  = in_array( $settings['text_tag'], array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ), true )
				? $settings['text_tag']
				: 'h2';

			if ( '' !== trim( $text ) ) {
				printf(
					'<%1$s class="gsap-ew-reveal-text gsap-ew-reveal-target">%2$s</%1$s>',
					esc_attr( $tag ),
					esc_html( $text )
				);
			}
		} else {
			$image = isset( $settings['image'] ) ? $settings['image'] : array();
			if ( ! empty( $image['url'] ) ) {
				$alt = '';
				if ( ! empty( $image['id'] ) ) {
					$alt = get_post_meta( $image['id'], '_wp_attachment_image_alt', true );
				}
				printf(
					'<img class="gsap-ew-reveal-media gsap-ew-reveal-target" src="%1$s" alt="%2$s" />',
					esc_url( $image['url'] ),
					esc_attr( $alt )
				);
			}
		}

		echo '</div>';
		echo '</div>';
	}
}
