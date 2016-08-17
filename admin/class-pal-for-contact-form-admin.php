<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pal_For_Contact_Form
 * @subpackage Pal_For_Contact_Form/admin
 * @author     wppaypalcontact <wppaypalcontact@gmail.com>
 */
class Pal_For_Contact_Form_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-pal-for-contact-form-general-setting.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/class-pal-for-contact-form-html-output.php';
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pal_Pro_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pal_Pro_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pal-for-contact-form-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pal_Pro_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pal_Pro_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pal-for-contact-form-admin.js', array('jquery'), $this->version, false);
    }

    public function pal_contact_form_active() {
        if (!in_array('contact-form-7/wp-contact-form-7.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            add_action('admin_notices', array($this, 'pal_contact_form_notice_error'));
            return false;
        } else {
            return true;
        }
    }

    public function pal_contact_form_notice_error() {
        $class = 'notice notice-error';
        $message = __('<strong>Pal For Contact Form:</strong> Please installed or active Contact Form 7!.', 'pal-for-contact-form');
        printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
    }

    public function pal_contact_form_notice_shown() {
        if (!get_option('pal_contact_form_notice_shown')) {
            echo "<div class='updated'><p><a href='admin.php?page=pal_contact_form_admin_table'>Click here to view the plugin settings</a>.</p></div>";
            update_option("pal_contact_form_notice_shown", "true");
        }
    }

    public function pal_contact_form_admin_menu() {
        $addnew = add_submenu_page('wpcf7', __('PayPal Settings', 'contact-form-7'), __('PayPal Settings', 'contact-form-7'), 'wpcf7_edit_contact_forms', 'pal_contact_form_admin_table', array($this, 'pal_contact_form_admin_table'));
    }

    public function pal_contact_form_admin_table() {

        if (!current_user_can("manage_options")) {
            wp_die(__("You do not have sufficient permissions to access this page."));
        }

        $setting_tabs = apply_filters('pal_contact_form_admin_table_setting_tab', array('general' => 'General'));
        $current_tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general';
        ?>
        <h2 class="nav-tab-wrapper">
            <?php
            foreach ($setting_tabs as $name => $label)
                echo '<a href="' . admin_url('admin.php?page=pal_contact_form_admin_table&tab=' . $name) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
            ?>
        </h2>
        <?php
        foreach ($setting_tabs as $setting_tabkey => $setting_tabvalue) {
            switch ($setting_tabkey) {
                case $current_tab:
                    do_action('pal_contact_form_' . $setting_tabkey . '_setting_save_field');
                    do_action('pal_contact_form_' . $setting_tabkey . '_setting');
                    break;
            }
        }
    }

    public function pal_contact_form_editor_panels($tab) {
        $settings = array(
            'PayPal' => array(
                'title' => __('PayPal Settings', 'contact-form-7'),
                'callback' => array($this, 'pal_contact_form_additional_settings')
            )
        );

        $tab = array_merge($tab, $settings);

        return $tab;
    }

    public function pal_contact_form_additional_settings() {

        $get_result = array();
        $result = array();

        $post_id = sanitize_text_field($_GET['post']);

        if (isset($post_id) && !empty($post_id)) {
            $get_result = get_post_meta($post_id, '_pal_contact_form_setting_data');
            if (isset($get_result[0]) && !empty($get_result[0]))
                $result = $get_result[0];
        }

        $pal_contact_form_paypal_enable = "0";
        $pal_contact_form_paypal_is_check = "";
        $pal_contact_form_item_disc = "";
        $pal_contact_form_item_price = "";
        $pal_contact_form_item_sku = "";

        if (isset($result) && !empty($result)) {
            if (isset($result['pal_contact_form_paypal_enable'])) {
                $pal_contact_form_paypal_enable = ($result['pal_contact_form_paypal_enable']) ? $result['pal_contact_form_paypal_enable'] : 0;
                $pal_contact_form_paypal_is_check = ($result['pal_contact_form_paypal_enable'] == 1) ? 'checked' : '';
            }

            $pal_contact_form_item_disc = ($result['pal_contact_form_item_disc']) ? $result['pal_contact_form_item_disc'] : '';
            $pal_contact_form_item_price = ($result['pal_contact_form_item_price']) ? $result['pal_contact_form_item_price'] : '';
            $pal_contact_form_item_sku = ($result['pal_contact_form_item_sku']) ? $result['pal_contact_form_item_sku'] : '';
        }
        ?>
        <form>
            <div class='mail-field'>
                <table class="form-table">
                    <tbody>
                        <tr valign="top" class="">
                            <th scope="row" class="titledesc"><?php _e('Enable PayPal', 'pal-for-contact-form') ?></th>
                            <td class="forminp forminp-checkbox">
                                <fieldset>
                                    <legend class="screen-reader-text"><span><?php _e('Enable PayPal', 'pal-for-contact-form') ?></span></legend>
                                    <label for="pal_contact_form_paypal_enable">
                                        <input name="pal_contact_form_paypal_enable" id="pal_contact_form_paypal_enable" type="checkbox" value="<?php echo $pal_contact_form_paypal_enable; ?>" <?php echo $pal_contact_form_paypal_is_check; ?>>
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table class="form-table pal_contact_form_paypal_enable_table">
                <tbody>                    
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Item Name', 'pal-for-contact-form') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Item Name', 'pal-for-contact-form') ?></span></legend>
                                <label for="pal_contact_form_item_disc" style="width: 100%">
                                    <input name="pal_contact_form_item_disc" id="pal_contact_form_item_disc" type="text" value="<?php echo $pal_contact_form_item_disc; ?>">
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Item Price', 'pal-for-contact-form') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Item Price', 'pal-for-contact-form') ?></span></legend>
                                <label for="pal_contact_form_item_price" class="pal_contact_form_item_price_lable">
                                    <input name="pal_contact_form_item_price" id="pal_contact_form_item_price" type="text" value="<?php echo $pal_contact_form_item_price; ?>">  Format: Enter 5 not $5 (Optional).
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top" class="">
                        <th scope="row" class="titledesc"><?php _e('Item ID / SKU', 'pal-for-contact-form') ?></th>
                        <td class="forminp forminp-checkbox">
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e('Item ID / SKU', 'pal-for-contact-form') ?></span></legend>
                                <label for="pal_contact_form_item_sku">
                                    <input name="pal_contact_form_item_sku" id="pal_contact_form_sandbox" type="text" value="<?php echo $pal_contact_form_item_sku; ?>">  (Optional).
                                </label>
                            </fieldset>
                        </td>
                    </tr>                    
                </tbody>
            </table> 
        </form>
        <?php
    }

    public function pal_contact_form_save() {

        $result = array();
        $post_id = sanitize_text_field($_GET['post']);
        
        if (!isset($post_id) && empty($post_id)) {
            return false;
        }

        $result['pal_contact_form_paypal_enable'] = intval($_POST['pal_contact_form_paypal_enable']);
        $result['pal_contact_form_item_disc'] = sanitize_text_field($_POST['pal_contact_form_item_disc']);
        $result['pal_contact_form_item_price'] = sanitize_text_field($_POST['pal_contact_form_item_price']);
        $result['pal_contact_form_item_sku'] = sanitize_text_field($_POST['pal_contact_form_item_sku']);

        update_post_meta($post_id, "_pal_contact_form_setting_data", $result);
    }

}