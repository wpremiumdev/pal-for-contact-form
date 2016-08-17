<?php
/**
 * @class       Pal_For_Contact_Form_General_Setting
 * @version	1.0.0
 * @package	pal-for-contact-form
 * @category	Class
 */

class Pal_For_Contact_Form_General_Setting {

    /**
     * Hook in methods
     * @since    1.0.0
     * @access   static
     */
    public static function init() {
        
        add_action('pal_contact_form_general_setting_save_field', array(__CLASS__, 'pal_contact_form_general_setting_save_field'));
        add_action('pal_contact_form_general_setting', array(__CLASS__, 'pal_contact_form_general_setting'));
        
    }  

    public static function pal_contact_form_setting_fields() {
            $fields[] = array('title' => __('Pal For Contact Form Settings', 'donation-button'), 'type' => 'title', 'desc' => '', 'id' => 'pal_for_contact_form_options');
            $fields[] = array(
                'title' => __('Enable PayPal Sandbox.', 'donation-button'),
                'id' => 'pal_contact_form_sandbox',
                'default' => 'no',
                'desc' => __(' PayPal sandbox can be used to test payments.', 'donation-button'),
                'type' => 'checkbox'
            );
            $fields[] = array(
                'title' => __('Sandbox Account', 'donation-button'),
                'id' => 'pal_contact_form_sandbox_account',
                'desc' => __('To create a Sandbox account, you first need a Developer Account. You can sign up for free at the <a target="_blank" href="https://www.paypal.com/webapps/merchantboarding/webflow/unifiedflow?execution=e1s2">PayPal Developer</a> site. ', 'donation-button'),
                'default' => '',
                'type' => 'text'
            );
            $fields[] = array(
                'title' => __('Live Account', 'donation-button'),
                'id' => 'pal_contact_form_live_account',
                'desc' => __('If you don\'t have a PayPal account, you can sign up for free at <a target="_blank" href="https://paypal.com">PayPal</a>. ', 'donation-button'),                
                'type' => 'text'
            );            
            $fields[] = array(
                'title' => __('Currency', 'easy-payment'),
                'desc' => __('This is the currency for your visitors to make Payments and PayPal currently supports 25 currencies.', 'easy-payment'),
                'id' => 'pal_contact_form_currency',                                
                'type' => 'select',
                'class' => 'chosen_select',
                'options' => self::pal_contact_form_currency()
            );
            $fields[] = array(
                'title' => __('Language', 'easy-payment'),
                'desc' => __('PayPal currently supports 18 languages.', 'easy-payment'),
                'id' => 'pal_contact_form_language',                                
                'type' => 'select',
                'class' => 'chosen_select',
                'options' => self::pal_contact_form_language()
            );
            $fields[] = array(
                'title' => __('Cancel URL', 'donation-button'),
                'id' => 'pal_contact_form_cancel_url',
                'desc' => __('If the customer goes to PayPal and clicks the cancel button, where do they go.', 'donation-button'),                
                'type' => 'text'
            ); 
            $fields[] = array(
                'title' => __('Return URL', 'donation-button'),
                'id' => 'pal_contact_form_return_url',
                'desc' => __('If the customer goes to PayPal and successfully pays, where are they redirected to after.', 'donation-button'),                
                'type' => 'text'
            ); 
            $fields[] = array('type' => 'sectionend', 'id' => 'general_options');
            return $fields;
        }

    public static function pal_contact_form_general_setting() {
            $setting_fields = self::pal_contact_form_setting_fields();
            $Html_output = new Pal_For_Contact_Form_Html_output();
            ?> 
        <form id="Pal_For_Contact_Form" enctype="multipart/form-data" action="" method="post">
        <?php $Html_output->init($setting_fields); ?>            
            <p class="submit">
                <input type="submit" name="pal_for_contact_form" class="button-primary" value="<?php esc_attr_e('Save changes', 'Option'); ?>" />
            </p>
        </form>
        <?php
    }

    public static function pal_contact_form_general_setting_save_field() {
        $setting_fields = self::pal_contact_form_setting_fields();
        $Html_output = new Pal_For_Contact_Form_Html_output();
        $Html_output->save_fields($setting_fields);
    }
    
    public static function pal_contact_form_language() {
        return array(
            'da_DK' => 'Danish',
            'nl_BE' => 'Dutch',
            'EN_US' => 'English',
            'fr_CA' => 'French',
            'de_DE' => 'German',
            'he_IL' => 'Hebrew',
            'it_IT' => 'Italian',
            'ja_JP' => 'Japanese',
            'no_NO' => 'Norwgian',
            'pl_PL' => 'Polish',
            'pt_BR' => 'Portuguese',
            'ru_RU' => 'Russian',
            'es_ES' => 'Spanish',
            'sv_SE' => 'Swedish',
            'zh_CN' => 'Simplified Chinese -China only',
            'zh_HK' => 'Traditional Chinese - Hong Kong only',
            'zh_TW' => 'Traditional Chinese - Taiwan only',
            'tr_TR' => 'Turkish',
            'th_TH' => 'Thai',
            'en_GB' => 'English - UK'
        );
       
    }
    
