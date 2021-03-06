<?php
if ( ! function_exists( '_wd_setup' ) ) :
function _wd_setup() {
	require( get_template_directory() . '/inc/tweaks.php' );
	load_theme_textdomain( '_s', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	register_nav_menus( array('primary' => __( 'Primary Menu', '_wd' ), ) );
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
	add_theme_support( 'custom-background', apply_filters( '_s_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array( 'comment-list', 'search-form', 'comment-form', ) );
}
endif; // _wd_setup
add_action( 'after_setup_theme', '_wd_setup' );

function _wd_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', '_wd' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', '_wd_widgets_init' );

function _wd_scripts() {
	wp_enqueue_style( '_wd-style', get_stylesheet_uri() );
	//wp_enqueue_script( '_wd-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	//wp_enqueue_script( '_wd-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', '_wd_scripts' );


//require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/extras.php';
require get_template_directory() . '/inc/customizer.php';
//require get_template_directory() . '/inc/jetpack.php';
