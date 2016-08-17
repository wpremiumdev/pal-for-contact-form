<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pal_For_Contact_Form
 * @subpackage Pal_For_Contact_Form/public
 * @author     wppaypalcontact <wppaypalcontact@gmail.com>
 */
class Pal_For_Contact_Form_Public {

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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
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
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pal-for-contact-form-public.css', array(), $this->version, 'all');
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
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pal-for-contact-form-public.js', array('jquery'), $this->version, false);
    }

    public function pal_contact_form_after_paypal($cf7) {

        
        $postid = $cf7->id();
        if (empty($postid)) {
            return false;           
        }
        
        $result = "";
        $get_result = get_post_meta($postid, '_pal_contact_form_setting_data');        
        if( isset($get_result[0]) && !empty($get_result[0]))       
            $result = $get_result[0];
        
        if ( isset($result) && !empty($result) && $result['pal_contact_form_paypal_enable'] == "1") {           
            $this->pal_contact_form_paypal_form($result);
            exit;           
        }
    }
    
    public function pal_contact_form_load_js(){
        return false;
    }

    public function pal_contact_form_paypal_form( $result ) {        
        
        $redirct_url = (get_option('pal_contact_form_sandbox') == 'yes') ? 'sandbox.paypal' : 'paypal' ;
        $business = (get_option('pal_contact_form_sandbox') == 'yes') ? get_option('pal_contact_form_sandbox_account') : get_option('pal_contact_form_live_account') ;
        $currency_code = (get_option('pal_contact_form_currency')) ? get_option('pal_contact_form_currency') : 'USD' ;
        $lc = (get_option('pal_contact_form_language')) ? get_option('pal_contact_form_language') : 'EN_US' ;
        $return_url = (get_option('pal_contact_form_return_url')) ? get_option('pal_contact_form_return_url') : '' ;
        $cancel_url = (get_option('pal_contact_form_cancel_url')) ? get_option('pal_contact_form_cancel_url') : '' ;
        
        ?>
        <html>
            <head><title>Redirecting to Paypal...</title></head>
            <body>
                <form action='https://www.<?php echo $redirct_url; ?>.com/cgi-bin/webscr' method='post' name="pal_for_contact_form">
                    <input type='hidden' name='cmd' value='_xclick' />
                    <input type='hidden' name='business' value='<?php echo $business; ?>' />
                    <input type='hidden' name='item_name' value='<?php echo $result['pal_contact_form_item_disc']; ?>' />
                    <input type='hidden' name='currency_code' value='<?php echo $currency_code; ?>' />
                    <input type='hidden' name='amount' value='<?php echo $result['pal_contact_form_item_price']; ?>' />
                    <input type='hidden' name='lc' value='<?php echo $lc; ?>'>
                    <input type='hidden' name='item_number' value='<?php echo $result['pal_contact_form_item_sku']; ?>' />
                    <input type='hidden' name='return' value='<?php echo $return_url; ?>' />
                    <input type='hidden' name='bn' value='mbjtechnolabs_SP'>
                    <input type='hidden' name='cancel_return' value='<?php echo $cancel_url; ?>' />
                    <img alt='' border='0' style='border:none;display:none;' src='https://www.paypal.com/$language/i/scr/pixel.gif' width='1' height='1'>
                </form>
                <script type="text/javascript">
                    document.pal_for_contact_form.submit();
                </script>
            </body>
        </html>
    <?php
    }
}