    public static function pal_contact_form_currency() {
       
        $county_code = array(
            'AUD' => __('Australian Dollars', 'pal-for-contact-form'),
            'BRL' => __('Brazilian Real', 'pal-for-contact-form'),
            'CAD' => __('Canadian Dollars', 'pal-for-contact-form'),
            'CZK' => __('Czech Koruna', 'pal-for-contact-form'),
            'DKK' => __('Danish Krone', 'pal-for-contact-form'),
            'EUR' => __('Euros', 'pal-for-contact-form'),
            'HKD' => __('Hong Kong Dollar', 'pal-for-contact-form'),
            'HUF' => __('Hungarian Forint', 'pal-for-contact-form'),
            'ILS' => __('Israeli Shekel', 'pal-for-contact-form'),
            'JPY' => __('Japanese Yen', 'pal-for-contact-form'),
            'MYR' => __('Malaysian Ringgits', 'pal-for-contact-form'),
            'MXN' => __('Mexican Peso', 'pal-for-contact-form'),
            'NOK' => __('Norwegian Krone', 'pal-for-contact-form'),
            'NZD' => __('New Zealand Dollar', 'pal-for-contact-form'),
            'PHP' => __('Philippine Pesos', 'pal-for-contact-form'),
            'PLN' => __('Polish Zloty', 'pal-for-contact-form'),
            'GBP' => __('Pounds Sterling', 'pal-for-contact-form'),
            'RUB' => __('Russian Ruble', 'pal-for-contact-form'),
            'SGD' => __('Singapore Dollar', 'pal-for-contact-form'),
            'SEK' => __('Swedish Krona', 'pal-for-contact-form'),
            'CHF' => __('Swiss Franc', 'pal-for-contact-form'),
            'TWD' => __('Taiwan New Dollars', 'pal-for-contact-form'),
            'THB' => __('Thai Baht', 'pal-for-contact-form'),
            'TRY' => __('Turkish Lira', 'pal-for-contact-form'),
            'USD' => __('US Dollars', 'pal-for-contact-form'),
        );    

        foreach ($county_code as $code => $name) {
            $county_code[$code] = $name . ' (' . self::pal_contact_form_currency_symbol($code) . ')';
        }
        return $county_code;
    }
    
    public static function pal_contact_form_currency_symbol($currency = '') {

        $currency_symbol = "";
        switch ($currency) {
            case 'AED' :
                $currency_symbol = 'د.إ';
                break;
            case 'AUD' :
            case 'ARS' :
            case 'CAD' :
            case 'CLP' :
            case 'COP' :
            case 'HKD' :
            case 'MXN' :
            case 'NZD' :
            case 'SGD' :
            case 'USD' :
                $currency_symbol = '&#36;';
                break;
            case 'BDT':
                $currency_symbol = '&#2547;&nbsp;';
                break;
            case 'BGN' :
                $currency_symbol = '&#1083;&#1074;.';
                break;
            case 'BRL' :
                $currency_symbol = '&#82;&#36;';
                break;
            case 'CHF' :
                $currency_symbol = '&#67;&#72;&#70;';
                break;
            case 'CNY' :
            case 'JPY' :
            case 'RMB' :
                $currency_symbol = '&yen;';
                break;
            case 'CZK' :
                $currency_symbol = '&#75;&#269;';
                break;
            case 'DKK' :
                $currency_symbol = 'DKK';
                break;
            case 'DOP' :
                $currency_symbol = 'RD&#36;';
                break;
            case 'EGP' :
                $currency_symbol = 'EGP';
                break;
            case 'EUR' :
                $currency_symbol = '&euro;';
                break;
            case 'GBP' :
                $currency_symbol = '&pound;';
                break;
            case 'HRK' :
                $currency_symbol = 'Kn';
                break;
            case 'HUF' :
                $currency_symbol = '&#70;&#116;';
                break;
            case 'IDR' :
                $currency_symbol = 'Rp';
                break;
            case 'ILS' :
                $currency_symbol = '&#8362;';
                break;
            case 'INR' :
                $currency_symbol = 'Rs.';
                break;
            case 'ISK' :
                $currency_symbol = 'Kr.';
                break;
            case 'KIP' :
                $currency_symbol = '&#8365;';
                break;
            case 'KRW' :
                $currency_symbol = '&#8361;';
                break;
            case 'MYR' :
                $currency_symbol = '&#82;&#77;';
                break;
            case 'NGN' :
                $currency_symbol = '&#8358;';
                break;
            case 'NOK' :
                $currency_symbol = '&#107;&#114;';
                break;
            case 'NPR' :
                $currency_symbol = 'Rs.';
                break;
            case 'PHP' :
                $currency_symbol = '&#8369;';
                break;
            case 'PLN' :
                $currency_symbol = '&#122;&#322;';
                break;
            case 'PYG' :
                $currency_symbol = '&#8370;';
                break;
            case 'RON' :
                $currency_symbol = 'lei';
                break;
            case 'RUB' :
                $currency_symbol = '&#1088;&#1091;&#1073;.';
                break;
            case 'SEK' :
                $currency_symbol = '&#107;&#114;';
                break;
            case 'THB' :
                $currency_symbol = '&#3647;';
                break;
            case 'TRY' :
                $currency_symbol = '&#8378;';
                break;
            case 'TWD' :
                $currency_symbol = '&#78;&#84;&#36;';
                break;
            case 'UAH' :
                $currency_symbol = '&#8372;';
                break;
            case 'VND' :
                $currency_symbol = '&#8363;';
                break;
            case 'ZAR' :
                $currency_symbol = '&#82;';
                break;
            default :
                $currency_symbol = '';
                break;
        }
        return $currency_symbol;
    }
}
Pal_For_Contact_Form_General_Setting::init();