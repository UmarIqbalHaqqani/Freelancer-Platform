<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Freeio_Elementor_Freeio_Freelancers extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_element_freeio_freelancers';
    }

	public function get_title() {
        return esc_html__( 'Apus Freelancers', 'freeio' );
    }
    
	public function get_categories() {
        return [ 'freeio-elements' ];
    }

    public function get_tax_keys() {
        return array('category', 'location');
    }

	protected function register_controls() {

        $meta_obj = WP_Freeio_Freelancer_Meta::get_instance(0);

        $fields = $meta_obj->get_metas();

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Freelancers', 'freeio' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'freeio' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your title here', 'freeio' ),
            ]
        );

        $this->add_control(
            'des',
            [
                'label' => esc_html__( 'Description', 'freeio' ),
                'type' => Elementor\Controls_Manager::TEXTAREA,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter your description here', 'freeio' ),
            ]
        );

        $tax_keys = $this->get_tax_keys();
        foreach( $tax_keys as $tax_key ) {
            if ( $meta_obj->check_post_meta_exist($tax_key) ) {
                $this->add_control(
                    $tax_key.'_slugs',
                    [
                        'label' => sprintf(esc_html__( '%s Slug', 'freeio' ), $fields[WP_FREEIO_FREELANCER_PREFIX.$tax_key]['name']),
                        'type' => Elementor\Controls_Manager::TEXTAREA,
                        'rows' => 1,
                        'default' => '',
                        'placeholder' => esc_html__( 'Enter slugs spearate by comma(,)', 'freeio' ),
                    ]
                );
            }
        }

        $this->add_control(
            'limit',
            [
                'label' => esc_html__( 'Limit', 'freeio' ),
                'type' => Elementor\Controls_Manager::NUMBER,
                'input_type' => 'number',
                'description' => esc_html__( 'Limit jobs to display', 'freeio' ),
                'default' => 4
            ]
        );
        
        $this->add_control(
            'orderby',
            [
                'label' => esc_html__( 'Order by', 'freeio' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'freeio'),
                    'date' => esc_html__('Date', 'freeio'),
                    'ID' => esc_html__('ID', 'freeio'),
                    'author' => esc_html__('Author', 'freeio'),
                    'title' => esc_html__('Title', 'freeio'),
                    'modified' => esc_html__('Modified', 'freeio'),
                    'rand' => esc_html__('Random', 'freeio'),
                    'comment_count' => esc_html__('Comment count', 'freeio'),
                    'menu_order' => esc_html__('Menu order', 'freeio'),
                ),
                'default' => ''
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Sort order', 'freeio' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'freeio'),
                    'ASC' => esc_html__('Ascending', 'freeio'),
                    'DESC' => esc_html__('Descending', 'freeio'),
                ),
                'default' => ''
            ]
        );

        $this->add_control(
            'get_freelancers_by',
            [
                'label' => esc_html__( 'Get Freelancers By', 'freeio' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'featured' => esc_html__('Featured Freelancers', 'freeio'),
                    'urgent' => esc_html__('Urgent Freelancers', 'freeio'),
                    'recent' => esc_html__('Recent Freelancers', 'freeio'),
                ),
                'default' => 'recent'
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => esc_html__( 'Layout', 'freeio' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'grid' => esc_html__('Grid', 'freeio'),
                    'carousel' => esc_html__('Carousel', 'freeio'),
                ),
                'default' => 'carousel'
            ]
        );

        $this->add_control(
            'item_type',
            [
                'label' => esc_html__( 'Item Style', 'freeio' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'grid' => esc_html__('Grid', 'freeio'),
                    'grid-v1' => esc_html__('Grid 1', 'freeio'),
                    'grid-v2' => esc_html__('Grid 2', 'freeio'),
                    'grid-v3' => esc_html__('Grid 3', 'freeio'),
                    'list' => esc_html__('List', 'freeio'),
                ),
                'default' => 'grid'
            ]
        );

        $columns = range( 1, 12 );
        $columns = array_combine( $columns, $columns );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__( 'Columns', 'freeio' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => $columns,
                'frontend_available' => true,
                'default' => 3,
            ]
        );

        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label' => esc_html__( 'Slides to Scroll', 'freeio' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'description' => esc_html__( 'Set how many slides are scrolled per swipe.', 'freeio' ),
                'options' => $columns,
                'condition' => [
                    'columns!' => '1',
                    'layout_type' => 'carousel',
                ],
                'frontend_available' => true,
                'default' => 1,
            ]
        );

        $this->add_control(
            'rows',
            [
                'label' => esc_html__( 'Rows', 'freeio' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'number',
                'placeholder' => esc_html__( 'Enter your rows number here', 'freeio' ),
                'default' => 1,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_nav',
            [
                'label'         => esc_html__( 'Show Navigation', 'freeio' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'freeio' ),
                'label_off'     => esc_html__( 'Hide', 'freeio' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label'         => esc_html__( 'Show Pagination', 'freeio' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Show', 'freeio' ),
                'label_off'     => esc_html__( 'Hide', 'freeio' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'style_pagination',
            [
                'label'         => esc_html__( 'Style Pagination', 'freeio' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '' => esc_html__('Default', 'freeio'),
                    'style_white' => esc_html__('White', 'freeio'),
                ),
                'default' => '',
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label'         => esc_html__( 'Autoplay', 'freeio' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'freeio' ),
                'label_off'     => esc_html__( 'No', 'freeio' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'infinite_loop',
            [
                'label'         => esc_html__( 'Infinite Loop', 'freeio' ),
                'type'          => Elementor\Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'freeio' ),
                'label_off'     => esc_html__( 'No', 'freeio' ),
                'return_value'  => true,
                'default'       => true,
                'condition' => [
                    'layout_type' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'view_all',
            [
                'label' => esc_html__( 'View All', 'freeio' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => esc_html__( 'Hide', 'freeio' ),
                'label_off' => esc_html__( 'Show', 'freeio' ),
            ]
        );

        $this->add_control(
            'button_type',
            [
                'label' => esc_html__( 'Style Button', 'freeio' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'st1' => esc_html__('Style 1', 'freeio'),
                    'st2' => esc_html__('Style 2', 'freeio'),
                ),
                'default' => 'st1',
                'condition' => [
                    'view_all' => ['yes'],
                ]
            ]
        );

        $this->add_control(
            'text_view',
            [
                'label' => esc_html__( 'Text View All', 'freeio' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'default' => 'Browse All',
                'condition' => [
                    'view_all' => ['yes'],
                ]
            ]
        );

        $this->add_control(
            'link_view',
            [
                'label' => esc_html__( 'View Link', 'freeio' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => esc_html__( 'Enter your Link here', 'freeio' ),
                'condition' => [
                    'view_all' => ['yes'],
                ]
            ]
        );
        
   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'freeio' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'freeio' ),
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_header_style',
            [
                'label' => esc_html__( 'Header', 'freeio' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'freeio' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'des_color',
            [
                'label' => esc_html__( 'Description Color', 'freeio' ),
                'type' => Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .des' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_options',
            [
                'label' => esc_html__( 'Button', 'freeio' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( 'tabs_button_header_style' );

            $this->start_controls_tab(
                'tab_button_header_header_normal',
                [
                    'label' => esc_html__( 'Normal', 'freeio' ),
                ]
            );

            $this->add_control(
                'button_header_color',
                [
                    'label' => esc_html__( 'Color', 'freeio' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .view_more .btn' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_header_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'freeio' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .view_more .btn' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'border_button_header',
                    'label' => esc_html__( 'Border', 'freeio' ),
                    'selector' => '{{WRAPPER}} .view_more .btn',
                ]
            );

            $this->end_controls_tab();

            // tab hover
            $this->start_controls_tab(
                'tab_button_header_header_hover',
                [
                    'label' => esc_html__( 'Hover', 'freeio' ),
                ]
            );

            $this->add_control(
                'button_header_color_hv',
                [
                    'label' => esc_html__( 'Color', 'freeio' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .view_more .btn:hover' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .view_more .btn:focus' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_header_bg_color_hv',
                [
                    'label' => esc_html__( 'Background Color', 'freeio' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .view_more .btn:hover' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .view_more .btn:focus' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'border_button_header_hv',
                    'label' => esc_html__( 'Border', 'freeio' ),
                    'selector' => '{{WRAPPER}} .view_more .btn:hover',
                    'selector' => '{{WRAPPER}} .view_more .btn:focus',
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();
        // end tab 

        $this->end_controls_section();

        $this->start_controls_section(
            'section_item_style',
            [
                'label' => esc_html__( 'Item', 'freeio' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_item_style' );

            $this->start_controls_tab(
                'tab_item_normal',
                [
                    'label' => esc_html__( 'Normal', 'freeio' ),
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'border_box',
                    'label' => esc_html__( 'Border', 'freeio' ),
                    'selector' => '{{WRAPPER}} .freelancer-item',
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'box_shadow',
                    'label' => esc_html__( 'Box Shadow', 'freeio' ),
                    'selector' => '{{WRAPPER}} .freelancer-item',
                ]
            );

            $this->end_controls_tab();

            // tab hover
            $this->start_controls_tab(
                'tab_item_hover',
                [
                    'label' => esc_html__( 'Hover', 'freeio' ),
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'border_box_hv',
                    'label' => esc_html__( 'Border', 'freeio' ),
                    'selector' => '{{WRAPPER}} .freelancer-item:hover',
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'box_shadow_hv',
                    'label' => esc_html__( 'Box Shadow', 'freeio' ),
                    'selector' => '{{WRAPPER}} .freelancer-item:hover',
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();
        // end tab 

        $this->end_controls_section();


        $this->start_controls_section(
            'section_button_style',
            [
                'label' => esc_html__( 'Button', 'freeio' ),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

            $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => esc_html__( 'Normal', 'freeio' ),
                ]
            );

            $this->add_control(
                'button_color',
                [
                    'label' => esc_html__( 'Color', 'freeio' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .freelancer-item .btn' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_bg_color',
                [
                    'label' => esc_html__( 'Background Color', 'freeio' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .freelancer-item .btn' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'border_button',
                    'label' => esc_html__( 'Border', 'freeio' ),
                    'selector' => '{{WRAPPER}} .freelancer-item .btn',
                ]
            );


            $this->end_controls_tab();

            // tab hover
            $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => esc_html__( 'Hover', 'freeio' ),
                ]
            );

            $this->add_control(
                'button_color_hv',
                [
                    'label' => esc_html__( 'Color', 'freeio' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .freelancer-item .btn:hover' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .freelancer-item:hover .btn' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'button_bg_color_hv',
                [
                    'label' => esc_html__( 'Background Color', 'freeio' ),
                    'type' => Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .freelancer-item .btn:hover' => 'background-color: {{VALUE}};',
                        '{{WRAPPER}} .freelancer-item:hover .btn' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'border_button_hv',
                    'label' => esc_html__( 'Border', 'freeio' ),
                    'selector' => '{{WRAPPER}} .freelancer-item .btn:hover',
                    'selector' => '{{WRAPPER}} .freelancer-item:hover .btn',
                ]
            );

            $this->end_controls_tab();

        $this->end_controls_tabs();
        // end tab 
        $this->add_control(
            'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'freeio' ),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .freelancer-item .btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );

        $args = array(
            'limit' => $limit,
            'get_freelancers_by' => $get_freelancers_by,
            'orderby' => $orderby,
            'order' => $order,
        );

        $tax_keys = $this->get_tax_keys();
        foreach( $tax_keys as $tax_key ) {
            $args[$tax_key] = !empty($settings[$tax_key.'_slugs']) ? array_map('trim', explode(',', $settings[$tax_key.'_slugs'])) : array();
        }
        
        $loop = freeio_get_freelancers($args);
        if ( $loop->have_posts() ) {
            $columns = !empty($columns) ? $columns : 3;
            $columns_tablet = !empty($columns_tablet) ? $columns_tablet : 2;
            $columns_mobile = !empty($columns_mobile) ? $columns_mobile : 1;
            
            $slides_to_scroll = !empty($slides_to_scroll) ? $slides_to_scroll : $columns;
            $slides_to_scroll_tablet = !empty($slides_to_scroll_tablet) ? $slides_to_scroll_tablet : $slides_to_scroll;
            $slides_to_scroll_mobile = !empty($slides_to_scroll_mobile) ? $slides_to_scroll_mobile : 1;

            ?>
            <div class="widget-freelancers <?php echo esc_attr($layout_type); ?> <?php echo esc_attr($el_class); ?>">
                <?php if ( $title || ($view_more_text && $view_more_url) ) { ?>
                    <div class="top-widget-info d-md-flex align-items-end">
                        <div class="inner-left">
                            <?php if ( $title ) { ?>
                                <h2 class="widget-title"><?php echo trim($title); ?></h2>
                            <?php } ?>
                            <?php if ( !empty($des) ) { ?>
                                <div class="des"><?php echo esc_html($des); ?></div>
                            <?php } ?>
                        </div>
                        <?php if ( $view_all == 'yes' && !(empty($link_view)) && !(empty($text_view)) ) { ?>
                            <div class="view_more ms-auto">
                                <?php if( $button_type == 'st1' ) { ?>
                                    <a href="<?php echo esc_url( $link_view ); ?>" class="btn btn-view">
                                        <?php echo esc_html($text_view); ?><i class="flaticon-right-arrow-1 next"></i>
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo esc_url( $link_view ); ?>" class="btn btn-small btn-theme-rgba10 radius-50">
                                        <?php echo esc_html($text_view); ?><i class="flaticon-right-up next"></i>
                                    </a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="widget-content">
                    <?php if ( $layout_type == 'carousel' ): ?>
                        <div class="slick-carousel <?php echo esc_attr( ( ($columns * $rows) >= ($loop->post_count)) ? 'hidden-dots':'' ); ?>
                        <?php echo esc_attr($style_pagination); ?>"
                            data-items="<?php echo esc_attr($columns); ?>"
                            data-large="<?php echo esc_attr( $columns_tablet ); ?>"
                            data-medium="<?php echo esc_attr( $columns_tablet ); ?>"
                            data-small="<?php echo esc_attr($columns_mobile); ?>"
                            data-smallest="<?php echo esc_attr($columns_mobile); ?>"

                            data-slidestoscroll="<?php echo esc_attr($slides_to_scroll); ?>"
                            data-slidestoscroll_large="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>"
                            data-slidestoscroll_medium="<?php echo esc_attr( $slides_to_scroll_tablet ); ?>"
                            data-slidestoscroll_small="<?php echo esc_attr($slides_to_scroll_mobile); ?>"
                            data-slidestoscroll_smallest="<?php echo esc_attr($slides_to_scroll_mobile); ?>"

                            data-pagination="<?php echo esc_attr( $show_pagination ? 'true' : 'false' ); ?>"
                            data-nav="<?php echo esc_attr( $show_nav ? 'true' : 'false' ); ?>"
                            data-rows="<?php echo esc_attr( $rows ); ?>"
                            data-infinite="<?php echo esc_attr( $infinite_loop ? 'true' : 'false' ); ?>"
                            data-autoplay="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>">
                            <?php while ( $loop->have_posts() ): $loop->the_post(); ?>
                                <div class="item">
                                    <?php get_template_part( 'template-jobs/freelancers-styles/inner', $item_type); ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <?php
                            $mdcol = 12/$columns;
                            $smcol = 12/$columns_tablet;
                            $xscol = 12/$columns_mobile;
                        ?>
                        <div class="row">
                            <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                                <div class="col-lg-<?php echo esc_attr($mdcol); ?> col-md-<?php echo esc_attr($smcol); ?> col-<?php echo esc_attr( $xscol ); ?>">
                                    <?php get_template_part( 'template-jobs/freelancers-styles/inner', $item_type ); ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
            <?php
        }
    }
}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Freeio_Elementor_Freeio_Freelancers );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Freeio_Elementor_Freeio_Freelancers );
}