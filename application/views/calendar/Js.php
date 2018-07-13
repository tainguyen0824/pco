<script>
var Dom_Calendar, EventFullCalendar, beginOfDate, endOfDate, LastDayMonth, type_name, ArrSelected;
Calendar = {
    settings: {
       Overlay: $('#wrap-overlay'),
       Calendar: $('#calendar_calendar'),
    },   
    toggle_action: function(){
        var action_selected_active = $('.action_selected_active').val();
        if((1 - parseInt(action_selected_active)) == 0){
            $('.Action_Selected').slideUp();
            $('.btn_new_calendar').prop('disabled', false);
            $('.btn_miss_calendar').prop('disabled', false);
            $('.eventsAction').empty();
            $('body').removeClass('borderEvent');
        }else{
            $('.Action_Selected').slideDown();
            $('.btn_new_calendar').prop('disabled', true);
            $('.btn_miss_calendar').prop('disabled', true);
        }
        $('.action_selected_active').val((1 - parseInt(action_selected_active)));
    },
    LoadCalendar: function(ArrFilter){
        EventFullCalendar = Dom_Calendar.Calendar.fullCalendar({
            header: {
                left: 'agendaDay,agendaWeek,month,year,prev,next',
                center: 'title',
                right: 'details'
            },
            timeFormat: 'hh:mm a',
            lazyFetching: false,
            eventClick: function(calEvent, jsEvent, view) {
                var action_selected_active = $('.action_selected_active').val();
                var STime_Calendar         = moment(calEvent.start).format('hh:mm a');
                var ETime_Calendar         = moment(calEvent.end).format('hh:mm a');
                var SDate_Calendar         = moment(calEvent.start).format('MM/DD/YYYY');
                var EDate_Calendar         = moment(calEvent.end).format('MM/DD/YYYY');
                var id                     = calEvent.id;
                var arr                    = id.split('|');
                var scheduling_id          = arr[0];
                var service_id             = arr[1];
                var customer_id            = arr[2];
                var technician_id          = arr[3];
                var Events_id              = arr[4];

                if(action_selected_active == 0){
                    Js_Top.show_loading();
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo url::base() ?>calendar/Edit_calendar', 
                        data: {id : id},
                        success: function (data) {
                            Dom_Calendar.Overlay.html(data);
                            Js_Top.openNav();  
                            Js_Top.hide_loading();
                            Js_Top.LoadAddMainService('','edit_calendar',customer_id,service_id,STime_Calendar,ETime_Calendar,SDate_Calendar,EDate_Calendar,technician_id,Events_id);
                        }
                    });  
                }else{
                    if($(this).hasClass('borderEvent')){
                        $(this).removeClass('borderEvent');
                        $('.events_'+Events_id).remove();
                    }else{
                        var strEvent = '<span class="events_'+Events_id+'">['+calEvent.title+' '+moment(calEvent.start).format('MM/DD/YYYY hh:mm a')+' - '+moment(calEvent.end).format('MM/DD/YYYY hh:mm a')+']</span>';
                        $('.eventsAction').append(strEvent);
                        $(this).addClass('borderEvent');
                    }
                }  
            },
            eventMouseover: function(calEvent, jsEvent) { 
                var durationTime = moment(calEvent.start).format('hh:mm a') + " - " + moment(calEvent.end).format('hh:mm a') + '<br>';
                var tooltip = '<div class="tooltipevent" style="padding-left:10px;padding-right:10px;width: auto;height: auto;background:rgb(94, 172, 253);position:absolute;z-index:10001;color: rgb(255, 255, 255);text-align: center;min-width: 100px;min-height: 100px;border: 3px solid rgb(254, 254, 255);">' + '<strong>'+durationTime+'</strong>' + '<span>'+calEvent.title+'</span>' + '</div>';
                var $tooltip = $(tooltip).appendTo('body');
                $(this).mouseover(function(e) {
                    $(this).css('z-index', 10000);
                    $tooltip.fadeIn('500');
                    $tooltip.fadeTo('10', 1.9);
                }).mousemove(function(e) {
                    $tooltip.css('top', e.pageY + 10);
                    $tooltip.css('left', e.pageX + 20);
                });
            },
            eventMouseout: function(calEvent, jsEvent) {
                $(this).css('z-index', 8);
                $('.tooltipevent').remove();
            },
		    loading: function(bool) {
                if (bool){
                    $('.overlay-calendar').show();
                    $('.FilterCalendar').empty().addClass('working_div_48');
                }
			},
            events: function(){
                Js_Top.show_loading();
                beginOfDate  = Dom_Calendar.Calendar.fullCalendar('getView').start.format('YYYY-MM-DD');
                endOfDate    = Dom_Calendar.Calendar.fullCalendar('getView').end.format('YYYY-MM-DD');
                LastDayMonth = Dom_Calendar.Calendar.fullCalendar('getDate').format('YYYY-MM-01');
                type_name    = Dom_Calendar.Calendar.fullCalendar('getView').name;

                if(type_name == 'agendaDay' || type_name == 'agendaWeek' || type_name == 'month'){
                    endOfDate = moment(endOfDate);
                    endOfDate = endOfDate.subtract(1, "days").format("YYYY-MM-DD");
                }else if(type_name == 'year'){
                   
                }

                $.ajax({
                    type: "POST",
                    url: "<?php echo url::base() ?>calendar/LoadDataCalendarToday",
                    dataType: 'JSON',
                    data:{beginOfDate : beginOfDate, endOfDate : endOfDate, LastDayMonth : LastDayMonth, type_name : type_name},
                    success: function(d){
                        var Arr_CLD       = [];
                        var Arr_CLD_Color = [];
                        var events = new Array();
                        $.each(d, function(i,item){
                            event                 = new Object();      
                            event.id              = item.id;  
                            event.title           = item.title; 
                            event.start           = item.start; 
                            event.end             = item.end; 
                            event.backgroundColor = item.backgroundColor; 
                            event.borderColor     = item.borderColor; 
                            event.allDay          = false;
                            events.push(event);
                            Arr_CLD[i] = item.id+'|'+item.backgroundColor;
                        });

                        Dom_Calendar.Calendar.fullCalendar('removeEvents');
                        Dom_Calendar.Calendar.fullCalendar('addEventSource', events);         
                        Dom_Calendar.Calendar.fullCalendar('rerenderEvents');

                        Calendar.LoadHtmlFilter(Arr_CLD);   
                        Calendar.ChangeTitleCalendar();
                        if(type_name == 'year')
                            Calendar.Filter_Calendar();
                    }
                });
            },
            yearColumns: 1,
            defaultView: 'agendaWeek',
            height: 'parent',
        })
    },
    LoadHtmlFilter: function(data){
    	$.ajax({
            type: 'POST',
            url: '<?php echo url::base() ?>calendar/LoadHtmlFilter', 
            data: {data: data},
            success: function (d) {
               $('.FilterCalendar').removeClass('working_div_48');
               $('.overlay-calendar').hide();
               $('.FilterCalendar').html(d);
               Js_Top.hide_loading();
           	}
        });
    },
    ChangeFilter: function(t_this){
    	if($(t_this).val() == 'no_filter'){
    		$('#filter_zip').slideUp();
    		$('#filter_city').slideUp();
    	}else if($(t_this).val() == 'filter_zip'){
    		$('#filter_zip').slideDown();
    		$('#filter_city').slideUp();
    	}else{
    		$('#filter_zip').slideUp();
    		$('#filter_city').slideDown();
    	}
    },
    Filter_Calendar: function(){
        Js_Top.show_loading();
        var FilterTechnician = '';
        var FilterZip        = '';
        var FilterCity       = '';
        var ArrFilter        = {};

        // Filter by Technician
        $(".filter_technician_calendar").each(function(){
            if ($(this).prop('checked') == false){ 
                FilterTechnician += $(this).val()+',';
            }
        });

        // Filter by Area
        var area = $('.filter_area_calendar:checked').val();
        if(area == 'no_filter'){
            FilterZip = '';
            FilterCity = '';
        }else if(area == 'filter_zip'){
            FilterZip = $('input[name=filter_zip_calendar]:checked').val();
            FilterCity = '';
        }else{
            FilterCity = $('input[name=filter_city_calendar]:checked').val();
            FilterZip = '';
        }
        ArrFilter = {'Arr_Technician': FilterTechnician.slice(0,-1),'Arr_Zip': FilterZip,'Arr_City': FilterCity}; 

        $.ajax({
            type: "POST",
            url: "<?php echo url::base() ?>calendar/LoadDataCalendarToday",
            dataType: 'JSON',
            data:{ArrFilter : ArrFilter, beginOfDate : beginOfDate, endOfDate : endOfDate},
            success: function(d){
                var events = new Array();
                $.each(d, function(i,item){
                    event                 = new Object();      
                    event.id              = item.id;  
                    event.title           = item.title; 
                    event.start           = item.start; 
                    event.end             = item.end; 
                    event.backgroundColor = item.backgroundColor; 
                    event.borderColor     = item.borderColor; 
                    event.allDay          = false;
                    events.push(event);
                });

                Dom_Calendar.Calendar.fullCalendar('removeEvents');
                Dom_Calendar.Calendar.fullCalendar('addEventSource', events);         
                Dom_Calendar.Calendar.fullCalendar('rerenderEvents');
                Js_Top.hide_loading();
            }
        });
    },
    Slt_Date_Show: function($type){
        if($type == 'day'){
            $('.fc-agendaDay-button').click();
        }else if($type == 'week'){
            $('.fc-agendaWeek-button').click();
        }else if($type == 'month'){
            $('.fc-month-button').click();
        }else if($type == 'year'){
            $('.fc-year-button').click();
        }else if($type == 'prev'){
            $('.fc-prev-button').click();
        }else if($type == 'next'){
            $('.fc-next-button').click();
        }
        Calendar.ChangeTitleCalendar();
    },
    ChangeTitleCalendar: function(){
        var fc_center = $('.fc-center h2').text();
        $('.fc-center-custom').html(fc_center);
    },
    AddNewWorkOrder: function(){
        Js_Top.show_loading();
        $.ajax({
            type: 'GET',
            url: '<?php echo url::base() ?>calendar/new_work_order', 
            success: function (data) {
               Dom_New_Work_Order.Overlay.html(data);
               Js_Top.openNav();
               Js_Top.hide_loading();
               New_Work_Order.LoadDataEventDefault();
           }
       });
    },
    Ready: function(){
        Calendar.LoadCalendar();
    },
    init: function() {
        Dom_Calendar = this.settings;
        Dom_Calendar.Overlay;
        Dom_Calendar.Calendar;
        this.Ready();
    }  
};

