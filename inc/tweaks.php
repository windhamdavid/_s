<?php
function wd_fromemail($email) {
	$wpfrom = get_option('admin_email');
    return $wpfrom;
	//return 'david@davidawindham.com';
}
function wd_fromname($email){
   	$wpfrom = get_option('blogname');
    return $wpfrom;
	//return 'davidawindham.com';
}
add_filter('wp_mail_from', 'wd_fromemail');
add_filter('wp_mail_from_name', 'wd_fromname');

function _wd_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', '_wd_page_menu_args' );


function _wd_body_classes( $classes ) {
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	return $classes;
}
add_filter( 'body_class', '_wd_body_classes' );


function _wd_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;
	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';
	return $url;
}
add_filter( 'attachment_link', '_wd_enhanced_image_navigation', 10, 2 );


add_action('init', 'wd_head_cleanup');
function wd_head_cleanup() {
  remove_action('wp_head', 'feed_links');
  remove_action('wp_head', 'feed_links', 2 );
  remove_action('wp_head', 'feed_links_extra');
  remove_action('wp_head', 'feed_links_extra', 3 );
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'index_rel_link');
  remove_action('wp_head', 'parent_post_rel_link');
  remove_action('wp_head', 'start_post_rel_link');
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'wp_shortlink_wp_head');
  remove_action('wp_head', 'rel_canonical');
}

add_action('admin_menu', 'remove_menus');
function remove_menus () {
global $menu;
//	$restricted = array(__('Dashboard'), __('Posts'), __('Media'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Users'), __('Settings'), __('Comments'), __('Plugins'));
	$restricted = array();
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}

add_action('login_head', 'wd_login_logo');
function wd_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url('.get_bloginfo('template_directory').'/img/login.png) !important; }
    </style>';
}

add_action( 'admin_head', 'wd_hide' );
function wd_hide() {
    ?>
    <style type="text/css" media="screen">
    #footer {display:none;}
	/* #screen-meta-links {display: none;} */
	#wpadminbar {display: none;}
	body.admin-bar #wpcontent, body.admin-bar #adminmenu {padding-top:0;}
	html.wp-toolbar{padding-top:0;}
	#postexcerpt p {display: none;}
    </style>
<?php }

add_filter( 'script_loader_src', 'remove_src_version' );
add_filter( 'style_loader_src', 'remove_src_version' );
function remove_src_version ( $src ) {
  global $wp_version;
  $version_str = '?ver='.$wp_version;
  $version_str_offset = strlen( $src ) - strlen( $version_str );
  if( substr( $src, $version_str_offset ) == $version_str )
    return substr( $src, 0, $version_str_offset );
  else
    return $src;
}


add_action('wp_dashboard_setup', 'remove_dashboard_widgets');
function remove_dashboard_widgets(){
  global$wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
  //unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
  //unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); 
  //unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); 
  //unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); 
}

function wd_rss_output(){
    echo '<div class="rss-widget">'; 
       wp_widget_rss_output(array(
            'url' => 'http://' . $_SERVER['HTTP_HOST'] . '/feed', 
            'title' => 'feed',
            'items' => 4, 
            'show_summary' => 1,
            'show_author' => 0,
            'show_date' => 1
       ));
       echo "</div>";
}

add_action('wp_dashboard_setup', 'wd_rss_widget');
function wd_rss_widget(){
  wp_add_dashboard_widget( 'wd-rss', 'site-feed', 'wd_rss_output');
}
