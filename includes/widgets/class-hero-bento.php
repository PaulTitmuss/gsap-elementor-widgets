<?php
/**
 * Hero to Bento widget.
 *
 * @package GSAP_Elementor_Widgets
 */

namespace GSAP_Elementor_Widgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Hero_Bento
 *
 * A full-screen hero (video / image / colour background) that, as the visitor
 * scrolls, shrinks and settles into its place within a "bento" grid of smaller
 * cards which reveal around it. Inspired by the hero on elementor.com. Powered
 * by GSAP ScrollTrigger pinning.
 */
class Hero_Bento extends Widget_Base {

	/**
	 * Widget slug / name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'gsap-hero-bento';
	}

	/**
	 * Human readable title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Hero to Bento Scroll', 'gsap-elementor-widgets' );
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
		return array( 'gsap', 'hero', 'bento', 'grid', 'scroll', 'pin', 'video', 'shrink', 'scale' );
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
		 * Hero content section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_hero',
			array(
				'label' => esc_html__( 'Hero', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'bg_type',
			array(
				'label'   => esc_html__( 'Background Type', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => array(
					'video' => esc_html__( 'Video', 'gsap-elementor-widgets' ),
					'image' => esc_html__( 'Image', 'gsap-elementor-widgets' ),
					'color' => esc_html__( 'Solid Colour', 'gsap-elementor-widgets' ),
				),
			)
		);

		$this->add_control(
			'video_url',
			array(
				'label'       => esc_html__( 'Video URL (MP4)', 'gsap-elementor-widgets' ),
				'description' => esc_html__( 'Paste a direct link to a self-hosted or external .mp4 file. It will autoplay muted and loop.', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'https://example.com/video.mp4',
				'condition'   => array(
					'bg_type' => 'video',
				),
			)
		);

		$this->add_control(
			'bg_image',
			array(
				'label'     => esc_html__( 'Background Image', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'bg_type' => array( 'image', 'video' ),
				),
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'     => esc_html__( 'Background Colour', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1e1e2f',
				'condition' => array(
					'bg_type' => 'color',
				),
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'     => esc_html__( 'Overlay Colour', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.45)',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-herobento-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'       => esc_html__( 'Heading', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Build stunning websites', 'gsap-elementor-widgets' ),
			)
		);

		$this->add_control(
			'subheading',
			array(
				'label'   => esc_html__( 'Sub Heading', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Scroll to see the hero settle into the grid.', 'gsap-elementor-widgets' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => esc_html__( 'Button Text', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Get Started', 'gsap-elementor-widgets' ),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'       => esc_html__( 'Button Link', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://your-site.com',
				'condition'   => array(
					'button_text!' => '',
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Bento items section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_items',
			array(
				'label' => esc_html__( 'Bento Cards', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_image',
			array(
				'label'   => esc_html__( 'Image', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'item_title',
			array(
				'label'       => esc_html__( 'Title', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Card title', 'gsap-elementor-widgets' ),
			)
		);

		$repeater->add_control(
			'item_text',
			array(
				'label'   => esc_html__( 'Text', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'A short supporting line of text.', 'gsap-elementor-widgets' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Cards', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ item_title }}}',
				'default'     => array(
					array( 'item_title' => esc_html__( 'Lightning fast', 'gsap-elementor-widgets' ) ),
					array( 'item_title' => esc_html__( 'Fully responsive', 'gsap-elementor-widgets' ) ),
					array( 'item_title' => esc_html__( 'Easy to edit', 'gsap-elementor-widgets' ) ),
					array( 'item_title' => esc_html__( 'SEO friendly', 'gsap-elementor-widgets' ) ),
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Layout section
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout & Scroll', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'   => esc_html__( 'Grid Columns', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '3',
				'options' => array(
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
			)
		);

		$this->add_control(
			'hero_span_columns',
			array(
				'label'      => esc_html__( 'Hero Width (columns)', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '' ),
				'range'      => array(
					'' => array(
						'min'  => 1,
						'max'  => 4,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 2,
				),
			)
		);

		$this->add_control(
			'hero_span_rows',
			array(
				'label'      => esc_html__( 'Hero Height (rows)', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '' ),
				'range'      => array(
					'' => array(
						'min'  => 1,
						'max'  => 3,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 2,
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'      => esc_html__( 'Gap (px)', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 60,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 16,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-herobento-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'grid_max_width',
			array(
				'label'      => esc_html__( 'Grid Max Width (px)', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 600,
						'max'  => 1600,
						'step' => 10,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 1200,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-herobento-grid' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'grid_height',
			array(
				'label'      => esc_html__( 'Grid Height (% of screen)', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '' ),
				'range'      => array(
					'' => array(
						'min'  => 50,
						'max'  => 95,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 82,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-herobento-grid' => 'height: {{SIZE}}vh;',
				),
			)
		);

		$this->add_control(
			'scroll_length',
			array(
				'label'       => esc_html__( 'Scroll Length (% of screen)', 'gsap-elementor-widgets' ),
				'description' => esc_html__( 'How much scrolling the shrink effect lasts. Larger = slower, more drawn-out.', 'gsap-elementor-widgets' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( '' ),
				'range'       => array(
					'' => array(
						'min'  => 60,
						'max'  => 300,
						'step' => 10,
					),
				),
				'default'     => array(
					'size' => 160,
				),
			)
		);

		$this->add_control(
			'easing',
			array(
				'label'   => esc_html__( 'Card Easing', 'gsap-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'power2.out',
				'options' => array(
					'none'        => esc_html__( 'Linear', 'gsap-elementor-widgets' ),
					'power1.out'  => 'Power1',
					'power2.out'  => 'Power2',
					'power3.out'  => 'Power3',
					'power4.out'  => 'Power4',
					'back.out'    => 'Back',
					'elastic.out' => 'Elastic',
					'circ.out'    => 'Circ',
					'expo.out'    => 'Expo',
					'sine.out'    => 'Sine',
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Style — Hero
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_style_hero',
			array(
				'label' => esc_html__( 'Hero Style', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => esc_html__( 'Heading Colour', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-herobento-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector' => '{{WRAPPER}} .gsap-ew-herobento-heading',
			)
		);

		$this->add_control(
			'subheading_color',
			array(
				'label'     => esc_html__( 'Sub Heading Colour', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.85)',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-herobento-subheading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Button Background', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-herobento-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Button Text Colour', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1e1e2f',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-herobento-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------------------
		 * Style — Cards
		 * ------------------------------------------------------------- */
		$this->start_controls_section(
			'section_style_cards',
			array(
				'label' => esc_html__( 'Card Style', 'gsap-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg_color',
			array(
				'label'     => esc_html__( 'Card Background', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f4f4f8',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-herobento-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_radius',
			array(
				'label'      => esc_html__( 'Corner Radius (px)', 'gsap-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 40,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 14,
				),
				'selectors'  => array(
					'{{WRAPPER}} .gsap-ew-herobento-item, {{WRAPPER}} .gsap-ew-herobento-hero' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_title_color',
			array(
				'label'     => esc_html__( 'Card Title Colour', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1e1e2f',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-herobento-item-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_text_color',
			array(
				'label'     => esc_html__( 'Card Text Colour', 'gsap-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5a5a6e',
				'selectors' => array(
					'{{WRAPPER}} .gsap-ew-herobento-item-text' => 'color: {{VALUE}};',
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
			'scrollLength' => isset( $settings['scroll_length']['size'] ) ? (float) $settings['scroll_length']['size'] : 160,
			'easing'       => isset( $settings['easing'] ) ? $settings['easing'] : 'power2.out',
		);

		$cols       = isset( $settings['columns'] ) ? (int) $settings['columns'] : 3;
		$hero_cols  = isset( $settings['hero_span_columns']['size'] ) ? (int) $settings['hero_span_columns']['size'] : 2;
		$hero_rows  = isset( $settings['hero_span_rows']['size'] ) ? (int) $settings['hero_span_rows']['size'] : 2;
		$hero_cols  = min( $hero_cols, $cols );

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'          => 'gsap-ew-herobento',
				'data-gsap-type' => 'hero-bento',
				'data-gsap'      => wp_json_encode( $config ),
			)
		);

		$grid_style = sprintf( 'grid-template-columns:repeat(%d,1fr);', $cols );
		$hero_style = sprintf( 'grid-column:span %d;grid-row:span %d;', $hero_cols, $hero_rows );

		$bg_type = isset( $settings['bg_type'] ) ? $settings['bg_type'] : 'image';

		echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="gsap-ew-herobento-stage">';
		echo '<div class="gsap-ew-herobento-grid" style="' . esc_attr( $grid_style ) . '">';

		/* -------------------- Hero cell -------------------- */
		echo '<div class="gsap-ew-herobento-hero" style="' . esc_attr( $hero_style ) . '">';

		// Background layer.
		if ( 'video' === $bg_type && ! empty( $settings['video_url'] ) ) {
			$poster = ! empty( $settings['bg_image']['url'] ) ? ' poster="' . esc_url( $settings['bg_image']['url'] ) . '"' : '';
			echo '<video class="gsap-ew-herobento-bg gsap-ew-herobento-video" autoplay muted loop playsinline' . $poster . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<source src="' . esc_url( $settings['video_url'] ) . '" type="video/mp4" />';
			echo '</video>';
		} elseif ( 'color' === $bg_type ) {
			$color = ! empty( $settings['bg_color'] ) ? $settings['bg_color'] : '#1e1e2f';
			echo '<div class="gsap-ew-herobento-bg" style="background-color:' . esc_attr( $color ) . ';"></div>';
		} elseif ( ! empty( $settings['bg_image']['url'] ) ) {
			echo '<div class="gsap-ew-herobento-bg" style="background-image:url(' . esc_url( $settings['bg_image']['url'] ) . ');"></div>';
		}

		echo '<div class="gsap-ew-herobento-overlay"></div>';

		// Hero content.
		echo '<div class="gsap-ew-herobento-content">';
		if ( ! empty( $settings['heading'] ) ) {
			echo '<h2 class="gsap-ew-herobento-heading">' . esc_html( $settings['heading'] ) . '</h2>';
		}
		if ( ! empty( $settings['subheading'] ) ) {
			echo '<p class="gsap-ew-herobento-subheading">' . esc_html( $settings['subheading'] ) . '</p>';
		}
		if ( ! empty( $settings['button_text'] ) ) {
			$btn_attrs = 'class="gsap-ew-herobento-btn"';
			if ( ! empty( $settings['button_link']['url'] ) ) {
				$btn_attrs .= ' href="' . esc_url( $settings['button_link']['url'] ) . '"';
				if ( ! empty( $settings['button_link']['is_external'] ) ) {
					$btn_attrs .= ' target="_blank"';
				}
				if ( ! empty( $settings['button_link']['nofollow'] ) ) {
					$btn_attrs .= ' rel="nofollow"';
				}
			} else {
				$btn_attrs = 'class="gsap-ew-herobento-btn" href="#"';
			}
			echo '<a ' . $btn_attrs . '>' . esc_html( $settings['button_text'] ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</div>'; // .content
		echo '</div>'; // .hero

		/* -------------------- Bento cards -------------------- */
		if ( ! empty( $settings['items'] ) && is_array( $settings['items'] ) ) {
			foreach ( $settings['items'] as $item ) {
				echo '<div class="gsap-ew-herobento-item">';
				if ( ! empty( $item['item_image']['url'] ) ) {
					echo '<div class="gsap-ew-herobento-item-media"><img src="' . esc_url( $item['item_image']['url'] ) . '" alt="' . esc_attr( isset( $item['item_title'] ) ? $item['item_title'] : '' ) . '" /></div>';
				}
				echo '<div class="gsap-ew-herobento-item-body">';
				if ( ! empty( $item['item_title'] ) ) {
					echo '<h3 class="gsap-ew-herobento-item-title">' . esc_html( $item['item_title'] ) . '</h3>';
				}
				if ( ! empty( $item['item_text'] ) ) {
					echo '<p class="gsap-ew-herobento-item-text">' . esc_html( $item['item_text'] ) . '</p>';
				}
				echo '</div>'; // .item-body
				echo '</div>'; // .item
			}
		}

		echo '</div>'; // .grid
		echo '</div>'; // .stage
		echo '</div>'; // .wrapper
	}
}
