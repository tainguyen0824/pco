<div class="col-lg-12" style="padding: 0px">
    <ul class="nav nav-tabs tab-customer">
        <li class="pull-right" style="margin-left: 5px;"><button onclick="Customer_Active.Next()" type="button" class="btn btn-sm btn_next_page_customers_active"><i class="fa fa-chevron-right" aria-hidden="true"></i></button></li>
        <li class="pull-right" style="margin-left: 5px;"><button onclick="Customer_Active.Previous()" type="button" class="btn btn-sm btn_prev_page_customers_active"><i class="fa fa-chevron-left" aria-hidden="true"></i></button></li>
        <li class="pull-right" style="margin-left: 5px;"><button class="btn btn-sm btn_print"><span class="glyphicon glyphicon-print"></span></button></li>
        <li class="pull-right add_customer" style="margin-left: 5px;"><button class="btn btn-sm btn_add_new" type="button" onclick="Customer.AddCustomers()">Add New Customer</button></li>

        <li onclick="Customer.LoadCustomers('active')" class="active responsive-customers-active"><a data-toggle="tab" class="tab_active" href="#active_customers">Active</a></li>
        <li onclick="Customer.LoadCustomers('inactive')" class="responsive-customers-inactive"><a data-toggle="tab" class="tab_inactive" href="#inactive_customers">Inactive</a></li>
        
    </ul>

    <div class="tab-content tab-content-customers">
        <div id="active_customers" class="tab-pane fade in active">
              
        </div>
        <div id="inactive_customers" class="tab-pane fade">

        </div>
    </div>
</div>
