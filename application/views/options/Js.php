<script>
Options = {
    settings: {

    }, 
    Change_Tax: function(t_this){
        var Code_Tax = $(t_this).val();
        var Arr_Tax = Code_Tax.split('|');
        var Tax = Arr_Tax[1];
        $('input[name="val_default_tax"]').val(Tax);
    },
    // duration_time_scheduling: function(){
    //     var start_time = $('input[name="start_time_scheduling"]').val();
    //     var end_time   = $('input[name="end_time_scheduling"]').val();
    //     if(start_time == '')
    //         $.growl.error({ message: "Please set start time." });
    //     if(end_time == '')
    //         $.growl.error({ message: "Please set end time." });
    //     if(start_time != '' && end_time != ''){
    //         $.ajax({
    //             url: '<?php echo url::base() ?>options/C_duration_time_Scheduling',
    //             type: 'POST',
    //             dataType: 'json',
    //             data: {start_time: start_time, end_time: end_time},
    //         })
    //         .done(function(d) {
    //             $('.hours').text(d.hours);
    //             $('.minutes').text(d.minutes);
    //         });  
    //     }
    // },
    AddMoreTime: function(t_this){
        var flag = true;
        $('input[name="start_time[]"]').each(function(){
            if($(this).val() == ''){
                $.growl.error({ message: "Please input start time." });
                flag = false;
                return false;
            }
        })

        $('input[name="end_time[]"]').each(function(){
            if($(this).val() == ''){
                $.growl.error({ message: "Please input end time." });
                flag = false;
                return false;
            }
        })
        
        if(flag){
            $.ajax({
                type: 'GET',
                url: '<?php echo url::base() ?>options/AddMoreTime'
            })
            .done(function(data) {
                $('.wap_content_time').append(data);
                jquery_plugins.InitDatetimepicker();
            });    
        }     
    },
    RemoveTime: function(t_this){
        $(t_this).parent().parent().remove();
    },
    Ready: function(){
        jquery_plugins.InitDatetimepicker();
        jquery_plugins.OnlyNumberDot();
        jquery_plugins.OnlyNumber();
        jquery_plugins.MinmaxNumberMinute();
    },
    init: function() {
        Dom = this.settings;
        this.Ready();
    } 
};
$(document).ready(function() {
    Options.init();
});
</script>