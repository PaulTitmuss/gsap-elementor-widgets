<?php
/**
 * Scroll Counter widget.
 *
 * @package GSAP_Elementor_Widgets
 */

namespace GSAP_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Scroll_Counter
 *
 * A number that counts up from a start value to a target number when
 * scrolled into view, powered by GSAP ScrollTrigger + gsap.to.
 */
class Scroll_Counter extends Widget_Base {

	/**
	 * Widget slug / name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'gsap-scroll-counter';
	}

	/**
	 * Human readable title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Scroll Counter', 'gsap-elementor-widgets' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-counter';
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
		return array( 'gsap', 'counter', 'count', 'number', 'stat', 'scroll' );
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
				'label' => esc_html__( 'Counter', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'start_number',
			array(
				'label'   => esc_html__( 'Start Number', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
			)
		);

		$this->add_control(
			'end_number',
			array(
				'label'   => esc_html__( 'End Number', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 100,
			)
		);

		$this->add_control(
			'decimals',
			array(
				'label'   => esc_html__( 'Decimal Places', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 4,
				'step'    => 1,
				'default' => 0,
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
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 2,
				),
			)
		);

		$this->add_control(
			'prefix',
			array(
				'label'       => esc_html__( 'Prefix', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'e.g. $', 'gsap-elementor-widgets' ),
			)
		);

		$this->add_control(
			'suffix',
			array(
				'label'       => esc_html__( 'Suffix', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'e.g. % or k+', 'gsap-elementor-widgets' ),
			)
		);

		$this->add_control(
			'thousands_separator',
			array(
				'label'        => esc_html__( 'Number Formatting (commas)', 'gsap-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'gsap-elementor-widgets' ),
				'label_off'    => esc_html__( 'Off', 'gsap-elementor-widgets' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'separator_char',
			array(
				'label'     => esc_html__( 'Separator Character', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => ',',
				'condition' => array(
					'thousands_separator' => 'yes',
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-counter' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'number_color',
			array(
				'label'     => esc_html__( 'Number Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-counter-number' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'font_size',
			array(
				'label'      => esc_html__( 'Font Size', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
					'em' => array(
						'min'  => 0.5,
						'max'  => 12,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 48,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-counter-number' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'number_typography',
				'selector' => '{{WRAPPER}} .gsap-ew-counter-number',
			)
		);

		$this->add_control(
			'affix_heading',
			array(
				'label'     => esc_html__( 'Prefix / Suffix', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'affix_color',
			array(
				'label'     => esc_html__( 'Prefix / Suffix Color', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-counter-prefix, {{WRAPPER}} .gsap-ew-counter-suffix' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'affix_typography',
				'selector' => '{{WRAPPER}} .gsap-ew-counter-prefix, {{WRAPPER}} .gsap-ew-counter-suffix',
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

		$start    = isset( $settings['start_number'] ) && '' !== $settings['start_number'] ? (float) $settings['start_number'] : 0;
		$end      = isset( $settings['end_number'] ) && '' !== $settings['end_number'] ? (float) $settings['end_number'] : 0;
		$decimals = isset( $settings['decimals'] ) ? absint( $settings['decimals'] ) : 0;

		$config = array(
			'start'     => $start,
			'end'       => $end,
			'duration'  => isset( $settings['duration']['size'] ) ? (float) $settings['duration']['size'] : 2,
			'decimals'  => $decimals,
			'separator' => ( 'yes' === $settings['thousands_separator'] ) ? ( '' !== $settings['separator_char'] ? $settings['separator_char'] : ',' ) : '',
			'repeat'    => ( 'yes' === $settings['repeat'] ),
		);

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'          => 'gsap-ew-counter',
				'data-gsap-type' => 'scroll-counter',
				'data-gsap'      => wp_json_encode( $config ),
			)
		);

		// The initial displayed value is the formatted start number.
		$initial = number_format( $start, $decimals, '.', $config['separator'] );

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( '' !== $settings['prefix'] ) {
			echo '<span class="gsap-ew-counter-prefix">' . esc_html( $settings['prefix'] ) . '</span>';
		}

		echo '<span class="gsap-ew-counter-number">' . esc_html( $initial ) . '</span>';

		if ( '' !== $settings['suffix'] ) {
			echo '<span class="gsap-ew-counter-suffix">' . esc_html( $settings['suffix'] ) . '</span>';
		}

		echo '</div>';
	}
}
