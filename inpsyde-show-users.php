<?php
/**
 * Plugin Name: Show Users
 * Plugin URI: http://example.com
 * Description: A wordpress plugin that utilises a static URL to display a virtual page of https://jsonplaceholder.typicode.com/users in table format
 * Version: 1.0
 * Author: Lorenzo Dante
 * Author URI: mailto:lorenzodante.dev@gmail.com
 */
defined( 'ABSPATH' ) or die();

add_filter( 'template_include', 'contact_page_template', 99 );
function contact_page_template( $template ) {

    $file_name = 'template-inpsyde-show-users.php';

    $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
    $templatename = 'show-users'; 
    $pos = strpos($url_path, $templatename); 

    if ($pos !== false) {
    	status_header(200);
        if ( locate_template( $file_name ) ) {
            $template = locate_template( $file_name );
        } else {
            // Template not found in theme's folder, use plugin's template as a fallback
            $template = dirname( __FILE__ ) . '/assets/templates/' . $file_name;
        }
    }

    return $template;
}
// add_filter('pre_handle_404', function($preempt, $wp_query) {
//     global $wp;
//     $customPages = ['show-users'];

//     if (in_array($wp->request, $customPages)) {
//       $preempt = true;
//     }

//     return $preempt;
// }, 10, 2);

add_action( 'admin_menu', 'linked_url' );
	function linked_url() {
	add_menu_page( 'linked_url', 'Show Users', 'read', 'my_slug', '', 'dashicons-text', 1 );
}

function my_js_include_function() {
	// var_dump(dirname( __FILE__ )); die();
    wp_enqueue_style( 'style.css', '/../wp-content/plugins/inpsyde-show-users/assets/css/style.css');
    wp_enqueue_script( 'script.js', '/../wp-content/plugins/inpsyde-show-users/assets/js/script.js', array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'my_js_include_function' );

add_action( 'admin_menu' , 'linkedurl_function' );
	function linkedurl_function() {
	global $menu;
	$menu[1][2] = home_url('show-users');
}

