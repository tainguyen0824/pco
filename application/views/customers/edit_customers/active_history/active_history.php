<div class="col-lg-6 col-md-6 search-box padding_zero" style="overflow: hidden;">
    <div class="inner-addon left-addon">
        <i class="glyphicon glyphicon-search"></i>
        <input type="text" id="SeachHistory" placeholder="Search" class="form-control Search">
    </div>
</div>
<div class="col-lg-6 col-md-6 search-box padding_zero" style="overflow: hidden;">
    <button class="btn btn-sm btn-primary pull-right full_sreen_accounting" type="button" onclick="Customer_Edit.Full_Sreen_Accounting()"><i class="fa fa-desktop" aria-hidden="true"></i></button>
    <button class="btn btn-sm btn-primary pull-right close_full_sreen_accounting" type="button" onclick="Customer_Edit.Close_Full_Sreen_Accounting()"><i class="fa fa-desktop" aria-hidden="true"></i></button>
    <button class="btn btn-sm btn-primary btn_show_column dropdown_hover dropdown-toggle pull-right"  type="button" data-toggle="dropdown" style="margin-left: 5px; margin-right: 5px;"><span class="glyphicon glyphicon-th"></span></button>
    <button onclick="Customer_Edit.Add_Activity_History()" class="btn btn-primary btn-sm btn_show_column dropdown_hover dropdown-toggle pull-right"  type="button" data-toggle="dropdown" style="margin-left: 5px;">Post Entry </button>
</div>

<div class="clearfix"></div>
<div class="table-responsive">       
    <table style="width:100%" class="display table table-striped tbl_Active_History" cellspacing="0">
        <thead>
            <tr>
                <th style="width:10%">Date</th>
                <th style="width:10%">User</th>
                <th style="width:30%">Activity</th>
                <th style="width:10%">PO#</th>
                <th style="width:20%">Service Address</th>
                <th style="width:10%">Technician</th>
                <th style="width:10%">Route</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
