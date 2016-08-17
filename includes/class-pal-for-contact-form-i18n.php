<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pal_For_Contact_Form
 * @subpackage Pal_For_Contact_Form/includes
 * @author     wppaypalcontact <wppaypalcontact@gmail.com>
 */
class Pal_For_Contact_Form_i18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
                'pal-for-contact-form', false, dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }

}
