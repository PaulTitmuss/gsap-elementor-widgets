<?php
/**
 * 3D Icon Box widget.
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
 * Class Icon_Box_3D
 *
 * An icon + title + description box that enters the viewport with a GSAP
 * powered 3D transform (flip, rotate, unfold, swing). Optional 3D hover tilt.
 */
class Icon_Box_3D extends Widget_Base {

	/**
	 * Widget slug / name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'gsap-icon-box-3d';
	}

	/**
	 * Human readable title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( '3D Icon Box', 'gsap-elementor-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-icon-box';
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
		return array( 'gsap', 'icon', 'box', '3d', 'flip', 'rotate', 'feature', 'service' );
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
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'title_text',
			array(
				'label'   => esc_html__( 'Title', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Feature Title', 'gsap-elementor-widgets' ),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
			)
		);

		$this->add_control(
			'description_text',
			array(
				'label'   => esc_html__( 'Description', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => esc_html__( 'Describe this feature or service in a sentence or two. The whole box enters with an eye-catching 3D animation.', 'gsap-elementor-widgets' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'gsap-elementor-widgets' ),
				'default'     => array( 'url' => '' ),
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
				'label'   => esc_html__( '3D Entrance', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'flip-x',
				'options' => array(
					'flip-x'      => esc_html__( 'Flip In X (top edge)', 'gsap-elementor-widgets' ),
					'flip-y'      => esc_html__( 'Flip In Y (side edge)', 'gsap-elementor-widgets' ),
					'rotate-3d'   => esc_html__( 'Rotate In 3D', 'gsap-elementor-widgets' ),
					'zoom-rotate' => esc_html__( 'Zoom + Rotate', 'gsap-elementor-widgets' ),
					'swing'       => esc_html__( 'Swing In', 'gsap-elementor-widgets' ),
					'unfold'      => esc_html__( 'Unfold', 'gsap-elementor-widgets' ),
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
				'default'    => array( 'size' => 1 ),
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
			'perspective',
			array(
				'label'      => esc_html__( 'Perspective (px)', 'gsap-elementor-widgets' ),
				'description' => esc_html__( 'Lower values create a more dramatic 3D effect.', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 200,
						'max'  => 2000,
						'step' => 50,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 800,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-iconbox-3d' => 'perspective: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'easing',
			array(
				'label'   => esc_html__( 'Easing', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'power3.out',
				'options' => array(
					'none'        => esc_html__( 'Linear', 'gsap-elementor-widgets' ),
					'power1.out'  => 'Power1',
					'power2.out'  => 'Power2',
					'power3.out'  => 'Power3',
					'power4.out'  => 'Power4',
					'back.out'    => 'Back',
					'elastic.out' => 'Elastic',
					'bounce.out'  => 'Bounce',
					'circ.out'    => 'Circ',
					'expo.out'    => 'Expo',
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

		$this->add_control(
			'hover_tilt',
			array(
				'label'        => esc_html__( '3D Hover Tilt', 'gsap-elementor-widgets' ),
				'description'  => esc_html__( 'Tilts the box in 3D following the cursor on hover.', 'gsap-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'gsap-elementor-widgets' ),
				'label_off'    => esc_html__( 'Off', 'gsap-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Box style section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_box_style',
			array(
				'label' => esc_html__( 'Box', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'box_alignment',
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-iconbox-inner' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'box_background',
			array(
				'label'     => esc_html__( 'Background Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-iconbox-inner' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 40,
					'right'    => 30,
					'bottom'   => 40,
					'left'     => 30,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-iconbox-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_radius',
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
					'size' => 14,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-iconbox-inner' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .gsap-ew-iconbox-inner',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Icon style section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => esc_html__( 'Icon', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6c47ff',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-iconbox-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .gsap-ew-iconbox-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_bg_color',
			array(
				'label'     => esc_html__( 'Icon Background', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(108, 71, 255, 0.12)',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-iconbox-icon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 120,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 34,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-iconbox-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gsap-ew-iconbox-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_box_size',
			array(
				'label'      => esc_html__( 'Icon Container Size', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 40,
						'max' => 200,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 80,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-iconbox-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_radius',
			array(
				'label'      => esc_html__( 'Icon Container Radius', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-iconbox-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Content style section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => esc_html__( 'Title & Description', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_heading',
			array(
				'label' => esc_html__( 'Title', 'gsap-elementor-widgets' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-iconbox-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .gsap-ew-iconbox-title',
			)
		);

		$this->add_control(
			'desc_heading',
			array(
				'label'     => esc_html__( 'Description', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-iconbox-desc' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .gsap-ew-iconbox-desc',
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

		$title = isset( $settings['title_text'] ) ? $settings['title_text'] : '';
		$desc  = isset( $settings['description_text'] ) ? $settings['description_text'] : '';

		$title_tag = in_array( $settings['title_tag'], array( 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span' ), true )
			? $settings['title_tag']
			: 'h3';

		$config = array(
			'animation' => $settings['animation_type'],
			'duration'  => isset( $settings['duration']['size'] ) ? (float) $settings['duration']['size'] : 1,
			'delay'     => isset( $settings['delay']['size'] ) ? (float) $settings['delay']['size'] : 0,
			'easing'    => $settings['easing'],
			'trigger'   => $settings['trigger'],
			'repeat'    => ( 'yes' === $settings['repeat'] ),
			'hoverTilt' => ( 'yes' === $settings['hover_tilt'] ),
		);

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'          => 'gsap-ew-iconbox-3d',
				'data-gsap-type' => 'icon-box-3d',
				'data-gsap'      => wp_json_encode( $config ),
			)
		);

		$has_link = ! empty( $settings['link']['url'] );
		if ( $has_link ) {
			$this->add_link_attributes( 'link', $settings['link'] );
		}

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $has_link ) {
			echo '<a ' . $this->get_render_attribute_string( 'link' ) . ' class="gsap-ew-iconbox-inner">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo '<div class="gsap-ew-iconbox-inner">';
		}

		// Icon.
		if ( ! empty( $settings['icon']['value'] ) ) {
			echo '<span class="gsap-ew-iconbox-icon">';
			\Elementor\Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) );
			echo '</span>';
		}

		if ( '' !== trim( $title ) ) {
			printf(
				'<%1$s class="gsap-ew-iconbox-title">%2$s</%1$s>',
				esc_attr( $title_tag ),
				esc_html( $title )
			);
		}

		if ( '' !== trim( $desc ) ) {
			printf( '<p class="gsap-ew-iconbox-desc">%s</p>', esc_html( $desc ) );
		}

		echo $has_link ? '</a>' : '</div>';
		echo '</div>';
	}
}
