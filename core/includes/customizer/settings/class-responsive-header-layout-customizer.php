<?php
/**
 * Header Customizer Options
 *
 * @package Responsive WordPress theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Responsive_Header_Layout_Customizer' ) ) :
	/**
	 * Header Customizer Options */
	class Responsive_Header_Layout_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {

			add_action( 'customize_register', array( $this, 'customizer_options' ) );

		}

		/**
		 * Customizer options
		 *
		 * @since 0.2
		 *
		 * @param  object $wp_customize WordPress customization option.
		 */
		public function customizer_options( $wp_customize ) {
			$wp_customize->add_section(
				'responsive_header_layout',
				array(
					'title'    => esc_html__( 'Primary Header', 'responsive' ),
					'panel'    => 'responsive_header',
					'priority' => 10,

				)
			);

			// Full Width Header.
			$header_full_width_label = __( 'Full Width Header', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'header_full_width', $header_full_width_label, 'responsive_header_layout', 10, 0, 'responsive_active_site_layout_contained', 'postMessage' );

			// Inline logo & site title.
			$inline_logo_site_title = __( 'Inline logo & Site Title', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'inline_logo_site_title', $inline_logo_site_title, 'responsive_header_layout', 10, 0, 'responsive_active_site_layout_contained', 'postMessage' );

			// Enable Header Bottom Border.
			$enable_header_bottom_border_label = __( 'Enable Header Bottom Border', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'enable_header_bottom_border', $enable_header_bottom_border_label, 'responsive_header_layout', 10, 1, null );

			/**
			 * Header Elements Positioning
			 */
			$wp_customize->add_setting(
				'responsive_header_elements',
				array(
					'default'           => array( 'site-branding', 'main-navigation' ),
					'sanitize_callback' => 'responsive_sanitize_multi_choices',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				new Responsive_Customizer_Sortable_Control(
					$wp_customize,
					'responsive_header_elements',
					array(
						'label'    => esc_html__( 'Header Elements', 'responsive' ),
						'section'  => 'responsive_header_layout',
						'settings' => 'responsive_header_elements',
						'priority' => 10,
						'choices'  => responsive_header_elements(),
					)
				)
			);

			// Header Layout.
			$header_layout_label   = esc_html__( 'Header Layout', 'responsive' );
			$header_layout_choices = array(
				'horizontal' => esc_html__( 'Horizontal', 'responsive' ),
				'vertical'   => esc_html__( 'Vertical', 'responsive' ),
			);
			responsive_select_control( $wp_customize, 'header_layout', $header_layout_label, 'responsive_header_layout', 20, $header_layout_choices, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_layout' ), null );

			// Header Height.
			$header_height_label = __( 'Header Height', 'responsive' );
			responsive_drag_number_control( $wp_customize, 'header_height', $header_height_label, 'responsive_header_layout', 20, 0, null, 300, 0, 'postMessage', 1 );

			// Header Alignment.
			$header_alignment_label   = esc_html__( 'Header Alignment', 'responsive' );
			$header_alignment_choices = array(
				'center' => esc_html__( 'Center', 'responsive' ),
				'left'   => esc_html__( 'Left', 'responsive' ),
				'right'  => esc_html__( 'Right', 'responsive' ),
			);

			if ( is_rtl() ) {
				$header_alignment_choices = array(
					'left'   => esc_html__( 'Right', 'responsive' ),
					'right'  => esc_html__( 'Left', 'responsive' ),
					'center' => esc_html__( 'center', 'responsive' ),
				);
			}
			responsive_select_control( $wp_customize, 'header_alignment', $header_alignment_label, 'responsive_header_layout', 30, $header_alignment_choices, Responsive\Core\get_responsive_customizer_defaults( 'responsive_header_alignment' ), 'responsive_active_vertical_header', 'postMessage' );

			// Mobile Header Layout.
			$mobile_header_layout_label = esc_html__( 'Mobile Header Layout', 'responsive' );
			responsive_select_control( $wp_customize, 'mobile_header_layout', $mobile_header_layout_label, 'responsive_header_layout', 30, $header_layout_choices, get_theme_mod( 'responsive_header_layout', 'horizontal' ), null );

			// Mobile Header Alignment.
			$mobile_header_alignment_label = esc_html__( 'Mobile Header Alignment', 'responsive' );
			responsive_select_control( $wp_customize, 'mobile_header_alignment', $mobile_header_alignment_label, 'responsive_header_layout', 35, $header_alignment_choices, get_theme_mod( 'responsive_header_alignment', 'center' ), 'responsive_active_mobile_vertical_header', 'postMessage' );

			// Logo Padding.
			$logo_padding_label = esc_html__( 'Logo Padding (px)', 'responsive' );
			responsive_padding_control( $wp_customize, 'header', 'responsive_header_layout', 40, Responsive\Core\get_responsive_customizer_defaults( 'logo_padding' ), 0, null, $logo_padding_label );

			// Bottom Border.
			$bottom_border_label = __( 'Bottom Border Size', 'responsive' );
			responsive_drag_number_control( $wp_customize, 'bottom_border', $bottom_border_label, 'responsive_header_layout', 45, 0, 'responsive_enable_header_bottom_border_check', 300, 0, 'postMessage', 1 );

			$header_builder_settings_separator_label = esc_html__( 'Header Builder Settings', 'responsive' );
			responsive_separator_control( $wp_customize, 'header_builder_settings_separator', $header_builder_settings_separator_label, 'responsive_header_layout', 75 );

			// HTML content.
			$header_html_content = __( 'HTML content', 'responsive' );
			responsive_text_control( $wp_customize, 'header_html_content', $header_html_content, 'responsive_header_layout', 80, '<p>Enter HTML here!</p>', null, 'sanitize_text_field', 'textarea' );

			$wpautop = __( 'Automatically Add Paragraphs', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'header_html_wpautop', $wpautop, 'responsive_header_layout', 85, 1 );

			// Mobile HTML content.
			$mobile_html_content = __( 'Mobile HTML content', 'responsive' );
			responsive_text_control( $wp_customize, 'mobile_html_content', $mobile_html_content, 'responsive_header_layout', 85, '<p>Enter HTML here!</p>', null, 'sanitize_text_field', 'textarea' );

			$mobile_wpautop = __( 'Automatically Add Paragraphs (Mobile)', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'mobile_html_wpautop', $mobile_wpautop, 'responsive_header_layout', 85, 1 );

			$header_desktop_tablet_mobile_layout_choices = array(
				'default'   => __( 'Standard', 'responsive' ),
				'fullwidth' => __( 'Full Width', 'responsive' ),
				'contained' => __( 'Contained', 'responsive' ),
			);

			$stretch_primary_navigation_label = __( 'Stretch Primary Menu?', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'stretch_primary_navigation', $stretch_primary_navigation_label, 'responsive_header_layout', 95, 0, null );

			$stretch_secondary_navigation_label = __( 'Stretch Secondary Menu?', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'stretch_secondary_navigation', $stretch_secondary_navigation_label, 'responsive_header_layout', 100, 0, null );

			$stretch_mobile_navigation_label = __( 'Stretch Mobile Menu?', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'stretch_mobile_navigation', $stretch_mobile_navigation_label, 'responsive_header_layout', 105, 0, null );

			$primary_navigation_fill_stretch_label = __( 'Fill and Center Primary Menu Items?', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'primary_navigation_fill_stretch', $primary_navigation_fill_stretch_label, 'responsive_header_layout', 110, 0, null );

			$secondary_navigation_fill_stretch_label = __( 'Fill and Center Secondary Menu Items?', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'secondary_navigation_fill_stretch', $secondary_navigation_fill_stretch_label, 'responsive_header_layout', 115, 0, null );

			$logo_layout_include_choices       = array(
				'logo'               => __( 'Logo', 'responsive' ),
				'logo_title'         => __( 'Logo & Title', 'responsive' ),
				'logo_title_tagline' => __( 'Logo, Title & Tagline', 'responsive' ),
			);
			$desktop_logo_layout_include_label = __( 'Desktop Logo Layout', 'responsive' );
			responsive_select_control( $wp_customize, 'desktop_logo_layout_include', $desktop_logo_layout_include_label, 'responsive_header_layout', 120, $logo_layout_include_choices, 'logo_title', null );
			$tablet_logo_layout_include_label = __( 'Tablet Logo Layout', 'responsive' );
			responsive_select_control( $wp_customize, 'tablet_logo_layout_include', $tablet_logo_layout_include_label, 'responsive_header_layout', 125, $logo_layout_include_choices, 'logo', null );
			$mobile_logo_layout_include_label = __( 'Mobile Logo Layout', 'responsive' );
			responsive_select_control( $wp_customize, 'mobile_logo_layout_include', $mobile_logo_layout_include_label, 'responsive_header_layout', 130, $logo_layout_include_choices, 'logo', null );

			$logo_layout_structure_choices       = array(
				'standard'           => __( 'Standard', 'responsive' ),
				'title_tag_logo'     => __( 'Title - Tagline - Logo', 'responsive' ),
				'top_logo_title_tag' => __( 'Top Logo - Title - Tagline', 'responsive' ),
				'top_title_tag_logo' => __( 'Top Title - Tagline - Logo', 'responsive' ),
				'top_title_logo_tag' => __( 'Top Title - Logo - Tagline', 'responsive' ),
			);
			$desktop_logo_layout_structure_label = __( 'Desktop Logo Layout Structure', 'responsive' );
			responsive_select_control( $wp_customize, 'desktop_logo_layout_structure', $desktop_logo_layout_structure_label, 'responsive_header_layout', 135, $logo_layout_structure_choices, 'standard', null );
			$tablet_logo_layout_structure_label = __( 'Tablet Logo Layout Structure', 'responsive' );
			responsive_select_control( $wp_customize, 'tablet_logo_layout_structure', $tablet_logo_layout_structure_label, 'responsive_header_layout', 140, $logo_layout_structure_choices, 'standard', null );
			$mobile_logo_layout_structure_label = __( 'Mobile Logo Layout Structure', 'responsive' );
			responsive_select_control( $wp_customize, 'mobile_logo_layout_structure', $mobile_logo_layout_structure_label, 'responsive_header_layout', 145, $logo_layout_structure_choices, 'standard', null );

			$header_primary_navigation_style_choices = array(
				'standard'             => __( 'Standard', 'responsive' ),
				'fullheight'           => __( 'Full height', 'responsive' ),
				'underline'            => __( 'Underline', 'responsive' ),
				'underline-fullheight' => __( 'Underline - Full height', 'responsive' ),
			);
			$header_primary_navigation_style_label   = __( 'Primary Navigation Style', 'responsive' );
			responsive_select_control( $wp_customize, 'primary_navigation_style', $header_primary_navigation_style_label, 'responsive_header_layout', 150, $header_primary_navigation_style_choices, 'standard', null );

			$dropdown_navigation_reveal_choices = array(
				'none'      => __( 'None', 'responsive' ),
				'fade'      => __( 'Fade', 'responsive' ),
				'fade-up'   => __( 'Fade Up', 'responsive' ),
				'fade-down' => __( 'Fade Down', 'responsive' ),
			);
			$dropdown_navigation_reveal_label   = __( 'Dropdown Reveal', 'responsive' );
			responsive_select_control( $wp_customize, 'dropdown_navigation_reveal', $dropdown_navigation_reveal_label, 'responsive_header_layout', 155, $dropdown_navigation_reveal_choices, 'none', null );

			// Button Label.
			$header_button_label = __( 'Button Label', 'responsive' );
			responsive_text_control( $wp_customize, 'header_button_label', $header_button_label, 'responsive_header_layout', 160, 'Button', null, 'sanitize_text_field', 'text' );

			// Header Button Link.
			$header_button_link = __( 'Button URL', 'responsive' );
			responsive_text_control( $wp_customize, 'header_button_link', $header_button_link, 'responsive_header_layout', 165, '', null, 'sanitize_text_field', 'text' );

			// Header Button Target.
			$header_button_target = __( 'Open in New Tab', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'header_button_target', $header_button_target, 'responsive_header_layout', 170, 0, null );

			// Header Button nofollow.
			$header_button_nofollow = __( 'Set link to nofollow', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'header_button_nofollow', $header_button_nofollow, 'responsive_header_layout', 175, 0, null );

			// Header Button sponsored.
			$header_button_sponsored = __( 'Set link attribute Sponsored', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'header_button_sponsored', $header_button_sponsored, 'responsive_header_layout', 180, 0, null );

			// Header Button download.
			$header_button_download = __( 'Set link to download', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'header_button_download', $header_button_download, 'responsive_header_layout', 185, 0, null );

			// Header Button size.
			$header_button_size_choices = array(
				'small'  => __( 'Small', 'responsive' ),
				'medium' => __( 'Medium', 'responsive' ),
				'large'  => __( 'large', 'responsive' ),
			);
			$header_button_size         = __( 'Header Button Size', 'responsive' );
			responsive_select_control( $wp_customize, 'header_button_size', $header_button_size, 'responsive_header_layout', 190, $header_button_size_choices, 'medium', null );

			// Header Button Style.
			$header_button_style_choices = array(
				'filled'  => __( 'Filled', 'responsive' ),
				'outline' => __( 'Outline', 'responsive' ),
			);
			$header_button_style         = __( 'Header Button Style.', 'responsive' );
			responsive_select_control( $wp_customize, 'header_button_style', $header_button_style, 'responsive_header_layout', 195, $header_button_style_choices, 'medium', null );

			// Header Button visibility.
			$header_button_visibility_choices = array(
				'everyone'  => __( 'Everyone', 'responsive' ),
				'loggedin'  => __( 'Logged In Only', 'responsive' ),
				'loggedout' => __( 'Logged Out Only', 'responsive' ),
			);
			$header_button_visibility         = __( 'Header Button Visibility', 'responsive' );
			responsive_select_control( $wp_customize, 'header_button_visibility', $header_button_visibility, 'responsive_header_layout', 200, $header_button_visibility_choices, 'everyone', null );

			// Mobile Button Label.
			$mobile_button_label = __( 'Mobile Button Label', 'responsive' );
			responsive_text_control( $wp_customize, 'mobile_button_label', $mobile_button_label, 'responsive_header_layout', 205, 'Button', null, 'sanitize_text_field', 'text' );

			// Mobile Header Button Link.
			$mobile_button_link = __( 'Mobile Button URL', 'responsive' );
			responsive_text_control( $wp_customize, 'mobile_button_link', $mobile_button_link, 'responsive_header_layout', 210, '', null, 'sanitize_text_field', 'text' );

			// Mobile Header Button Target.
			$mobile_button_target = __( 'Mobile Open in New Tab', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'mobile_button_target', $mobile_button_target, 'responsive_header_layout', 215, 0, null );

			// Mobile Header Button nofollow.
			$mobile_button_nofollow = __( 'Mobile Set link to nofollow', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'mobile_button_nofollow', $mobile_button_nofollow, 'responsive_header_layout', 220, 0, null );

			// Mobile Header Button sponsored.
			$mobile_button_sponsored = __( 'Mobile Set link attribute Sponsored', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'mobile_button_sponsored', $header_button_sponsored, 'responsive_header_layout', 225, 0, null );

			// Mobile Header Button download.
			$mobile_button_download = __( 'Mobile Set link to download', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'mobile_button_download', $mobile_button_download, 'responsive_header_layout', 230, 0, null );

			// Mobile Header Button size.
			$mobile_button_size_choices = array(
				'small'  => __( 'Small', 'responsive' ),
				'medium' => __( 'Medium', 'responsive' ),
				'large'  => __( 'large', 'responsive' ),
			);
			$mobile_button_size         = __( 'Mobile Header Button Size', 'responsive' );
			responsive_select_control( $wp_customize, 'mobile_button_size', $mobile_button_size, 'responsive_header_layout', 235, $header_button_size_choices, 'medium', null );

			// Mobile Header Button Style.
			$header_button_style_choices = array(
				'filled'  => __( 'Filled', 'responsive' ),
				'outline' => __( 'Outline', 'responsive' ),
			);
			$mobile_button_style         = __( 'Mobile Header Button Style.', 'responsive' );
			responsive_select_control( $wp_customize, 'mobile_button_style', $mobile_button_style, 'responsive_header_layout', 240, $header_button_style_choices, 'medium', null );

			// Mobile Header Button visibility.
			$mobile_button_visibility_choices = array(
				'everyone'  => __( 'Everyone', 'responsive' ),
				'loggedin'  => __( 'Logged In Only', 'responsive' ),
				'loggedout' => __( 'Logged Out Only', 'responsive' ),
			);
			$mobile_button_visibility         = __( 'Mobile Header Button Visibility', 'responsive' );
			responsive_select_control( $wp_customize, 'mobile_button_visibility', $mobile_button_visibility, 'responsive_header_layout', 245, $header_button_visibility_choices, 'everyone', null );

			// Mobile Menu Trigger Style.
			$mobile_trigger_style_choices = array(
				'default'  => __( 'Default', 'responsive' ),
				'bordered' => __( 'Outline', 'responsive' ),
			);
			$mobile_trigger_style         = __( 'Trigger Style', 'responsive' );
			responsive_select_control( $wp_customize, 'mobile_trigger_style', $mobile_trigger_style, 'responsive_header_layout', 250, $mobile_trigger_style_choices, 'everyone', null );

			// Mobile Menu Trigger Icon.
			$mobile_trigger_icon_choices = array(
				'menu'  => __( 'Icon 1', 'responsive' ),
				'menu2' => __( 'Icon 2', 'responsive' ),
				'menu3' => __( 'Icon 3', 'responsive' ),
			);
			$mobile_trigger_icon         = __( 'Trigger Icon', 'responsive' );
			responsive_select_control( $wp_customize, 'mobile_trigger_icon', $mobile_trigger_icon, 'responsive_header_layout', 255, $mobile_trigger_icon_choices, 'menu', null );

			// Mobile Menu Layout.
			$header_popup_layout_choices = array(
				'sidepanel' => __( 'Sidepanel', 'responsive' ),
				'fullwidth' => __( 'Fullwidth', 'responsive' ),
			);
			$header_popup_layout         = __( 'Menu Drawer Layout', 'responsive' );
			responsive_select_control( $wp_customize, 'header_popup_layout', $header_popup_layout, 'responsive_header_layout', 260, $header_popup_layout_choices, 'sidepanel', null );

			// Sidepanel Popup Side.
			$header_popup_side_choices = array(
				'right' => __( 'Right', 'responsive' ),
				'left'  => __( 'Left', 'responsive' ),
			);
			$header_popup_side         = __( 'Sidepanel Popup Side', 'responsive' );
			responsive_select_control( $wp_customize, 'header_popup_side', $header_popup_side, 'responsive_header_layout', 265, $header_popup_side_choices, 'right', 'is_sidepanel_active' );

			// Fullwidth Menu Layout Animation.
			$header_popup_animation_choices = array(
				'fade'  => __( 'Fade', 'responsive' ),
				'scale' => __( 'Scale', 'responsive' ),
				'slice' => __( 'Slice', 'responsive' ),
			);
			$header_popup_animation         = __( 'Fullwidth Menu Animation', 'responsive' );
			responsive_select_control( $wp_customize, 'header_popup_animation', $header_popup_animation, 'responsive_header_layout', 270, $header_popup_animation_choices, 'fade', 'is_fullwidth_active' );

			// Menu Content Alignment.
			$header_popup_content_align_choices = array(
				'left'   => __( 'Left', 'responsive' ),
				'center' => __( 'Center', 'responsive' ),
				'right'  => __( 'Right', 'responsive' ),

			);
			$header_popup_content_align = __( 'Menu Content Alignment', 'responsive' );
			responsive_select_control( $wp_customize, 'header_popup_content_align', $header_popup_content_align, 'responsive_header_layout', 275, $header_popup_content_align_choices, 'left', null );

			// Menu Content Vertical Alignment.
			$header_popup_vertical_align_choices = array(
				'top'    => __( 'Top', 'responsive' ),
				'middle' => __( 'Middle', 'responsive' ),
				'bottom' => __( 'Bottom', 'responsive' ),

			);
			$header_popup_vertical_align = __( 'Menu Content Vertical Alignment', 'responsive' );
			responsive_select_control( $wp_customize, 'header_popup_vertical_align', $header_popup_vertical_align, 'responsive_header_layout', 280, $header_popup_vertical_align_choices, 'top', null );

			// Collapse Submenu Items.
			$mobile_navigation_collapse = __( 'Collapse Submenu Items?', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'mobile_navigation_collapse', $mobile_navigation_collapse, 'responsive_header_layout', 285, 1, null );

			// Entire parent menu item expands sub menu.
			$mobile_navigation_parent_toggle = __( 'Parent menu item expands sub menu', 'responsive' );
			responsive_checkbox_control( $wp_customize, 'mobile_navigation_parent_toggle', $mobile_navigation_parent_toggle, 'responsive_header_layout', 290, 0, 'is_menu_collapsible' );

		}
	}

endif;

return new Responsive_Header_Layout_Customizer();
