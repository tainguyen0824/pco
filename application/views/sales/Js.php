<script>
    var Dom;
    Sales = {
        settings: {
           Overlay: $('#wrap-overlay'),
           DivEstimates: $('#Estimates'),
           DivWork_Order: $('#Work_Order'),
           DivInvoices: $('#Invoices'),
        },   
        LoadEstimates: function(){
            Js_Top.show_loading();
            Dom.DivEstimates.empty();
            $.ajax({
                type: 'POST',
                url: '<?php echo url::base() ?>sales/LoadEstimates',
                success: function (data) {
                    Dom.DivEstimates.html(data);
                    jquery_plugins.DropdownHover();
                    Js_Top.hide_loading();
                }
           });
        },
        CreateEstimates: function(){
            Js_Top.show_loading();
            $.ajax({
                type: 'GET',
                url: '<?php echo url::base() ?>sales/CreateEstimates', 
                success: function (data) {
                    Dom.Overlay.html(data);
                    Js_Top.openNav();
                    Js_Top.hide_loading();
                    Js_Top.Load_Index_Existing_New('sales');
                }
            });
        },
        Ready: function(){
           Sales.LoadEstimates();
        },
        init: function() {
            Dom = this.settings;
            Dom.DivEstimates;
            Dom.DivWork_Order;
            Dom.DivInvoices;
            Dom.Overlay;
            this.Ready();
        }   
    };

    var Dom_Estimates;
    Estimates = {
        settings: {
           Overlay: $('#wrap-overlay')
        },   
        SaveEstimates: function(t_this){
            var flag_save    = false;
            var flag_requied = false;
            var DOM = $(t_this).parents('#wrap-close-overlay').next('.overlay-content').children();
            if(DOM.find('#new_customers_calendar').length){
                if(DOM.find("input[name='customer_name']").val() == ''){
                    $.growl.error({ message: "Customer Name no empty." });
                    return false;
                }
                if(DOM.find("input[name='customer_no']").val() == ''){
                    $.growl.error({ message: "Customer Number no empty." });
                    return false;
                }
                if($('.CheckExistsCustomerNumber').val() == 0){
                    $.growl.error({ message: "This number already exists." });
                    return false; 
                }
                flag_save = true; 
            }else{
                if($('.Wap_Billing_Address').find('input[name="billing_id"]').length == 0){
                    $.growl.error({ message: "Please select customers." });
                    return false;
                }
                flag_save = true; 
            }
            //flag_requied = Js_Top.CheckRequiedScheduling();
            flag_requied = true;
            if(flag_save && flag_requied){
                var data = $( "form.save_existing_new" ).serializeArray();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo url::base() ?>calendar/save_calendar', 
                    data : data,
                    success: function (data) {
                       Js_Top.closeNav();
                    }
                });
            }
        },
        Ready: function(){
           
        },
        init: function() {
            Dom_Estimates = this.settings;
            Dom_Estimates.Overlay;
            this.Ready();
        }   
    };


    $(document).ready(function() {
        Sales.init();
    });
</script>