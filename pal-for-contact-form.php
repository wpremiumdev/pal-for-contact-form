<?php

/**
 * @link              https://www.premiumdev.com/
 * @since             1.0.0
 * @package           Pal_For_Contact_Form
 *
 * @wordpress-plugin
 * Plugin Name:       PayPal for Contact Form 7
 * Plugin URI:        https://www.premiumdev.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            wppaypalcontact
 * Author URI:        https://www.premiumdev.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pal-for-contact-form
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


if (!defined('PAL_FOR_CONTACT_FORM_PLUGIN_BASENAME')) {
    define('PAL_FOR_CONTACT_FORM_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pal-pro-activator.php
 */
function activate_pal_for_contact_form() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-pal-for-contact-form-activator.php';
    Pal_For_Contact_Form_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pal-pro-deactivator.php
 */
function deactivate_pal_for_contact_form() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-pal-for-contact-form-deactivator.php';
    Pal_For_Contact_Form_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_pal_for_contact_form');
register_deactivation_hook(__FILE__, 'deactivate_pal_for_contact_form');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-pal-for-contact-form.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pal_for_contact_form() {

    $plugin = new pal_for_contact_form();
    $plugin->run();
}

add_action('plugins_loaded', 'load_pal_for_contact_form');

function load_pal_for_contact_form() {
    run_pal_for_contact_form();
}
