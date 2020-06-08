<?php
/**
 * Plugin Name: Show Users
 * Plugin URI: https://github.com/jjjjcccjjf/ldps-show-users
 * Description: A wordpress plugin that utilises a static URL to display a virtual page of https://jsonplaceholder.typicode.com/users in table format
 * Version: 1.0
 * Author: Lorenzo Dante
 * Author URI: mailto:lorenzodante.dev@gmail.com
 */

declare(strict_types=1);

// do not allow direct access
defined('ABSPATH') or die();


if (! class_exists('ldpsShowUsers')) :

class ldpsShowUsers
{

    // Variables
    // Define our default global variables for plugin options context
    private $optionDefaults = [
      'virtual_slug' => 'show-users',
      'use_default_style' => 1,
    ];
    
    private $filteredOptions;

    public function __construct()
    {
        $this->filteredOptions  = wp_parse_args(get_option('ldps_show_users'), $this->optionDefaults);

        // Add to admin menu our main link
        add_action('admin_menu', [$this, 'ldps_link_virtual_page_url']);

        // Detect the slug and hijack it if it matches our desired slug
        add_filter('template_include', [$this, 'ldps_do_virtual_page_template'], 99);

        // Add to sidebar our settings link
        add_action('admin_menu', [$this, 'ldps_link_settings_sidebar']);

        // Add settings to plugin page
        add_filter('plugin_action_links_ldps-show-users/ldps-show-users.php', [$this, 'ldps_link_settings_plugin_page']);

        add_action('wp_print_styles', [$this, 'ldps_control_styles'], 100); // We use this hook because the theme's styles are not loaded yet on wp_enqueue_scripts so we have to remove the theme's files AFTER wordpress enqueues them

        // Initialize options
        add_action('admin_init', [$this, 'ldps_show_users_options_init']);

        // Create settings page
        add_action('admin_menu', [$this, 'ldps_show_users_options_add_page']);
    }

    /**
     * Add direct access to our virtual page on the topmost level of our admin menu
     */
    public function ldps_link_virtual_page_url()
    {
        add_menu_page('ldps_link_virtual_page_url', 'Show Users', 'read', 'my_slug', '', 'dashicons-text', 1);
    }

    /**
     * Hijack the page if it matches our virtual_slug and display our template
     */
    public function ldps_do_virtual_page_template(string $template) : ?string
    {
        $fileName = 'template-ldps-show-users.php';

        $urlPath = trim(parse_url(add_query_arg([]), PHP_URL_PATH), '/');
        $templateName = $this->filteredOptions['virtual_slug']; // get our option value
        $pos = strpos($urlPath, $templateName);

        // Make sure we only hijack the page when the exact virtual_slug is retrieved from the options
        if ($pos !== false) {
            status_header(200);
            if (locate_template($fileName)) { // locate the template in themes in case it is overridden
                return locate_template($fileName);
            }

            // If template is not found in theme's folder, use plugin's template as a fallback
            return dirname(__FILE__) . '/assets/templates/' . $fileName;
        }

        return $template;
    }

    /**
     * Add settings link to WP Sidebar > Settings
     */
    public function ldps_link_settings_sidebar()
    {
        global $menu, $filteredOptions;
        $menu[1][2] = home_url($this->filteredOptions['virtual_slug']);
    }

    /**
     * Add a Settings link to the Plugin page
     */
    public function ldps_link_settings_plugin_page(array $links) : array
    {
        // Build and escape the URL.
        $url = esc_url(add_query_arg(
            'page',
            'ldps_show_users_options',
            get_admin_url() . 'admin.php'
        ));
        // Create the link.
        $settingsLink = "<a href='$url'>" . __('Settings') . '</a>';
        // Adds the link to the end of the array.
        array_push(
            $links,
            $settingsLink
        );
        return $links;
    }



    /**
     * Load our default script and styles and also allow the user to choose whether they want their theme's main CSS to take over or just use the default plugin CSS
     */
    public function ldps_control_styles()
    {
        global $wp_styles;
        $useDefaultStyle = $this->filteredOptions['use_default_style'];

        $urlPath = trim(parse_url(add_query_arg([]), PHP_URL_PATH), '/');
        $templateName = $this->filteredOptions['virtual_slug']; // get our option value
        $pos = strpos($urlPath, $templateName);

        // Make sure we only hijack the page when the exact virtual_slug is retrieved from the options
        if ($pos !== false) {
            // Load default custom styling for modals, etc. and our main script
            wp_enqueue_style('ldps-style', plugin_dir_url(__FILE__) . 'assets/css/ldps-style.css', [], null, 'all');
            wp_enqueue_script('ldps-script', plugin_dir_url(__FILE__) . 'assets/js/ldps-script.js', ['jquery']);

            if ($useDefaultStyle) {
                foreach ($wp_styles->queue as $style) { // We remove all other styles if the user prefers the default plugin style
                    if (!in_array($style, ['admin-bar', 'wp-block-library', 'ldps-style'], true)) {
                        wp_deregister_style($style);
                        wp_dequeue_style($style);
                    }
                }
                // Then load the copied twentytwenty CSS
                wp_enqueue_style('ldps-twentytwenty', plugin_dir_url(__FILE__) . 'assets/css/ldps-twentytwenty.css', [], null, 'all');
            }
        }
    }


    // Init plugin options to white list our options
    public function ldps_show_users_options_init()
    {
        register_setting('ldps_show_users_options', 'ldps_show_users', 'ldps_show_users_options_validate');
    }

    // Add menu page
    public function ldps_show_users_options_add_page()
    {
        add_options_page('Show Users Options', 'Show Users Options', 'manage_options', 'ldps_show_users_options', [$this, 'ldps_show_users_options_do_page']);
    }

    // Draw the menu page itself
    public function ldps_show_users_options_do_page()
    {
        $virtualSlug = $this->filteredOptions['virtual_slug'];
        $useDefaultStyle = $this->filteredOptions['use_default_style']; ?>
        <div class="wrap">
            <h2>Show Users Options</h2>
            <form method="post" action="options.php">
                <?php settings_fields('ldps_show_users_options'); ?>
                <table class="form-table">
                    <tr valign="top"><th scope="row">Use default style</th>
                        <td><input name="ldps_show_users[use_default_style]" type="checkbox" value="1" <?php checked('1', $useDefaultStyle); ?> /></td>
                    </tr>
                    <tr valign="top"><th scope="row">Custom Page Slug</th>
                        <td><input type="text" name="ldps_show_users[virtual_slug]" value="<?php echo esc_html($virtualSlug); ?>" /></td>
                    </tr>
                </table>
                <p class="submit">
                <input type="submit" class="button-primary" value="<?php esc_html_e('Save Changes') ?>" />
                </p>
            </form>
        </div>
        <?php
    }




    /**
     * Sanitize and validate input. Accepts an array, return a sanitized array.
     * @return array $input
     */
    public function ldps_show_users_options_validate(array $input) : array
    {
        // Our first value is either 0 or 1
        $input['use_default_style'] = ((int) $input['use_default_style'] === 1 ? 1 : 0);

        // Sanitize to make URL-like
        $input['virtual_slug'] =  sanitize_title($input['virtual_slug']);
        
        return $input;
    }
}

global $ldpsShowUsers;
$ldpsShowUsers = new ldpsShowUsers();
endif;
