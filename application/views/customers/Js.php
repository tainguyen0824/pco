<script>
    var Dom;
    Customer = {
        settings: {
           Overlay: $('#wrap-overlay'),
           DivActive: $('#active_customers'),
           DivInactive: $('#inactive_customers'),
        },   
        LoadCustomers: function(ac_in_tive){
            Js_Top.show_loading();
            Dom.DivActive.empty();
            Dom.DivInactive.empty();
            $.ajax({
                type: 'POST',
                url: '<?php echo url::base() ?>customers/LoadCustomers',
                data: {ac_in_tive : ac_in_tive},
                success: function (data) {
                    if(ac_in_tive == 'active')
                        Dom.DivActive.html(data);
                    else
                        Dom.DivInactive.html(data);
                    jquery_plugins.DropdownHover();
                    Js_Top.hide_loading();
                }
           });
        },
        AddCustomers: function(){
            Js_Top.show_loading();
            $.ajax({
                type: 'GET',
                url: '<?php echo url::base() ?>customers/add_customers', 
                success: function (data) {
                    Dom.Overlay.html(data);
                    Js_Top.openNav();
                    Js_Top.hide_loading();
                    jquery_plugins.MaskPhone();
                    jquery_plugins.NoSpaceInput();
                }
            });
        },
        EditCustomer: function(id,tab_active){
            Js_Top.show_loading();
            $.ajax({
                type: 'POST',
                url: '<?php echo url::base() ?>customers/E_Edit_Customers', 
                data: {id:id},
                success: function (data) {
                    Dom.Overlay.html(data);
                    Js_Top.openNav();
                    Js_Top.hide_loading();
                    $('.nav-tabs li:eq('+tab_active+') a').tab('show');
                    if(tab_active == 0){
                        Customer_Edit.Load_Service_Group();
                    }else if(tab_active == 1){
                        Customer_Edit.Load_Accounting();
                    }
                    Customer_Edit.LoadLogs(id);
                    Customer_Edit.LoadInvoiceBalance(id);
                }
            });
        },
        Ready: function(){
           Customer.LoadCustomers('active');
        },
        init: function() {
            Dom = this.settings;
            Dom.DivActive;
            Dom.DivInactive;
            Dom.Overlay;
            this.Ready();
        }   
    };

    var Dom_Add;
    Customer_Add = {
        settings: {},
        // service 1
            AddContacts: function (t_this) {
                if($('.index_contact').length){
                    var nums = $(".index_contact").map(function(i, e) {
                        return parseInt($(e).val());
                    }).get();
                    var index_contact = Math.max.apply(this, nums) + 1;
                }else{
                    var index_contact = 1;
                }
                $('.btn-add-contact').hide();
                Js_Top.Add_Image_Loading($(t_this).parent().parent());
                $.ajax({
                    type: 'POST',
                    url: '<?php echo url::base() ?>customers/add_contacts', 
                    data:{index_contact:index_contact},
                    success: function (data) {
                        $('#wrap_add_contacts').append(data);
                        $('.btn-add-contact').show();
                        jquery_plugins.MaskPhone();
                        $(t_this).parent().parent().children().eq(-1).remove();
                    }
                });
            },
            Remove_Contact: function(t_this){
                $(t_this).parent().parent().remove();
            },
        // End service 1

        // service 2
            LoadFirstService: function(index_service) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo url::base() ?>customers/add_services',
                    data:{index_service : index_service}, 
                    success: function (data) {
                        if(index_service == 1){
                            $('.tab_content_service').removeAttr('style');
                            $('.tab_content_service').children().eq(0).remove();
                        }
                        $(".tab_service_customer").find('li:last-child').before('<li><a href="#service_' + index_service + '">Service ' + index_service + '</a> </li>');
                        $('.tab_content_service').append(data);
                        $(".tab_service_customer").find('li:last-child').prev().addClass('active');
                        $('.tab_content_service').children().addClass('active');
                        jquery_plugins.MaskPhone();
                        Js_Top.LoadAddMainService(index_service,'Add_customers','','');
                    }
                });
            },
            AddService: async function() {
                $(".tab_service_customer").find('li:last-child button').prop('disabled', true);
                if($('.index_service').length){
                    var nums = $(".index_service").map(function(i, e) {
                       return parseInt($(e).val());
                   }).get();
                    var index_service = Math.max.apply(this, nums) + 1;
                }else{
                    var index_service = 1;
                }

                $.ajax({
                    type: 'POST',
                    url: '<?php echo url::base() ?>customers/add_services',
                    data:{index_service : index_service}, 
                    success: function (data) {
                        $(".tab_service_customer").find('li:last-child').before('<li><a href="#service_' + index_service + '">Service ' + index_service + '</a> </li>');
                        $('.tab_content_service').append(data);
                        $(".tab_service_customer li").removeClass('active');
                        $(".tab_service_customer").find('li:last-child').prev().addClass('active');
                        $('.tab_content_service').children('.active').removeClass('active');
                        $('.tab_content_service').children('div:last-child').addClass('active');
                        $(".tab_service_customer").find('li:last-child button').prop('disabled', false);
                        jquery_plugins.MaskPhone();
                        Js_Top.LoadAddMainService(index_service,'Add_customers','','');
                    }
                });
            },
            CloseService: function() {
                if($('.tab_content_service').children('div').length > 1){
                    $('ul.tab_service_customer li.active').remove();
                    $('.tab_content_service').children('div.active').remove();
                    $('.tab_service_customer a:last').tab('show');
                }else{
                    Js_Top.wanrning('You can not delete the last service.');
                }
            },
            CheckboxSameBilling:function(t_this){
                var id = $(t_this).data('id');
                if($(t_this).is(':checked')){
                    Customer_Add.InitFormSameBilling($(t_this),id);
                    $('div.service_address_'+id).find('input[type="text"]').attr('readonly',true);
                    $('div.service_address_'+id).find('textarea').attr('readonly',true);

                    $('div.service_address_'+id).find('select.same_as_billing_dis').parent().css('position', 'relative');
                    $('div.service_address_'+id).find('select.same_as_billing_dis').parent().append('<div></div>');
                    $('div.service_address_'+id).find('select.same_as_billing_dis').next().css({
                        'position': 'absolute',
                        'z-index': '9999',
                        'background-color': 'rgba(0, 0, 0, 0)',
                        'width': '100%',
                        'height': '100%',
                        'top': '0%'
                    });
                    $('div.service_address_'+id).find('select.same_as_billing_dis').attr('readonly',true);

                    $('div.service_address_'+id).find('input[type="checkbox"][class="dis_same_as_billing"]').parent().css('position', 'relative');
                    $('div.service_address_'+id).find('input[type="checkbox"][class="dis_same_as_billing"]').parent().append('<div></div>');
                    $('div.service_address_'+id).find('input[type="checkbox"][class="dis_same_as_billing"]').next().next().css({
                        'position': 'absolute',
                        'z-index': '9999',
                        'background-color': 'rgba(0, 0, 0, 0)',
                        'width': '100%',
                        'height': '100%',
                        'top': '0%'
                    });
                    $('div.service_address_'+id).find('input[type="checkbox"][class="dis_same_as_billing"]').attr('readonly',true);

                    $('div.service_address_'+id).find('input[type="radio"]').parent().parent().append('<div></div>');
                    $('div.service_address_'+id).find('input[type="radio"]').parent().next().next().css({
                        'position': 'absolute',
                        'z-index': '9999',
                        'background-color': 'rgba(0, 0, 0, 0)',
                        'width': '100%',
                        'height': '100%',
                        'top': '0%',
                        'left': '0%'
                    });
                    $('div.service_address_'+id).find('input[type="radio"]').attr('readonly',true);
                }else{
                    $('div.service_address_'+id).find('input[type="text"]').attr('readonly',false);
                    $('div.service_address_'+id).find('textarea').attr('readonly',false);
                    $('div.service_address_'+id).find('select.same_as_billing_dis').attr('readonly',false);
                    $('div.service_address_'+id).find('select.same_as_billing_dis').next().remove();
                    $('div.service_address_'+id).find('input[type="checkbox"][class="dis_same_as_billing"]').next().next().remove();
                    $('div.service_address_'+id).find('input[type="checkbox"][class="dis_same_as_billing"]').attr('readonly',false);
                    $('div.service_address_'+id).find('input[type="radio"]').parent().next().next().remove();
                    $('div.service_address_'+id).find('input[type="radio"]').attr('readonly',false);
                }   
            },
            OnChangeBilling: function(){
                $('input[name="index_service[]"]').each(function() {
                    var id = $(this).val();
                    Customer_Add.InitFormSameBilling($('input[name="same_as_billing_address_'+id+'"]'),id);
                });  
            },
            InitFormSameBilling: function(DomCheckbox,id){
                (function ($) {
                    $.fn.serializeFormJSON = function () {

                        var o = {};
                        var a = this.serializeArray();
                        $.each(a, function () {
                            if (o[this.name]) {
                                if (!o[this.name].push) {
                                    o[this.name] = [o[this.name]];
                                }
                                o[this.name].push(this.value || '');
                            } else {
                                o[this.name] = this.value || '';
                            }
                        });
                        return o;
                    };
                })(jQuery);

                if(DomCheckbox.is(':checked')){
                    var FrmData = $('.add_new_customer').serializeFormJSON();
                    $('input[name="service_address_name_'+id+'"]').val(FrmData.billing_name);
                    $('input[name="service_atn_'+id+'"]').val(FrmData.billing_attention);
                    $('input[name="service_address_1_'+id+'"]').val(FrmData.billing_address_1);
                    $('input[name="service_address_2_'+id+'"]').val(FrmData.billing_address_2);
                    $('input[name="service_city_'+id+'"]').val(FrmData.billing_city);
                    $('select[name="service_state_'+id+'"] option[value='+FrmData.billing_state+']').prop('selected',true);
                    $('input[name="service_zip_'+id+'"]').val(FrmData.billing_zip);
                    $('input[name="service_county_'+id+'"]').val(FrmData.billing_county);
                    var Total_Phone = FrmData["billing_phone_type[]"].length - 2;
                    $('#wrap_add_phone_service_'+id).empty();
                    $.each(FrmData["billing_phone_type[]"], function( index, value ) { 
                        if(index < Total_Phone){
                            Js_Top.AddPhone('clone_phone_service_','wrap_add_phone_service_',id);
                        }
                        if(index <= Total_Phone){
                            $('input[name="service_phone_number_'+id+'[]"]:eq('+index+')').val(FrmData["billing_phone_number[]"][index]);
                            $('input[name="service_phone_ext_'+id+'[]"]:eq('+index+')').val(FrmData["billing_phone_ext[]"][index]);
                            $('select[name="service_phone_type_'+id+'[]"]:eq('+index+') option[value='+FrmData["billing_phone_type[]"][index]+']').prop('selected',true);
                        }
                    });
                    $('input[name="service_phone_primary_'+id+'"]:eq('+FrmData.billing_index_primary+')').prop('checked',true);
                    $('.index_phone_service_'+id).val(FrmData.billing_index_primary);
                    if(FrmData.billing_invoice_email){
                        $('input[name="service_invoice_email_'+id+'"]').prop('checked',true);
                    }else{
                        $('input[name="service_invoice_email_'+id+'"]').prop('checked',false);
                    }
                    if(FrmData.billing_work_order_email){
                        $('input[name="service_work_order_email_'+id+'"]').prop('checked',true);
                    }else{
                        $('input[name="service_work_order_email_'+id+'"]').prop('checked',false);
                    }
                    $('input[name="service_email_'+id+'"]').val(FrmData.billing_email);
                    $('input[name="service_websites_'+id+'"]').val(FrmData.billing_websites);
                    $('textarea[name="service_notes_'+id+'"]').val(FrmData.billing_notes);
                }
            },
        // end service 2

        // serive 3
            LoadTemplateFinish: function(){
                var data = $('form.add_new_customer').serializeArray();
                $.ajax({
                    url: '<?php echo url::base() ?>customers/LoadTemplateFinish',
                    type: 'POST',
                    data: data,
                })
                .done(function(d) {
                    $('.template_finish').html(d);
                });   
            },
        // end service 3

        NextStep: function(t_this) {
            $('input[name="skip_service"]').val(0);
            var parent_fieldset = $(t_this).parents('fieldset');
            var ChkNextStep = true;
            Js_Top.show_loading();

            // Check Step 1
            if(parent_fieldset.attr('class') == 'fieldset_1'){

                var DOM = parent_fieldset.parents('#wrap-close-overlay').next('.overlay-content').children();
                if(DOM.find("input[name='customer_name']").val() == ''){
                    Js_Top.error('Customer Name empty.');
                    Js_Top.hide_loading();
                    ChkNextStep = false;
                    return false;
                }

                if(DOM.find("input[name='customer_no']").val() == ''){
                    Js_Top.error('Customer No empty.');
                    Js_Top.hide_loading();
                    ChkNextStep = false;
                    return false;
                }

                $.ajax({
                    type:'POST',
                    url:''+domain_origin+'/service/CheckExistsCustomerNumber',
                    data:{number_customer:DOM.find("input[name='customer_no']").val()},
                    success:function(data){
                        if(data == 1){
                            Js_Top.hide_loading();
                            Js_Top.error('This Customer No already exists.');
                        }else{
                            if(ChkNextStep){
                                if($('.tab_content_service').children('div').length < 1){
                                    Js_Top.Add_Image_Loading($('.tab_content_service'));
                                    $('.tab_content_service').css('background-color','#fff');
                                    $('.tab_content_service').children().css({'margin':'0 auto'});
                                    Customer_Add.LoadFirstService(1);
                                }
                                parent_fieldset.fadeOut(400, function() {
                                    $(this).next().fadeIn();
                                    var class_this = $(this).get(0).className;
                                    $('#overlay-content fieldset.' + class_this).hide();
                                    $('#overlay-content fieldset.' + class_this).next().show();
                                    Js_Top.ChangeWidthOverlay();
                                    var Height_content_overlay = document.getElementById('wrap-close-overlay').clientHeight;
                                    document.getElementById("overlay-content").style.top = Height_content_overlay+'px';
                                });
                                Js_Top.hide_loading();
                            }
                        }   
                    }
                });
            }
            
            // Check Step 2
            if(parent_fieldset.attr('class') == 'fieldset_2'){
                // ****** Get Index Service
                var promiseRemoveKeySession = Js_Top.RemoveALLKeySession();   // Xoá tất cả Keysession tồn tại
                promiseRemoveKeySession.success(function (data) {

                    // Get Many Keys
                    var ArrIndex = new Array();
                    $('input[name^=scheduling_first_date_]').each(function(index, el) {
                        var name_FirstDate = $(el).attr('name');
                        var index_service  = name_FirstDate.replace(/[^0-9]/g, '');
                        ArrIndex.push(index_service);
                    });

                    // Lấy tất cả thời gian sét manually time
                    jQuery.each(ArrIndex, function(index, index_service) {
                        var FirstDate          = moment($('input[name="scheduling_first_date_'+index_service+'"]').val()).format('YYYY-MM-DD');
                        var Hours              = $('input[name="hours_'+index_service+'"]').val();
                        var Minutes            = $('input[name="minutes_'+index_service+'"]').val();
                        var Scheduling_Options = $('input[name="start_time_'+index_service+'"]:checked').val();
                        var TypeUnderFrequency = $('input[name="option_scheduling_'+index_service+'"]:checked').val();
                        var ManuallyTime       = $('input[name="time_start_scheduling_'+index_service+'"]').val();
                        var G_Technician       = $('select[name="scheduling_technician_'+index_service+'"] option:selected').val();
                        if(G_Technician == '')
                            G_Technician = 0;
                        var DateTimeStart = FirstDate+' '+ManuallyTime;
                        var DateTimeEnd = moment(DateTimeStart).add(Hours, 'hours').add(Minutes, 'minutes').format('YYYY-MM-DD hh:mm a');

                        if(((Hours == '' || Hours <= 0) && (Minutes == '' || Minutes <= 0))){
                            Js_Top.error('Service Duration empty. Please fill out scheduling information for this service !');
                            Js_Top.hide_loading();
                            ChkNextStep = false;
                            return false;
                        }else if(Scheduling_Options == 'manually_settime' && TypeUnderFrequency != 'slt_push' && ManuallyTime == ''){
                            Js_Top.error('Manually set time empty.');
                            Js_Top.hide_loading();
                            ChkNextStep = false;
                            return false;
                        }else if(Scheduling_Options == 'manually_settime' && TypeUnderFrequency != 'slt_push'){
                            $.ajax({
                                url: ''+domain_origin+'/service/CreateKeySession', 
                                type: 'POST',
                                async: false,
                                data: {G_Technician: G_Technician, DateTimeStart: DateTimeStart, DateTimeEnd: DateTimeEnd},
                            });
                        }
                    });

                    if(!ChkNextStep){
                        return false;
                        Js_Top.hide_loading();
                    }

                    // Lấy thời gian tự động
                    var ArrDateOver = [];
                    var DateNoValid = [];
                    jQuery.each(ArrIndex, function(index, index_service) {
                        var FirstDate          = moment($('input[name="scheduling_first_date_'+index_service+'"]').val()).format('YYYY-MM-DD');
                        var Hours              = $('input[name="hours_'+index_service+'"]').val();
                        var Minutes            = $('input[name="minutes_'+index_service+'"]').val();
                        var Scheduling_Options = $('input[name="start_time_'+index_service+'"]:checked').val();
                        var TypeUnderFrequency = $('input[name="option_scheduling_'+index_service+'"]:checked').val();
                        var ManuallyTime       = $('input[name="time_start_scheduling_'+index_service+'"]').val();
                        var ServiceName        = $('input[name="service_name_'+index_service+'"]').val();
                        var G_Technician       = $('select[name="scheduling_technician_'+index_service+'"] option:selected').val();
                        if(G_Technician == '')
                            G_Technician = 0;
                        var Frequency          = $('select[name="scheduling_frequency_'+index_service+'"] option:selected').val();

                        if(Scheduling_Options == 'automatically_settime' && TypeUnderFrequency != 'slt_push'){
                            
                            var CheckTimeValid = true;
                            $.ajax({
                                url: ''+domain_origin+'/service/GetTimeAuto/'+FirstDate+'/'+Hours+'/'+Minutes+'/'+G_Technician+'/'+Frequency,
                                type: 'GET',
                                async: false,
                                dataType: 'json'
                            })
                            .done(function(d) {
                                if(d.message == 'time_no_valid'){
                                    CheckTimeValid = false;
                                }else{
                                    var DateAuto = moment(d.start).format('YYYY-MM-DD');
                                    if(DateAuto != FirstDate){
                                        if(d.DateValidJson)
                                            ArrDateOver.push([index_service, DateAuto, FirstDate, Frequency]);   // thời gian có thể cho qua ngày
                                        else
                                            DateNoValid.push([index_service, DateAuto, FirstDate, Frequency]);   // thời gian không thể qua ngày vì tuần suất
                                    }
                                    var TimeAuto = moment(d.start).format('hh:mm a');
                                    $('.TimeAuto_'+index_service).val(TimeAuto);
                                }     
                            }); 

                            if(!CheckTimeValid){
                                Js_Top.error('Specified service duration is too long.');
                                Js_Top.hide_loading();
                                ChkNextStep = false;
                                return false;
                            }

                        }       
                    });

                    // Show những thằng qua ngày
                    if (ArrDateOver.length > 0) {   // thời gian có thể qua ngày

                        var $ServiceOverDate = '';
                        $.each( ArrDateOver, function( key, value ) {
                            $ServiceOverDate +='['+$('input[name="service_name_'+value[0]+'"]').val()+'], '; 
                        });
                        $ServiceOverDate = $ServiceOverDate.substring(0,$ServiceOverDate.length - 2);

                        $.confirm({
                            title: 'Message',
                            content: 'System could not find available space to accommodate for the specified event duration on scheduled dates. Would you like to offset some of the scheduled events to avoid conflicts?'+'<p style="font-weight:bold">'+$ServiceOverDate+'</p>',
                            columnClass: 'col-md-4 col-centered',
                            containerFluid: true,
                            buttons: {                     
                                OK: {
                                    text: 'OK',
                                    btnClass: 'btn-blue',
                                    action: function(){
                                        $.each( ArrDateOver, function( key, value ) {
                                            $('input[name="scheduling_first_date_'+value[0]+'"]').datepicker('setDate', moment(value[1]).format('MM/DD/YYYY'));
                                        });
                                        if(DateNoValid.length > 0) {  // thời gian không cho qua ngày vì tuần suất
                                            var Arr_Str_OverDay_InValid = '';
                                            $.each( DateNoValid, function( key_1, value_1 ) {
                                                Arr_Str_OverDay_InValid +='['+$('input[name="service_name_'+value_1[0]+'"]').val()+'], '; 
                                            });
                                            Arr_Str_OverDay_InValid = Arr_Str_OverDay_InValid.substring(0,Arr_Str_OverDay_InValid.length - 2);
                                            if(Arr_Str_OverDay_InValid != ''){
                                                $.confirm({
                                                    title: 'Message',
                                                    content: 'The system can not find the available space to meet the specified event time on scheduled dates. Some events may overlap.'+'<p style="font-weight:bold">'+Arr_Str_OverDay_InValid+'</p>',
                                                    columnClass: 'col-md-4 col-centered',
                                                    containerFluid: true,
                                                    buttons: {                     
                                                        OK: {
                                                            text: 'OK',
                                                            btnClass: 'btn-blue',
                                                            action: function(){
                                                                Customer_Add.Private_Next_Step2(parent_fieldset);
                                                                this.close();
                                                            }
                                                        }
                                                    }
                                                });
                                            }else{
                                                Customer_Add.Private_Next_Step2(parent_fieldset);
                                            }
                                        }else{
                                            Customer_Add.Private_Next_Step2(parent_fieldset);
                                        }
                                    }
                                },
                                CANCEL: function () {
                                    Customer_Add.Private_Next_Step2(parent_fieldset);
                                    this.close();
                                },
                            }
                        });
                        Js_Top.hide_loading();
                    }else{
                        if(ChkNextStep){
                            if(DateNoValid.length > 0) {    // thời gian không cho qua ngày vì tuần suất
                                var Arr_Str_OverDay_InValid = '';
                                $.each( DateNoValid, function( key_1, value_1 ) {
                                    Arr_Str_OverDay_InValid +='['+$('input[name="service_name_'+value_1[0]+'"]').val()+'], '; 
                                });
                                Arr_Str_OverDay_InValid = Arr_Str_OverDay_InValid.substring(0,Arr_Str_OverDay_InValid.length - 2);
                                if(Arr_Str_OverDay_InValid != ''){
                                    $.confirm({
                                        title: 'Message',
                                        content: 'The system can not find the available space to meet the specified event time on scheduled dates. Some events may overlap.'+'<p style="font-weight:bold">'+Arr_Str_OverDay_InValid+'</p>',
                                        columnClass: 'col-md-4 col-centered',
                                        containerFluid: true,
                                        buttons: {                     
                                            OK: {
                                                text: 'OK',
                                                btnClass: 'btn-blue',
                                                action: function(){
                                                    Customer_Add.Private_Next_Step2(parent_fieldset);
                                                    this.close();
                                                }
                                            }
                                        }
                                    });
                                }else{
                                    Customer_Add.Private_Next_Step2(parent_fieldset);
                                }
                            }else{
                                Customer_Add.Private_Next_Step2(parent_fieldset);
                            }  
                        }
                        Js_Top.hide_loading();
                    }   
                });
            }    
        },
        Private_Next_Step2: function(parent_fieldset){
            Customer_Add.LoadTemplateFinish();
            if($('.tab_content_service').children('div').length < 1){
                Js_Top.Add_Image_Loading($('.tab_content_service'));
                $('.tab_content_service').css('background-color','#fff');
                $('.tab_content_service').children().css({'margin':'0 auto'});
                Customer_Add.LoadFirstService(1);
            }
            parent_fieldset.fadeOut(400, function() {
                $(this).next().fadeIn();
                var class_this = $(this).get(0).className;
                $('#overlay-content fieldset.' + class_this).hide();
                $('#overlay-content fieldset.' + class_this).next().show();
                Js_Top.ChangeWidthOverlay();
                var Height_content_overlay = document.getElementById('wrap-close-overlay').clientHeight;
                document.getElementById("overlay-content").style.top = Height_content_overlay+'px';
            });
            Js_Top.hide_loading();
        },
        PrevStep: function(t_this) {
            $(t_this).parents('fieldset').fadeOut(400, function() {
                $(this).prev().fadeIn();
                var class_this = $(this).get(0).className;
                $('#overlay-content fieldset.' + class_this).hide();
                $('#overlay-content fieldset.' + class_this).prev().show();
                Js_Top.ChangeWidthOverlay();
                var Height_content_overlay = document.getElementById('wrap-close-overlay').clientHeight;
                document.getElementById("overlay-content").style.top = Height_content_overlay+'px';
            });
        },
        SkipStep: function(t_this) {
            $('input[name="skip_service"]').val(1);
            if($(t_this).parents('fieldset').attr('class') == 'fieldset_2'){
                Customer_Add.LoadTemplateFinish();
            }
            $(t_this).parents('fieldset').fadeOut(400, function() {
                $(this).next().fadeIn();
                var class_this = $(this).get(0).className;
                $('#overlay-content fieldset.' + class_this).hide();
                $('#overlay-content fieldset.' + class_this).next().show();
                Js_Top.ChangeWidthOverlay();
            });
        },    
        SaveCustomers : function() {
            var data = $('form.add_new_customer').serializeArray();
            $.ajax({
                type: 'POST',
                url: '<?php echo url::base() ?>customers/save_customers',
                data:data, 
                success: function (data) {
                    Customer.LoadCustomers('active');
                    Js_Top.closeNav();
                }
            });
        },
        Ready: function(){	
        },
        init: function() {  
            Dom_Add = this.settings;
            Dom_Add.CustomerNumber = $('.customer_number');
            this.Ready();
        }   
    };

    var Dom_Edit, tbl_Accounting_Ecustomer, tbl_Preview_Accounting_Ecustomer, tbl_Active_History;
    Customer_Edit = {
        settings: {
            Overlay: $('#wrap-overlay'),
        },
        Change_Tab_Edit: function(str) {
            Customer_Edit.Close_Full_Sreen_Accounting();
            if(str == 'service_group')
                Customer_Edit.Load_Service_Group();
            else if(str == 'accounting')
                Customer_Edit.Load_Accounting();
            else if(str == 'active_history')
                Customer_Edit.Load_Active_History();
            else if(str == 'credit_card')
                Customer_Edit.Load_Credit_Card();
            else if(str == 'attachments')
                Customer_Edit.Load_Attachments();
        },
        LoadLogs: function(customer_id){
            $('#edit-notes-content').empty().addClass('working_div_32 backgroundCenter');
            $.ajax({
                url: '<?php echo url::base() ?>customers/Listlogs',
                type: 'POST',
                data: {customer_id: customer_id},
            })
            .done(function(d) {
                $('#edit-notes-content').html(d);
                $('input[name="content_logs"]').val('');
                $('#edit-notes-content').removeClass('working_div_32 backgroundCenter');
            });
        },
        Savelogs: function(customer_id){
            var content_logs = $('input[name="content_logs"]').val();
            if(content_logs != ''){
                $.ajax({
                    url: '<?php echo url::base() ?>customers/Savelogs',
                    type: 'POST',
                    data: {content_logs:content_logs, customer_id:customer_id},
                })
                .done(function(d) {
                    if(d == 'success'){
                        Customer_Edit.LoadLogs(customer_id);
                        Js_Top.success('Save Success.');
                    }else{
                        Js_Top.error('Content log is empty.');
                    }
                });
            }else{
                Js_Top.error('Content log is empty.');
            }  
        },
        Deletelogs: function(log_id){
            $.ajax({
                url: '<?php echo url::base() ?>customers/Deletelogs',
                type: 'POST',
                data: {log_id:log_id},
            })
            .done(function(customer_id) {
                if(customer_id != ''){
                    Customer_Edit.LoadLogs(customer_id);
                    Js_Top.success('Delete Success.');
                }else{
                    Js_Top.error('Progress error.');
                }
            });
        },
        LoadInvoiceBalance: function(customer_id){
            $.ajax({
                url: '<?php echo url::base() ?>customers/LoadInvoiceBalance',
                type: 'POST',
                dataType: 'json',
                data: {customer_id: customer_id},
            })
            .done(function(d) {
                $('.total_balance').html(d.total_balance);
                $('.unapplied_payments').html(d.unapplied_payments);
                $('.open_invoices').html(d.open_invoices);
            });
        },
        Save_Edit_Details: function(customer_id){
            var formData = $('form#Edit_Details').serializeArray();
            formData.push({ name: "customer_id", value: customer_id });
            Js_Top.show_loading();
            $.ajax({
                url: '<?php echo url::base() ?>customers/Save_Edit_Details',
                type: 'POST',
                data: formData,
            })
            .done(function(data) {
                Js_Top.hide_loading();
                Customer_Edit.Close_Edit_Details(customer_id);
            });
        },
        EditDetails: function(customer_id){
            Js_Top.closeNav();
            Js_Top.show_loading();
            $.ajax({
                url: '<?php echo url::base() ?>customers/EditDetails',
                type: 'POST',
                data: {customer_id: customer_id},
            })
            .done(function(data) {
                Dom.Overlay.html(data);
                Js_Top.openNav();
                Js_Top.hide_loading();
                jquery_plugins.MaskPhone();
                jquery_plugins.NoSpaceInput();
            });
        },
        Close_Edit_Details: function(customer_id){
            Js_Top.closeNav();
            Customer.EditCustomer(customer_id,0);
        },
        //****** SERVICE GROUP //
            Load_Service_Group : function() {
                var customer_id = $('#wrap_customer_hide_input_edit #customer_id').val();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo url::base() ?>customers/E_Service_Group', 
                    data: {customer_id:customer_id},
                    success: function (data) {
                        $('#service_group').html(data);
                    }
                });
            },
            Edit_Service: function(customer_id,service_id) {
                Js_Top.closeNav();
                Js_Top.show_loading();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo url::base() ?>customers/E_Edit_Service', 
                    data: {customer_id:customer_id,service_id:service_id},
                    success: function (data) {
                        Dom.Overlay.html(data);
                        Js_Top.openNav();
                        Js_Top.hide_loading();
                        jquery_plugins.MaskPhone(); 
                        Js_Top.LoadAddMainService('','Edit_customers',customer_id,service_id);
                        // if($('input[name="same_as_billing_address_"]').is(':checked')){
                        //     Customer_Add.CheckboxSameBilling($('input[name="same_as_billing_address_"]'));
                        // }
                    }
                });
            },  
            Close_Edit_Service: function(customer_id){
                Js_Top.closeNav();
                Customer.EditCustomer(customer_id,0);
            },
            Delete_Service: function(customer_id,service_id){
                $.confirm({
                    title: 'Alert',
                    content: '<p>Delete this service?</p>',
                    buttons:{
                        OK: {
                            text: 'OK',
                            btnClass: 'btn btn-primary',
                            action: function () {
                                $.ajax({
                                    url: '<?php echo url::base() ?>customers/Delete_Service',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {customer_id: customer_id,service_id: service_id},
                                })
                                .done(function(d) {
                                    if(d.message){
                                        Customer_Edit.Close_Edit_Service(customer_id);
                                    }else{
                                    }
                                });
                            }
                        },
                        Cancel:{
                            text: 'Cancel',
                            btnClass: 'btn btn-default'
                        }
                    }
                })

            },
            E_Update_Service: function(customer_id,service_id){
                // $origform_Edit_Service in Js_Top (script.js => LoadAddMainService)
                
                var ChkNextStep       = true;
                Js_Top.show_loading();
 
                var ArrIndex = new Array();
                $('input[name^=scheduling_first_date_]').each(function(index, el) {
                    var name_FirstDate = $(el).attr('name');
                    var index_service  = name_FirstDate.replace(/[^0-9]/g, '');
                    ArrIndex.push(index_service);
                });

                // Lấy tất cả thời gian sét manually time
                jQuery.each(ArrIndex, function(index, index_service) {
                    var Hours              = $('input[name="hours_'+index_service+'"]').val();
                    var Minutes            = $('input[name="minutes_'+index_service+'"]').val();
                    var Scheduling_Options = $('input[name="start_time_'+index_service+'"]:checked').val();
                    var TypeUnderFrequency = $('input[name="option_scheduling_'+index_service+'"]:checked').val();
                    var ManuallyTime       = $('input[name="time_start_scheduling_'+index_service+'"]').val();

                    if(((Hours == '' || Hours <= 0) && (Minutes == '' || Minutes <= 0))){
                        Js_Top.error('Service Duration empty. Please fill out scheduling information for this service !');
                        Js_Top.hide_loading();
                        ChkNextStep = false;
                        return false;
                    }else if(Scheduling_Options == 'manually_settime' && TypeUnderFrequency != 'slt_push' && ManuallyTime == ''){
                        Js_Top.error('Manually set time empty.');
                        Js_Top.hide_loading();
                        ChkNextStep = false;
                        return false;
                    }
                });
                
                if(!ChkNextStep){
                    Js_Top.hide_loading();
                    return false;    
                }else{
                    Js_Top.hide_loading();
                    if($('form#Frm_E_Service').serialize() !== $origform_Edit_Service){
                        
                            $.ajax({
                                url: '<?php echo url::base() ?>customers/dialog_saving_change',
                                type: 'POST',
                                success: function (html_saving_change) {
                                    $.confirm({
                                        title: 'Saving Change',
                                        content: ''+html_saving_change+'',
                                        columnClass: 'col-md-4 col-centered',
                                        containerFluid: true,
                                        buttons: {                     
                                            OK: {
                                                text: 'OK',
                                                btnClass: 'btn-blue',
                                                action: function(){
                                                    var self              = this;
                                                    var eventAfterDate    = $('.eventAfterDate').val();
                                                    var eventAfterTime    = $('.eventAfterTime').val();
                                                    var ChangeDateService = $('.ChangeDateService:checked').val();
                                                    var ArrDateOver       = [];
                                                    var DateNoValid       = [];

                                                    var promiseRemoveKeySession = Js_Top.RemoveALLKeySession();   // Xoá tất cả Keysession tồn tại
                                                    promiseRemoveKeySession.success(function (data) {
                                                        // Lấy thời gian tự động
                                                        jQuery.each(ArrIndex, function(index, index_service){
                                                            if(ChangeDateService == 1)
                                                                var FirstDate = moment().add(1, 'days').format('YYYY-MM-DD');
                                                            else
                                                                var FirstDate = moment(eventAfterDate).format('YYYY-MM-DD');
                                                            
                                                            var Hours              = $('input[name="hours_'+index_service+'"]').val();
                                                            var Minutes            = $('input[name="minutes_'+index_service+'"]').val();
                                                            var Scheduling_Options = $('input[name="start_time_'+index_service+'"]:checked').val();
                                                            var TypeUnderFrequency = $('input[name="option_scheduling_'+index_service+'"]:checked').val();
                                                            var ManuallyTime       = $('input[name="time_start_scheduling_'+index_service+'"]').val();
                                                            var ServiceName        = $('input[name="service_name_'+index_service+'"]').val();
                                                            var G_Technician       = $('select[name="scheduling_technician_'+index_service+'"] option:selected').val();
                                                            if(G_Technician == '')
                                                                G_Technician = 0;
                                                            var Frequency          = $('select[name="scheduling_frequency_'+index_service+'"] option:selected').val();

                                                            if(Scheduling_Options == 'automatically_settime' && TypeUnderFrequency != 'slt_push'){
                                                                var CheckTimeValid = true;
                                                                $.ajax({
                                                                    url: ''+domain_origin+'/service/GetTimeAuto/'+FirstDate+'/'+Hours+'/'+Minutes+'/'+G_Technician+'/'+Frequency+'/'+service_id+'/'+ChangeDateService+'/'+eventAfterTime,
                                                                    type: 'GET',
                                                                    async: true,
                                                                    dataType: 'json'
                                                                })
                                                                .done(function(d) {
                                                                    if(d.message == 'time_no_valid'){
                                                                        CheckTimeValid = false;
                                                                    }else{
                                                                        var DateAuto = moment(d.start).format('YYYY-MM-DD');
                                                                        if(DateAuto != FirstDate){
                                                                            if(d.DateValidJson){
                                                                                ArrDateOver.push([index_service, DateAuto, FirstDate, Frequency]);   // thời gian có thể cho qua ngày
                                                                            }
                                                                            else{
                                                                                DateNoValid.push([index_service, DateAuto, FirstDate, Frequency]);   // thời gian không thể qua ngày vì tuần suất
                                                                            }
                                                                        }
                                                                        var TimeAuto = moment(d.start).format('hh:mm a');
                                                                        $('.TimeAuto_'+index_service).val(TimeAuto);
                                                                    }   

                                                                    if(!CheckTimeValid){
                                                                        self.close();
                                                                        Js_Top.error('Specified service duration is too long.');
                                                                        Js_Top.hide_loading();
                                                                        ChkNextStep = false;
                                                                        return false;
                                                                    }

                                                                    if (ArrDateOver.length > 0 || DateNoValid.length > 0) {   // thời gian có thể qua ngày || // thời gian không cho qua ngày vì tuần suất
                                                                        $.confirm({
                                                                            title: 'Message',
                                                                            content: 'System could not find available space to accommodate for the specified event duration on scheduled dates. Would you like to offset some of the scheduled events to avoid conflicts?',
                                                                            columnClass: 'col-md-4 col-centered',
                                                                            containerFluid: true,
                                                                            buttons: {                     
                                                                                OK: {
                                                                                    text: 'OK',
                                                                                    btnClass: 'btn-blue',
                                                                                    action: function(){
                                                                                        Customer_Edit.PrivateUpdateEvent(customer_id,service_id,ChangeDateService,eventAfterDate,eventAfterTime);    
                                                                                        this.close();
                                                                                        self.close();
                                                                                    }
                                                                                },
                                                                                CANCEL: function () {
                                                                                    this.close();
                                                                                },
                                                                            }
                                                                        });
                                                                        Js_Top.hide_loading();
                                                                    }else{
                                                                        if(ChkNextStep){
                                                                            Customer_Edit.PrivateUpdateEvent(customer_id,service_id,ChangeDateService,eventAfterDate,eventAfterTime);    
                                                                            self.close();
                                                                        } 
                                                                    }        
                                                                });  
                                                            }else{
                                                                self.close();
                                                                Customer_Edit.PrivateUpdateEvent(customer_id,service_id,ChangeDateService,eventAfterDate,eventAfterTime);
                                                            }      
                                                        });  
                                                    });
                                                    return false;
                                                }
                                            },
                                            CANCEL: function () {
                                                Customer_Edit.Close_Edit_Service(customer_id);
                                                self.close();
                                            },
                                        },
                                        onContentReady: function () {
                                            jquery_plugins.InitDatepicker();
                                            jquery_plugins.InitDatetimepicker();
                                        }
                                    });
                                }
                            })   
                         
                    }else{
                        Customer_Edit.Close_Edit_Service(customer_id);
                    } 
                }       
            },
            PrivateUpdateEvent: function(customer_id,service_id,ChangeDateService,eventAfterDate,eventAfterTime){
                var formData = $('#Frm_E_Service').serializeArray();
                formData.push({ name: "customer_id", value: customer_id });
                formData.push({ name: "service_id", value: service_id });
                formData.push({ name: "ChangeDateService", value: ChangeDateService });
                formData.push({ name: "eventAfterDate", value: eventAfterDate });
                formData.push({ name: "eventAfterTime", value: eventAfterTime });
                $.ajax({
                    url: '<?php echo url::base() ?>customers/E_Update_Service',
                    type: 'POST',
                    data: formData,
                })
                .done(function(d) {
                    Customer_Edit.Close_Edit_Service(customer_id);
                });   
            },
            SaveChangeEvents: function(type){
                if(type == 1){
                    $('.timeEventAfter').slideUp();
                }else{
                    $('.timeEventAfter').slideDown();
                }
            },
        //****** END SERVICE GROUP //

        //****** ACCOUNTING //
            Load_Accounting: function() {
                var customer_id = $('#wrap_customer_hide_input_edit #customer_id').val();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo url::base() ?>customers/accounting', 
                    data: {customer_id:customer_id},
                    success: function (data) {
                        $('#accounting').html(data);
                        Customer_Edit.ShowDataAccounting();
                        jquery_plugins.DropdownHover();
                    }
                });
            },
            ShowDataAccounting: function(){
                var customer_id = $('#wrap_customer_hide_input_edit #customer_id').val();
                tbl_Accounting_Ecustomer = $('.tbl_Accounting_Ecustomer').DataTable({
                    serverSide: true,
                    responsive: true,
                    info: false,
                    processing: false,
                    deferRender: true,
                    ordering: false,
                    pageLength: 10,
                    ajax: {
                        url: "customers/list_accounting",
                        type: "POST",
                        data: function( d ){
                            d._main_count = document.getElementById('total_record_accounting').value;
                            d.customer_id = customer_id
                        },
                        beforeSend : function(){
                            Js_Top.Add_Image_Loading_Datatables($('.tbl_Accounting_Ecustomer').find('tbody'));
                        }
                    },
                    columnDefs: [{ 
                        targets: 'no-sort', 
                        orderable: false,
                    }],
                    "columns": [
                    {
                        "class":"tdEdit",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdEdit;
                        }
                    },{
                        "class":"tdDate",
                        "data": "<button>Click!</button>",
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdDate;
                        }
                    },{
                        "class":"tdType",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdType;
                        }
                    },{
                        "class":"tdType",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdServices;
                        }
                    },{
                        "class":"tdRecordNo",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdRecordNo;
                        }
                    },{
                        "class":"tdDebit",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdDebit;
                        }
                    },{
                        "class":"tdCredit",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdCredit;
                        }
                    },{
                        "class":"tdBilling",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdBilling;
                        }
                    },{
                        "class":"tdRoute",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdRoute;
                        }
                    },{
                        "class":"tdTechnician",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdTechnician;
                        }
                    },{
                        "class":"tdSType",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdSType;
                        }
                    },{
                        "class":"tdSalesperson",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdSalesperson;
                        }
                    },{
                        "class":"tdPType",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdPType;
                        }
                    },{
                        "class":"tdNotes",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdNotes;
                        }
                    }]
                });
                tbl_Accounting_Ecustomer.columns([7,8,9,10,11,12]).visible(false);
            },
            Display_Column_Accounting: function(t_this){
                var column = tbl_Accounting_Ecustomer.column($(t_this).attr('data-columnindex'));
                column.visible(!column.visible());
            },
            Next_Accouting: function(){
                tbl_Accounting_Ecustomer.page('next').draw('page');
            },
            Prev_Accouting: function(){
                tbl_Accounting_Ecustomer.page('previous').draw('page');
            },
            Payment_Accounting: function(customer_id,id_or_add){
                Js_Top.closeNav();
                Js_Top.show_loading();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo url::base() ?>customers/Payment_Accounting', 
                    data: {customer_id : customer_id,id_or_add : id_or_add},
                    success: function (data) {
                        Dom.Overlay.empty();
                        Dom.Overlay.html(data);
                        Js_Top.openNav();
                        Js_Top.hide_loading();
                        jquery_plugins.InitDatepicker();
                        jquery_plugins.maskMoneyUSD();
                        Customer_Edit.ShowDataAccounting();
                        jquery_plugins.DropdownHover();
                    }
                });
            },
            Save_Payment: function(customer_id){
                var formData = $('#Frm_E_Payment').serializeArray();
                formData.push({ name: "customer_id", value: customer_id });
                $.ajax({
                    url: '<?php echo url::base() ?>customers/Save_Payment',
                    type: 'POST',
                    data: formData,
                })
                .done(function(d) {
                    Customer_Edit.Close_Invoice_Payment_Accouting(customer_id);
                    Customer_Edit.LoadInvoiceBalance(customer_id);
                });  
            },
            Invoice_Accounting: function(customer_id,type_billto,id_or_add){
                Js_Top.closeNav();
                Js_Top.show_loading();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo url::base() ?>customers/Invoice_Accounting', 
                    data: {customer_id:customer_id,id_or_add : id_or_add},
                    success: function (data) {
                        Dom.Overlay.html(data);
                        Js_Top.openNav();
                        Js_Top.hide_loading();
                        Customer_Edit.LoadBillingInvoice(type_billto,id_or_add);
                        Js_Top.LoadSingleLineItem(id_or_add);
                    }
                });
            },
            BillToInvoice: function(t_this){
                var Dom = $(t_this).parent().next();
                Dom.empty();
                Js_Top.Add_Image_Loading(Dom);
                Customer_Edit.LoadBillingInvoice($(t_this).val(),'add');
            },
            LoadBillingInvoice: function(id,id_or_add){
                Js_Top.show_loading();
                if(id == ''){
                    var type_id = '';
                }else{
                    var arr_srt = id.split("_");
                    var type_id = arr_srt[0] || '';
                    var id      = arr_srt[1] || 0;
                }
                $.ajax({
                    type: 'POST',
                    url: '<?php echo url::base() ?>customers/LoadBillingInvoice', 
                    data: {id : id,type_id : type_id,id_or_add : id_or_add},
                    success: function (data) {
                        $('#wap_Billing_Invoice').html(data);
                        jquery_plugins.MaskPhone();
                        Js_Top.hide_loading(); 
                    }
                });
            },
            Save_Invoice:function(customer_id){
                var formData = $('#Frm_E_Invoice').serializeArray();
                formData.push({ name: "customer_id", value: customer_id });
                $.ajax({
                    url: '<?php echo url::base() ?>customers/Save_Invoice',
                    type: 'POST',
                    data: formData,
                })
                .done(function(d) {
                    Customer_Edit.Close_Invoice_Payment_Accouting(customer_id);
                    Customer_Edit.LoadInvoiceBalance(customer_id);
                });  
            },
            Close_Invoice_Payment_Accouting: function(customer_id){
                Js_Top.closeNav();
                Customer.EditCustomer(customer_id,1);
            },
            Full_Sreen_Accounting: function() {
                $('.full_sreen_accounting').hide();
                $('.close_full_sreen_accounting').show();
                $('#wrap-edit-notes').hide();
                $('.edit_billing_info').parent('div').hide();
                $('.wrap_edit_info_invoce').removeClass('col-lg-8 col-md-8 padding_zero_left').addClass('col-lg-12 col-md-12 padding_zero', {duration:500});
                $('#wrap-edit-title-customers').removeClass('col-lg-8 col-md-8').addClass('col-lg-3 col-md-3', {duration:500});
                $('#wrap-edit-vitem-invoice').removeClass('col-lg-12 col-md-12').addClass('col-lg-9 col-md-9', {duration:500});
            },
            Close_Full_Sreen_Accounting: function() {
                $('.full_sreen_accounting').show();
                $('.close_full_sreen_accounting').hide();
                $('#wrap-edit-notes').show();
                $('.edit_billing_info').parent('div').show();
                $('.wrap_edit_info_invoce').removeClass('col-lg-12 col-md-12 padding_zero').addClass('col-lg-8 col-md-8 padding_zero_left', {duration:500});
                $('#wrap-edit-title-customers').removeClass('col-lg-3 col-md-3').addClass('col-lg-8 col-md-8', {duration:500});
                $('#wrap-edit-vitem-invoice').removeClass('col-lg-9 col-md-9').addClass('col-lg-12 col-md-12', {duration:500});
            },
        //****** END ACCOUNTING //

        //****** ACTIVITY HISTORY //
            Load_Active_History: function() {
                $.ajax({
                    type: 'POST',
                    url: 'customers/active_history', 
                    success: function (data) {
                        $('#active_history').html(data);
                        Customer_Edit.ShowDataActicityHistory();
                    }
                });
            },
            ShowDataActicityHistory: function(){
                var customer_id = $('#wrap_customer_hide_input_edit #customer_id').val();
                tbl_Active_History = $('.tbl_Active_History').DataTable({
                    serverSide: true,
                    responsive: true,
                    info: false,
                    processing: false,
                    deferRender: true,
                    ordering: false,
                    pageLength: 10,
                    "bDestroy": true,
                    ajax: {
                        url: "customers/ShowDataActicityHistory",
                        type: "POST",
                        data: function( d ){
                            d.customer_id = customer_id
                        },
                        beforeSend : function(){
                            Js_Top.Add_Image_Loading_Datatables($('.tbl_Active_History').find('tbody'));
                        }
                    },
                    columnDefs: [{ 
                        targets: 'no-sort', 
                        orderable: false,
                    }],
                    "columns": [
                    {
                        "class":"tdDate",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdDate;
                        }
                    },{
                        "class":"tdUser",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdUser;
                        }
                    },{
                        "class":"tdActivity",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdActivity;
                        }
                    },{
                        "class": "tdPO",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdPO;
                        }
                    },{
                        "class":"tdServiceAddress",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdServiceAddress;
                        }
                    },{
                        "class":"tdTechnician",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdTechnician;
                        }
                    },{
                        "class":"Route",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.Route;
                        }
                    }]
                });

                // Search HIstory
                var delay = (function(){
                    var timer = 0;
                    return function(callback, ms){
                        clearTimeout (timer);
                        timer = setTimeout(callback, ms);
                    };
                })();
                $('#SeachHistory').on('keyup', function() {
                    var search = $(this).val();
                    delay(function(){
                        tbl_Active_History.search(search).draw(); 
                    }, 1000 );
                });
            },
            Add_Activity_History: function(){
                var customer_id = $('#wrap_customer_hide_input_edit #customer_id').val();
                $.ajax({
                    type: 'POST',
                    url: 'customers/Add_Activity_History', 
                    data: {customer_id:customer_id},
                    success: function (data) {
                        $.confirm({
                            title: 'Post Activity History',
                            content: ''+data+'',
                            columnClass: 'col-md-5 col-centered',
                            containerFluid: true,
                            buttons: {    
                                OK: {
                                    text: 'OK', 
                                    btnClass: 'btn-blue', 
                                    action: function(){
                                        this.buttons.OK.disable();
                                        var self = this;
                                        if($('input[name="PO"]').val() == ''){
                                            Js_Top.error('PO empty.');
                                        }else if($('input[name="service_address"]').val() == ''){
                                            Js_Top.error('Service Address empty.');
                                        }else if(CKEDITOR.instances['activity'].getData() == ''){
                                            Js_Top.error('Activity empty.');
                                        }else{
                                            var SaveHistory = Customer_Edit.Save_Activity_History(customer_id); 
                                            SaveHistory.success(function (data) {
                                                if(data.message){
                                                    Js_Top.success('Save success.');
                                                    Customer_Edit.ShowDataActicityHistory();
                                                    self.close();
                                                }else{
                                                    Js_Top.error(data.content);
                                                }
                                            });
                                        }
                                        self.buttons.OK.enable();
                                        return false;
                                    }  
                                }, 
                                CANCEL: function(){
                                    this.close();
                                }             
                            },
                            onOpen: function () {
                                jquery_plugins.Ckeditor();
                                Customer_Edit.Auto_PO();
                            },
                        });
                    }
                });
            },
            Save_Activity_History: function(customer_id){
                var Frm_activity_history = $('.Frm_activity_history').serializeArray();
                Frm_activity_history.push({ name: "customer_id", value: customer_id });
                return $.ajax({
                    url: '<?php echo url::base() ?>customers/Save_Activity_History',
                    type: 'POST',
                    dataType: 'json',
                    data: Frm_activity_history
                });
            },
            Auto_PO: function(){
                var customer_id = $('#wrap_customer_hide_input_edit #customer_id').val();
                $('.Auto_PO').autocomplete({
                    source: function( request, response ) {
                        $.ajax({
                            url : ''+domain_origin+'/customers/Auto_PO',
                            dataType: "json",
                            data: {
                               name_startsWith: request.term, customer_id: customer_id                          
                            },
                             success: function( data ) {
                                 response( $.map( data, function( item ) {
                                    var code = item.split("|");
                                        return {
                                            label: code[1],
                                            value: code[1],
                                            data : item
                                        }
                                }));
                            }
                        });
                    },
                    autoFocus: true,
                    minLength: 0,
                    html:true,
                    search  : function(){
                        $(this).addClass('working_input');
                    },
                    open    : function(){
                        $(this).removeClass('working_input');
                        $("#wrap-overlay").on({
                            'mousewheel': function(e) {
                                if (e.target.id == 'el') return;
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        })
                    },
                    close: function( event, ui ) {
                        $("#wrap-overlay").unbind("mousewheel");
                    },
                    select: function( event, ui ) {
                        
                    }              
                }).data("ui-autocomplete")._renderItem = function (ul, item) {
                    return $("<li></li>")
                        .data("item.autocomplete", item)
                        .append("<div>" + item.label + "</div>")
                        .appendTo(ul);
                };
            },
        //****** END ACTIVITY HISTORY //

        //****** CREDIT CARD //
            Load_Credit_Card: function() {
                $.ajax({
                    type: 'GET',
                    url: 'customers/credit_card', 
                    success: function (data) {
                        $('#credit_card').html(data);
                        Customer_Edit.ShowDataCreditCard();
                    }
                });
            },
            ShowDataCreditCard: function(){
                var customer_id = $('#wrap_customer_hide_input_edit #customer_id').val();
                $('.tbl_Credit_Card').DataTable({
                    serverSide: true,
                    responsive: true,
                    info: false,
                    processing: false,
                    deferRender: true,
                    ordering: false,
                    pageLength: 10,
                    "bDestroy": true,
                    ajax: {
                        url: "customers/ShowDataCreditCard",
                        type: "POST",
                        data: function( d ){
                            d.customer_id = customer_id
                        },
                        beforeSend : function(){
                            Js_Top.Add_Image_Loading_Datatables($('.tbl_Credit_Card').find('tbody'));
                        }
                    },
                    columnDefs: [{ 
                        targets: 'no-sort', 
                        orderable: false,
                    }],
                    "columns": [
                    {
                        "class":"tdCardType",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdCardType;
                        }
                    },{
                        "class":"tdCardImage",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdCardImage;
                        }
                    },{
                        "class":"tdExpiry",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdExpiry;
                        }
                    },{
                        "class": "tdNameCard",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdNameCard;
                        }
                    },{
                        "class":"tdAutoPay",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return full.tdAutoPay;
                        }
                    },{
                        "class":"tdBtnDelete",
                        "data": null,
                        "orderable": false,
                        "render": function ( data, type, full, meta ) {
                            return '<button type="button" class="btn btn-sm btn-primary" onclick="Customer_Edit.ConfirmRemoveCard(\'' + full.tdCardId + '\',\'' + full.tdStripeCustomerId + '\')"><i class="fa fa-times" aria-hidden="true" style="color:#fff"></i></button>';
                        }
                    }]
                });
            },
            Add_Credit_Card: function(t_this){
                var customer_id = $('#wrap_customer_hide_input_edit #customer_id').val();
                $.ajax({
                    type: 'POST',
                    url: 'customers/Add_Credit_Card', 
                    data: {customer_id:customer_id},
                    success: function (data) {
                        $.confirm({
                            title: 'Add Card',
                            content: ''+data+'',
                            columnClass: 'col-md-6 col-centered',
                            containerFluid: true,
                            buttons: {    
                            	OK: {
						            text: 'OK', 
						            btnClass: 'btn-blue', 
						            action: function(){
						            	Js_Top.show_loading();
						            	this.buttons.OK.disable();
						                var self = this;
	                            		var SaveProbCard = Customer_Edit.SaveCreditCard(customer_id); 
	                					SaveProbCard.success(function (data) {
							            	if(data.message){
							            		Js_Top.success('Save success.');
							            		Customer_Edit.ShowDataCreditCard();
							            		self.close();
							            	}else{
							            		Js_Top.error(data.content);
							            	}
							            	Js_Top.hide_loading();
							            	self.buttons.OK.enable();
							            });
							            return false;
						            }
						        }, 
						        CANCEL: function(){
						        	this.close();
						       	}             
                            }
                        });
                    }
                });
            },
            SaveCreditCard: function(customer_id){
                var Frm_credit_card = $('.Frm_credit_card').serializeArray();
                Frm_credit_card.push({ name: "customer_id", value: customer_id });
                return $.ajax({
                    url: '<?php echo url::base() ?>customers/SaveCreditCard',
                    type: 'POST',
                    dataType: 'json',
                    data: Frm_credit_card
                });
            },
            ConfirmRemoveCard: function(StripeCardId,StripeCustomerId){
            	$.confirm({
                    title: 'Confirm',
                    content: 'Delete this card?',
                    columnClass: 'col-md-3 col-centered',
                    containerFluid: true,
                    buttons: {    
                    	OK: {
				            text: 'OK', 
				            btnClass: 'btn-blue', 
				            action: function(){
				            	Js_Top.show_loading();
				            	this.buttons.OK.disable();
				                var self = this;
                        		var DleteProbCard = Customer_Edit.RemoveCard(StripeCardId,StripeCustomerId); 
            					DleteProbCard.success(function (data) {
					            	if(data.message){
					            		Js_Top.success(data.content);
			                    		Customer_Edit.ShowDataCreditCard();
			                    		self.close();
			                    	}else{
			                    		Js_Top.error(data.content);
			                    	}
					            	Js_Top.hide_loading();
					            	self.buttons.OK.enable();
					            });
					            return false;
				            }
				        }, 
				        CANCEL: function(){
				        	this.close();
				       	}             
                    }
                });    
            },
            RemoveCard: function(StripeCardId,StripeCustomerId){
            	return $.ajax({
                    url: '<?php echo url::base() ?>customers/RemoveCard',
                    type: 'POST',
                    dataType: 'json',
                    data: {StripeCardId:StripeCardId,StripeCustomerId:StripeCustomerId},
                });
            },
        //****** END CREDIT CARD //

        //****** ATTACHEMENTS //
            Load_Attachments: function() {
                var customer_id = $('#wrap_customer_hide_input_edit #customer_id').val();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo url::base() ?>customers/E_Attachments', 
                    data: {customer_id:customer_id},
                    success: function (data) {
                        $('#attachments').html(data);
                    }
                });
            },
        //****** END ATTACHEMENTS //
        Ready: function(){
            
        },
        init: function() {  
            Dom_Edit = this.settings;
            Dom_Edit.Overlay;
            this.Ready();
        }   
    };
    var address_1 = '', address_2 = '', city = '', state = 'CA', zip = '';
    function matchRoute(t_this){
        var type = $(t_this).attr('data-type');

        switch (type){
            case 'address_1': address_1 = $(t_this).val();break;
            case 'address_2': address_2 = $(t_this).val();break;
            case 'city': city = $(t_this).val();break;
            case 'state': state = $(t_this).val();break;
            case 'zip': zip = $(t_this).val();break;
            default: console.log('none');break;
        }

        if((address_1 != '' || address_2 != '') && city != '' && zip != ''){
            var address = '';
            if(address_1 != ''){
                address = address_1 + ' ' + city + ' ' + state + ' ' + zip;
            }
            else{
                address = address_2 + ' ' + city + ' ' + state + ' ' + zip;
            }
            $.post('<?=url::base()?>routes/lookup',{address: address})
            .done(function (result) {
                if(result != 'none'){
                    var data = JSON.parse(result);
                    var location = {lat: parseFloat(data.latitude), lng: parseFloat(data.longitude)};

                    //check location for zone of route
                    checkZoneAndZip(location,zip,function (rs) {
                        $('.svRoute').val(rs);
                    })
                }
            })

        }
    }

    function checkZoneAndZip(location,zip,callback){
        $.post('<?=url::base()?>routes/getRouteOfActiveSet')
        .done(function (result) {
            var idCall = 0;
            var all = JSON.parse(result);
            var zones = all.zone;
            var routes = all.route;

            var marker = new google.maps.Marker({
                position: location
            });

            //Check in zone
            for(var i = 0; i < zones.length; i++){
                var zone = zones[i][0];
                var data = JSON.parse(zone.position);
                if (zone.type == 'CIRCLE') {
                    var center = {lat: parseFloat(data[0].center.lat), lng: parseFloat(data[0].center.lng)};
                    var radius = parseFloat(data[0].radius);
                    var overlay = new google.maps.Circle({
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        center: center,
                        radius: radius,
                    });

                    if (overlay.getBounds().contains(marker.position)) {
                        idCall = zone.route_id;
                        break;
                    }
                }
                if (zone.type == 'RECTANGLE') {
                    var location1 = {lat: parseFloat(data[0].south), lng: parseFloat(data[0].west)};
                    var location2 = {lat: parseFloat(data[0].north), lng: parseFloat(data[0].east)};

                    var bound = new google.maps.LatLngBounds(location1, location2);
                    var overlay = new google.maps.Rectangle({
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        bounds: bound,
                    });

                    if (overlay.getBounds().contains(marker.position)) {
                        idCall = zone.route_id;
                        break;
                    }
                }
                if (zone.type == 'POLYGON') {
                    var path = [];
                    for (var i in data[0]) {
                        path.push({lat: parseFloat(data[0][i].lat), lng: parseFloat(data[0][i].lng)});
                    }
                    var overlay = new google.maps.Polygon({
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillOpacity: 0.35,
                        paths: path,
                    });

                    if (google.maps.geometry.poly.containsLocation(marker.position, overlay)) {
                        idCall = zone.route_id;
                        break;
                    }
                }
            }

            //Check in route zip
            if(idCall == 0){
                for (var i = 0; i < routes.length; i++){
                    var idMatch = 0;
                    var zips = routes[i].route_zip.split(',');
                    for(var j = 0; j < zips.length; j++)
                    {
                        if(zips[j] == zip){
                            idCall = routes[i].route_id;
                            break;
                        }
                    }
                    if(idCall != 0)
                        break;
                }
            }
            callback(idCall);
        })
    }
    function SaveService(customer_id) {
        $.confirm({
            title: 'Alert',
            content: '<p>Save?</p>',
            buttons:{
                OK: {
                    text: 'OK',
                    btnClass: 'btn btn-primary',
                    action: async function () {
                        Customer_Add.SaveCustomers();
                    }
                },
                Cancel:{
                    text: 'Cancel',
                    btnClass: 'btn btn-default'
                }
            }
        })
    }
    function AddNewService(customer_id) {
        Js_Top.closeNav();
        Js_Top.show_loading();
        $('#wrap-close-overlay').html('<div class="col-lg-6 col-xs-6 title-left-close-overlay" style="width: 35%;">' +
            '        <div class="DivParent">' +
            '           <div class="DivWhichNeedToBeVerticallyAligned">' +
            '              Add Service' +
            '           </div><div class="DivHelper"></div>' +
            '        </div>' +
            '    </div><div class="col-lg-6 col-xs-6 title-right-close-overlay" style="width: 39%;">' +
            '        <div style="text-align: right;">' +
            '           <button onclick="SaveService(' + customer_id + ')" type="button" class="btn btn-sm btn-primary">Save</button>' +
            '           <button type="button" class="btn btn-sm btn-primary" onclick="Customer_Edit.Close_Edit_Service(' + customer_id + ')"><i class="fa fa-times" aria-hidden="true" style="color:#fff"></i></button>\n' +
            '        </div>' +
            '    </div>');
        $('#overlay-content').html('<fieldset><form class="add_new_customer">' +
            '<input type="hidden" name="idToEdit" value="' + customer_id + '"><div style="margin-top: 5px;">' +
            '                            <ul class="nav nav-tabs tab_service_customer" id="tab_sv" role="tablist">' +
            '                                <li>' +
            '                                    <button onclick="Customer_Add.AddService()" type="button" class="btn btn-sm btn-primary" style="margin-left: 5px;margin-top: 2px;">Add another service</button>\n' +
            '                                </li>' +
            '                            </ul>' +
            '                            <div class="tab-content tab_content_service"></div>' +
            '                        </div>');
        Customer_Add.AddService().then(function () {
            $('#overlay-content').append('</form></fieldset>');
            Js_Top.hide_loading();
            Js_Top.openNav();

            $('.tab_service_customer').on('click','a',function (e) {
                e.preventDefault();
                $(this).tab('show');
            })
        });
    }
    $(document).ready(function() {
        Customer.init();
        // Customer_Add.init();
    })
</script>