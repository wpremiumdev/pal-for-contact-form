jQuery(function($) {
    jQuery('#pal_contact_form_sandbox').change(function () {
       
        var sandbox = jQuery('#pal_contact_form_sandbox_account').closest('tr'),
                production = jQuery('#pal_contact_form_live_account').closest('tr');
        if (jQuery(this).is(':checked')) {          
            sandbox.show();
            production.hide();
        } else {
            sandbox.hide();
            production.show();
        }
    }).change();
    
    jQuery('#pal_contact_form_paypal_enable').change(function () {       
        if (jQuery(this).is(':checked')) {  
            jQuery('#pal_contact_form_paypal_enable').val('1');
            jQuery('.pal_contact_form_paypal_enable_table').show();            
        } else {
            jQuery('#pal_contact_form_paypal_enable').val('0');
            jQuery('.pal_contact_form_paypal_enable_table').hide();            
        }
    }).change();
    
    jQuery(document).on('keyup change', '#pal_contact_form_item_price', function (e) {
        $(this).next('span').remove();
        var value = $(this).val();
        var regex = new RegExp('[^\-0-9\%\\' + '.' + ']+', 'gi');
        var newvalue = value.replace(regex, '');
        if (value !== newvalue) {
            $(this).val('');
            $('.pal_contact_form_item_price_remove').remove();
            $('.pal_contact_form_item_price_lable').after('<span class="pal_contact_form_item_price_remove" style="color:red">Numeric Only Allow.</span>');
        } else {
            $('.pal_contact_form_item_price_remove').remove();
        }
    });

});