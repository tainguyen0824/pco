<div class="col-lg-6 col-md-6">
    <div class="inner-addon left-addon" style="margin-top: 10px">
        <i class="glyphicon glyphicon-search"></i>
        <input onkeyup="initTableTech()" id="searchTech" type="text"  placeholder="Search" class="form-control Search">
    </div>
</div>
<div class="col-lg-6 col-md-6 right">
    <div style="margin-top: 10px">
        <button class="btn btn-primary" onclick="">New Technician</button>
        <button class="btn btn-primary" onclick="">Select all</button>
        <button class="btn btn-primary dropdown_hover dropdown-toggle" data-toggle="dropdown" >Actions</button>
    </div>
</div>
<table class="table table-hover table-striped" id="tblTechnician">
    <thead>
        <tr>
            <th>
                <div class="custom-checkbox" style="margin-left: 10px">
                    <input type="checkbox" onchange="checkAll(this)" name="" id="check_all">
                    <label for="check_all"></label>
                </div>
            </th>
            <th>Employee #</th>
            <th>Technician</th>
            <th>Color</th>
            <th>Services</th>
            <th>License #</th>
            <th>Commission</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Employee Notes</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<script>
    var tblTech = '';
    var Total_Tech = 0, Total_Filter_Tech = 0;
    function initTableTech(){
        tblTech = $('#tblTechnician').DataTable({
            destroy: true,
            serverSide: true,
            processing: false,
            autoWidth: false,
            deferRender: true,
            ordering: false,
            pageLength: 8,
            paging: true,
            ajax: {
                url: "<?php echo url::base()?>routes/LoadTechnician",
                type: "POST",
                data: {
                    search: $('#searchTech').val()
                },
                beforeSend: function () {
                    Js_Top.Add_Image_Loading_Datatables($('#tblTechnician'));
                },
                complete: function(d){
                    Total_Tech = d.responseJSON.recordsFiltered;
                    Total_Filter_Tech = d.responseJSON.recordsTotal;
                    $('#tblTechnician').children('div:last-child').remove();
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
                    "class":"tdInput",
                    "data": null,
                    "orderable": false,
                    "render": function ( data, type, full, meta ) {
                        return '<div class="custom-checkbox" style="margin-left: 10px;"><input onchange="" type="checkbox" value="'+full.tdID+'" class="checkTech" id="td_row_'+full.tdID+'"><label style="margin-bottom: 4px !important;" for="td_row_'+full.tdID+'"></label></div>';
                    }
                },{
                    "class":"tdID",
                    "data": null,
                    "orderable": false,
                    "render": function ( data, type, full, meta ) {
                        return '<p style="cursor:pointer;font-weight:bold" onclick="">'+full.tdID+'</p>';
                    }
                },{
                    "class":"tdName",
                    "data": null,
                    "orderable": false,
                    "render": function ( data, type, full, meta ) {
                        return '<p style="cursor:pointer;font-weight:bold" onclick="">'+full.tdName+'</p>';
                    }
                },{
                    "class":"tdColor",
                    "data": null,
                    "orderable": false,
                    "render": function ( data, type, full, meta ) {
                        return '<span style="display: block; width: 15px;height: 15px;border: 1px solid; background-color: '+full.tdColor+'"></span>';
                    }
                },{
                    "class":"tdService",
                    "data": null,
                    "orderable": false,
                    "render": function ( data, type, full, meta ) {
                        return '<p>'+ full.tdService +'</p>';
                    }
                }
            ],
            rowCallback: function ( row, data ) {
                var chk = $('#check_all');
                if(chk.is(':checked')){
                    $(row).find('.tdInput input').prop('checked',true);
                }
                else{
                    $(row).find('.tdInput input').prop('checked',false);
                }
            }
        })
    }
    function checkAll(t_this){
        if($(t_this).is(':checked'))
            $(':checkbox', tblTech.rows().nodes()).prop('checked', true);
        else
            $(':checkbox', tblTech.rows().nodes()).prop('checked', false);
    }
    $(document).ready(function () {
        initTableTech();
    })
</script>