<?php
/**
 * Exit if accessed directly.
 *
 * @package Responsive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Define constants.
 */
define( 'RESPONSIVE_THEME_VERSION', '4.0.0' );
define( 'RESPONSIVE_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'RESPONSIVE_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );
/**
 *
 * WARNING: Please do not edit this file in any way
 *
 * Load the theme function files
 */
global $responsive_blog_layout_columns;
$responsive_blog_layout_columns = array( 'blog-2-col', 'blog-3-col', 'blog-4-col' );

$responsive_template_directory = get_template_directory();
require $responsive_template_directory . '/core/includes/functions.php';
require $responsive_template_directory . '/core/includes/functions-update.php';
require $responsive_template_directory . '/core/includes/functions-about.php';
require $responsive_template_directory . '/core/includes/functions-sidebar.php';
require $responsive_template_directory . '/core/includes/functions-install.php';
require $responsive_template_directory . '/core/includes/functions-admin.php';
require $responsive_template_directory . '/core/includes/functions-extras.php';
require $responsive_template_directory . '/core/includes/functions-extentions.php';
require $responsive_template_directory . '/core/includes/theme-options/theme-options.php';
require $responsive_template_directory . '/core/includes/post-custom-meta.php';
require $responsive_template_directory . '/core/includes/hooks.php';
require $responsive_template_directory . '/core/includes/version.php';
require $responsive_template_directory . '/core/includes/customizer/controls/typography/webfonts.php';
require $responsive_template_directory . '/core/includes/customizer/helper.php';
require $responsive_template_directory . '/core/includes/customizer/customizer.php';
require $responsive_template_directory . '/core/includes/customizer/custom-styles.php';
require $responsive_template_directory . '/core/includes/compatibility/woocommerce/class-responsive-woocommerce.php';
require $responsive_template_directory . '/admin/admin-functions.php';
require $responsive_template_directory . '/core/includes/classes/class-responsive-blog-markup.php';
require $responsive_template_directory . '/core/includes/classes/class-responsive-mobile-menu-markup.php';
require $responsive_template_directory . '/core/gutenberg/gutenberg-support.php';

/**
 * Return value of the supplied responsive free theme option.
 *
 * @param  array   $option  options.
 * @param  boolean $default flag.
 */
function responsive_free_get_option( $option, $default = false ) {
	global $responsive_options;

	// If the option is set then return it's value, otherwise return false.
	if ( isset( $responsive_options[ $option ] ) ) {
		return $responsive_options[ $option ];
	}

	return $default;
}



/**
 * Responsive_free_setup
 */
