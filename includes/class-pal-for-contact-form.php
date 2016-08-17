<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pal_For_Contact_Form
 * @subpackage Pal_For_Contact_Form/includes
 * @author     wppaypalcontact <wppaypalcontact@gmail.com>
 */
class Pal_For_Contact_Form {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Pal_Pro_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'pal-for-contact-form';
        $this->version = '1.0.0';

        
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
		$prefix = is_network_admin() ? 'network_admin_' : '';
        add_filter("{$prefix}plugin_action_links_".PAL_FOR_CONTACT_FORM_PLUGIN_BASENAME, array($this, 'pal_for_contact_form_settings_link'), 10, 4);        
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Pal_Pro_Loader. Orchestrates the hooks of the plugin.
     * - Pal_Pro_i18n. Defines internationalization functionality.
     * - Pal_Pro_Admin. Defines all hooks for the admin area.
     * - Pal_Pro_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pal-for-contact-form-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pal-for-contact-form-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-pal-for-contact-form-admin.php';
        
        /**
         * The class responsible for defining all actions that occur in the public area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-pal-for-contact-form-public.php';
        

        $this->loader = new Pal_For_Contact_Form_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Pal_Pro_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Pal_For_Contact_Form_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Pal_For_Contact_Form_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('init', $plugin_admin, 'pal_contact_form_active');
        $this->loader->add_action('init', $plugin_admin, 'pal_contact_form_notice_shown');
        $this->loader->add_action('admin_menu', $plugin_admin, 'pal_contact_form_admin_menu', 20);       
        $this->loader->add_filter('wpcf7_editor_panels', $plugin_admin, 'pal_contact_form_editor_panels');
        $this->loader->add_action('wpcf7_admin_after_additional_settings', $plugin_admin, 'pal_contact_form_additional_settings');
        $this->loader->add_action('wpcf7_save_contact_form', $plugin_admin, 'pal_contact_form_save');
    }
    
     /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Pal_For_Contact_Form_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_public, 'enqueue_scripts');            
        $this->loader->add_action('wpcf7_mail_sent', $plugin_public, 'pal_contact_form_after_paypal');
        $this->loader->add_filter('wpcf7_load_js', $plugin_public, 'pal_contact_form_load_js');        
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Pal_Pro_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
    
    public function pal_for_contact_form_settings_link($actions, $plugin_file, $plugin_data, $context){        
		$custom_actions = array(
            'configure' => sprintf('<a href="%s">%s</a>', 'admin.php?page=pal_contact_form_admin_table', __('Configure', 'donation-button')),
            'docs' => sprintf('<a href="%s" target="_blank">%s</a>', 'https://www.premiumdev.com/product/paypal-contact-form-7/', __('Docs', 'donation-button')),
            'support' => sprintf('<a href="%s" target="_blank">%s</a>', 'https://wordpress.org/support/plugin/pal-for-contact-form', __('Support', 'donation-button')),
            'review' => sprintf('<a href="%s" target="_blank">%s</a>', 'https://wordpress.org/support/view/plugin-reviews/pal-for-contact-form', __('Write a Review', 'donation-button')),
        );
		
        return array_merge($custom_actions, $actions);
    }
}