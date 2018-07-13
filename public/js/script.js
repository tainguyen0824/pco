var Dom_Top,$origform_Edit_Service;
Js_Top = {
    settings: {
        Loading: $('#loading'),
        MenuLi: $('.nav_menu li'),
    },
    show_loading: function() {
        $('#loading').show();
    },
    hide_loading: function() {
        $('#loading').hide();
    },
    wanrning: function(message){
        $.alert({
            title: 'Warning',
            columnClass: 'col-md-4 col-centered',
            containerFluid: true,
            icon: 'fa fa-warning',
            type: 'orange',
            content: ''+message+'',
        });
    },
    error: function(message){
        $.alert({
            title: 'Error',
            columnClass: 'col-md-4 col-centered',
            containerFluid: true,
            icon: 'fa fa-minus-circle',
            type: 'red',
            content: ''+message+'',
        });
    },
    success: function(message){
        $.alert({
            title: 'Success',
            columnClass: 'col-md-4 col-centered',
            containerFluid: true,
            icon: 'fa fa-check-square-o',
            type: 'green',
            content: ''+message+'',
        });
    },
    ChangeWidthOverlay: function() {
        if(window.innerWidth <= 991) {
            document.getElementById("wrap-overlay").style.width = "100%";
            $(".title-left-close-overlay").css('width','100%');
            $(".title-right-close-overlay").css('width','100%');
        }else{
            document.getElementById("wrap-overlay").style.width = "75%";
            $(".title-left-close-overlay").css('width','35%');
            $(".title-right-close-overlay").css('width','39%');
        } 
    },
    openNav : function(){
        var offsetHeight = document.getElementById('menu').clientHeight;
        var Height_content_overlay = document.getElementById('wrap-close-overlay').clientHeight;
        Js_Top.ChangeWidthOverlay();
        document.getElementById("overlay-content").style.display = 'block';
        document.getElementById("wrap-overlay").style.top  = offsetHeight+'px';
        document.getElementById("overlay-content").style.top = Height_content_overlay+'px';
        document.getElementById("wrap-overlay").style.boxShadow   = '10px 10px 20px 10px grey';
        $('body').css('overflow','hidden');
        $('#opacity_overlay').show();
    },
    closeNav: function() {
        document.getElementById("wrap-overlay").style.width = "0%";
        document.getElementById("overlay-content").style.display = 'none';
        document.getElementById("wrap-overlay").style.boxShadow   = '0px 0px 0px 0px grey';
        $('body').css('overflow','auto');
        $('#opacity_overlay').hide();
    },
    Add_Image_Loading: function(Tthis){
        Tthis.append('<img class="img-responsive" style="margin: 0px auto;padding-top: 10px;padding-bottom: 10px;" src="'+domain_origin+'/public/images/loading_32.gif" alt="">');
    },
    Add_Image_Loading_Datatables: function(Tthis){
        Tthis.append('<div style="width: 100%;height: 100%;background-color: rgba(239, 239, 239, 0.62);top: 0;position: absolute;"><div style="position: absolute;top: 50%;left: 45%;"><img class="img-responsive" src="'+domain_origin+'/public/images/loading_32.gif" alt=""></div></div>');
    },
    getNumbers: function(inputString){
        var regex=/\d+\.\d+|\.\d+|\d+/g, 
            results = [],
            n;
        while(n = regex.exec(inputString)) {
            results.push(parseFloat(n[0]));
        }
        return results;
    },
    LoadAddMainService: function(index,type,customer_id,service_id,STime_Calendar,ETime_Calendar,SDate_Calendar,EDate_Calendar,technician_id,Events_id){
        Js_Top.Add_Image_Loading($('.main_service_'+index));
        $.ajax({
            type: 'POST',
            url: ''+domain_origin+'/service/LoadAddMainService', 
            data: { index          : index,
                    type           : type,
                    customer_id    : customer_id,
                    id             : service_id,
                    STime_Calendar : STime_Calendar,
                    ETime_Calendar : ETime_Calendar,
                    SDate_Calendar : SDate_Calendar,
                    EDate_Calendar : EDate_Calendar,
                    technician_id  : technician_id,
                    Events_id      : Events_id
            },
            success: function (data) {
                $('.main_service_'+index).html(data);
                jquery_plugins.maskMoneyUSD();
                jquery_plugins.OnlyNumber();
                jquery_plugins.OnlyNumberDot();
                jquery_plugins.InitDatepicker();
                jquery_plugins.InitDatetimepicker();
                jquery_plugins.MinmaxNumberMonth();
                jquery_plugins.MinmaxPercent();
                jquery_plugins.MinmaxNumberMinute();
                Js_Top.Autocomplete_pesticide();

                var chk_service_billing_frequency = $('input[name="billing_generate_invoice_'+index+'"]:checked').val();
                Js_Top.Billing_generate_invoice(index,chk_service_billing_frequency);

                if(type == 'Edit_customers'){
                    Js_Top.LoadBillingHistory();
                    $origform_Edit_Service = $('form#Frm_E_Service').serialize();
                }

                if(type == 'edit_calendar'){
                    Calendar_Edit.Format_Time_Duration(STime_Calendar,ETime_Calendar,SDate_Calendar,EDate_Calendar);
                    jquery_plugins.EventDateCalendar();
                    jquery_plugins.EventTimeCalendar();
                    Calendar_Edit.Chk_Event_Status($('#complete_event_calendar'));
                }
            }
        });
    },
    // Billing
        LoadBillingHistory: function(){
            $('#tbl_billing_history').DataTable({
                serverSide: false,
                "bInfo" : false,
                paginate:true,
                processing: false,
                autoWidth: false,
                deferRender: true,
                ordering: false,
                pageLength: 5,
                deferRender: true,
            });
        },
        CheckAll_Taxable: function(t_this){
            var checkbox = $(t_this).parents('thead').next('tbody').find('.chk_taxable');
            if($(t_this).is(':checked')){
                checkbox.parents('div').next().val(1);
                checkbox.prop('checked', true);
            }else{
                checkbox.parents('div').next().val(0);
                checkbox.prop('checked', false);
            }
            t_this = $(t_this).parents('thead').next('tbody').find('.val_tax');
            Js_Top.Calculator_Total_Tax(t_this);
        },
        Add_Billing: function(t_this,service_id){
            var checkbox = $(t_this).parents('tbody').prev('thead').find('.Chk_All_Taxable');
            $(t_this).hide();
            var Tthis = $(t_this).parent();
            Js_Top.Add_Image_Loading(Tthis);
            $.ajax({
                type: 'POST',
                url: ''+domain_origin+'/service/Add_Billing',
                data: {service_id: service_id},
                success: function (data) {
                    $(t_this).parents('tbody').children('tr').eq(-3).before(data);
                    $(t_this).next().remove();
                    $(t_this).show();
                    if($(checkbox).is(':checked'))
                        Js_Top.CheckAll_Taxable(checkbox);
                    jquery_plugins.maskMoneyUSD();
                    jquery_plugins.OnlyNumber();
                }
            });
        },
        Remove_Billing: function(t_this){
            var count_Item = $(t_this).parents('tbody').children('tr').length;
            if(count_Item > 4){
                var C_this = $(t_this).parents('tbody').find('.val_tax');
                $(t_this).hide();
                var Tthis = $(t_this).parent();
                Js_Top.Add_Image_Loading(Tthis);
                $(t_this).parents('tr').remove(); 
                Js_Top.Calculator_Total_Discount(C_this);
            }else{
                Js_Top.wanrning('You can not delete the last item.');
            }
        },
        Change_Quantity_Billing: function(t_this){
            var Unit_Price     = $(t_this).parent().next().children();
            var Amount_span    = $(t_this).parent().next().next().children('span');
            var Val_Quantity   = $(t_this).val();
            // console.log(Val_Quantity);
            var Val_Unit_Price = Unit_Price.val();
            Val_Unit_Price     = Number(Val_Unit_Price.replace(/[^0-9\.-]+/g,""));
            var Total_Amount   = Val_Quantity * Val_Unit_Price;
            Total_Amount = $.number(Total_Amount, 2,'.',',');
            Amount_span.text(Total_Amount);
            Js_Top.Calculator_Total_Discount(t_this);
        },
        Change_Unit_Price_Billing: function(t_this){
            var Val_Unit_Price = $(t_this).val();
            Val_Unit_Price     = Number(Val_Unit_Price.replace(/[^0-9\.-]+/g,""));
            var Quantity       = $(t_this).parent().prev().children();
            var Val_Quantity   = Quantity.val();
            var Amount_span    = $(t_this).parent().next().children('span');
            var Total_Amount = Val_Quantity * Val_Unit_Price;
            Total_Amount = $.number(Total_Amount, 2,'.',',');
            Amount_span.text(Total_Amount);
            Js_Top.Calculator_Total_Discount(t_this);
        },
        Change_Taxable_Billing: function(t_this){
            if($(t_this).is(':checked')){
                $(t_this).parent().next().val(1);
            }else{
                $(t_this).parent().next().val(0);
            }
            Js_Top.Calculator_Total_Tax(t_this);
        },
        Change_Discount_Billing: function(t_this){
            var Total_For = ($(t_this).parents('tbody').children('tr').length) - 3;
            var Total_Amount = 0;
            var Precent_Discount;
            for (var i = 0; i < Total_For; i++){
                var Amount = $(t_this).parents('tbody').children('tr').eq(i).find('.B_amount').text();
                Amount     = Number(Amount.replace(/[^0-9\.-]+/g,""));
                Total_Amount += parseFloat(Amount);
            };

            if($(t_this).val() == ''){
                Precent_Discount = 0;
            }else{
                Precent_Discount = $(t_this).val();
            }
            if(Precent_Discount > 100)
                Precent_Discount = 100;
            var Discount = ((Precent_Discount * Total_Amount) / 100);
            Discount = $.number(Discount, 2,'.',',');
            $(t_this).parents('td').next().text(Discount);
            Js_Top.Calculator_Total_Billing(t_this);
        },
        Change_Code_Taxable_Billing: function(t_this){
            var Total_For = ($(t_this).parents('tbody').children('tr').length) - 3;
            var Total_Amount = 0;
            for (var i = 0; i < Total_For; i++){
                var Amount = $(t_this).parents('tbody').children('tr').eq(i).find('.B_amount').text();
                Amount     = Number(Amount.replace(/[^0-9\.-]+/g,""));
                var val_checkbox_tax = $(t_this).parents('tbody').children('tr').eq(i).find('.val_checkbox_tax').val();
                if(val_checkbox_tax == 1)
                    Total_Amount += parseFloat(Amount);
            };

            var Code_Tax = $(t_this).val();
            var Arr_Tax = Code_Tax.split('|');
            var Tax = Arr_Tax[1];
            var Val_Tax = $(t_this).parent().next().children('div').children('input');
            Val_Tax.val(Tax);

            var Dom_Amount_Tax = $(t_this).parent().next().next();
            var Txt_Amount     = (Total_Amount * Tax) / 100;
            Txt_Amount         = $.number(Txt_Amount, 2,'.',',');
            Dom_Amount_Tax.text(Txt_Amount);
            Js_Top.Calculator_Total_Billing(t_this);
        },
        Change_Value_Taxable_Billing: function(t_this){
            var Total_For = ($(t_this).parents('tbody').children('tr').length) - 3;
            var Total_Amount = 0;
            for (var i = 0; i < Total_For; i++){
                var Amount = $(t_this).parents('tbody').children('tr').eq(i).find('.B_amount').text();
                Amount     = Number(Amount.replace(/[^0-9\.-]+/g,""));
                var val_checkbox_tax = $(t_this).parents('tbody').children('tr').eq(i).find('.val_checkbox_tax').val();
                if(val_checkbox_tax == 1)
                    Total_Amount += parseFloat(Amount);
            };
            var Val_Tax        = $(t_this).val();
            var Txt_Amount     = (Total_Amount * Val_Tax) / 100;
            Txt_Amount         = $.number(Txt_Amount, 2,'.',',');
            var Dom_Amount_Tax = $(t_this).parent().parent().next();
            Dom_Amount_Tax.text(Txt_Amount);
            Js_Top.Calculator_Total_Billing(t_this);
        },  
        Calculator_Total_Discount: function(t_this){
            var Total_For = ($(t_this).parents('tbody').children('tr').length) - 3;
            var Total_Amount = 0;
            for (var i = 0; i < Total_For; i++){
                var Amount = $(t_this).parents('tbody').children('tr').eq(i).find('.B_amount').text();
                Amount     = Number(Amount.replace(/[^0-9\.-]+/g,""));
                Total_Amount += parseFloat(Amount);
            };

            var Precent_Discount = $(t_this).parents('tbody').find('.val_discount').val();
            var Discount = ((Precent_Discount * Total_Amount) / 100);
            Discount = $.number(Discount, 2,'.',',');
            $(t_this).parents('tbody').find('.txt_billing_discount').text(Discount);
            Js_Top.Calculator_Total_Tax(t_this);
        },
        Calculator_Total_Tax: function(t_this){
            var Total_For = ($(t_this).parents('tbody').children('tr').length) - 3;
            var Total_Amount = 0;
            for (var i = 0; i < Total_For; i++){
                var Amount = $(t_this).parents('tbody').children('tr').eq(i).find('.B_amount').text();
                Amount     = Number(Amount.replace(/[^0-9\.-]+/g,""));
                var val_checkbox_tax = $(t_this).parents('tbody').children('tr').eq(i).find('.val_checkbox_tax').val();
                if(val_checkbox_tax == 1)
                    Total_Amount += parseFloat(Amount);
            };

            var Precent_Tax = $(t_this).parents('tbody').find('.val_tax').val();
            var Tax         = ((Precent_Tax * Total_Amount) / 100);
            Tax             = $.number(Tax, 2,'.',',');
            $(t_this).parents('tbody').find('.txt_billing_taxable').text(Tax);
            Js_Top.Calculator_Total_Billing(t_this);
        },
        Calculator_Total_Billing: function(t_this){
            var Total_For = ($(t_this).parents('tbody').children('tr').length) - 3;
            var Total_Amount = 0;
            for (var i = 0; i < Total_For; i++){
                var Amount = $(t_this).parents('tbody').children('tr').eq(i).find('.B_amount').text();
                Amount     = Number(Amount.replace(/[^0-9\.-]+/g,""));
                Total_Amount += parseFloat(Amount);
            };
            var Discount  = $(t_this).parents('tbody').find('.txt_billing_discount').text();
            var Taxable   = $(t_this).parents('tbody').find('.txt_billing_taxable').text();
            Discount      = Number(Discount.replace(/[^0-9\.-]+/g,""));
            Taxable       = Number(Taxable.replace(/[^0-9\.-]+/g,""));
            var Total     = parseFloat(Total_Amount) - parseFloat(Discount) + parseFloat(Taxable);
            Total         = $.number(Total, 2,'.',',');
            var Dom_total = $(t_this).parents('tbody').find('.txt_billing_total');
            Dom_total.text(Total);
            // Edit Calendar
            if ($(".Event_Invoice")[0]){
                $('.Event_Invoice').text(Total);
            }
            $(t_this).parents('.content-sub-tabcontent-customers').find('.content_commissions_customers').find('.Total_Billing_Commission').val(Total_Amount);
        },
        Billing_generate_invoice: function(index_service,type){
            if(type == 1){
                $('.wap_auto_billing_generate_invoice_'+index_service).slideUp();
            }else{
                $('.wap_auto_billing_generate_invoice_'+index_service).slideDown();
            }
        },
    // End Billing

    // Scheduling
        LoadDataScheduling: function(t_this,id){
            var Dom_Frequency = $(t_this).parents('ul').next().find('#ServiceFrequency_'+id);
            Js_Top.SchedulingFrequency(Dom_Frequency,id,'');

            var Dom_End_Condition = $(t_this).parents('ul').next().find('#End_condition_'+id);
            Js_Top.SchedulingEndcondition(Dom_End_Condition,id);

            var Dom_Sub_Frequency = $(t_this).parents('ul').next().find("input[name^='option_scheduling_"+id+"']:checked:enabled");
            Js_Top.SchedulingSubFrequency(Dom_Sub_Frequency,id);

            var Dom_Start_Time = $(t_this).parents('ul').next().find("input[name^='start_time_"+id+"']:checked:enabled");
            Js_Top.Option_Settime_Scheduling(Dom_Start_Time,id);

            Js_Top.Months_1_6(id);
            jquery_plugins.InitDatetimepicker();
            jquery_plugins.InitDatepicker();
        },
        ShowCalendarPrevViews: function(id){
            $('.calendar-scheduling-'+id).css('opacity', '0');
            $('#loading_content_sheduling_'+id).show();
            if ($(".add_new_customer")[0]){
                var data = $('form.add_new_customer').serializeArray();
            } else {
                var data = $('form#Frm_E_Service').serializeArray();
            }
            data.push({name: "id", value: id});
            var dataSource = [];
            $.ajax({
                type: 'POST',
                url: ''+domain_origin+'/service/ShowCalendarPrevViews', 
                data: data,
                success: function (result) {
                    var obj = jQuery.parseJSON(result);
                    $.each(obj, function(i, item) {
                        var _Event = {};
                        _Event.id        = 2;
                        _Event.name      = 'Google I/O';
                        _Event.location  = 'San Francisco, CA';
                        _Event.startDate = new Date(item.date);
                        _Event.endDate   = new Date(item.date);
                        dataSource.push(_Event);
                    });
                    $('.calendar-scheduling-'+id).data('calendar').setDataSource(dataSource);
                    $('#loading_content_sheduling_'+id).hide();
                    $('.calendar-scheduling-'+id).css('opacity', '1');
                }
            });   
        },
        SchedulingFrequency: function(t_this,id,event_change) {
            // event_change phân biệt gọi hàm và người dùng change
            if($(t_this).val() == 'onetime'){
                $('#no_available_'+id).show();
                $('.wrap_option_scheduling_'+id).slideUp('slow');
                $('.option_number_endcondition_scheduling_'+id).slideUp('slow');
                $('.option_amount_endcondition_scheduling_'+id).slideUp('slow');
                $('#End_condition_'+id).addClass('no_click');
                $('#End_condition_'+id+' option[value=never]').attr('selected','selected');
            }else if($(t_this).val() == 'weekly'){
                $('#no_available_'+id).hide();
                $('.option_frequency_1st_'+id).hide();
                $('.wrap_slt_month_scheduling_'+id).hide();
                $('.wrap_option_scheduling_'+id).show();
                $('#End_condition_'+id).removeClass('no_click');
                if(event_change != ''){
                    $('input[name="option_scheduling_'+id+'"][value="slt_auto"]').prop('checked', true);
                    Js_Top.SchedulingSubFrequency($('input[name="option_scheduling_'+id+'"][value="slt_auto"]'),id);
                }    
            }else{
                $('.wrap_option_scheduling_'+id).show();
                $('#no_available_'+id).hide();
                $('.option_frequency_1st_'+id).show();
                $('.wrap_slt_month_scheduling_'+id).show();
                $('#End_condition_'+id).removeClass('no_click');
                if(event_change != ''){
                    $('input[name="option_scheduling_'+id+'"][value="slt_auto"]').prop('checked', true);
                    Js_Top.SchedulingSubFrequency($('input[name="option_scheduling_'+id+'"][value="slt_auto"]'),id);
                }
            }
        },
        SchedulingSubFrequency: function(t_this,id) {
            if($(t_this).val() == 'slt_week'){
                $('.slt_week_scheduling_'+id).slideDown();
                $('.Scheduling_Options_'+id).slideDown();
                $('.slt_month_scheduling_'+id).slideUp();
                $('.wap_auto_schedule_working_days_'+id).slideUp();
            }else if($(t_this).val() == 'slt_auto'){
                $('.wap_auto_schedule_working_days_'+id).slideDown();
                $('.Scheduling_Options_'+id).slideDown();
                $('.slt_week_scheduling_'+id).slideUp();
                $('.slt_month_scheduling_'+id).slideUp();
            }else if($(t_this).val() == 'slt_push'){
                $('.Scheduling_Options_'+id).slideUp();
                $('.wap_auto_schedule_working_days_'+id).slideUp();
                $('.slt_week_scheduling_'+id).slideUp();
                $('.slt_month_scheduling_'+id).slideUp();
            }else{   
                $('.slt_month_scheduling_'+id).slideDown();
                $('.Scheduling_Options_'+id).slideDown();
                $('.slt_week_scheduling_'+id).slideUp();
                $('.wap_auto_schedule_working_days_'+id).slideUp();
            }    
        },
        SchedulingEndcondition: function(t_this,id) {
            if($(t_this).val() == 'never'){
                $('.option_number_endcondition_scheduling_'+id).hide();
                $('.option_amount_endcondition_scheduling_'+id).hide();
            }else if($(t_this).val() == 'xnumber'){
                $('.option_number_endcondition_scheduling_'+id).show();
                $('.option_amount_endcondition_scheduling_'+id).hide();
            }else{
                $('.option_number_endcondition_scheduling_'+id).hide();
                $('.option_amount_endcondition_scheduling_'+id).show();
            }
        },
        Option_Settime_Scheduling: function(t_this,index_service){
            if($(t_this).val() == 'manually_settime'){
                $('.manually_settime_scheduling_'+index_service).slideDown('slow');
            }else{
                $('.manually_settime_scheduling_'+index_service).slideUp('slow');
            }
            Js_Top.UpdateClickCancel(index_service);
        },
        Months_1_6: function(id){
            $('.prev-month-scheduling-'+id).prop('disabled', true);
            $('.next-month-scheduling-'+id).prop('disabled', false);
            var Year_select = $('.Year_select_'+id).val();
            var d = new Date();
            $('.calendar-scheduling-'+id).calendar({
                //minDate: new Date((d.getMonth() + 1) + "/" + d.getDate() + "/" + d.getFullYear()),
                enableContextMenu: true,
                enableRangeSelection: true,
                style:'background',
                startYear: Year_select,
                renderEnd: function (e) {  
                    // $('.calendar-scheduling-'+id+' .month-container').each(function (idx, el) { 
                    //     if (idx+1 < 7) { $(this).css("display", "block"); } 
                    //     if (idx+1 > 6) { $(this).css("display", "none"); } 
                    // }); 
                    $('.Year_select_'+id).val(e.currentYear);
                } 
            });
            Js_Top.ShowCalendarPrevViews(id);
        },
        // Months_6_12: function(id){
        //     $('.next-month-scheduling-'+id).prop('disabled', true);
        //     $('.prev-month-scheduling-'+id).prop('disabled', false);
        //     var Year_select = $('.Year_select_'+id).val();
        //     var d = new Date();
        //     $('.calendar-scheduling-'+id).calendar({
        //         //minDate: new Date((d.getMonth() + 1) + "/" + d.getDate() + "/" + d.getFullYear()),
        //         enableContextMenu: true,
        //         enableRangeSelection: true,
        //         style:'background',
        //         startYear: Year_select,
        //         renderEnd: function (e) {  
        //             $('.calendar-scheduling-'+id+' .month-container').each(function (idx, el) { 
        //                 if (idx+1 > 6) { $(this).css("display", "block"); } 
        //                 if (idx+1 < 7) { $(this).css("display", "none"); } 
        //             }); 
        //             $('.Year_select_'+id).val(e.currentYear);
        //         } 
        //     });
        //     Js_Top.ShowCalendarPrevViews(id);
        // },
        Add_pesticide: function(index_service){
            $.ajax({
                type: 'POST',
                url: ''+domain_origin+'/service/Add_Pesticide', 
                data: {index_service: index_service},
                success: function (data) {
                    $('div.wap_Pesticide_'+index_service).append(data);
                    jquery_plugins.maskMoneyUSD();
                    Js_Top.Autocomplete_pesticide();
                }
            });
        },
        Remove_pesticide: function(id,t_this){
            if($('.wap_Pesticide_'+id).children('div').length > 1){
                $(t_this).parent().remove();
            }else{
                Js_Top.wanrning('You can not delete the last item.');
            }
        },
        Autocomplete_pesticide: function(){
            $('.autocomplete_pesticide').autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url : ''+domain_origin+'/service/Autocomplete_pesticide',
                        dataType: "json",
                        data: {
                           name_startsWith: request.term,                          
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
                    var t_this = event.target;
                    var code = ui.item.data.split("|");
                    $(t_this).parent().next().next().children('span').html(code[2]);
                    $(t_this).parent().next().next().children('input').val(code[2]);
                    $(t_this).parent().parent().find('.id_pesticide_select').val(code[0]);
                }              
            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<div>" + item.label + "</div>")
                    .appendTo(ul);
            };
        },
        RemoveALLKeySession: function(){
            return $.ajax({
                url: ''+domain_origin+'/service/RemoveALLKeySession', 
                type: 'POST',
                success:function(data) {
                     
                }
            });
        },
        UpdateClickCancel: function(index_service){
            $('.ClickCancel_'+index_service).val(0);
        },
    // End Scheduling

    // Commissions
        LoadDataCommissions:function(t_this){
            var Dom_Total_Billing = $(t_this).parents('ul').next().children().find('.Total_Billing_Commission');
            var Val_Total_Billing = Dom_Total_Billing.val();
            var Dom_Amount = $(t_this).parents('ul').next().children().find('.Amount_Commission');
            Dom_Amount.each(function( index ) {
                var Dom_Commission_Type = $(this).parent().parent().prev().children();
                var Val_Commission_Type = Dom_Commission_Type.val();
                if(Val_Commission_Type == 'percent'){
                    Total_Commissions = Val_Total_Billing * ((parseFloat($(this).val()) || 0) / 100);
                    var Dom_Commission_Amount = $(this).parent().parent().next();
                    Dom_Commission_Amount.children('span').text($.number(Total_Commissions, 2,'.',','));
                    Js_Top.Calculator_Total_Commission(this);
                    $(this).next().show();
                    $(this).next().next().hide();
                }else{
                    $(this).next().hide();
                    $(this).next().next().show();
                }
            });
        },
        Add_Commissions: function(t_this,service_id){
            $(t_this).hide();
            var Tthis = $(t_this).parent();
            Js_Top.Add_Image_Loading(Tthis);
            $.ajax({
                type: 'POST',
                url: ''+domain_origin+'/service/Add_Commissions', 
                data: {service_id: service_id},
                success: function (data) {
                   $(t_this).parents('tbody').children('tr').eq(-3).after(data);
                   $(t_this).next().remove();
                   $(t_this).show();
                   Js_Top.Calculator_Total_Commission(t_this);
                   jquery_plugins.OnlyNumberDot();
                   jquery_plugins.MinmaxPercent();
                }
            });
        },
        Remove_Commissions: function(t_this){
            var count_Item = $(t_this).parents('tbody').children('tr').length;
            if(count_Item > 3){
                var C_this = $(t_this).parents('tbody').find('.Total_line_commission');
                $(t_this).hide();
                var Tthis = $(t_this).parent();
                Js_Top.Add_Image_Loading(Tthis);
                $(t_this).parents('tr').remove();
                Js_Top.Calculator_Total_Commission(C_this);
            }else{
                Js_Top.wanrning('You can not delete the last item.');
            }
            
        },
        Calculator_Commission_Type: function(t_this){
            var Total_Billing_Commission = $(t_this).parents('.content_commissions_customers').children('.Total_Billing_Commission').val();
            var Val_Commission_Type      = $(t_this).val();
            var Dom_Commission_Amount    = $(t_this).parent().next().next();
            var Dom_Amount               = $(t_this).parent().next().children().children('input');
            Dom_Amount.val('');
            var Val_Amount               = Dom_Amount.val();
            Val_Amount                   = Number(Val_Amount.replace(/[^0-9\.-]+/g,""));
            var Total_Commissions        = 0;
            if(Val_Commission_Type == 'percent'){
                Total_Commissions = Total_Billing_Commission * ((parseFloat(Val_Amount) || 0) / 100);
                Dom_Amount.next().next().hide();
                Dom_Amount.next().show();
                Dom_Amount.addClass('MinmaxPercent');
                jquery_plugins.MinmaxPercent();
                Dom_Amount.removeClass('moneyUSD');
                Dom_Amount.maskMoney('destroy');
            }else{
                Total_Commissions = parseFloat(Val_Amount) || 0;
                Dom_Amount.next().hide();
                Dom_Amount.next().next().show();
                Dom_Amount.addClass('moneyUSD');
                Dom_Amount.removeClass('MinmaxPercent');
                jquery_plugins.maskMoneyUSD();
            }

            Dom_Commission_Amount.children('span').text($.number(Total_Commissions, 2,'.',','));
            Js_Top.Calculator_Total_Commission(t_this);
        },
        Calculator_Commission_Amount: function(t_this){
            var Total_Billing_Commission = $(t_this).parents('.content_commissions_customers').children('.Total_Billing_Commission').val();
            var Val_Amount = $(t_this).val();
            Val_Amount     = Number(Val_Amount.replace(/[^0-9\.-]+/g,""));
            if(Val_Amount > 100)
                Val_Amount = 100;
            var Dom_Commission_Amount = $(t_this).parent().parent().next();
            var Dom_Commission_Type = $(t_this).parent().parent().prev().children();
            var Val_Commission_Type = Dom_Commission_Type.val();
            var Total_Commissions = 0;
            if(Val_Commission_Type == 'percent'){
                Total_Commissions = Total_Billing_Commission * ((parseFloat(Val_Amount) || 0) / 100);
            }else{
                Total_Commissions = parseFloat(Val_Amount) || 0;
            }
            
            Dom_Commission_Amount.children('span').text($.number(Total_Commissions, 2,'.',','));
            Js_Top.Calculator_Total_Commission(t_this);
        },
        Calculator_Total_Commission: function(t_this){
            var Total_For = ($(t_this).parents('tbody').children('tr').length) - 2;
            var Total_Amount = 0;
            for (var i = 0; i < Total_For; i++){
                var Amount = $(t_this).parents('tbody').children('tr').eq(i).find('.Total_line_commission').text();
                Amount     = Number(Amount.replace(/[^0-9\.-]+/g,""));
                Total_Amount += parseFloat(Amount);
            };
            var Dom_total = $(t_this).parents('tbody').find('.Total_Commissions');
            Dom_total.text($.number(Total_Amount, 2,'.',','));
        },
    // End Commissions

    // Attachments
        Add_Attachments: function(t_this,service_id){
            $(t_this).hide();
            var Tthis = $(t_this).parent();
            Js_Top.Add_Image_Loading(Tthis);
            $.ajax({
                type: 'POST',
                url: ''+domain_origin+'/service/Add_Attachments',
                data: {service_id: service_id}, 
                success: function (data) {
                   $(t_this).parents('tbody').children('tr').eq(-2).after(data);
                   $(t_this).next().remove();
                   $(t_this).show();
                }
            });
        },
        Remove_Attachments: function(t_this,type){
            if(type != 'edit_detail'){
                $.confirm({
                    title: 'Message',
                    content: 'This action will completely remove from your data. Do you want to continue?',
                    columnClass: 'col-md-4 col-centered',
                    containerFluid: true,
                    buttons: {                     
                        OK: {
                            text: 'OK',
                            btnClass: 'btn-blue',
                            action: function(){
                                $(t_this).parents('tr').remove();
                                Js_Top.Remove_Attachments_Success(type);
                                this.close();
                            }
                        },
                        CANCEL: {
                            text: 'CANCEL',
                            btnClass: 'btn-default',
                            action: function(){
                                this.close();
                            }
                        }
                    }
                });
            }else{
                var count_Item = $(t_this).parents('tbody').children('tr').length;
                if(count_Item > 2){
                    $(t_this).hide();
                    var Tthis = $(t_this).parent();
                    Js_Top.Add_Image_Loading(Tthis);
                    $(t_this).parents('tr').remove();
                }else{
                    Js_Top.wanrning('You can not delete the last item.');
                }
            }
        },
        Remove_Attachments_Success: function(id){
            $.ajax({
                url: ''+domain_origin+'/service/Remove_Attachments_Success',
                type: 'POST',
                data: {id: id},
            })
            .done(function() {
                console.log("success");
            });
        },
        Upload_Attachments: function(t_this){
            $(t_this).parent().hide();
            var Tthis = $(t_this).parent().parent();
            Js_Top.Add_Image_Loading(Tthis);
            var formData = new FormData();
            formData.append('file', $(t_this)[0].files[0]);
            $.ajax({
                url: ''+domain_origin+'/service/Upload_Attachments',
                type: 'POST',
                processData: false,  
                contentType: false,  
                data: formData,
            })
            .done(function(d) {
                $(t_this).parent().next().show().text(d);
                $(t_this).parent().next().next().remove();
                $(t_this).parent().parent().prev().prev().prev().val(d);
            });
        },
    // End Attachments

    // Add Phone
        AddPhone: function(clone_tpl,wap_tpl,id){
            $("#"+clone_tpl+id).children().clone().appendTo("div#"+wap_tpl+id+":last");
            jquery_plugins.MaskPhone();
        },
        ChangePrimary: function(t_this,class_radio,class_input,id){
            var radioButtons = $("input:radio[class='"+class_radio+""+id+"']");
            var index_radio = $(t_this).index('input:radio[class='+class_radio+''+id+']');
            $('.'+class_input+id).val(index_radio);    
        },
        RemovePhone: function(t_this,class_input,class_radio,id){
            $(t_this).parents('.wap_phone_add').remove();
            if($(t_this).prev().children().is(':checked')){
                $('.'+class_radio+id).first().prop('checked', true);
                $('.'+class_input+id).val(0);
            }else{
                $('.'+class_radio+id).each(function( index ) {
                    if($(this).is(':checked'))
                        $('.'+class_input+id).val(index);
                });
            }
        },
    // End Add Phone

    // Customer Number
        ChangeCustomerNo: function(t_this) {
            if($(t_this).is(':checked')){
                $('input[name="customer_no"]').addClass('working_input');
                $.ajax({
                    url: ''+domain_origin+'/service/GetMaxCustomerNumber', 
                    type: 'POST',
                })
                .done(function(d) {
                    $('input[name="customer_no"]').val(d);
                    Js_Top.CheckExistsCustomerNumber($('input[name="customer_no"]'));
                    $('.customer_number').prop('readonly', true);
                    $('input[name="customer_no"]').removeClass('working_input');
                });
            }else{
                $('.customer_number').prop('readonly', false);
            }
        },
        CheckExistsCustomerNumber:function(t_this){
            $.ajax({
                type:'POST',
                url:''+domain_origin+'/service/CheckExistsCustomerNumber',
                data:{number_customer:$(t_this).val()},
                success:function(data){
                    if(data == 1){
                        $('.CheckExistsCustomerNumber').val(0);
                    }else{
                        $('.CheckExistsCustomerNumber').val(1);
                    }   
                }
            });
        },
    // End Customer Number


    // Existing_New
        Load_Index_Existing_New: function(type_controller){
            $('.Create_Existing_new').empty();
            $('.Create_Existing_new').addClass('working_div');
            $.ajax({
                type: 'POST',
                url:''+domain_origin+'/service/Load_Index_Existing_New',
                data: {type_controller: type_controller},
                success: function (data) {
                    $('.Create_Existing_new').html(data);
                    $('.Create_Existing_new').removeClass('working_div');
                    Js_Top.Load_Existing(type_controller);
                }
            });
        },
        Load_Existing: function(type_controller){
            $('#Tpl_chk_customers').empty();
            $('#Tpl_chk_customers').addClass('working_div');
            $.ajax({
                type: 'POST',
                url:''+domain_origin+'/service/Load_Existing', 
                data: {type_controller: type_controller},
                success: function (data) {
                    $('#Tpl_chk_customers').html(data);
                    $('#Tpl_chk_customers').removeClass('working_div');
                    if(type_controller == 'sales')
                        Js_Top.LoadSingleLineItem();
                }
            });
        },
        Load_New: function(type_controller){
            $('#Tpl_chk_customers').empty();
            $('#Tpl_chk_customers').addClass('working_div');
            $.ajax({
                type: 'POST',
                url:''+domain_origin+'/service/Load_New', 
                data: {type_controller: type_controller},
                success: function (data) {
                    $('#Tpl_chk_customers').html(data);
                    $('#Tpl_chk_customers').removeClass('working_div');
                    Js_Top.LoadAddMainService('','calendar');
                }
            });
        },
        Change_Existing_New: function(t_this,type_controller){
            if($(t_this).val() == 'exitsing'){
                Js_Top.Load_Existing(type_controller);
            }else{
                Js_Top.Load_New(type_controller);
            }
        },
        LoadSingleLineItem: function(id_or_add){
            $('.main_service_').empty();
            $('.main_service_').addClass('working_div');
            $.ajax({
                type: 'POST',
                url:''+domain_origin+'/service/LoadSingleLineItem', 
                data: {id_or_add : id_or_add},
                success: function (data) {
                    $('.main_service_').html(data);
                    $('.main_service_').removeClass('working_div');
                    jquery_plugins.maskMoneyUSD();
                    jquery_plugins.OnlyNumber();
                    jquery_plugins.OnlyNumberDot();
                    jquery_plugins.MinmaxPercent();
                }
            });
        },
    // End Existing_New
    Ready: function() {
        $(document).on('click.bs.dropdown.data-api', '.dropdown.keep-inside-clicks-open', function (e) {
            e.stopPropagation();
        })

        $(window).resize(function() {
            var offsetHeight = document.getElementById('menu').clientHeight;
            document.getElementById("wrap-content").style.paddingTop = offsetHeight + 'px';
            document.getElementById("wrap-overlay").style.top = offsetHeight + 'px';
            var Height_content_overlay = document.getElementById('wrap-close-overlay').clientHeight;
            document.getElementById("overlay-content").style.top = Height_content_overlay+'px';

            if ($('#wrap-overlay').text() != '') {
                if (window.innerWidth <= 991)
                    document.getElementById("wrap-overlay").style.width = "100%";
                else
                    document.getElementById("wrap-overlay").style.width = "75%";
            }
            Js_Top.ChangeWidthOverlay();
        });  

        var offsetHeight = document.getElementById('menu').clientHeight;
        document.getElementById("wrap-content").style.paddingTop  = offsetHeight+'px';
        $('.overlay').scroll(function() {
            var offsetHeight_menu = document.getElementById('menu').clientHeight;
            document.getElementById("wrap-close-overlay").style.top = offsetHeight_menu+'px';
            document.getElementById("wrap-close-overlay").style.width = '100%';
            document.getElementById("wrap-close-overlay").style.position  = 'fixed';
            document.getElementById("wrap-close-overlay").style.marginTop  = '0px';
            var offsetHeight = document.getElementById('wrap-close-overlay').clientHeight;
            document.getElementById("overlay-content").style.top = offsetHeight+'px';
        });
    },
    init: function() {
        Dom_Top = this.settings;
        Dom_Top.Loading;
        Dom_Top.MenuLi;
        this.Ready();
    }  
};

$(document).ready(function() {
    Js_Top.init();
});

function NextYearEvent(t_this,Next_Prev){ 
    var Dom_Service_Date = $(t_this).parents('.wap_calendar_scheduling').prev();
    var Val_First_Date = Dom_Service_Date.find('.First_Date_Scheduling').val();      
    var Class_Name_Year = $(t_this).parent().parent().parent().prev().prev().prev().attr('class');
    var id = Class_Name_Year.replace(/\D/g, '');
    if($('#End_condition_'+id).val() == 'never' && Val_First_Date != '' && $('#ServiceFrequency_'+id).val() != 'onetime'){
        if(Next_Prev == 'Next'){
            var Next_Year = parseInt($('.Year_select_'+id).val()) + 1;
            $('.Year_select_'+id).val(Next_Year);
        }else{
            var Prev_Year = parseInt($('.Year_select_'+id).val()) - 1;
            $('.Year_select_'+id).val(Prev_Year);
        }
        
        Js_Top.ShowCalendarPrevViews(id);
    } 
}
