<div id="wrap-close-overlay">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-left-close-overlay">
        <div class="DivParent">
            <div class="DivWhichNeedToBeVerticallyAligned">Total <?=$title?> Service</div>
            <div class="DivHelper"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 title-right-close-overlay">
        <form action="<?=url::base()?>routes/exportServiceCSV" method="post" target="_blank">
            <input class="hidden" type="text" value="<?=$idRoute?>" name="idRoute">
            <input class="hidden" type="text" value="<?=$title?>" name="active">
            <button class="btn btn-primary">CSV Export</button>
            <button type="button" class="btn btn-sm btn-primary" onclick="CloseService()">X</button>
        </form>
    </div>
</div>
<div id="overlay-content" class="overlay-content">
    <div class="inner-addon left-addon" style="margin-top: 10px">
        <i class="glyphicon glyphicon-search"></i>
        <input onkeyup="searchService(this)" id="searchService" type="text"  placeholder="Search" class="form-control Search" style="width: 50%">
    </div>
    <table class="table table-hover table-striped" id="tbl_service">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Service Name</th>
                <th>Technician</th>
                <th>Service Address</th>
                <th>Property Type</th>
                <th>Service Type</th>
                <th>Service Frequency</th>
                <th>Next Invoice</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<script>
    var tbl_service = $('#tbl_service');
    var total_service = 0;
    var total_filter = 0;

    var table = tbl_service.DataTable({
        destroy: true,
        serverSide: true,
        processing: false,
        autoWidth: false,
        deferRender: true,
        ordering: false,
        pageLength: 10,
        deferRender: true,
        ajax: {
            url: "<?php echo url::base()?>routes/loadService",
            type: "POST",
            data: {
                active: '<?=$title?>',
                idRoute: '<?=$idRoute?>',
                search: $('#searchService').val()
        //         d._ac_in_tive      = document.getElementById('ac_in_tive').value,
        //         d.ValFilterType    = ValFilterType,
        //         d.ValFilterBalance = ValFilterBalance
            },
            beforeSend : function(){
                Js_Top.Add_Image_Loading_Datatables(tbl_service);
            },
            complete: function(d){
                total_service = d.responseJSON.recordsFiltered;
                total_filter = d.responseJSON.recordsTotal;
                tbl_service.children('div:last-child').remove();
                // $("#loading").hide();
        //         $('.tab-content').children('div:last-child').remove();
        //         Arr_id_Search = d.responseJSON.Str_id;
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
            "class":"bold",
            "data": null,
            "orderable": false,
            "render": function ( data, type, full, meta ) {
                return '<p><strong>'+full.tdCustomer+'</strong></p><p>'+full.tdCusNo+'</p>';
            }
        },{
            "class":"",
            "data": null,
            "orderable": false,
            "render": function ( data, type, full, meta ) {
                return '<p>'+full.tdSvName+'</p>';
            }
        },{
            "class":"",
            "data": null,
            "orderable": false,
            "render": function ( data, type, full, meta ) {
                return '<p>'+full.tdTechnician+'</p>';
            }
        },{
            "class":"",
            "data": null,
            "orderable": false,
            "render": function ( data, type, full, meta ) {
                return '<p>'+full.tdSvAdd+'</p>';
            }
        },{
            "class":"",
            "data": null,
            "orderable": false,
            "render": function ( data, type, full, meta ) {
                return '<p>'+full.tdPropType+'</p>';
            }
        },{
            "class":"",
            "data": null,
            "orderable": false,
            "render": function ( data, type, full, meta ) {
                return '<p>'+full.tdTypeName+'</p>';
            }
        },
        {
            "class":"",
            "data": null,
            "orderable": false,
            "render": function ( data, type, full, meta ) {
                return '<p>'+full.tdFre+'</p>';
            }
        },
        {
            "class":"",
            "data": null,
            "orderable": false,
            "render": function ( data, type, full, meta ) {
                return '<p>'+full.tdInvoice+'</p>';
            }
        }]
    })
    function searchService(t_this) {
        table.draw();
    }
    function CloseService() {
        Js_Top.closeNav();
        var idRoute = '<?=$idRoute?>';
        if(idRoute == -1)
            NewRoute();
        else{
            Route_Active.EditRoute(idRoute);
        }
    }
    function ExportService() {
        $.post('<?=url::base()?>routes/exportServiceCSV',{
            active: '<?=$title?>',
            idRoute: '<?=$idRoute?>',
        });
    }
</script>