<script>
var Dom_Active,Table_Active;
var selected              = [];
var Unselected            = [];
var Total_Record_Filter   = 0;
var ValFilterType         = [];
var ValFilterBalance      = [];
var Arr_id_Search         = '';
Customer_Active = {
    settings: {
        TblActive: $('.tbl_customer'),
        CheckboxAll: $('#select_all_chk_active'),
        BtnPrev: $('.btn_prev_page_customers_active'),
        BtnNext: $('.btn_next_page_customers_active'),
        TotalRecord: $('#TotalRecord'),
        Button_Unselect_All: $('#Button_Unselect_All'),
        Button_All: $('#Button_All')
    },
    LoadDefault: function() {
        Table_Active = Dom_Active.TblActive.DataTable({
            destroy: true,
            serverSide: true,
            processing: false,
            autoWidth: false,
            deferRender: true,
            ordering: false,
            pageLength: 10,
            deferRender: true,
            ajax: {
                url: "<?php echo url::base() ?>customers/js_LoadCustomers",
                type: "POST",
                data: function( d ){
                    d._main_count      = document.getElementById('TotalRecord').value,
                    d._ac_in_tive      = document.getElementById('ac_in_tive').value,
                    d.ValFilterType    = ValFilterType,
                    d.ValFilterBalance = ValFilterBalance
                },
                beforeSend : function(){
                    Js_Top.Add_Image_Loading_Datatables($('.tab-content'));
                },
                complete: function(d){
                    Total_Record_Filter = d.responseJSON.recordsFiltered;
                    $("#loading").hide();
                    $('.tab-content').children('div:last-child').remove();
                    Arr_id_Search = d.responseJSON.Str_id;
                }
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
            },{
                "class":"td_Cuatomers_Service",
                "data": null,
                "orderable": false,
                "render": function ( data, type, full, meta ) {
                    return full.td_Cuatomers_Service;
                }
            },{
                "class":"td_Cuatomers_Blance",
                "data": null,
                "orderable": false,
                "render": function ( data, type, full, meta ) {
                    return '<p style="cursor:pointer" onclick="Customer.EditCustomer('+full.CustomerID+',0)">'+full.td_Cuatomers_Blance+'</p>';
                }
            }],
            dom: 'lft<"TES_active">rip',
            initComplete: function(){
                $("div.TES_active").html('');   
            },
            'rowCallback': function(row, data, dataIndex){
                if($.inArray(data.DT_RowId, selected) !== -1 ) {
                    $(row).find('input[type="checkbox"]').prop('checked', true);
                    $(row).addClass('selected');
                }
            },
            "fnDrawCallback": function( oSettings ) {
                $.each(Table_Active.rows().nodes(), function (i, row) {
                    var id = $(this).attr("id");
                    if($('input[name="select_all"]').is(':checked')){
                        var index_Unselected = $.inArray(id, Unselected);
                        if ( index_Unselected === -1 ) {
                            $(Table_Active.row('#'+id+'').nodes()).addClass('selected');
                            $(':checkbox', Table_Active.row('#'+id+'').nodes()).prop('checked', true);                  
                        } else {
                            $(Table_Active.row('#'+id+'').nodes()).removeClass('selected').addClass('unselected');
                            $(':checkbox', Table_Active.row('#'+id+'').nodes()).prop('checked', false);          
                        }
                    }else{
                        var index_selected = $.inArray(id, selected);
                        if ( index_selected === -1 ) {
                            $(Table_Active.row('#'+id+'').nodes()).removeClass('selected').addClass('unselected');
                            $(':checkbox', Table_Active.row('#'+id+'').nodes()).prop('checked', false);                
                        } else {
                            $(Table_Active.row('#'+id+'').nodes()).addClass('selected');
                            $(':checkbox', Table_Active.row('#'+id+'').nodes()).prop('checked', true);           
                        }
                    }
                });
            }
        });

        // Click All
        $('thead input[name="select_all"]', Table_Active.table().container()).on('click', function(e){
            if(this.checked){
                $(':checkbox', Table_Active.rows().nodes()).prop('checked', true);
                $(Table_Active.rows().nodes()).addClass('selected');
                selected=[];
                $('input[name="Type_Get_IDReport"]').val('unselected');
            } else {
                $(':checkbox', Table_Active.rows().nodes()).prop('checked', false);
                $(Table_Active.rows().nodes()).removeClass('selected');
                Unselected=[];
                $('input[name="Type_Get_IDReport"]').val('selected');
            }
            e.stopPropagation();
        });      
    },
    ClickItem: function(id,t_this){
        id = 'row_'+id;
        var t_this = $(t_this).parents('tr');
        if($('input[name="select_all"]').is(':checked')){
            var index_Unselected = $.inArray(id, Unselected);
            if ( index_Unselected === -1 ) {
                $('#td_'+id).prop('checked', false);
                Unselected.push( id );
            } else {
                $('#td_'+id).prop('checked', true);
                Unselected.splice( index_Unselected, 1 );
            } 
        }else{
            var index = $.inArray(id, selected);
            if ( index === -1 ) {
                $('#td_'+id).prop('checked', true);
                selected.push( id );
            } else {
                $('#td_'+id).prop('checked', false);
                selected.splice( index, 1 );
            }
        }
        $(t_this).toggleClass('selected');
    },
    Seach: function(t_this){
        var search = $(t_this).val();
        selected        = [];
        Unselected      = [];
        $('thead input[name="select_all"]', Table_Active.table().container()).prop('checked', false);
        Table_Active.search(search).draw();
    },
    showHideColumn: function(t_this){
        var Table_Active = Dom_Active.TblActive.DataTable();
        var column = Table_Active.column($(t_this).attr('data-columnindex'));
        column.visible(!column.visible());
    },
    Next: function(){
        Table_Active.page('next').draw('page');
    },
    Previous: function(){
        Table_Active.page('previous').draw('page');
    },
    Btn_Select_All: function() {
        $('thead input[name="select_all"]', Table_Active.table().container()).click();
        Dom_Active.Button_All.hide();
		Dom_Active.Button_Unselect_All.show();
    },
    Btn_Unselect_All: function() {
        $('thead input[name="select_all"]', Table_Active.table().container()).click();
        Dom_Active.Button_All.show();
    	Dom_Active.Button_Unselect_All.hide();
    },

