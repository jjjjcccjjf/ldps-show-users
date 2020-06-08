<?php

declare(strict_types=1);

namespace jjjjcccjjf\ShowUsers;

// do not allow direct access
defined('ABSPATH') or die();

class LdpsShowUsers
{
    private $optionDefaults = [
          'virtual_slug' => 'show-users',
          'use_default_style' => 1,
        ];
    
    private $baseTemplateName = 'template-ldps-show-users.php';

    private $filteredOptions;

    public function __construct()
    {
        $this->filteredOptions = wp_parse_args(get_option('ldps_show_users'), $this->optionDefaults);
    }

    /**
     * hook everything
     */
    public function hookAll() : void
    {
        // Add to admin menu our main link
        add_action('admin_menu', [$this, 'linkVirtualPageUrl']);

        // Detect the slug and hijack it if it matches our desired slug
        add_filter('template_include', [$this, 'doVirtualPageTemplate'], 99);

        // Add to sidebar our settings link
        add_action('admin_menu', [$this, 'linkSettingsSidebar']);

        // Add settings to plugin page
        add_filter(
            'plugin_action_links_ldps-show-users/ldps-show-users.php',
            [$this, 'linkSettingsPluginPage']
        );

        // We use this hook because the theme's styles are not loaded yet on wp_enqueue_scripts
        // so we have to remove the theme's files AFTER wordpress enqueues them
        add_action('wp_print_styles', [$this, 'controlStyles'], 100);

        // Initialize options
        add_action('admin_init', [$this, 'showUsersOptionsInit']);

        // Create settings page
        add_action('admin_menu', [$this, 'showUsersOptionsAddPage']);
    }

    /**
     * Add direct access to our virtual page on the topmost level of our admin menu
     */
    public function linkVirtualPageUrl()
    {
        add_menu_page(
            'ldps_link_virtual_page_url',
            'Show Users',
            'read',
            'my_slug',
            '',
            'dashicons-text',
            1
        );
    }

    /**
     * Hijack the page if it matches our virtual_slug and display our template
     */
    public function doVirtualPageTemplate(string $template) : ?string
    {
        // Make sure we only hijack the page when the exact virtual_slug is retrieved from the options
        if ($this->validateVirtualSlug()) {
            status_header(200); // We override the wordpress 404 status
            // locate the template in themes in case it is overridden
            if (locate_template($this->baseTemplateName)) {
                return locate_template($this->baseTemplateName);
            }

            // If template is not found in theme's folder, use plugin's template as a fallback
            return dirname(__FILE__) . '/assets/templates/' . $this->baseTemplateName;
        }

        return $template;
    }

    /**
     * Check if our current URL's slug matches our virtual slug stored in our options
     * @return bool
     */
    public function validateVirtualSlug() : bool
    {
        $urlPath = trim(parse_url(add_query_arg([]), PHP_URL_PATH), '/');
        $templateName = $this->filteredOptions['virtual_slug']; // get our option value
        $pos = strpos($urlPath, $templateName);
        return $pos !== false;
    }

    /**
     * Add settings link to WP Sidebar > Settings
     */
    public function linkSettingsSidebar()
    {
        global $menu, $filteredOptions;
        $menu[1][2] = home_url($this->filteredOptions['virtual_slug']);
    }

    /**
     * Add a Settings link to the Plugin page
     */
    public function linkSettingsPluginPage(array $links) : array
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
     * Load our default script and styles and also allow the user to choose
     * whether they want their theme's main CSS to take over or just use the default plugin CSS
     */
    public function controlStyles()
    {
        global $wp_styles;
        $useDefaultStyle = $this->filteredOptions['use_default_style'];

        // Make sure we only hijack the page when the exact virtual_slug is retrieved from the options
        if ($this->validateVirtualSlug()) {
            // Load default custom styling for modals, etc. and our main script
            wp_enqueue_style(
                'ldps-style',
                plugin_dir_url(__FILE__) . 'assets/css/ldps-style.css',
                [],
                null,
                'all'
            );
            wp_enqueue_script(
                'ldps-script',
                plugin_dir_url(__FILE__) . 'assets/js/ldps-script.js',
                ['jquery']
            );

            if ($useDefaultStyle) {
                // We remove all other styles if the user prefers the default plugin style
                foreach ($wp_styles->queue as $style) {
                    if (!in_array($style, ['admin-bar', 'wp-block-library', 'ldps-style'], true)) {
                        wp_deregister_style($style);
                        wp_dequeue_style($style);
                    }
                }
                // Then load the copied twentytwenty CSS
                wp_enqueue_style(
                    'ldps-twentytwenty',
                    plugin_dir_url(__FILE__) . 'assets/css/ldps-twentytwenty.css',
                    [],
                    null,
                    'all'
                );
            }
        }
    }

    // Init plugin options to white list our options
    public function showUsersOptionsInit()
    {
        register_setting(
            'ldps_show_users_options',
            'ldps_show_users',
            [$this, 'showUsersOptionsValidate']
        );
    }

    // Add menu page
    public function showUsersOptionsAddPage()
    {
        add_options_page(
            'Show Users Options',
            'Show Users Options',
            'manage_options',
            'ldps_show_users_options',
            [$this, 'showUsersOptionsDoPage']
        );
    }

    // Draw the menu page itself
    public function showUsersOptionsDoPage()
    {
        $virtualSlug = $this->filteredOptions['virtual_slug'];
        $useDefaultStyle = $this->filteredOptions['use_default_style']; ?>
        <div class="wrap">
            <h2>Show Users Options</h2>
            <form method="post" action="options.php">
                    <?php settings_fields('ldps_show_users_options'); ?>
                <table class="form-table">
                    <tr valign="top"><th scope="row">Use default style</th>
                        <td><input name="ldps_show_users[use_default_style]" 
                            type="checkbox" value="1" <?php checked('1', $useDefaultStyle); ?> />
                        </td>
                    </tr>
                    <tr valign="top"><th scope="row">Custom Page Slug</th>
                        <td><input type="text" name="ldps_show_users[virtual_slug]" 
                            value="<?php echo esc_html($virtualSlug); ?>" />
                        </td>
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
    public function showUsersOptionsValidate(array $input) : array
    {
        // Our first value is either 0 or 1
        $input['use_default_style'] = ($input['use_default_style'] === '1' ? 1 : 0);

        // Sanitize to make URL-like
        $input['virtual_slug'] =  sanitize_title($input['virtual_slug']);
        
        return $input;
    }
}
