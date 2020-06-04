<?php
/**
 * Plugin Name: Show Users
 * Plugin URI: http://example.com
 * Description: A wordpress plugin that creates a dedicated page to view https://jsonplaceholder.typicode.com/users
 * Version: 1.0
 * Author: Lorenzo Dante
 * Author URI: mailto:lorenzodante.dev@gmail.com
 */

add_action( 'admin_menu', 'linked_url' );
	function linked_url() {
	add_menu_page( 'linked_url', 'External link', 'read', 'my_slug', '', 'dashicons-text', 1 );
}

add_action( 'admin_menu' , 'linkedurl_function' );
	function linkedurl_function() {
	global $menu;
	$menu[1][2] = "http://www.example.com";
}