var Dom_New_Work_Order;
New_Work_Order = {
    settings: {
       Overlay: $('#wrap-overlay'),
    },   
    LoadDataEventDefault: function(){
        $('#Tpl_chk_customers').empty();
        $('#Tpl_chk_customers').addClass('working_div');
        $.ajax({
            type: 'GET',
            url: '<?php echo url::base() ?>calendar/existing_customers', 
            success: function (data) {
                $('#Tpl_chk_customers').html(data);
                Js_Top.LoadAddMainService('','calendar');
                New_Work_Order.Autocomplete_Customer();
                $('#Tpl_chk_customers').removeClass('working_div');
            }
        });
    },
    ChangeDataEvent: function(t_this){
        $('#Tpl_chk_customers').empty();
        $("input[name='check_customers_calendar']").attr("disabled", true);
        $('#Tpl_chk_customers').empty();
        $('#Tpl_chk_customers').addClass('working_div');
        if($(t_this).val() == 'exitsing'){
            $.ajax({
                type: 'GET',
                url: '<?php echo url::base() ?>calendar/existing_customers', 
                success: function (data) {
                    $('#Tpl_chk_customers').html(data); 
                    $("input[name='check_customers_calendar']").attr("disabled", false);
                    Js_Top.LoadAddMainService('','calendar');
                    New_Work_Order.Autocomplete_Customer();
                    New_Work_Order.Autocomplete_Customer();
                    $('#Tpl_chk_customers').removeClass('working_div');
                }
           });
        }else{
            $.ajax({
                type: 'GET',
                url: '<?php echo url::base() ?>calendar/new_customer', 
                success: function (data) {
                    $('#Tpl_chk_customers').html(data);
                    $("input[name='check_customers_calendar']").attr("disabled", false);
                    Js_Top.LoadAddMainService('','calendar');
                    jquery_plugins.MaskPhone();
                    $('#Tpl_chk_customers').removeClass('working_div');
                }
           });
        }
    },
    Autocomplete_Customer: function(){
        $('#autocomplete').autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url : '<?php echo url::base() ?>calendar/Autocomplete_Customer',
                    dataType: "json",
                    data: {
                       name_startsWith: request.term,                          
                    },
                     success: function( data ) {
                         response( $.map( data, function( item ) {
                            var code = item.split("|");
                                return {
                                    label: '<div style="font-size: 15px;">'+code[0]+'</div>'+'<span style="font-size: 11px;">'+code[2]+code[3]+code[4]+code[5]+'</span>',
                                    value: code[6],
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
            },
            select: function( event, ui ) {
                var names = ui.item.data.split("|");
                var customer_id = names[1];
                if(customer_id != ''){
                    $('.Wap_Billing_Address').empty();
                    $('.Wap_Billing_Address').addClass('working_div');
                    $.ajax({
                        url: '<?php echo url::base() ?>calendar/Get_Billing_Autocomplete',
                        type: 'POST',
                        data: {customer_id: customer_id},
                    })
                    .done(function(d) {
                        $('.Wap_Billing_Address').html(d);
                        $('.Wap_Billing_Address').removeClass('working_div');
                    });

                    $('.Wap_Service_Address').empty();
                    $('.Wap_Service_Address').addClass('working_div');
                    $.ajax({
                        url: '<?php echo url::base() ?>calendar/Get_Service_Autocomplete',
                        type: 'POST',
                        data: {customer_id: customer_id},
                    })
                    .done(function(d) {
                        $('.Wap_Service_Address').html(d);
                        $('.Wap_Service_Address').removeClass('working_div');
                    });

                }
            }              
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<div>" + item.label + "</div>")
                .appendTo(ul);
        };
    },
    Select_Service: function(service_id,customer_id){
        $('.Wap_Service_Address').empty();
        $('.Wap_Service_Address').addClass('working_div');
        $.ajax({
            url: '<?php echo url::base() ?>calendar/Select_Service',
            type: 'POST',
            data: {service_id: service_id,customer_id: customer_id},
        })
        .done(function(d) {
            $('.Wap_Service_Address').html(d);
            $('.Wap_Service_Address').removeClass('working_div');
        });
    },
    CheckboxSameBilling: function(t_this){
        var id = $(t_this).data('id');
        if($(t_this).is(':checked')){
            New_Work_Order.InitFormSameBilling($(t_this));
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
        New_Work_Order.InitFormSameBilling($('input[name="same_as_billing_address_"]'));
    },
    InitFormSameBilling: function(DomCheckbox){
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
            $('input[name="service_address_name_"]').val(FrmData.billing_name);
            $('input[name="service_atn_"]').val(FrmData.billing_attention);
            $('input[name="service_address_1_"]').val(FrmData.billing_address_1);
            $('input[name="service_address_2_"]').val(FrmData.billing_address_2);
            $('input[name="service_city_"]').val(FrmData.billing_city);
            $('select[name="service_state_"] option[value='+FrmData.billing_state+']').prop('selected',true);
            $('input[name="service_zip_"]').val(FrmData.billing_zip);
            $('input[name="service_county_"]').val(FrmData.billing_county);
            var Total_Phone = FrmData["billing_phone_type[]"].length - 2;
            $('#wrap_add_phone_service_').empty();
            $.each(FrmData["billing_phone_type[]"], function( index, value ) { 
                if(index < Total_Phone){
                    Js_Top.AddPhone('clone_phone_service_','wrap_add_phone_service_','');
                }
                if(index <= Total_Phone){
                    $('input[name="service_phone_number_[]"]:eq('+index+')').val(FrmData["billing_phone_number[]"][index]);
                    $('input[name="service_phone_ext_[]"]:eq('+index+')').val(FrmData["billing_phone_ext[]"][index]);
                    $('select[name="service_phone_type_[]"]:eq('+index+') option[value='+FrmData["billing_phone_type[]"][index]+']').prop('selected',true);
                }
            });
            $('input[name="service_phone_primary_"]:eq('+FrmData.billing_index_primary+')').prop('checked',true);
            $('.index_phone_service_').val(FrmData.billing_index_primary);
            $('input[name="service_email_"]').val(FrmData.billing_email);
            $('input[name="service_websites_"]').val(FrmData.billing_websites);
            $('textarea[name="service_notes_"]').val(FrmData.billing_notes);
        }
    },
    SaveCalendar: function(t_this){
        var ChkNextStep = true;
        var check_customers_calendar = $('input[name="check_customers_calendar"]:checked').val();
        if(check_customers_calendar == 'new'){
            if($('input[name="customer_name"]').val() == ''){
                Js_Top.error('Customer Name empty.');
                ChkNextStep = false;
                return false;
            }

            if($('input[name="customer_no"]').val() == ''){
                Js_Top.error('Customer No empty.');
                ChkNextStep = false;
                return false;
            }
        }else{
            if (! $('input[name="billing_id"]').length) {
                Js_Top.wanrning('Please select customer.');
                ChkNextStep = false;
                return false;
            }
        }

        var promiseRemoveKeySession = Js_Top.RemoveALLKeySession();   // Xoá tất cả Keysession tồn tại
        promiseRemoveKeySession.success(function (data) {
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
                    Js_Top.error('Service Duration empty.');
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
            jQuery.each(ArrIndex, function(index, index_service) {
                var FirstDate          = moment($('input[name="scheduling_first_date_'+index_service+'"]').val()).format('YYYY-MM-DD');
                var Hours              = $('input[name="hours_'+index_service+'"]').val();
                var Minutes            = $('input[name="minutes_'+index_service+'"]').val();
                var Scheduling_Options = $('input[name="start_time_'+index_service+'"]:checked').val();
                var TypeUnderFrequency = $('input[name="option_scheduling_'+index_service+'"]:checked').val();
                var ManuallyTime       = $('input[name="time_start_scheduling_'+index_service+'"]').val();
                var ServiceName        = $('input[name="service_name_'+index_service+'"]').val();
                var G_Technician       = $('select[name="scheduling_technician_'+index_service+'"] option:selected').val();
                var ClickCancel        = $('.ClickCancel_'+index_service).val();
                if(G_Technician == '')
                    G_Technician = 0;

                if(Scheduling_Options == 'automatically_settime' && TypeUnderFrequency != 'slt_push' && ClickCancel == 0){
                    var CheckTimeValid = true;
                    $.ajax({
                        url: ''+domain_origin+'/service/GetTimeAuto/'+FirstDate+'/'+Hours+'/'+Minutes+'/'+G_Technician,
                        type: 'GET',
                        async: false,
                        dataType: 'json'
                    })
                    .done(function(d) {
                        if(d.message == 'time_no_valid'){
                            CheckTimeValid = false;
                        }else{
                            var DateAuto = moment(d.start).format('YYYY-MM-DD');
                            if(DateAuto != FirstDate && $('.ClickCancel_'+index_service).val() == 0)
                                ArrDateOver.push([index_service, DateAuto]);
                            var TimeAuto = moment(d.start).format('hh:mm a');
                            $('.TimeAuto_'+index_service).val(TimeAuto);
                        }     
                    });  
                    if(!CheckTimeValid){
                        $.confirm({
                            title: 'Message',
                            content: 'Service Duration is not in working days.',
                            columnClass: 'col-md-4 col-centered',
                            containerFluid: true,
                            buttons: {                     
                                OK: {
                                    text: 'OK',
                                    btnClass: 'btn-blue',
                                    keys: ['enter'],
                                    action: function(){
                                        this.close();
                                    }
                                }
                            }
                        });
                        Js_Top.hide_loading();
                        ChkNextStep = false;
                        return false;
                    }
                }       
            });

            // Show những thằng qua ngày
            if (ArrDateOver.length > 0) {
                $.each( ArrDateOver, function( key, value ) {
                    $.confirm({
                        title: 'Message',
                        content: 'The proper time for installation is <b>'+value[1]+'</b> of the service name <b>'+value[0]+'</b>',
                        columnClass: 'col-md-4 col-centered',
                        containerFluid: true,
                        buttons: {                     
                            OK: {
                                text: 'OK',
                                btnClass: 'btn-blue',
                                keys: ['enter'],
                                action: function(){
                                    $('input[name="scheduling_first_date_'+value[0]+'"]').datepicker('setDate', moment(value[1]).format('MM/DD/YYYY')); 
                                }
                            },
                            CANCEL: function () {
                                $('.ClickCancel_'+value[0]).val(1);
                                this.close();
                            },
                        }
                    });
                });
                Js_Top.hide_loading();
            }else{
                $.ajax({
                    type:'POST',
                    url:''+domain_origin+'/service/CheckExistsCustomerNumber',
                    data:{number_customer:$('input[name="customer_no"]').val()},
                    success:function(data){
                        if(data == 1){
                            Js_Top.error('This Customer No already exists.');
                        }else{
                            if(ChkNextStep){
                                var data = $( "form#form_new_customer_calendar" ).serializeArray();
                                $.ajax({
                                    type: 'POST',
                                    url: '<?php echo url::base() ?>calendar/save_calendar',
                                    data : data,
                                    success: function (data) {
                                       Js_Top.closeNav();
                                       Dom_Calendar.Calendar.fullCalendar('refetchEvents');
                                    }
                                });
                            }
                        }   
                    }
                });
            }   
        });
    },
    Ready: function(){
    },
    init: function() {
        Dom_New_Work_Order = this.settings;
        Dom_New_Work_Order.Overlay;
        this.Ready();
    }   
};

var Dom_Mark_As_Complete;
Mark_As_Complete = {
    settings: {
       Overlay: $('#wrap-overlay')
    }, 
    LoadEventComplete: function(){
        if($('.eventsAction').is(':empty')) {
            Js_Top.error('Please select event(s) want to complete.');
        }else{
            var ArrEventId = [];
            $(".eventsAction").children('span').each(function(index, value) {
                var Class = $(this).attr('class');
                var ArrClass = Class.match(/\d+/);
                var Event_id = ArrClass[0];
                ArrEventId.push(Event_id);
            });
            Js_Top.show_loading();
            $.ajax({
                type: 'POST',
                url: '<?php echo url::base() ?>calendar/LoadEventComplete', 
                data: {ArrEventId: ArrEventId},
                success: function (data) {
                    Dom_Mark_As_Complete.Overlay.html(data);
                    Js_Top.openNav();
                    Js_Top.hide_loading();
                }
            });
        }  
    }, 
    Ready: function(){
        jquery_plugins.DropdownHover();
    },
    init: function() {
        Dom_Mark_As_Complete = this.settings;
        Dom_Mark_As_Complete.Overlay;
        this.Ready();
    }   
};

var Dom_Work_Pool;
var TblWorkPoolVarible;
Work_Pool = {
    settings: {
        Overlay: $('#wrap-overlay')
    }, 
    LoadWorkPool: function(){
        Js_Top.show_loading();
        $.ajax({
            type: 'GET',
            url: '<?php echo url::base() ?>calendar/LoadWorkPool', 
            success: function (data) {
                Dom_Work_Pool.Overlay.html(data);
                Js_Top.openNav();
                Js_Top.hide_loading();
                Work_Pool.Js_LoadWorkPool();
            }
        });
    }, 
    Js_LoadWorkPool: function(){
        TblWorkPoolVarible = $('.tbl_work_pool').DataTable({
            "info": false,
            "searching": false,
            "bLengthChange": false,
            "pagingType": "numbers",
            destroy: true,
            serverSide: true,
            processing: false,
            autoWidth: false,
            deferRender: true,
            ordering: false,
            pageLength: 10,
            deferRender: true,
            ajax: {
                url: "<?php echo url::base() ?>calendar/Js_LoadWorkPool",
                type: "POST"
            },
            columnDefs: [{ 
                orderable: false,
                targets: 0,
            }],
            order: [
                [1, 'asc']
            ],
            "columns": [
            {
                "class":"td_Cuatomers_Chk",
                "data": null,
                "orderable": false,
                "render": function ( data, type, full, meta ) {
                    return '<div class="custom-checkbox"><input onchange="Customer_Active.ClickItem('+full.CustomerID+',this)" type="checkbox" class="chk_record_detail" id="td_row_'+full.CustomerID+'"><label style="margin-bottom: 4px !important;" for="td_row_'+full.CustomerID+'"></label></div>';
                }
            },{
                "class":"td_Cuatomers_No",
                "data": "<button>Click!</button>",
                "orderable": false,
                "render": function ( data, type, full, meta ) {
                    return '<p style="cursor:pointer;font-weight:bold" onclick="Customer.EditCustomer('+full.CustomerID+',0)">'+full.td_Cuatomers_No+'</p>';
                }
            },{
                "class":"td_Cuatomers_Name_B_address",
                "data": null,
                "orderable": false,
                "render": function ( data, type, full, meta ) {
                    return '<p style="cursor:pointer;font-weight:bold" onclick="Customer.EditCustomer('+full.CustomerID+',0)">'+full.td_Cuatomers_Name_B_address+'</p> <p style="cursor:pointer;font-size:11px" onclick="Customer.EditCustomer('+full.CustomerID+',0)">'+full.billing_address_1+full.billing_city+full.billing_state+full.billing_zip+'</p>';
                }
            },{
                "class":"td_C_address",
                "data": null,
                "orderable": false,
                "render": function ( data, type, full, meta ) {
                    return '<p style="cursor:pointer;font-weight:bold">'+full.td_C_address+'</p>';
                }
            },{
                "class":"td_Cuatomers_Email",
                "data": null,
                "orderable": false,
                "render": function ( data, type, full, meta ) {
                    return '<p style="cursor:pointer" onclick="Customer.EditCustomer('+full.CustomerID+',0)">'+full.td_Cuatomers_Email+'</p>';
                }
            }]
        });  
    },
    Ready: function(){
        jquery_plugins.DropdownHover();
    },
    init: function() {
        Dom_Work_Pool = this.settings;
        Dom_Work_Pool.Overlay;
        this.Ready();
    }   
};

var Dom_Calendar_Edit;
Calendar_Edit = {
    settings: {
       Overlay: $('#wrap-overlay'),
    },   
    Chk_Event_Status: function(t_this){
        if($(t_this).is(':checked')){
            $('#Wap_Event_Complete').slideDown();
        }else{
            $('#Wap_Event_Complete').slideUp();
        }
    },
    Set_Time_End: function(time){
        $('.Event_service_duration').empty();
        Js_Top.Add_Image_Loading($('.Event_service_duration'));
        var M_monent;
        var Time_End;
        var Event_val_time_start = $('.Event_val_time_start').val();
        var Event_val_date_start = $('.Event_val_date_start').val();
        var Event_hours          = $('.Event_hours').val();
        var Event_minutes        = $('.Event_minutes').val();
        var Event_val_time_end   = $('.Event_val_time_end').val();
        var Event_val_date_end   = $('.Event_val_date_end').val();

        $.ajax({
            url: '<?php echo url::base() ?>calendar/Set_Time_End',
            type: 'POST',
            dataType: 'json',
            data: {
                Event_val_time_start : Event_val_time_start, 
                Event_val_date_start : Event_val_date_start,
                Event_hours : Event_hours,
                Event_minutes : Event_minutes,
                Event_val_time_end : Event_val_time_end,
                Event_val_date_end : Event_val_date_end,
                time: time
            },
        })
        .done(function(d) {
            $('.Event_val_time_end').val(d.time_end);
            $('.Event_val_date_end').val(d.date_end);
            Calendar_Edit.Format_Time_Duration(Event_val_time_start,d.time_end,Event_val_date_start,d.date_end);
        }); 
    },
    Format_Time_Duration: function(STime_Calendar,ETime_Calendar,SDate_Calendar,EDate_Calendar){
        $.ajax({
            type: 'POST',
            url: ''+domain_origin+'/calendar/Edit_Format_Time_Duration', 
            dataType: 'json',
            data: {STime_Calendar : STime_Calendar,ETime_Calendar : ETime_Calendar,SDate_Calendar : SDate_Calendar,EDate_Calendar : EDate_Calendar},
            success: function (d) {
                $('.Event_service_duration').text(d.Str_Duration);
                $('.Event_hours').val(d.Total_Hours);
                $('.Event_minutes').val(d.Minutes);
            }
        });
    },
    Update_Event: function(scheduling_id,Event_id){
        var data = $( "form#Frm_E_Service" ).serializeArray();
        data.push({ name: "scheduling_id", value: scheduling_id });
        data.push({ name: "Event_id", value: Event_id });
        $.ajax({
            type: 'POST',
            url: '<?php echo url::base() ?>calendar/Update_Event', 
            dataType : 'json',
            data : data,
            success: function (d) {
                if(d.message){
                    Js_Top.closeNav();
                    Dom_Calendar.Calendar.fullCalendar('refetchEvents');
                    $.growl.notice({ message: ""+d.content+"" });
                }else{
                    $.growl.error({ message: ""+d.content+"" });
                }
            }
        });
    },
    Delete_Event: function(Event_id){
        $.confirm({
            title: 'Dialogue!',
            content: 'This work order will be deleted. All future events will be removed from the calendar.',
            buttons: {
                Confirm: {
                    text: 'Confirm',
                    btnClass: 'btn btn-primary',
                    action: function () {
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo url::base() ?>calendar/Delete_Event',
                            dataType : 'json',
                            data : {Event_id : Event_id},
                            success: function (d) {
                                if(d.message){
                                    Js_Top.closeNav();
                                    Dom_Calendar.Calendar.fullCalendar('refetchEvents');
                                    $.growl.notice({ message: ""+d.content+"" });
                                }else{
                                    $.growl.error({ message: ""+d.content+"" });
                                }
                            }
                        });
                    }
                },
                Cancel: {
                    text: 'Cancel',
                    btnClass: 'btn btn-default',
                }
            }
        });

    },
    Ready: function(){
    },
    init: function() {
        Dom_Calendar_Edit = this.settings;
        Dom_Calendar_Edit.Overlay;
        this.Ready();
    }   
};

$(document).ready(function() {
    Calendar.init();
    New_Work_Order.init();
    Mark_As_Complete.init();
    Work_Pool.init();
    Calendar_Edit.init();
    // $('#calendar_calendar').fullCalendar('option', 'height', 'parent');
});

</script>: