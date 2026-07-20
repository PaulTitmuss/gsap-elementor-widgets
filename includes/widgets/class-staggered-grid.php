<?php
/**
 * Staggered Card Grid widget.
 *
 * @package GSAP_Elementor_Widgets
 */

namespace GSAP_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Staggered_Grid
 *
 * A responsive grid of repeatable cards that animate in one-by-one on scroll
 * using GSAP stagger + ScrollTrigger.
 */
class Staggered_Grid extends Widget_Base {

	/**
	 * Widget slug / name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'gsap-staggered-grid';
	}

	/**
	 * Human readable title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Staggered Card Grid', 'gsap-elementor-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
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
		return array( 'gsap', 'grid', 'cards', 'stagger', 'scroll', 'animation' );
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
		 * Cards content section (repeater)
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_cards',
			array(
				'label' => esc_html__( 'Cards', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'card_image',
			array(
				'label'   => esc_html__( 'Image', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'card_title',
			array(
				'label'   => esc_html__( 'Title', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Card Title', 'gsap-elementor-widgets' ),
			)
		);

		$repeater->add_control(
			'card_subtitle',
			array(
				'label'   => esc_html__( 'Subtitle', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Subtitle', 'gsap-elementor-widgets' ),
			)
		);

		$repeater->add_control(
			'card_description',
			array(
				'label'   => esc_html__( 'Description', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => esc_html__( 'A short description for this card goes here.', 'gsap-elementor-widgets' ),
			)
		);

		$repeater->add_control(
			'card_button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Learn More', 'gsap-elementor-widgets' ),
			)
		);

		$repeater->add_control(
			'card_button_link',
			array(
				'label'       => esc_html__( 'Button Link', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'gsap-elementor-widgets' ),
				'default'     => array(
					'url' => '#',
				),
			)
		);

		$this->add_control(
			'cards',
			array(
				'label'       => esc_html__( 'Card Items', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ card_title }}}',
				'default'     => array(
					array(
						'card_title'    => esc_html__( 'First Card', 'gsap-elementor-widgets' ),
						'card_subtitle' => esc_html__( 'Subtitle One', 'gsap-elementor-widgets' ),
					),
					array(
						'card_title'    => esc_html__( 'Second Card', 'gsap-elementor-widgets' ),
						'card_subtitle' => esc_html__( 'Subtitle Two', 'gsap-elementor-widgets' ),
					),
					array(
						'card_title'    => esc_html__( 'Third Card', 'gsap-elementor-widgets' ),
						'card_subtitle' => esc_html__( 'Subtitle Three', 'gsap-elementor-widgets' ),
					),
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Layout & animation section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout & Animation', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'gsap-elementor-widgets' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'      => array(
					'{{WRAPPER}} .gsap-ew-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 24,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'animation_type',
			array(
				'label'   => esc_html__( 'Animation Type', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade-up',
				'options' => array(
					'fade-up'     => esc_html__( 'Fade Up', 'gsap-elementor-widgets' ),
					'fade-in'     => esc_html__( 'Fade In', 'gsap-elementor-widgets' ),
					'zoom-in'     => esc_html__( 'Zoom In', 'gsap-elementor-widgets' ),
					'slide-left'  => esc_html__( 'Slide From Left', 'gsap-elementor-widgets' ),
					'slide-right' => esc_html__( 'Slide From Right', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'stagger_delay',
			array(
				'label'      => esc_html__( 'Stagger Delay (seconds)', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '' ),
				'range'      => array(
					'' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.05,
					),
				),
				'default'    => array(
					'size' => 0.15,
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
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 0.8,
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
					'none'        => esc_html__( 'Linear', 'gsap-elementor-widgets' ),
					'power1.out'  => 'Power1',
					'power2.out'  => 'Power2',
					'power3.out'  => 'Power3',
					'back.out'    => 'Back',
					'elastic.out' => 'Elastic',
					'bounce.out'  => 'Bounce',
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
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Card style section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_card_style',
			array(
				'label' => esc_html__( 'Card Style', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_radius',
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
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-card' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Content Padding', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 24,
					'right'    => 24,
					'bottom'   => 24,
					'left'     => 24,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .gsap-ew-card',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-card-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => esc_html__( 'Subtitle Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-card-subtitle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-card-desc' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_heading',
			array(
				'label'     => esc_html__( 'Button', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Button Text Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-card-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Button Background', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6c47ff',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-card-button' => 'background-color: {{VALUE}};',
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

		if ( empty( $settings['cards'] ) ) {
			return;
		}

		$config = array(
			'animation' => $settings['animation_type'],
			'stagger'   => isset( $settings['stagger_delay']['size'] ) ? (float) $settings['stagger_delay']['size'] : 0.15,
			'duration'  => isset( $settings['duration']['size'] ) ? (float) $settings['duration']['size'] : 0.8,
			'easing'    => $settings['easing'],
			'repeat'    => ( 'yes' === $settings['repeat'] ),
		);

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'          => 'gsap-ew-grid-wrapper',
				'data-gsap-type' => 'staggered-grid',
				'data-gsap'      => wp_json_encode( $config ),
			)
		);

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="gsap-ew-grid">';

		foreach ( $settings['cards'] as $index => $card ) {
			$this->render_card( $card, $index );
		}

		echo '</div>'; // .gsap-ew-grid
		echo '</div>'; // .gsap-ew-grid-wrapper
	}

	/**
	 * Render a single card.
	 *
	 * @param array $card  Card settings.
	 * @param int   $index Card index.
	 * @return void
	 */
	private function render_card( $card, $index ) {
		echo '<div class="gsap-ew-card gsap-ew-card-item">';

		// Image.
		if ( ! empty( $card['card_image']['url'] ) ) {
			printf(
				'<div class="gsap-ew-card-image"><img src="%1$s" alt="%2$s" loading="lazy" /></div>',
				esc_url( $card['card_image']['url'] ),
				esc_attr( isset( $card['card_title'] ) ? $card['card_title'] : '' )
			);
		}

		echo '<div class="gsap-ew-card-body">';

		if ( ! empty( $card['card_subtitle'] ) ) {
			echo '<div class="gsap-ew-card-subtitle">' . esc_html( $card['card_subtitle'] ) . '</div>';
		}

		if ( ! empty( $card['card_title'] ) ) {
			echo '<h3 class="gsap-ew-card-title">' . esc_html( $card['card_title'] ) . '</h3>';
		}

		if ( ! empty( $card['card_description'] ) ) {
			echo '<div class="gsap-ew-card-desc">' . esc_html( $card['card_description'] ) . '</div>';
		}

		if ( ! empty( $card['card_button_text'] ) ) {
			$link_key = 'card_button_link_' . $index;
			$this->add_render_attribute( $link_key, 'class', 'gsap-ew-card-button' );

			if ( ! empty( $card['card_button_link']['url'] ) ) {
				$this->add_link_attributes( $link_key, $card['card_button_link'] );
			} else {
				$this->add_render_attribute( $link_key, 'href', '#' );
			}

			printf(
				'<a %1$s>%2$s</a>',
				$this->get_render_attribute_string( $link_key ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $card['card_button_text'] )
			);
		}

		echo '</div>'; // .gsap-ew-card-body
		echo '</div>'; // .gsap-ew-card
	}
}