function responsive_free_setup() {
	add_theme_support( 'title-tag' );
	// Adding Gutenberg support.
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'core/css/gutenberg-editor.css' );
	// Gutenberg editor color palette.
	add_theme_support( 'editor-color-palette', responsive_gutenberg_color_palette() );
	$small_font_sizes  = get_theme_mod( 'post_meta_typography' );
	$normal_sizes      = get_theme_mod( 'body_typography' );
	$larger_font_sizes = get_theme_mod( 'heading_h1_typography' );
	$large_font_sizes  = get_theme_mod( 'heading_h2_typography' );

	if ( isset( $small_font_sizes['font-size'] ) ) {
		$small_font_sizes_default_value = ( $small_font_sizes && isset( $small_font_sizes['font-size'] ) ) ? str_replace( 'px', '', $small_font_sizes['font-size'] ) : '12';
	} else {
		$small_font_sizes_default_value = 12;
	}
	if ( isset( $normal_sizes['font-size'] ) ) {
		$normal_sizes_default_value = ( $normal_sizes && isset( $normal_sizes['font-size'] ) ) ? str_replace( 'px', '', $normal_sizes['font-size'] ) : '14';
	} else {
		$normal_sizes_default_value = 14;
	}
	if ( isset( $larger_font_sizes['font-size'] ) ) {
		if ( strpos( $larger_font_sizes['font-size'], 'px' ) == false ) {
			$larger_font_sizes_default_value = ( $larger_font_sizes && isset( $larger_font_sizes['font-size'] ) ) ? str_replace( array( 'em', 'rem' ), '', $larger_font_sizes['font-size'] ) : '2.625';
			$larger_font_sizes_default_value = $normal_sizes_default_value * $larger_font_sizes_default_value;
		} else {
			$larger_font_sizes_default_value = str_replace( 'px', '', $larger_font_sizes['font-size'] );

		}
	} else {
		$larger_font_sizes_default_value = 40;
	}

	if ( isset( $large_font_sizes['font-size'] ) ) {
		if ( strpos( $large_font_sizes['font-size'], 'px' ) == false ) {
			$large_font_sizes_default_value = ( $large_font_sizes && isset( $large_font_sizes['font-size'] ) ) ? str_replace( array( 'em', 'rem' ), '', $large_font_sizes['font-size'] ) : '2.250';
			$large_font_sizes_default_value = $normal_sizes_default_value * $large_font_sizes_default_value;
		} else {
			$large_font_sizes_default_value = str_replace( 'px', '', $large_font_sizes['font-size'] );

		}
	} else {
		$large_font_sizes_default_value = 32;
	}
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name'      => _x( 'Small', 'Name of the small font size in the block editor', 'responsive' ),
				'shortName' => _x( 'S', 'Short name of the small font size in the block editor.', 'responsive' ),
				'size'      => $small_font_sizes_default_value,
				'slug'      => 'small',
			),
			array(
				'name'      => _x( 'Regular', 'Name of the regular font size in the block editor', 'responsive' ),
				'shortName' => _x( 'M', 'Short name of the regular font size in the block editor.', 'responsive' ),
				'size'      => $normal_sizes_default_value,
				'slug'      => 'normal',
			),
			array(
				'name'      => _x( 'Large', 'Name of the large font size in the block editor', 'responsive' ),
				'shortName' => _x( 'L', 'Short name of the large font size in the block editor.', 'responsive' ),
				'size'      => $large_font_sizes_default_value,
				'slug'      => 'large',
			),
			array(
				'name'      => _x( 'Larger', 'Name of the larger font size in the block editor', 'responsive' ),
				'shortName' => _x( 'XL', 'Short name of the larger font size in the block editor.', 'responsive' ),
				'size'      => $larger_font_sizes_default_value,
				'slug'      => 'larger',
			),
		)
	);
}
add_action( 'after_setup_theme', 'responsive_free_setup' );

add_filter( 'body_class', 'responsive_add_site_layout_classes' );

/**
 * [responsive_add_site_layout_classes description]
 *
 * @param array $classes Class.
 */
function responsive_add_site_layout_classes( $classes ) {
	global $responsive_options;
	$page_layout   = get_theme_mod( 'page_layout_option' );
	$blog_layout   = get_theme_mod( 'blog_layout_option' );
	$single_layout = get_theme_mod( 'single_layout_option' );
	if ( ! empty( $responsive_options['site_layout_option'] ) ) :
		$classes[] = $responsive_options['site_layout_option'];

	endif;

	if ( ! empty( $page_layout ) ) :
		$classes[] = 'page-' . $page_layout;
	endif;

	if ( ! empty( $blog_layout ) ) :
		$classes[] = 'blog-' . $blog_layout;

	endif;

	if ( ! empty( $single_layout ) ) :
		$classes[] = 'single-' . $single_layout;

	endif;

	return $classes;
}

/**
 * Add menu style class to body tag
 *
 * @param array $classes Class.
 */
function responsive_menu_style_layout_classes( $classes ) {
	// Handle mobile menu.
	$menu_style = get_theme_mod( 'mobile_menu_style' );
	if ( $menu_style ) {
		$menu_style_class = 'responsive-mobile-' . $menu_style;
	} else {
		$menu_style_class = 'responsive-mobile-dropdown';
	}
	$classes[] = $menu_style_class;

	return $classes;
}

add_filter( 'body_class', 'responsive_menu_style_layout_classes' );

$responsive_options = responsive_get_options();

if ( isset( $responsive_options['sticky-header'] ) && $responsive_options['sticky-header'] == '1' ) {
	add_action( 'wp_footer', 'responsive_fixed_menu_onscroll' );
	function responsive_fixed_menu_onscroll() {
		?>
	<script type="text/javascript">
	window.addEventListener("scroll", responsiveStickyHeader);

	function responsiveStickyHeader() {
		if (document.documentElement.scrollTop > 0 ) {
			document.getElementById("header_section").classList.add( 'sticky-header' );
		} else {
			document.getElementById("header_section").classList.remove( 'sticky-header' );
		}
	}
	</script>
		<?php
	}
}

