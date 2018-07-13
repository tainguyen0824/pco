jquery_plugins = {
    NoSpaceInput: function(){
        $('.NoSpace').keypress(function(e){
            if(e.which === 32){
                return false;
            }    
        });
    },
    OnlyNumber: function() {
        $(".OnlyNumber").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
    },
    OnlyNumberDot: function(){
        $('.OnlyNumberDot').keypress(function(e) {
            var a = [46];
            var k = e.which;
            for (i = 48; i < 58; i++)
                a.push(i);
        
            if (!($.inArray(k,a)>=0))
                e.preventDefault();
        });
    },
    MinmaxNumberMonth: function(){
        $('.MinmaxNumberMonth').jStepper({minValue:1, maxValue:31, minLength:2});
    },
    MinmaxNumberMinute: function(){
        $('.MinmaxNumberMinute').jStepper({minValue:0, maxValue:59, minLength:2});
    },
    MinmaxPercent: function(){
        $('.MinmaxPercent').jStepper({minValue:0, maxValue:100, minLength:1});
    },
    MaskPhone: function() {
        $(".maskphone").mask("(999) 999-9999");
    },
    maskMoneyUSD: function(){
        $('.moneyUSD').maskMoney();
    },
    DropdownHover: function(){
        $('.dropdown_hover').dropdownhover();
    },
    InitDatepicker: function() {
        var d = new Date();
        $('.datepicker').datepicker({
            dateFormat: 'mm/dd/yy',
        });
        $('.limit_min_datepicker').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'mm/dd/yy',
            minDate: new Date((d.getMonth() + 1) + ", " + d.getDate() + ", " + d.getFullYear()),
            onSelect: function(dateText) {
                var name_FirstDate = $(this).attr('name');
                var index_service  = name_FirstDate.replace(/[^0-9]/g, '');
                $('.ClickCancel_'+index_service).val(0);
            }
        }); 
    },
    InitDatetimepicker: function(){
        $('.timepickerssss').timepicker({
            timeFormat: 'hh:mm tt'
        });
    },
    EventDateCalendar: function(){
        var d = new Date();
        var startDateTextBox = $('.Event_Date_Start');
        var endDateTextBox = $('.Event_Date_End');
        startDateTextBox.datepicker({ 
            dateFormat: 'mm/dd/yy',
            minDate: new Date((d.getMonth() + 1) + ", " + d.getDate() + ", " + d.getFullYear()),
            onSelect: function (selectedDateTime){
                endDateTextBox.datetimepicker('option', 'minDate', startDateTextBox.datetimepicker('getDate'));
                Calendar_Edit.Set_Time_End(0);
            }
        });
        endDateTextBox.datepicker({ 
            dateFormat: 'mm/dd/yy',
            minDate: new Date((d.getMonth() + 1) + ", " + d.getDate() + ", " + d.getFullYear()),
            onSelect: function (selectedDateTime){
                Calendar_Edit.Set_Time_End(0);
            }
        });
    },
    EventTimeCalendar: function(){
        $('.Event_Time_Calendar').timepicker({
            timeFormat: 'hh:mm tt',
            onSelect: function (selectedDateTime){
                Calendar_Edit.Set_Time_End(0);
            }
        });
    },
    Ckeditor: function(){
        $('.Ckeditor').ckeditor();
        CKEDITOR.config.toolbar = [
            ['Bold','Italic','Underline','Undo','Redo','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ];
    }
};



