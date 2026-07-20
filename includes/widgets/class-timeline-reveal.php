<?php
/**
 * Timeline Reveal widget.
 *
 * @package GSAP_Elementor_Widgets
 */

namespace GSAP_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Timeline_Reveal
 *
 * A vertical or horizontal timeline whose milestones animate into view in
 * sequence on scroll using GSAP ScrollTrigger + stagger.
 */
class Timeline_Reveal extends Widget_Base {

	/**
	 * Widget slug / name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'gsap-timeline-reveal';
	}

	/**
	 * Human readable title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Timeline Reveal', 'gsap-elementor-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-time-line';
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
		return array( 'gsap', 'timeline', 'milestone', 'steps', 'history', 'scroll' );
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
		 * Items content section (repeater)
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_items',
			array(
				'label' => esc_html__( 'Timeline Items', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_icon',
			array(
				'label'   => esc_html__( 'Icon', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
			)
		);

		$repeater->add_control(
			'item_label',
			array(
				'label'   => esc_html__( 'Date / Label', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '2024', 'gsap-elementor-widgets' ),
			)
		);

		$repeater->add_control(
			'item_title',
			array(
				'label'   => esc_html__( 'Title', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Milestone Title', 'gsap-elementor-widgets' ),
			)
		);

		$repeater->add_control(
			'item_description',
			array(
				'label'   => esc_html__( 'Description', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => esc_html__( 'Describe what happened at this milestone.', 'gsap-elementor-widgets' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Items', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ item_title }}}',
				'default'     => array(
					array(
						'item_label' => esc_html__( '2021', 'gsap-elementor-widgets' ),
						'item_title' => esc_html__( 'The Beginning', 'gsap-elementor-widgets' ),
					),
					array(
						'item_label' => esc_html__( '2022', 'gsap-elementor-widgets' ),
						'item_title' => esc_html__( 'Rapid Growth', 'gsap-elementor-widgets' ),
					),
					array(
						'item_label' => esc_html__( '2023', 'gsap-elementor-widgets' ),
						'item_title' => esc_html__( 'Going Global', 'gsap-elementor-widgets' ),
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

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => array(
					'vertical'   => esc_html__( 'Vertical', 'gsap-elementor-widgets' ),
					'horizontal' => esc_html__( 'Horizontal', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'animation_direction',
			array(
				'label'     => esc_html__( 'Animation Direction', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'alternate',
				'options'   => array(
					'alternate' => esc_html__( 'Alternating (left / right)', 'gsap-elementor-widgets' ),
					'left'      => esc_html__( 'All From Left', 'gsap-elementor-widgets' ),
					'right'     => esc_html__( 'All From Right', 'gsap-elementor-widgets' ),
				),
				'condition' => array(
					'layout' => 'vertical',
				),
			)
		);

		$this->add_control(
			'duration',
			array(
				'label'      => esc_html__( 'Animation Duration (seconds)', 'gsap-elementor-widgets' ),
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
					'size' => 0.7,
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
					'size' => 0.2,
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
			'line_style',
			array(
				'label'     => esc_html__( 'Connector Line Style', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'gsap-elementor-widgets' ),
					'dashed' => esc_html__( 'Dashed', 'gsap-elementor-widgets' ),
					'dotted' => esc_html__( 'Dotted', 'gsap-elementor-widgets' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-timeline' => '--gsap-ew-line-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'line_color',
			array(
				'label'     => esc_html__( 'Line Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d0d0e0',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-timeline' => '--gsap-ew-line-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dot_color',
			array(
				'label'     => esc_html__( 'Dot / Icon Background Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6c47ff',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-timeline' => '--gsap-ew-dot-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-timeline-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-timeline-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Date / Label Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-timeline-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-timeline-desc' => 'color: {{VALUE}};',
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

		if ( empty( $settings['items'] ) ) {
			return;
		}

		$layout    = in_array( $settings['layout'], array( 'vertical', 'horizontal' ), true ) ? $settings['layout'] : 'vertical';
		$direction = ( 'vertical' === $layout ) ? $settings['animation_direction'] : 'left';

		$config = array(
			'layout'    => $layout,
			'direction' => $direction,
			'duration'  => isset( $settings['duration']['size'] ) ? (float) $settings['duration']['size'] : 0.7,
			'stagger'   => isset( $settings['stagger_delay']['size'] ) ? (float) $settings['stagger_delay']['size'] : 0.2,
			'easing'    => $settings['easing'],
			'repeat'    => ( 'yes' === $settings['repeat'] ),
		);

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'          => array(
					'gsap-ew-timeline',
					'gsap-ew-timeline--' . $layout,
					'gsap-ew-timeline--dir-' . $direction,
				),
				'data-gsap-type' => 'timeline-reveal',
				'data-gsap'      => wp_json_encode( $config ),
			)
		);

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="gsap-ew-timeline-track">';

		foreach ( $settings['items'] as $index => $item ) {
			$side = 'left';
			if ( 'vertical' === $layout ) {
				if ( 'alternate' === $direction ) {
					$side = ( 0 === $index % 2 ) ? 'left' : 'right';
				} else {
					$side = $direction;
				}
			}

			echo '<div class="gsap-ew-timeline-item gsap-ew-timeline-item--' . esc_attr( $side ) . '">';

			// Dot with icon.
			echo '<div class="gsap-ew-timeline-marker">';
			echo '<span class="gsap-ew-timeline-dot">';
			if ( ! empty( $item['item_icon']['value'] ) ) {
				echo '<span class="gsap-ew-timeline-icon">';
				\Elementor\Icons_Manager::render_icon( $item['item_icon'], array( 'aria-hidden' => 'true' ) );
				echo '</span>';
			}
			echo '</span>';
			echo '</div>';

			// Content card.
			echo '<div class="gsap-ew-timeline-content">';

			if ( ! empty( $item['item_label'] ) ) {
				echo '<div class="gsap-ew-timeline-label">' . esc_html( $item['item_label'] ) . '</div>';
			}

			if ( ! empty( $item['item_title'] ) ) {
				echo '<h3 class="gsap-ew-timeline-title">' . esc_html( $item['item_title'] ) . '</h3>';
			}

			if ( ! empty( $item['item_description'] ) ) {
				echo '<div class="gsap-ew-timeline-desc">' . esc_html( $item['item_description'] ) . '</div>';
			}

			echo '</div>'; // .gsap-ew-timeline-content
			echo '</div>'; // .gsap-ew-timeline-item
		}

		echo '</div>'; // .gsap-ew-timeline-track
		echo '</div>'; // .gsap-ew-timeline
	}
}
