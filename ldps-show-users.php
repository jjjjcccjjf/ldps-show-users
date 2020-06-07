<?php
/**
 * Plugin Name: Show Users
 * Plugin URI: https://github.com/jjjjcccjjf/ldps-show-users
 * Description: A wordpress plugin that utilises a static URL to display a virtual page of https://jsonplaceholder.typicode.com/users in table format
 * Version: 1.0
 * Author: Lorenzo Dante
 * Author URI: mailto:lorenzodante.dev@gmail.com
 */
defined( 'ABSPATH' ) or die();

add_filter( 'template_include', 'contact_page_template', 99 );
function contact_page_template( $template ) {

    $file_name = 'template-ldps-show-users.php';

    $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
    // var_dump($url_path); die();
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

add_filter( 'plugin_action_links_ldps-show-users/ldps-show-users.php', 'nc_settings_link' );
function nc_settings_link( $links ) {
	// Build and escape the URL.
	$url = esc_url( add_query_arg(
		'page',
		'ldps_show_users_options',
		get_admin_url() . 'admin.php'
	) );
	// Create the link.
	$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
	// Adds the link to the end of the array.
	array_push(
		$links,
		$settings_link
	);
	return $links;
}//end nc_settings_link()

add_action( 'admin_menu', 'linked_url' );
function linked_url() {
	add_menu_page( 'linked_url', 'Show Users', 'read', 'my_slug', '', 'dashicons-text', 1 );
}

function my_js_include_function() {
    global $option_defaults;
    $options = wp_parse_args(get_option('ldps_show_users'), $option_defaults);
    $use_default_style = $options['use_default_style'];
    
    wp_enqueue_style('ldps-style', plugin_dir_url(__FILE__) . 'assets/css/ldps-style.css', array(), null, 'all');
    if ($use_default_style) {
        wp_dequeue_style( 'twentytwenty-style' );
    	wp_enqueue_style('ldps-twentytwenty', plugin_dir_url(__FILE__) . 'assets/css/ldps-twentytwenty.css', array(), null, 'all');
    }
    wp_enqueue_script('ldps-script', plugin_dir_url(__FILE__) . 'assets/js/ldps-script.js', array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'my_js_include_function' );

/////////////////////////////////

$option_defaults = array(
  'virtual_slug' => 'show-users',
  'use_default_style' => 1
);

function linkedurl_function() {
	global $menu;
    global $option_defaults;
    $options = wp_parse_args(get_option('ldps_show_users'), $option_defaults);
	$menu[1][2] = home_url($options['virtual_slug']);
}
add_action( 'admin_menu' , 'linkedurl_function' );

// Init plugin options to white list our options
function ldps_show_users_options_init(){
    register_setting( 'ldps_show_users_options', 'ldps_show_users', 'ldps_show_users_options_validate' );
}
add_action('admin_init', 'ldps_show_users_options_init' );

// Add menu page
function ldps_show_users_options_add_page() {
    add_options_page('Show Users Options', 'Show Users Options', 'manage_options', 'ldps_show_users_options', 'ldps_show_users_options_do_page');
}
add_action('admin_menu', 'ldps_show_users_options_add_page');

// Draw the menu page itself
function ldps_show_users_options_do_page() {
    global $option_defaults;
    $options = wp_parse_args(get_option('ldps_show_users'), $option_defaults);
    $virtual_slug = $options['virtual_slug'];
    $use_default_style = $options['use_default_style'];
    ?>
    <div class="wrap">
        <h2>Show Users Options</h2>
        <form method="post" action="options.php">
            <?php settings_fields('ldps_show_users_options'); ?>
            <table class="form-table">
                <tr valign="top"><th scope="row">Use default style</th>
                    <td><input name="ldps_show_users[use_default_style]" type="checkbox" value="1" <?php checked('1', $use_default_style); ?> /></td>
                </tr>
                <tr valign="top"><th scope="row">Custom Page Slug</th>
                    <td><input type="text" name="ldps_show_users[virtual_slug]" value="<?php echo $virtual_slug; ?>" /></td>
                </tr>
            </table>
            <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
    </div>
    <?php   
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function ldps_show_users_options_validate($input) {
    // Our first value is either 0 or 1
    $input['use_default_style'] = ( $input['use_default_style'] == 1 ? 1 : 0 );

    // Say our second option must be safe text with no HTML tags
    $input['virtual_slug'] =  sanitize_title($input['virtual_slug']);
    
    return $input;
}
