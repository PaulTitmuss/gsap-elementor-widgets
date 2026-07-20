<?php
/**
 * Animated Text widget.
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
 * Class Animated_Text
 *
 * GSAP powered reveal animations for longer, rich body text: fade in, slide
 * variations, word / line / character reveals, and blur-in.
 */
class Animated_Text extends Widget_Base {

        /**
         * Widget slug / name.
         *
         * @return string
         */
        public function get_name() {
                return 'gsap-animated-text';
        }

        /**
         * Human readable title.
         *
         * @return string
         */
        public function get_title() {
                return esc_html__( 'Animated Text', 'gsap-elementor-widgets' );
        }

        /**
         * Widget icon.
         *
         * @return string
         */
        public function get_icon() {
                return 'eicon-t-letter';
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
                return array( 'gsap', 'text', 'paragraph', 'animation', 'reveal', 'word', 'line', 'blur' );
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
                        'text_content',
                        array(
                                'label'   => esc_html__( 'Text', 'gsap-elementor-widgets' ),
                                'type'    => Controls_Manager::WYSIWYG,
                                'default' => esc_html__( 'Add your engaging paragraph of text here. This widget reveals body copy with smooth GSAP animations — perfect for intros, mission statements and storytelling sections.', 'gsap-elementor-widgets' ),
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
                                'default' => 'fade-up',
                                'options' => array(
                                        'fade-in'     => esc_html__( 'Fade In', 'gsap-elementor-widgets' ),
                                        'fade-up'     => esc_html__( 'Slide Up', 'gsap-elementor-widgets' ),
                                        'slide-left'  => esc_html__( 'Slide From Left', 'gsap-elementor-widgets' ),
                                        'slide-right' => esc_html__( 'Slide From Right', 'gsap-elementor-widgets' ),
                                        'blur-in'     => esc_html__( 'Blur In', 'gsap-elementor-widgets' ),
                                        'words'       => esc_html__( 'Word by Word Reveal', 'gsap-elementor-widgets' ),
                                        'lines'       => esc_html__( 'Line by Line Reveal', 'gsap-elementor-widgets' ),
                                        'chars'       => esc_html__( 'Character by Character Reveal', 'gsap-elementor-widgets' ),
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
                                        'size' => 0.9,
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
                                'label'       => esc_html__( 'Stagger (seconds)', 'gsap-elementor-widgets' ),
                                'description' => esc_html__( 'Delay between each word / line / character. Only applies to the Word, Line and Character reveal types.', 'gsap-elementor-widgets' ),
                                'type'        => Controls_Manager::SLIDER,
                                'size_units'  => array( '' ),
                                'range'       => array(
                                        '' => array(
                                                'min'  => 0.01,
                                                'max'  => 0.5,
                                                'step' => 0.01,
                                        ),
                                ),
                                'default'     => array(
                                        'size' => 0.06,
                                ),
                                'condition'   => array(
                                        'animation_type' => array( 'words', 'lines', 'chars' ),
                                ),
                        )
                );

                $this->add_control(
                        'reveal_direction',
                        array(
                                'label'       => esc_html__( 'Reveal Direction', 'gsap-elementor-widgets' ),
                                'description' => esc_html__( 'The direction each word / line / character travels as it appears.', 'gsap-elementor-widgets' ),
                                'type'        => Controls_Manager::SELECT,
                                'default'     => 'up',
                                'options'     => array(
                                        'up'    => esc_html__( 'From Below (slide up)', 'gsap-elementor-widgets' ),
                                        'down'  => esc_html__( 'From Above (slide down)', 'gsap-elementor-widgets' ),
                                        'left'  => esc_html__( 'From Left', 'gsap-elementor-widgets' ),
                                        'right' => esc_html__( 'From Right', 'gsap-elementor-widgets' ),
                                        'scale' => esc_html__( 'Scale Up', 'gsap-elementor-widgets' ),
                                        'none'  => esc_html__( 'Fade Only', 'gsap-elementor-widgets' ),
                                ),
                                'condition'   => array(
                                        'animation_type' => array( 'words', 'lines', 'chars' ),
                                ),
                        )
                );

                $this->add_control(
                        'reveal_order',
                        array(
                                'label'       => esc_html__( 'Reveal Order', 'gsap-elementor-widgets' ),
                                'description' => esc_html__( 'The order the words / lines / characters appear in.', 'gsap-elementor-widgets' ),
                                'type'        => Controls_Manager::SELECT,
                                'default'     => 'start',
                                'options'     => array(
                                        'start'  => esc_html__( 'Left to Right (first to last)', 'gsap-elementor-widgets' ),
                                        'end'    => esc_html__( 'Right to Left (last to first)', 'gsap-elementor-widgets' ),
                                        'center' => esc_html__( 'From the Center Outwards', 'gsap-elementor-widgets' ),
                                        'edges'  => esc_html__( 'From the Edges Inwards', 'gsap-elementor-widgets' ),
                                        'random' => esc_html__( 'Random', 'gsap-elementor-widgets' ),
                                ),
                                'condition'   => array(
                                        'animation_type' => array( 'words', 'lines', 'chars' ),
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
                                        'power4.out'  => 'Power4',
                                        'back.out'    => 'Back',
                                        'elastic.out' => 'Elastic',
                                        'bounce.out'  => 'Bounce',
                                        'circ.out'    => 'Circ',
                                        'expo.out'    => 'Expo',
                                        'sine.out'    => 'Sine',
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
                                        'left'    => array(
                                                'title' => esc_html__( 'Left', 'gsap-elementor-widgets' ),
                                                'icon'  => 'eicon-text-align-left',
                                        ),
                                        'center'  => array(
                                                'title' => esc_html__( 'Center', 'gsap-elementor-widgets' ),
                                                'icon'  => 'eicon-text-align-center',
                                        ),
                                        'right'   => array(
                                                'title' => esc_html__( 'Right', 'gsap-elementor-widgets' ),
                                                'icon'  => 'eicon-text-align-right',
                                        ),
                                        'justify' => array(
                                                'title' => esc_html__( 'Justify', 'gsap-elementor-widgets' ),
                                                'icon'  => 'eicon-text-align-justify',
                                        ),
                                ),
                                'default'   => 'left',
                                'selectors' => array(
                                        '{{WRAPPER}} .gsap-ew-text' => 'text-align: {{VALUE}};',
                                ),
                        )
                );

                $this->add_control(
                        'text_color',
                        array(
                                'label'     => esc_html__( 'Text Color', 'gsap-elementor-widgets' ),
                                'type'      => Controls_Manager::COLOR,
                                'selectors' => array(
                                        '{{WRAPPER}} .gsap-ew-text' => 'color: {{VALUE}};',
                                ),
                        )
                );

                $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                                'name'     => 'typography',
                                'global'   => array(
                                        'default' => Global_Typography::TYPOGRAPHY_TEXT,
                                ),
                                'selector' => '{{WRAPPER}} .gsap-ew-text',
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

                $content = isset( $settings['text_content'] ) ? $settings['text_content'] : '';
                if ( '' === trim( wp_strip_all_tags( $content ) ) ) {
                        return;
                }

                $config = array(
                        'animation' => $settings['animation_type'],
                        'duration'  => isset( $settings['duration']['size'] ) ? (float) $settings['duration']['size'] : 0.9,
                        'delay'     => isset( $settings['delay']['size'] ) ? (float) $settings['delay']['size'] : 0,
                        'stagger'   => isset( $settings['stagger']['size'] ) ? (float) $settings['stagger']['size'] : 0.06,
                        'direction' => isset( $settings['reveal_direction'] ) ? $settings['reveal_direction'] : 'up',
                        'order'     => isset( $settings['reveal_order'] ) ? $settings['reveal_order'] : 'start',
                        'easing'    => $settings['easing'],
                        'trigger'   => $settings['trigger'],
                        'repeat'    => ( 'yes' === $settings['repeat'] ),
                );

                $this->add_render_attribute(
                        'wrapper',
                        array(
                                'class'          => 'gsap-ew-text gsap-ew-animated-text',
                                'data-gsap-type' => 'animated-text',
                                'data-gsap'      => wp_json_encode( $config ),
                        )
                );

                printf(
                        '<div %1$s>%2$s</div>',
                        $this->get_render_attribute_string( 'wrapper' ),
                        wp_kses_post( $content )
                );
        }
}