// Filter Customer
    Chk_Show_All_Type: function(){
        if($('#showAllType').is(':checked')){
            $('.SubFilterType').prop('checked', true);
        }else{
            $('.SubFilterType').removeAttr('checked');    
        }
        Customer_Active.SubFilterType();
    },
    Chk_Show_All_Balance: function(){
        if($('#showAllBalance').is(':checked')){
            $('.subBalance').prop('checked', true);
        }else{
            $('.subBalance').removeAttr('checked');    
        }
        Customer_Active.SubFilterType();
    },
    SubFilterType: function(){  
        //Filter Type
        ValFilterType = [];
        $('.SubFilterType:checked').each(function() {
            ValFilterType.push($(this).val());
        });
        if(ValFilterType.length === 0)
            ValFilterType = 'off';

        // Filter Balance
        ValFilterBalance = [];
        $('.subBalance:checked').each(function() {
            ValFilterBalance.push($(this).val());
        });
        if(ValFilterBalance.length === 0)
            ValFilterBalance = 'off';
        
        $('#Search_Customer').val('');
        $('thead input[name="select_all"]', Table_Active.table().container()).prop('checked', false);
        selected   = [];
        unselected = [];  
        Customer_Active.LoadDefault();
    },
// End Filter Customer

// Action Customer
    Delete: function(){
        Js_Top.Add_Image_Loading_Datatables($('.tab-content'));
        var flag_error = false;
        $('#wap_customers_id').empty();
        if($('input[name="select_all"]').is(':checked')){
            $('input[name="Type_Get_IDReport"]').val('unselected');
            $.each(Unselected, function(index, rowId) {
                rowId = Js_Top.getNumbers(rowId);
                $('#wap_customers_id').append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'customer_id[]')
                    .val(rowId)
                );
            });
            if(Total_Record_Filter != Unselected.length)
                flag_error = true;
        }else{
            $('input[name="Type_Get_IDReport"]').val('selected');
            $.each(selected, function(index, rowId) {
                rowId = Js_Top.getNumbers(rowId);
                $('#wap_customers_id').append(
                    $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'customer_id[]')
                    .val(rowId)
                );
            });
            if ($('input[name="customer_id[]"]').length > 0)
                flag_error = true;
        }
        if(flag_error == false){
            $.growl.error({ message: "Please choose customers you want delete." });
            $('.tab-content').children('div:last-child').remove();
        }else{
            Data = $("#Frm_customers").serializeArray();
            Data.push({name: 'Arr_id_Search', value: Arr_id_Search});
            $.ajax({
                url: '<?php echo url::base() ?>customers/delete',
                type: 'POST',
                dataType: 'json',
                data: Data,
            })
            .done(function(d) {
                if(d.message){
                    $.growl.notice({ message: ""+d.content+"" });
                    Customer.LoadCustomers('active');
                }else{
                    $.growl.error({ message: ""+d.content+"" });
                }
                $('#wap_customers_id').empty();
                $('.tab-content').children('div:last-child').remove();
            });
        }
    }, 
    PrintPDF: function(){

    },
// End Action Customer

    Ready: function(arr_total_check){
	    Customer_Active.LoadDefault();
    },
    init: function() {
        Dom_Active = this.settings;
        Dom_Active.TblActive;
        Dom_Active.CheckboxAll;
        Dom_Active.BtnPrev;
        Dom_Active.BtnNext;
        Dom_Active.TotalRecord;
        Dom_Active.Button_Unselect_All;
        Dom_Active.Button_All;
        this.Ready();
    }   
};
$(document).ready(function() {
    Customer_Active.init();
});
</script>