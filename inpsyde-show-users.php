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
    wp_enqueue_style('style.css', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), null, 'all');
    wp_enqueue_script('script.js', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'my_js_include_function' );


/////////////////////////////////

$option_defaults = array(
  'virtual_slug' => 'show-users',
);

function linkedurl_function() {
	global $menu;
    global $option_defaults;
    $options = wp_parse_args(get_option('ldps_show_users'), $option_defaults);
	$menu[1][2] = home_url($options['virtual_slug']);
}
add_action( 'admin_menu' , 'linkedurl_function' );

// Init plugin options to white list our options
function ldps_sampleoptions_init(){
    register_setting( 'ldps_show_users_options', 'ldps_show_users', 'ldps_sampleoptions_validate' );
}
add_action('admin_init', 'ldps_sampleoptions_init' );

// Add menu page
function ldps_sampleoptions_add_page() {
    add_options_page('Show Users Options', 'Show Users Options', 'manage_options', 'ldps_sampleoptions', 'ldps_sampleoptions_do_page');
}
add_action('admin_menu', 'ldps_sampleoptions_add_page');

// Draw the menu page itself
function ldps_sampleoptions_do_page() {
    global $option_defaults;
    $options = wp_parse_args(get_option('ldps_show_users'), $option_defaults);
    $virtual_slug = $options['virtual_slug'];
    ?>
    <div class="wrap">
        <h2>Show Users Options</h2>
        <form method="post" action="options.php">
            <?php settings_fields('ldps_show_users_options'); ?>
            <table class="form-table">
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
function ldps_sampleoptions_validate($input) {
    // Say our second option must be safe text with no HTML tags
    $input['virtual_slug'] =  sanitize_title($input['virtual_slug']);
    
    return $input;
}