if ( ! defined( 'ELEMENTOR_PARTNER_ID' ) ) {
	define( 'ELEMENTOR_PARTNER_ID', 2126 );
}
function responsiveedit_customize_register( $wp_customize ) {
	$wp_customize->selective_refresh->add_partial(
		'blogname',
		array(
			'selector' => '.site-name a',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'blogdescription',
		array(
			'selector' => '.site-description',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[home_headline]',
		array(
			'selector' => '.featured-title',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[home_subheadline]',
		array(
			'selector' => '.featured-subtitle',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[cta_text]',
		array(
			'selector' => '.call-to-action',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[banner_image]',
		array(
			'selector' => '#featured',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[about_title]',
		array(
			'selector' => '#about_div .section_title',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[about_text]',
		array(
			'selector' => '.about_text',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[about_cta_text]',
		array(
			'selector' => '.about-cta-button',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[feature_title]',
		array(
			'selector' => '#feature_div .section_title',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[testimonial_title]',
		array(
			'selector' => '#testimonial_div .section_title',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[team_title]',
		array(
			'selector' => '#team_div .section_title',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'nav_menu_locations[top]',
		array(
			'selector' => '.main-nav',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'sidebars_widgets[home-widget-1]',
		array(
			'selector' => '#home_widget_1',

		)
	);
	$wp_customize->selective_refresh->add_partial(
		'sidebars_widgets[home-widget-2]',
		array(
			'selector' => '#home_widget_2',

		)
	);
	$wp_customize->selective_refresh->add_partial(
		'sidebars_widgets[home-widget-3]',
		array(
			'selector' => '#home_widget_3',

		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[featured_content]',
		array(
			'selector' => '#featured-image',

		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[home_content_area]',
		array(
			'selector' => '#featured-content p',

		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[copyright_textbox]',
		array(
			'selector' => '.copyright',

		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[contact_title]',
		array(
			'selector' => '.contact_title',
		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[contact_subtitle]',
		array(
			'selector' => '.contact_subtitle',

		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[contact_add]',
		array(
			'selector' => '.contact_add',

		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[contact_email]',
		array(
			'selector' => '.contact_email',

		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[contact_ph]',
		array(
			'selector' => '.contact_ph',

		)
	);
	$wp_customize->selective_refresh->add_partial(
		'responsive_theme_options[contact_content]',
		array(
			'selector' => '.contact_right',

		)
	);
	$wp_customize->selective_refresh->add_partial(
		'header_image',
		array(
			'selector' => '#site-branding',

		)
	);
}
add_action( 'customize_register', 'responsiveedit_customize_register' );
add_theme_support( 'customize-selective-refresh-widgets' );

function responsive_custom_category_widget( $arg ) {
	$cat = get_theme_mod( 'exclude_post_cat' );

	if ( $cat ) {
		$cat            = array_diff( array_unique( $cat ), array( '' ) );
		$arg['exclude'] = $cat;
	}
	return $arg;
}
add_filter( 'widget_categories_args', 'responsive_custom_category_widget' );
add_filter( 'widget_categories_dropdown_args', 'responsive_custom_category_widget' );

function responsive_exclude_post_cat_recentpost_widget( $array ) {
	$s   = '';
	$i   = 1;
	$cat = get_theme_mod( 'exclude_post_cat' );

	if ( $cat ) {
		$cat = array_diff( array_unique( $cat ), array( '' ) );
		foreach ( $cat as $c ) {
			$i++;
			$s .= '-' . $c;
			if ( count( $cat ) >= $i ) {
				$s .= ', ';
			}
		}
	}

	$array['cat'] = array( $s );

	return $array;
}
add_filter( 'widget_posts_args', 'responsive_exclude_post_cat_recentpost_widget' );

if ( ! function_exists( 'responsive_page_featured_image' ) ) :

	function responsive_page_featured_image() {
		// check if the page has a Post Thumbnail assigned to it.
		$responsive_options = responsive_get_options();
		if ( has_post_thumbnail() && 1 == $responsive_options['featured_images'] ) {
			?>
						<div class="featured-image">
							<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'responsive' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" <?php responsive_schema_markup( 'url' ); ?>>
								<?php	the_post_thumbnail(); ?>
							</a>
						</div>
					<?php
		}
	}

endif;

/**
 * Exclude post with Category from blog and archive page.
 */
if ( ! function_exists( 'responsive_exclude_post_cat' ) ) :
	/**
	 * Exclude post with Category from blog and archive page.
	 *
	 * @param  object $query Query.
	 */
	function responsive_exclude_post_cat( $query ) {
		$responsive_options = responsive_get_options();
		$cat                = get_theme_mod( 'exclude_post_cat' );

		if ( $cat && ! is_admin() && $query->is_main_query() ) {
			if ( ! array( $cat ) ) {
				$cat = array( $cat );
			}
			$cat = array_diff( array_unique( $cat ), array( '' ) );
			if ( $query->is_home() || $query->is_archive() ) {
				$query->set( 'category__not_in', $cat );
			}
		}
	}
endif;
add_action( 'pre_get_posts', 'responsive_exclude_post_cat', 10 );

if ( ! function_exists( 'responsive_get_attachment_id_from_url' ) ) :
	function responsive_get_attachment_id_from_url( $attachment_url = '' ) {
		global $wpdb;
		$attachment_id = false;
		// If there is no url, return.
		if ( '' == $attachment_url ) {
			return;
		}
		// Get the upload directory paths.
		$upload_dir_paths = wp_upload_dir();

		// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image.
		if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

			// If this is the URL of an auto-generated thumbnail, get the URL of the original image.
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

			// Remove the upload path base directory from the attachment URL.
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

			// Finally, run a custom database query to get the attachment ID from the modified attachment URL.
			$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = %s AND wposts.post_type = 'attachment'", $attachment_url ) );
		}
		return $attachment_id;
	}
endif;


/* Lightbox support for woocommerce templates */
	$responsive_options = responsive_get_options();
if ( isset( $responsive_options['override_woo'] ) && 1 == $responsive_options['override_woo'] ) {
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}


/**
 * Enqueue customizer styling
 */
function responsive_controls_style() {
	wp_enqueue_style( 'responsive-blocks', get_stylesheet_directory_uri() . '/core/css/customizer.css', RESPONSIVE_THEME_VERSION, 'all' );
}

add_action( 'customize_controls_print_styles', 'responsive_controls_style' );

/**
 * Add rating links to the admin dashboard
 *
 * @param string $footer_text The existing footer text
 *
 * @return      string
 * @since        2.0.6
 * @global        string $typenow
 */
function responsive_admin_rate_us( $footer_text ) {
	$page        = isset( $_GET['page'] ) ? $_GET['page'] : '';
	$show_footer = array( 'responsive-options' );

	if ( in_array( $page, $show_footer, true ) ) {
		$rate_text = sprintf(
			/* translators: %s: Link to 5 star rating */
			__( 'If you like <strong>Responsive Theme</strong> please leave us a %s rating. It takes a minute and helps a lot. Thanks in advance!', 'responsive' ),
			'<a href="https://wordpress.org/support/view/theme-reviews/responsive?filter=5#postform" target="_blank" class="responsive-rating-link" style="text-decoration:none;" data-rated="' . esc_attr__( 'Thanks :)', 'responsive' ) . '">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
		);

		return $rate_text;
	} else {
		return $footer_text;
	}
}

add_filter( 'admin_footer_text', 'responsive_admin_rate_us' );



/**
 * Include menu.
 */
function responsive_display_menu_outside_container() {

	wp_nav_menu(
		array(
			'container'       => 'nav',
			'container_class' => 'main-nav',
			'container_id'    => 'main-nav',
			'fallback_cb'     => 'responsive_fallback_menu',
			'theme_location'  => 'header-menu',
		)
	);
}


/**
 * Check the responsive version is above 4.0.
 * Change the value layout if it is fullwidth_without_box.
 */
function responsive_check_previous_version() {
	$theme_data  = wp_get_theme();
	$new_version = $theme_data->Version;
	global $responsive_options;
	$responsive_options = responsive_get_options();

	// Check if we had a response and compare the current version on wp.org to version 2. If it is version 2 or greater display a message.
	if ( $new_version && version_compare( $new_version, '4.0.0', '=>' ) && 'full-width-no-box' === $responsive_options['site_layout_option'] ) {
		$responsive_options['site_layout_option'] = 'fullwidth-stretched';
		update_option( 'responsive_theme_options', $responsive_options );
	}
}
add_action( 'init', 'responsive_check_previous_version', 10 );
